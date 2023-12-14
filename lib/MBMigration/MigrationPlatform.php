<?php

namespace MBMigration;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\Checking;
use MBMigration\Builder\ColorMapper\ColorMapper;
use MBMigration\Builder\DebugBackTrace;
use MBMigration\Builder\PageBuilder;
use MBMigration\Builder\Utils\TextTools;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\ErrorDump;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;
use MBMigration\Layer\MB\MBProjectDataCollector;
use MBMigration\Parser\JS;

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

    use checking;
    use DebugBackTrace;

    public function __construct(Config $config, $buildPage = '')
    {
        $this->cache = VariableCache::getInstance();
        $this->cache->init();

        $this->errorDump = new ErrorDump($this->cache);
        set_error_handler([$this->errorDump, 'handleError']);
        register_shutdown_function([$this->errorDump, 'handleFatalError']);
        Utils::MESSAGES_POOL('initialization');
        $setConfig = $config;
        $this->finalSuccess['status'] = 'start';

        $this->buildPage = $buildPage;
    }

    public function start(string $projectID_MB, int $projectID_Brizy = 0): bool
    {
        if ($projectID_MB == 'sample') {
            Utils::MESSAGES_POOL(json_encode(JS::RichText(8152825, 'https://www.crosspointcoc.org/')));
        } else {
            try {
                $this->run($projectID_MB, $projectID_Brizy);
            } catch (Exception $e) {
               Utils::MESSAGES_POOL($e->getMessage());

                return false;
            } catch (GuzzleException $e) {
                Utils::MESSAGES_POOL($e->getMessage());

                return false;
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

        $projectID_MB = MBProjectDataCollector::getIdByUUID($projectUUID_MB);

        if ($projectID_Brizy == 0) {
            $this->projectID_Brizy = $this->brizyApi->createProject('Project_id:'.$projectID_MB, 4352671, 'id');
        } else {
            $this->projectID_Brizy = $projectID_Brizy;
        }

        $this->projectId = $projectUUID_MB.'_'.$this->projectID_Brizy.'_';
        $this->migrationID = $this->brizyApi->getNameHash($this->projectId, 10);
        $this->projectId .= $this->migrationID;

        $this->cache->set('container', $this->brizyApi->getProjectContainer($this->projectID_Brizy));

        $this->init($projectUUID_MB, $this->projectID_Brizy);
        $this->checkDesign($this->parser->getDesignSite());

        $this->createProjectFolders();

        $this->cache->set('GraphApi_Brizy', $this->graphApiBrizy);
        $this->cache->set('graphToken', $this->brizyApi->getGraphToken($this->projectID_Brizy));

        $this->QueryBuilder = new QueryBuilder(
            $this->graphApiBrizy,
            $this->brizyApi->getGraphToken($this->projectID_Brizy)
        );

        $this->cache->setClass($this->QueryBuilder, 'QueryBuilder');

        $this->getAllPage();

        $settings = $this->emptyCheck($this->parser->getSite(), self::trace(0).' Message: Site not found');

        $this->cache->set('settings', $settings);

        $this->brizyApi->setMetaDate();

        $parentPages = $this->parser->getParentPages();

        if (empty($parentPages)) {
            Utils::log(
                'MB project not found, migration did not start, process completed without errors!',
                1,
                "MAIN Foreach"
            );
            $this->logFinalProcess($this->startTime, false);

            throw new Exception('MB project not found, migration did not start, process completed without errors!');
        }

        $this->createPalette();
        $mainSection = $this->parser->getMainSection();
        $this->updateColorSection($mainSection);
        Utils::log('Upload Logo menu', 1, 'createMenu');
        $mainSection = $this->uploadPicturesFromSections($mainSection);
        $this->cache->set('mainSection', $mainSection);
//        file_put_contents(JSON_PATH.'/mainSection.json',json_encode($mainSection));
        $this->createBlankPages($parentPages);
        $this->createMenuStructure();

        if (Config::$devMode) {
            echo $this->cache->get('design', 'settings')."\n";
        }

        $this->launch($parentPages);

        Utils::log('Project migration completed successfully!', 6, 'PROCESS');

        $this->logFinalProcess($this->startTime);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function init(string $projectUUID_MB, int $projectID_Brizy): void
    {
        Utils::log('-------------------------------------------------------------------------------------- []', 4, '');
        Utils::log('Start Process!', 4, 'MIGRATION');

        Utils::init($this->cache);

        $this->startTime = microtime(true);

        $this->cache->set('migrationID', $this->migrationID);
        Utils::log('Migration ID: '.$this->migrationID, 4, 'MIGRATION');

        $this->graphApiBrizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $projectID_MB = MBProjectDataCollector::getIdByUUID($projectUUID_MB);

        $this->cache->set('projectId_MB', $projectID_MB);
        $this->cache->set('projectId_Brizy', $projectID_Brizy);
        $this->cache->set('Status', ['Total' => 0, 'Success' => 0]);

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

        if (!Config::$devMode) {
            Utils::keepItClean();
        }

        Utils::log('Work time: '.$this->Time($executionTime).' (seconds: '.round($executionTime, 1).')', 1, 'PROCESS');
        Utils::log('END', 1, "PROCESS");
    }

    /**
     * @throws Exception
     */
    private function launch($parentPages): void
    {
        foreach ($parentPages as $page) {
            if($page['hidden']){ continue; }

            if(!empty($page['parentSettings'])){
               $settings =  json_decode($page['parentSettings'], true);
               if(array_key_exists('external_url', $settings)){
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
            $this->collector($page);
        }
    }

    /**
     * @throws Exception
     */
    private function collector($page): void
    {
        Utils::log('Take page | ID: '.$page['id'], 4, 'MAIN Foreach');

        $this->cache->set('tookPage', $page);

        $preparedSectionOfThePage = $this->getItemsFromPage($page);
        if (!$preparedSectionOfThePage) {
            return;
        }

        $preparedSectionOfThePage = $this->uploadPicturesFromSections($preparedSectionOfThePage);
        $preparedSectionOfThePage = $this->sortArrayByPosition($preparedSectionOfThePage);
//        file_put_contents(JSON_PATH.'/preparedSectionOfThePage.json',json_encode($preparedSectionOfThePage));
        $collectionItem = $this->getCollectionItem($page['slug']);
        if (!$collectionItem) {
            $newPage = $this->creteNewPage($page['slug'], $page['name']);
            if (!$newPage) {
                Utils::log(
                    'Failed created pages | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug'],
                    2,
                    'creteNewPage'
                );
            } else {
                Utils::log(
                    'Success created pages | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug'],
                    1,
                    'creteNewPage'
                );
                $collectionItem = $newPage;
            }
        }

        $this->setCurrentPageOnWork($collectionItem);

        if (!empty($preparedSectionOfThePage)) {
            $this->runPageBuilder($preparedSectionOfThePage);
        } else {
            Utils::log(
                'Set default page template | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug'],
                1,
                'Foreach'
            );
            $this->runPageBuilder($preparedSectionOfThePage);
        }
    }

    private function getItemsFromPage(array $page)
    {
        Utils::log(
            'Parent Page id: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug'],
            1,
            'getItemsFromPage'
        );

        $child = $this->parser->getSectionsPage($page['id']);
        if (!empty($child)) {
            $sections = [];
            foreach ($child as $value) {

                Utils::log(
                    'Collection of item id: '.$value['id'].' -> Parent page id:'.$page['id'],
                    1,
                    'getItemsFromPage'
                );
                if ($this->checkArrayPath($value, 'settings/sections/color/subpalette')) {
                    $value['settings']['color'] = $this->getColorFromPalette(
                        $value['settings']['sections']['color']['subpalette']
                    );
                    unset($value['settings']['sections']['color']);
                }
                if ($this->checkArrayPath($value, 'settings/layout/color/subpalette')) {
                    $value['settings']['layout-color'] = $this->getColorFromPalette(
                        $value['settings']['layout']['color']['subpalette']
                    );
                    unset($value['settings']['layout']['color']);
                }

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
            Utils::log(
                'Empty parent page | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug'],
                1,
                'getItemsFromPage'
            );
            $result = false;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function createMenuStructure(): void
    {
        Utils::log('Create menu structure', 1, 'createMenuStructure');

        $parentPages = $this->cache->get('menuList');
        $mainMenu = $this->transformToBrizyMenu($parentPages['list']);

        $data = [
            'project' => $this->projectID_Brizy,
            'name' => 'mainMenu',
            'data' => json_encode($mainMenu),
        ];

        $result = $this->brizyApi->createMenu($data);


        $this->cache->add('menuList', $result);
//        $parentPages = $this->cache->get('menuList');
//        file_put_contents(JSON_PATH.'/menuList.json',json_encode($parentPages));
    }

    private function transformToBrizyMenu(array $parentMenu): array
    {
        $mainMenu = [];
        $textTransform = '';

        $settingsTextTransform = $this->cache->get('fonts', 'settings');
        foreach ($settingsTextTransform as $itemTextTransform) {
            if ($itemTextTransform['name'] === 'main_nav') {
                $textTransform = $itemTextTransform['text_transform'];
            }
        }

        foreach ($parentMenu as $item) {
            if (isset($item['hidden'])) {
                if($item['hidden']){
                    continue;
                }
            }
            $settings = json_decode($item['parentSettings'], true);

            if (array_key_exists('external_url', $settings)) {
                $mainMenu[] = [
                    'id' => '',
                    "uid" => Utils::getNameHash(),
                    "isNewTab" => $this->checkOpenInNewTab($settings),
                    "label" => TextTools::transformText($item['name'], $textTransform),
                    "type" => "custom_link",
                    'url' => $settings['external_url'],
                    "description" => "",
                    "items" =>  $this->transformToBrizyMenu($item['child']),
                ];
            } else {
                if(empty($item['collection'])){
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
        Utils::log('Page does not exist |  Slug: '.$slug, 1, 'getCollectionItem');

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

        $collectionItems = $this->QueryBuilder->getCollectionItems($foundCollectionTypes);

        foreach ($collectionItems['page']['collection'] as $entity) {
            $entities[$entity['slug']] = $entity['id'];
        }
        $this->cache->set('ListPages', $entities);
    }

    private function setCurrentPageOnWork($collectionItem): void
    {
        Utils::log('Set the current page to work: '.$collectionItem, 1, 'setCurrentPageOnWork');
        $this->cache->set('currentPageOnWork', $collectionItem);
    }

    /**
     * @throws Exception
     */
    private function creteNewPage($slug, $title, $setActivePage = true, bool $protectedPage = false)
    {
        if ($this->pageCheck($slug)) {
            Utils::log('Request to create a new page: '.$slug, 1, 'creteNewPage');
            $this->QueryBuilder->createCollectionItem($this->cache->get('mainCollectionType'), $slug, $title, $protectedPage);
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
    private function renameSlug($itemsID, $slug)
    {
        $res = $this->QueryBuilder->updateCollectionItem($itemsID, $slug);
        Utils::log('Page name is Rename', 1, 'renameSlug');

        return $res;
    }

    /**
     * @throws Exception
     */
    private function runPageBuilder($preparedSectionOfThePage, $defaultPage = false): void
    {

        if (!$defaultPage) {
            Utils::log('Start Builder', 4, 'RunPageBuilder');
        } else {
            Utils::log('Start Builder | create default Page', 4, 'RunPageBuilder');
        }

        $PageBuilder = new PageBuilder();

//        $context = new PageBuilderContext();
//        $context->setBrizyProject();
//        $context->setMBProject();
//        $context->setMBPage();
//        $context->setBrizyPage();
//        $context->setMBLayout();


        if ($PageBuilder->run($preparedSectionOfThePage)) {
            Utils::log('Page created successfully!', 1, 'PageBuilder');
        }
    }

    /**
     * @throws Exception
     */
    private function createBlankPages(array &$parentPages, $mainLevel = true): void
    {
        Utils::log('Start created pages', 1, 'createBlankPages');
        $i = 0;
        foreach ($parentPages as &$pages) {

            $projectPages = $this->brizyApi->getAllProjectPages();

            if ($pages['landing'] == true) {
                if ($i != 0 || !$mainLevel) {
                    if (!array_key_exists($pages['slug'], $projectPages['listPages'])) {
                        $newPage = $this->creteNewPage($pages['slug'], $pages['name'], $pages['protectedPage']);
                    } else {
                        $newPage = $projectPages['listPages'][$pages['slug']];
                    }
                } else {
                    if (!array_key_exists($pages['slug'], $projectPages['listPages'])) {
                        $updateNameResult = $this->renameSlug($projectPages['listPages']['home'], $pages['slug']);
                        $newPage = $updateNameResult['updateCollectionItem']['collectionItem']['id'];
                    } else {
                        $newPage = $projectPages['listPages'][$pages['slug']];
                    }
                }

                if (!$newPage) {
                    Utils::log(
                        'Failed created pages | ID: '.$pages['id'].' | Name page: '.$pages['name'].' | Slug: '.$pages['slug'],
                        2,
                        'createBlankPages'
                    );
                } else {
                    Utils::log(
                        'Success created pages | ID: '.$pages['id'].' | Name page: '.$pages['name'].' | Slug: '.$pages['slug'],
                        1,
                        'createBlankPages'
                    );
                    $pages['collection'] = $newPage;
                }
            } else {
                if (!empty($pages['child'])) {
                    $pages['collection'] = $pages['child'][0]['collection'];
                }
            }
            if (!empty($pages['child'])) {
                $this->createBlankPages($pages['child'], false);
            }
            $i++;
        }
        $this->cache->set('menuList', [
            'id' => null,
            'uid' => null,
            'name' => null,
            'create' => false,
            'list' => $parentPages,
            'data' => "",
        ]);
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
        Utils::log('Created url pictures: '.$url.' Type folder '.$type, 1, 'getPisturesUrl');

        return $url;
    }

    /**
     * @throws Exception
     */
    private function uploadPicturesFromSections(array $sectionsItems): array
    {
        Utils::log('Start upload image', 1, 'uploadPicturesFromSections');
        foreach ($sectionsItems as &$section) {
            if ($this->checkArrayPath($section, 'settings/sections/background/photo')) {
                if ($section['settings']['sections']['background']['photo'] != null) {
                    Utils::log('Found background image', 1, 'uploadPicturesFromSections');
                    $result = $this->brizyApi->createMedia(
                        $section['settings']['sections']['background']['photo'],
                        $this->projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        Utils::log('Upload image response: '.json_encode($result), 1, 'uploadPicturesFromSections');
                        $section['settings']['sections']['background']['photo'] = $result['name'];
                        $section['settings']['sections']['background']['filename'] = $result['filename'];
                        Utils::log(
                            'Success upload image fileName: '.$result['filename'].' srcName: '.$result['name'],
                            1,
                            'uploadPicturesFromSections'
                        );
                    }
                } else {
                    $this->checkItemForMediaFiles($section['items'], $section['typeSection']);
                }
            }

            if ($this->checkArrayPath($section, 'settings/background/photo')) {
                if ($section['settings']['background']['photo'] != null) {
                    Utils::log('Found background image', 1, 'uploadPicturesFromSections');
                    $result = $this->brizyApi->createMedia(
                        $section['settings']['background']['photo'],
                        $this->projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        Utils::log('Upload image response: '.json_encode($result), 1, 'uploadPicturesFromSections');
                        $section['settings']['background']['photo'] = $result['name'];
                        $section['settings']['background']['filename'] = $result['filename'];
                        Utils::log(
                            'Success upload image fileName: '.$result['filename'].' srcName: '.$result['name'],
                            1,
                            'uploadPicturesFromSections'
                        );
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
        Utils::log('Found new image', 1, 'media');
        $downloadImageURL = $this->getPisturesUrl($item['content'], $section);
        $result = $this->brizyApi->createMedia($downloadImageURL, $this->projectId);
        if ($result) {
            if (array_key_exists('status', $result)) {
                if ($result['status'] == 201) {
                    $result = json_decode($result['body'], true);
                    Utils::log('Upload image response: '.json_encode($result), 1, 'media');
                    $item['uploadStatus'] = true;
                    $item['imageFileName'] = $result['filename'];
                    $item['content'] = $result['name'];
                    Utils::log(
                        'Success upload image fileName: '.$result['filename'].' srcName: '.$result['name'],
                        1,
                        'media'
                    );
                } else {
                    Utils::log('Unexpected answer: '.json_encode($result), 3, 'media');
                }
            } else {
                Utils::log('Bad response: '.json_encode($result), 3, 'media');
            }
        } else {
            $item['uploadStatus'] = false;
            Utils::log('The structure of the image is damaged', 3, 'media');
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
            Utils::log('Create Directory: '.$directoryPath, 1, 'createDirectory');
            mkdir($directoryPath, 0777, true);
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

    private function keepItClean()
    {
        Utils::keepItClean();
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
            Utils::log(json_encode($this->errorDump->getDetailsMessage()), 0, 'DetailsMessage');

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