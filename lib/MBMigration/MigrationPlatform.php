<?php

namespace MBMigration;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\Checking;
use MBMigration\Builder\ColorMapper\ColorMapper;
use MBMigration\Builder\DebugBackTrace;
use MBMigration\Builder\Layout\Common\MenuBuilderFactory;
use MBMigration\Builder\PageBuilder;
use MBMigration\Builder\Utils\ExecutionTimer;
use MBMigration\Builder\Utils\TextTools;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\ErrorDump;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;
use MBMigration\Layer\MB\MBProjectDataCollector;
use MBMigration\Parser\JS;
use Psr\Log\LoggerInterface;

class MigrationPlatform
{
    protected $cache;
    protected $projectId;

    /**
     * @var MBProjectDataCollector
     */
    private $parser;
    /**
     * @var QueryBuilder
     */
    private $QueryBuilder;

    /**
     * @var BrizyApi
     */
    private $brizyApi;
    private $projectID_Brizy;
    private $startTime;
    private $graphApiBrizy;
    private $migrationID;
    /**
     * @var ErrorDump
     */
    private $errorDump;
    /**
     * @var mixed
     */
    private $finalSuccess;
    private $buildPage;
    /**
     * @var PageBuilder
     */
    private $PageBuilder;
    private LoggerInterface $logger;

    use checking;
    use DebugBackTrace;

    public function __construct(Config $config, LoggerInterface $logger, $buildPage = '')
    {
        $this->cache = VariableCache::getInstance(Config::$cachePath);
        $this->logger = $logger;

        $this->errorDump = new ErrorDump($this->cache);
        set_error_handler([$this->errorDump, 'handleError']);
        register_shutdown_function([$this->errorDump, 'handleFatalError']);
        \MBMigration\Core\Logger::instance()->info('initialization');

        $this->finalSuccess['status'] = 'start';

        $this->buildPage = $buildPage;
    }

    public function start(string $projectID_MB, int $projectID_Brizy = 0): bool
    {
        if ($projectID_MB == 'sample') {
            \MBMigration\Core\Logger::instance()->info(json_encode(JS::RichText(8152825, 'https://www.crosspointcoc.org/')));
        } else {
            try {
                $this->cache->loadDump($projectID_MB, $projectID_Brizy);
                $this->run($projectID_MB, $projectID_Brizy);
                $this->cache->dumpCache($projectID_MB, $projectID_Brizy);
            } catch (Exception $e) {
                \MBMigration\Core\Logger::instance()->info($e->getMessage());

                throw $e;
            } catch (GuzzleException $e) {
                \MBMigration\Core\Logger::instance()->info($e->getMessage());

                throw $e;
            }
        }

        return true;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function run(string $projectUUID_MB, int $projectID_Brizy = 0): void
    {
        $this->brizyApi = new BrizyAPI();

        $this->cache->setClass($this->brizyApi, 'brizyApi');

//        $this->projectMetadata($projectID_Brizy);

        if (!($projectID_MB = $this->cache->get('projectId_MB'))) {
            $projectID_MB = MBProjectDataCollector::getIdByUUID($projectUUID_MB);
        }

        if ($projectID_Brizy == 0) {
            $this->projectID_Brizy = $this->brizyApi->createProject('Project_id:'.$projectID_MB, 4352671, 'id');
//            $this->projectID_Brizy = $this->brizyApi->createProject('Project_id:'.$projectID_MB, 4423676, 'id');
        } else {
            $this->projectID_Brizy = $projectID_Brizy;
        }

        $this->projectId = $projectUUID_MB.'_'.$this->projectID_Brizy.'_';
        $this->migrationID = $this->brizyApi->getNameHash($this->projectId, 10);
        $this->projectId .= $this->migrationID;

        if (!$this->cache->get('container')) {
            $this->cache->set('container', $this->brizyApi->getProjectContainer($this->projectID_Brizy));
        }

        $this->init($projectID_MB, $this->projectID_Brizy);

        if (!$designName = $this->cache->get('designName')) {
            $designName = $this->parser->getDesignSite();
            $this->cache->set('designName', $designName);
        }

        $this->checkDesign($designName);

        $this->createProjectFolders();

        $this->cache->set('GraphApi_Brizy', $this->graphApiBrizy);

        if (!($graphToken = $this->cache->get('graphToken'))) {
            $graphToken = $this->brizyApi->getGraphToken($this->projectID_Brizy);
            $this->cache->set('graphToken', $graphToken);
        }

        $this->QueryBuilder = new QueryBuilder(
            $this->graphApiBrizy,
            $graphToken
        );

        $this->cache->setClass($this->QueryBuilder, 'QueryBuilder');

        if (!$this->cache->get('ListPages')) {
            $this->getAllPage();
        }
        if (!$this->cache->get('settings')) {
            $settings = $this->emptyCheck($this->parser->getSite(), self::trace(0).' Message: Site not found');
            $this->cache->set('settings', $settings);
        }

        $this->brizyApi->setMetaDate();

        $parentPages = $this->parser->getPages();

        if (empty($parentPages)) {
            \MBMigration\Core\Logger::instance()->info('MB project not found, migration did not start, process completed without errors!');
            $this->logFinalProcess($this->startTime, false);

            throw new Exception('MB project not found, migration did not start, process completed without errors!');
        }

        $this->createPalette();
        if (!$this->cache->get('mainSection')) {
            $mainSection = $this->parser->getMainSection();
            $this->updateColorSection($mainSection);
            \MBMigration\Core\Logger::instance()->info('Upload Logo menu');
            $mainSection = $this->uploadPicturesFromSections($mainSection);
            $this->cache->set('mainSection', $mainSection);
        }

        if (true || !$this->cache->get('menuList')) {
            \MBMigration\Core\Logger::instance()->info('Start created pages');
            $projectTitle = $this->cache->get('settings')['title'];

            $this->createBlankPages($parentPages, $projectTitle);
            $this->cache->set('menuList', [
                'id' => null,
                'uid' => null,
                'name' => null,
                'create' => false,
                'list' => $parentPages,
                'data' => "",
            ]);
            $this->createMenuStructure();
        } else {
            $parentPages = $this->cache->get('menuList')['list'];
        }

        if (Config::$devMode) {
            echo $this->cache->get('design', 'settings')."\n";
        }

        $this->launch($parentPages);

        if ($this->PageBuilder) {
            $this->PageBuilder->closeBrowser();
        }

        \MBMigration\Core\Logger::instance()->info('Project migration completed successfully!');

        $this->logFinalProcess($this->startTime);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function init(string $projectID_MB, int $projectID_Brizy): void
    {
        \MBMigration\Core\Logger::instance()->info('-------------------------------------------------------------------------------------- []');
        \MBMigration\Core\Logger::instance()->info('Start Process!');

        Utils::init($this->cache);

        $this->startTime = microtime(true);

        $this->cache->set('migrationID', $this->migrationID);
        \MBMigration\Core\Logger::instance()->info('Migration ID: '.$this->migrationID);

        $this->graphApiBrizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $this->cache->set('projectId_MB', $projectID_MB);
        $this->cache->set('projectId_Brizy', $projectID_Brizy);
        $this->cache->set('Status', ['Total' => 0, 'Success' => 0]);
        $this->cache->set('headBlockCreated', false);
        $this->cache->set('footerBlockCreated', false);

        $this->parser = new MBProjectDataCollector();
    }

    private function logFinalProcess(float $startTime, bool $successWorkCompletion = true): void
    {
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        $this->finalSuccess['UMID'] = $this->migrationID;
        if ($successWorkCompletion) {
            $this->finalSuccess['status'] = 'success';
        }
        $this->finalSuccess['progress'] = $this->cache->get('Status');
        $this->finalSuccess['processTime'] = round($executionTime, 1);

        \MBMigration\Core\Logger::instance()->info('Work time: '.$this->Time($executionTime).' (seconds: '.round($executionTime, 1).')');
        \MBMigration\Core\Logger::instance()->info('END');
    }

    /**
     * @throws Exception
     */
    private function launch($parentPages): void
    {
        foreach ($parentPages as $i=>$page) {
//            if($page['hidden']){ continue; }

            if (!empty($page['parentSettings'])) {
                $settings = json_decode($page['parentSettings'], true);
                if (array_key_exists('external_url', $settings)  ) {
                    continue;
                }

                // there a pages that have only one section only and de same slug as home page..
                if ( array_key_exists('category', $settings) && $settings['category']=='text' ) {
                    continue;
                }
            }

            if (!empty($page['child'])) {
                $this->launch($page['child']);
            }
            if (Config::$devMode && $this->buildPage !== '') {
                if ($page['slug'] !== $this->buildPage) {
                    continue;
                }
            }
            if ($page['landing'] !== true) {
                continue;
            }
            $this->collector($page);
        }
    }

    /**
     * @throws Exception
     */
    private function collector($page): void
    {
        \MBMigration\Core\Logger::instance()->info('Take page | ID: '.$page['id']);

        $this->cache->set('tookPage', $page);
        ExecutionTimer::start();

        if (!($preparedSectionOfThePage = $this->cache->get('preparedSectionOfThePage_'.$page['id']))) {
            $preparedSectionOfThePage = $this->getItemsFromPage($page);
            if (!$preparedSectionOfThePage) {
                return;
            }
            $preparedSectionOfThePage = $this->uploadPicturesFromSections($preparedSectionOfThePage);
            $preparedSectionOfThePage = $this->sortArrayByPosition($preparedSectionOfThePage);
            $this->cache->set('preparedSectionOfThePage_'.$page['id'], $preparedSectionOfThePage);
        }

        $collectionItem = $this->getCollectionItem($page['slug']);
        if (!$collectionItem) {
            $ProjectTitle = $this->cache->get('settings')['title'];
            $title = $ProjectTitle.' | '.$page['name'];

            $newPage = $this->creteNewPage($page['slug'], $page['name'], $title, $page['protectedPage']);
            if (!$newPage) {
                \MBMigration\Core\Logger::instance()->warning('Failed created pages | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']);
            } else {
                \MBMigration\Core\Logger::instance()->info('Success created pages | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']);
                $collectionItem = $newPage;
            }
        }

        $this->setCurrentPageOnWork($collectionItem);

        if (!empty($preparedSectionOfThePage)) {
            $this->runPageBuilder($preparedSectionOfThePage);
        } else {
            \MBMigration\Core\Logger::instance()->info('Set default page template | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']);
            $this->runPageBuilder($preparedSectionOfThePage);
        }
    }

    private function getItemsFromPage(array $page)
    {
        \MBMigration\Core\Logger::instance()->info('Parent Page id: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']);

        $child = $this->parser->getSectionsPage($page['id']);
        if (!empty($child)) {
            $sections = [];
            foreach ($child as $value) {

                \MBMigration\Core\Logger::instance()->info('Collection of item id: '.$value['id'].' -> Parent page id:'.$page['id']);

                $items = [
                    'sectionId' => $value['id'],
                    'typeSection' => $value['typeSection'],
                    'position' => $value['position'],
                    'category' => $value['category'],
                    'settings' => $value['settings'],
                    'head' => [],
                    'items' => [],

                ];
                $sectionItems = $this->parser->getSectionsItems($value, true);

                foreach ($sectionItems as $key => $Item) {
                    if ($key === 'item') {
                        $items['head'] = $Item;
                    } else {
                        $items['items'][] = $Item;
                    }
                }
                $sections[] = $items;
            }
            $result = $sections;
        } else {
            \MBMigration\Core\Logger::instance()->info('Empty parent page | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']);
            $result = false;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function createMenuStructure(): void
    {
        \MBMigration\Core\Logger::instance()->info('Create menu structure');

        $parentPages = $this->cache->get('menuList');
        $design = $this->cache->get('design');
        $brizyProject = $this->cache->get('projectId_Brizy');
        $fonts = $this->cache->get('fonts', 'settings');
        $brizyApi = $this->cache->get('brizyApi');

        $menuBuilder = MenuBuilderFactory::instanceOfThemeMenuBuilder($design, $brizyProject, $brizyApi, $fonts);
        $menuStructure = $menuBuilder->transformToBrizyMenu($parentPages['list']);
        $result = $menuBuilder->createBrizyMenu('mainMenu', $menuStructure);

        $this->cache->set('brizyMenuItems', $menuStructure);

        $this->cache->set('menuList', [
            'id' => $result['id'] ?? null,
            'uid' => $result['uid'] ?? null,
            'name' => $result['name'] ?? null,
            'create' => false,
            'list' => $parentPages['list'],
            'data' => $result['data'] ?? '',
        ]);
    }

    private function transformToBrizyMenu(array $parentMenu): array
    {
        $mainMenu = [];
        $textTransform = '';

        $settingsTextTransform = $this->cache->get('fonts', 'settings');
        foreach ($settingsTextTransform as $itemTextTransform) {
            if (isset($itemTextTransform['name']) && $itemTextTransform['name'] === 'main_nav') {
                $textTransform = $itemTextTransform['text_transform'] ?? 'none';
                break;
            }
        }

        foreach ($parentMenu as $item) {
            if (isset($item['hidden'])) {
                if ($item['hidden']) {
                    continue;
                }
            }
            $settings = json_decode($item['parentSettings'], true);

            if ($settings && array_key_exists('external_url', $settings)) {
                $mainMenu[] = [
                    'id' => '',
                    "uid" => Utils::getNameHash(),
                    "isNewTab" => $this->checkOpenInNewTab($settings),
                    "label" => TextTools::transformText($item['name'], $textTransform),
                    "type" => "custom_link",
                    'url' => $settings['external_url'],
                    "description" => "",
                    "items" => $this->transformToBrizyMenu($item['child']),
                ];
            } else {
                if (empty($item['collection'])) {
                    $item['collection'] = $item['child'][0]['collection'];
                }
                $mainMenu[] = [
                    "id" => $item['collection'],
                    "uid" => Utils::getNameHash(),
                    "isNewTab" => $this->checkOpenInNewTab($settings),
                    "label" => TextTools::transformText($item['name'], $textTransform),
                    "items" => $this->transformToBrizyMenu($item['child']),
                ];
            }
        }

        return $mainMenu;
    }

    private function checkOpenInNewTab($settings): bool
    {
        if (array_key_exists('new_window', $settings)) {
            return $settings['new_window'];
        } else {
            return false;
        }
    }

    private function getColorFromPalette(string $color)
    {
        $subPalette = $this->cache->get('subpalette', 'parameter');
        foreach ($subPalette as $key => $palette) {
            if ($key === $color) {
                return $palette;
            }
        }

        return false;
    }

    private function getCollectionItem($slug)
    {
        $ListPages = $this->cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems) {
            if ($listSlug == $slug) {
                return $collectionItems;
            }
        }
        \MBMigration\Core\Logger::instance()->info('Page does not exist |  Slug: '.$slug);

        return false;
    }

    /**
     * @throws Exception
     */
    private function getAllPage(): void
    {
        $collectionTypes = $this->emptyCheck(
            $this->QueryBuilder->getCollectionTypes(),
            self::trace(0).' Message: CollectionTypes not found'
        );

        $foundCollectionTypes = [];
        $entities = [];

        foreach ($collectionTypes as $collectionType) {
            if ($collectionType['slug'] == 'page') {
                $foundCollectionTypes[$collectionType['slug']] = $collectionType['id'];
                $this->cache->set('mainCollectionType', $collectionType['id']);
            }
        }

        $page = 1;
        do {
            $collectionItems = $this->QueryBuilder->getCollectionItems($foundCollectionTypes, $page++);

            foreach ($collectionItems['page']['collection'] as $entity) {
                $entities[$entity['slug']] = $entity['id'];
            }
        } while (count($collectionItems['page']['collection']) > 0);

        $this->cache->set('ListPages', $entities);
    }

    private function setCurrentPageOnWork($collectionItem): void
    {
        \MBMigration\Core\Logger::instance()->info('Set the current page to work: '.$collectionItem);
        $this->cache->set('currentPageOnWork', $collectionItem);
    }

    /**
     * @throws Exception
     */
    private function creteNewPage($slug, $title, $seoTitle, $protectedPage = false, $setActivePage = true)
    {
        if ($this->pageCheck($slug)) {
            \MBMigration\Core\Logger::instance()->info('Request to create a new page: '.$slug);
            $collectionItem = $this->QueryBuilder->createCollectionItem(
                $this->cache->get('mainCollectionType'),
                $slug,
                $title,
                $seoTitle,
                $protectedPage
            );
            $slug = $collectionItem['slug']; // the slug can be renamed as brizy has some restrictions like slug: blog.
            $this->getAllPage();
        }

        $mainCollectionItem = $this->getCollectionItem($slug);
        if ($mainCollectionItem) {
            if ($setActivePage) {
                $this->cache->set('currentPageOnWork', $mainCollectionItem);

                return $mainCollectionItem;
            }

            return $mainCollectionItem;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    private function renameSlug($itemsID, $slug, string $title, string $seoTitle)
    {

        $seo = [
            'enableIndexing' => true,
            'title' => $seoTitle,
        ];

        $res = $this->QueryBuilder->updateCollectionItem($itemsID, $slug, [], 'published', [], $title, $seo);
        \MBMigration\Core\Logger::instance()->info('Page name is Rename');

        return $res;
    }

    /**
     * @throws Exception
     */
    private function runPageBuilder($preparedSectionOfThePage, $defaultPage = false): void
    {

        if (!$defaultPage) {
            \MBMigration\Core\Logger::instance()->info('Start Builder');
        } else {
            \MBMigration\Core\Logger::instance()->info('Start Builder | create default Page');
        }

        $this->PageBuilder = new PageBuilder($this->brizyApi, $this->logger);

        if ($this->PageBuilder->run($preparedSectionOfThePage)) {
            \MBMigration\Core\Logger::instance()->info('Page created successfully!');
        }
    }

    /**
     * @throws Exception
     */
    private function createBlankPages(array &$parentPages,  $projectTitle, $mainLevel = true)
    {
        \MBMigration\Core\Logger::instance()->info('Start created pages');
        $projectPages = $this->brizyApi->getAllProjectPages();

        $i = 0;
        foreach ($parentPages as &$page) {
            $title = $projectTitle.' | '.$page['name'];

            if (!empty($page['child'])) {
                $this->createBlankPages($page['child'],  $projectTitle, false);
            }

            if ($page['landing'] == true) {
                if ($i != 0 || !$mainLevel) {
                    if (!array_key_exists($page['slug'], $projectPages['listPages'])) {
                        $newPage = $this->creteNewPage(
                            $page['slug'],
                            $page['name'],
                            $title,
                            $page['protectedPage']
                        );
                    } else {
                        $newPage = $projectPages['listPages'][$page['slug']];
                    }
                } else {
//                    if (!array_key_exists($pages['slug'], $projectPages['listPages'])) {
                    $updateNameResult = $this->renameSlug(
                        $projectPages['listPages']['home'],
                        $page['slug'],
                        $page['name'],
                        $title
                    );
                    $newPage = $updateNameResult['updateCollectionItem']['collectionItem']['id'];
//                    } else {
//                        $newPage = $projectPages['listPages'][$pages['slug']];
//                    }
                }

                if (!$newPage) {
                    \MBMigration\Core\Logger::instance()->warning('Failed created pages | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']);
                } else {
                    \MBMigration\Core\Logger::instance()->info('Success created pages | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']);
                    $page['collection'] = $newPage;
                }
            } else {
                if (!empty($page['child'])) {
                    $page['collection'] = $page['child'][0]['collection'];
                }
            }

            $i++;
        }
    }

    private function getPisturesUrl($nameImage, $type)
    {
        $folder = ['gallery-layout' => '/gallery/slides/'];

        if (array_key_exists($type, $folder)) {
            $folderload = $folder[$type];
        } else {
            $folderload = '/site-images/';
        }

        $uuid = $this->cache->get('settings')['uuid'];
        $prefix = substr($uuid, 0, 2);
        $url = Config::$MBMediaStaging."/".$prefix.'/'.$uuid.$folderload.$nameImage;
        \MBMigration\Core\Logger::instance()->info('Created url pictures: '.$url.' Type folder '.$type);

        return $url;
    }

    /**
     * @throws Exception
     */
    private function uploadPicturesFromSections(array $sectionsItems): array
    {
        \MBMigration\Core\Logger::instance()->info('Start upload image');
        foreach ($sectionsItems as &$section) {
            if ($this->checkArrayPath($section, 'settings/sections/background/photo')) {
                if ($section['settings']['sections']['background']['photo'] != null) {
                    \MBMigration\Core\Logger::instance()->info('Found background image');
                    $result = $this->brizyApi->createMedia(
                        $section['settings']['sections']['background']['photo'],
                        $this->projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        \MBMigration\Core\Logger::instance()->info('Upload image response: '.json_encode($result));
                        $section['settings']['sections']['background']['photo'] = $result['name'];
                        $section['settings']['sections']['background']['filename'] = $result['filename'];
                        \MBMigration\Core\Logger::instance()->info('Success upload image fileName: '.$result['filename'].' srcName: '.$result['name']);
                    }
                } else {
                    $this->checkItemForMediaFiles($section['items'], $section['typeSection']);
                }
            }

            if ($this->checkArrayPath($section, 'settings/background/photo')) {
                if ($section['settings']['background']['photo'] != null) {
                    \MBMigration\Core\Logger::instance()->info('Found background image');
                    $result = $this->brizyApi->createMedia(
                        $section['settings']['background']['photo'],
                        $this->projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        \MBMigration\Core\Logger::instance()->info('Upload image response: '.json_encode($result));
                        $section['settings']['background']['photo'] = $result['name'];
                        $section['settings']['background']['filename'] = $result['filename'];
                        \MBMigration\Core\Logger::instance()->info('Success upload image fileName: '.$result['filename'].' srcName: '.$result['name']);
                    }
                } else {
                    $this->checkItemForMediaFiles($section['items'], $section['typeSection']);
                }
            }
            $this->checkItemForMediaFiles($section['items'], $section['typeSection']);
        }

        return $sectionsItems;
    }

    /**
     * @throws Exception
     */
    private function checkItemForMediaFiles(&$section, $typeSection = ''): void
    {
        foreach ($section as &$item) {
            if ($item['category'] == 'photo' && $item['content'] != '') {
                $this->media($item, $typeSection);
            }
            if ($item['category'] == 'list') {
                foreach ($item['item'] as &$piece) {
                    if ($piece['category'] == 'photo' && $piece['content'] != '') {
                        $this->media($piece, $typeSection);
                    }
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    private function media(&$item, $section): void
    {
        \MBMigration\Core\Logger::instance()->info('Found new image');
        $downloadImageURL = $this->getPisturesUrl($item['content'], $section);
        $result = $this->brizyApi->createMedia($downloadImageURL, $this->projectId);
        if ($result) {
            if (array_key_exists('status', $result)) {
                if ($result['status'] == 201) {
                    $result = json_decode($result['body'], true);
                    \MBMigration\Core\Logger::instance()->info('Upload image response: '.json_encode($result));
                    $item['uploadStatus'] = true;
                    $item['imageFileName'] = $result['filename'];
                    $item['content'] = $result['name'];
                    \MBMigration\Core\Logger::instance()->info('Success upload image fileName: '.$result['filename'].' srcName: '.$result['name']);
                } else {
                    \MBMigration\Core\Logger::instance()->critical('Unexpected answer: '.json_encode($result));
                }
            } else {
                \MBMigration\Core\Logger::instance()->critical('Bad response: '.json_encode($result));
            }
        } else {
            $item['uploadStatus'] = false;
            \MBMigration\Core\Logger::instance()->critical('The structure of the image is damaged');
        }
    }

    public function getStatus()
    {
        return json_encode($this->cache->get('Status'));
    }


    private function sortArrayByPosition($array)
    {
        usort($array, function ($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $array;
    }

    private function checkArrayPath($array, $path): bool
    {
        $keys = explode('/', $path);
        $current = $array;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                return false;
            }
            $current = $current[$key];
        }

        return true;
    }

    private function colorPalette(): array
    {
        $result = [];
        $settings = $this->cache->get('settings');

        if ($this->checkArrayPath($settings, 'parameter/palette')) {
            $palette = $this->cache->get('settings')['parameter']['palette'];

            foreach ($palette as $item) {
                $result[$item['tag']] = strtolower($item['color']);
            }
        }

        return $result;
    }

    private function createProjectFolders(): void
    {
        $folds = [
            'main' => '/',
            'page' => '/page/',
            'media' => '/media/',
            'log' => '/log/',
            'dump' => '/log/dump/',
        ];
        foreach ($folds as $key => $fold) {
            $path = Config::$pathTmp.$this->projectId.$fold;
            $this->createDirectory($path);
            $paths[$key] = $path;
        }
        $this->cache->set('ProjectFolders', $paths);
    }

    private function createDirectory($directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            \MBMigration\Core\Logger::instance()->info('Create Directory: ' . $directoryPath);

            $result = shell_exec("mkdir -p " . escapeshellarg($directoryPath));

            if ($result === null) {
                \MBMigration\Core\Logger::instance()->info('Directory created successfully.');
            } else {
                \MBMigration\Core\Logger::instance()->critical('Error creating directory: ' . $result);
            }

            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
        }
    }

    private function Time($seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds - ($hours * 3600)) / 60);
        $seconds = $seconds - ($hours * 3600) - ($minutes * 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function updateColorSection(array &$mainSection): void
    {
        foreach ($mainSection as $item => &$value) {
            if (is_array($value)) {
                $this->updateColorSection($value);
            }
            if ($item === 'subpalette') {
                $value = $this->getColorFromPalette($value);
            }
        }
    }

    private function createPalette(): void
    {
        $colorMapper = new ColorMapper();
        $colorKit = $this->colorPalette();
        $design = $this->cache->get('design', 'settings');
        $this->cache->set('subpalette', [], 'parameter');
        $subPalette = $colorMapper->getPalette('Anthem', $colorKit);
        $this->cache->update('subpalette', $subPalette, 'parameter');
    }

    /**
     * @throws Exception
     */
    private function checkDesign($designName)
    {
        $designInDevelop = Config::$designInDevelop;
        $devMode = Config::$devMode;
        if (in_array($designName, $designInDevelop) && !$devMode) {
            throw new Exception('This design is not ready for migration, but is in development');
        }
    }

    public function getLogs(): string
    {
        if ($this->finalSuccess['status'] === 'success') {
            \MBMigration\Core\Logger::instance()->debug(json_encode($this->errorDump->getDetailsMessage()));

            return json_encode($this->finalSuccess);
        }

        return json_encode($this->errorDump->getAllErrors());
    }

    /**
     * @throws Exception
     */
    private function projectMetadata(int $projectID_Brizy): void
    {
        $metadata = $this->brizyApi->getProjectMetadata($projectID_Brizy);
        $this->cache->set('metadata', $metadata);
    }

    /**
     * @throws Exception
     */
    public function createBrizyProject($projectID_MB)
    {
        $brizyApi = new BrizyAPI();
        $this->projectID_Brizy = $brizyApi->createProject('Project_id:'.$projectID_MB, 4352671, 'id');

        return $this->projectID_Brizy;
    }

}