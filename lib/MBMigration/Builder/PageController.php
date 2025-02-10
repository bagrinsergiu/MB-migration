<?php

namespace MBMigration\Builder;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use HeadlessChromium\Exception\OperationTimedOut;
use MBMigration\Builder\Layout\Common\Concern\GlobalStylePalette;
use MBMigration\Builder\Layout\Common\DTO\PageDto;
use MBMigration\Builder\Layout\Common\RootListFontFamilyExtractor;
use MBMigration\Builder\Layout\Common\RootPalettesExtractor;
use MBMigration\Builder\Layout\Common\RootPalettes;
use MBMigration\Builder\Layout\Common\ThemeInterface;
use MBMigration\Builder\Utils\UrlUtils;
use MBMigration\Core\Logger;
use MBMigration\Browser\BrowserPHP;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\LayoutElementFactory;
use MBMigration\Builder\Layout\Common\ThemeContext;
use MBMigration\Builder\Utils\ExecutionTimer;
use MBMigration\Builder\Layout\Common\KitLoader;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;
use MBMigration\Layer\MB\MBProjectDataCollector;
use Psr\Log\LoggerInterface;

class PageController
{
    use Checking;
    use DebugBackTrace;
    use GlobalStylePalette;

    private $cache;
    /**
     * @var BrowserPHP
     */
    private $browser;
    /**
     * @var BrizyAPI
     */
    private $brizyAPI;
    private LoggerInterface $logger;
    private string $domain;
    private QueryBuilder $QueryBuilder;
    private MBProjectDataCollector $parser;
    private int $projectID_Brizy;
    private PageDto $pageDTO;

    public function __construct(MBProjectDataCollector $MBProjectDataCollector, BrizyAPI $brizyAPI, QueryBuilder $QueryBuilder, LoggerInterface $logger, $projectID_Brizy)
    {
        $this->cache = VariableCache::getInstance();
        $this->pageDTO = new PageDTO();
        $this->projectStyleDTO = new PageDTO();
        $this->brizyAPI = $brizyAPI;
        $this->QueryBuilder = $QueryBuilder;
        $this->projectID_Brizy = $projectID_Brizy;
        $this->logger = $logger;
        $this->parser = $MBProjectDataCollector;
    }

    /**
     * @throws ElementNotFound
     * @throws Exception
     * []
     */
    public function run($preparedSectionOfThePage, $pageMapping): bool
    {
        $itemsID = $this->cache->get('currentPageOnWork');
        $brizyContainerId = $this->cache->get('container',);
        $mainCollectionType = $this->cache->get('mainCollectionType');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];
        $pageId = $this->cache->get('tookPage')['id'];
        $fontController = new FontsController($brizyContainerId);

        $fontFamily = FontsController::getFontsFamily();

        $url = PathSlugExtractor::getFullUrlById($pageId);

        $this->cache->set('CurrentPageURL', $url);
        $this->cache->set('pageMapping', $pageMapping);

        $workClass = __NAMESPACE__.'\\Layout\\Theme\\'.$design.'\\'.$design;
        $_WorkClassTemplate = new $workClass();

        $layoutBasePath = dirname(__FILE__)."/Layout";

        $queryBuilder = $this->cache->getClass('QueryBuilder');

        if(UrlUtils::checkRedirect($url)){
            return true;
        }

        $this->browser = BrowserPHP::instance($layoutBasePath);
        try {
            try {
                $browserPage = $this->browser->openPage($url, $design);
            } catch (OperationTimedOut $e) {

                Logger::instance()->critical($e->getMessage());

                try{
                    $this->browser->closePage();
                    $this->browser->closeBrowser();
                } catch (\Exception $e) {}

                $this->browser = BrowserPHP::instance($layoutBasePath);
                $browserPage = $this->browser->openPage($url, $design);
            }

            $ParentPages = $this->cache->get('ParentPages');
            $projectDomain = PathSlugExtractor::getProjectDomain();
            $siteMap = PathSlugExtractor::getSiteMap($ParentPages, $projectDomain);

            $brizyKit = (new KitLoader($layoutBasePath))->loadKit($design);
            $layoutElementFactory = new LayoutElementFactory(
                $brizyKit,
                $browserPage,
                $queryBuilder,
                $this->brizyAPI,
                $fontController
            );
            $themeElementFactory = $layoutElementFactory->getFactory($design);
            $brizyMenuEntity = $this->cache->get('menuList');
            $brizyMenuItems = $this->cache->get('brizyMenuItems');
            $headItem = $this->cache->get('header', 'mainSection');
            $footerItem = $this->cache->get('footer', 'mainSection');
            $listSeries = $this->cache->get('series');
            $RootPalettesExtracted = new RootPalettesExtractor($browserPage);
            $RootListFontFamilyExtractor = new RootListFontFamilyExtractor($browserPage);

            $fontController->upLoadCustomFonts($RootListFontFamilyExtractor);

            $fontFamily = FontsController::getFontsFamily();

             $themeContext = new ThemeContext(
                $design,
                $browserPage,
                $brizyKit,
                $brizyMenuEntity,
                $brizyMenuItems,
                $headItem,
                $footerItem,
                $fontFamily['kit'],
                $fontFamily['Default']['name'],
                $themeElementFactory,
                $mainCollectionType,
                $itemsID,
                $slug,
                $pageMapping,
                $RootPalettesExtracted->extractRootPalettes(),
                $this->browser,
                $listSeries,
                $this->pageDTO,
                $this->cache->get('title','settings') ?? ''
            );

            /**
             * @var ThemeInterface $_WorkClassTemplate ;
             */

            $_WorkClassTemplate->setThemeContext($themeContext);
            $this->pageDTO->setPageStyleDetails($_WorkClassTemplate->beforeBuildPage());

            $_WorkClassTemplate->setThemeContext($themeContext);
            $brizySections = $_WorkClassTemplate->transformBlocks($preparedSectionOfThePage);

            $pageData = json_encode($brizySections);
            $queryBuilder = $this->cache->getClass('QueryBuilder');
            $queryBuilder->updateCollectionItem($itemsID, $slug, $pageData);
            Logger::instance()->info('Success Build Page : '.$itemsID.' | Slug: '.$slug);
            Logger::instance()->info('Completed in  : '.ExecutionTimer::stop());
            $this->cache->update('Success', '++', 'Status');

            return true;

        } catch (\Exception $e) {
            Logger::instance()->critical('Fail Build Page: '.$itemsID.',Slug: '.$slug, [$itemsID, $slug]);
            throw $e;
        } finally {
            if ($this->browser) {
                $this->browser->closePage();
                $this->browser->closeBrowser();
            }
        }
    }

    public function getPageMapping($parentPages, $projectID_Brizy, BrizyAPI $brizyApi): array
    {
        $mapping = [];
        $domain = $brizyApi->getDomain($projectID_Brizy);
        $this->pageMapping($parentPages, $mapping, $domain);

        return $mapping;
    }

    private function pageMapping($parentPages, array &$mapping, $domain)
    {
        foreach ($parentPages as $page)
        {
            if (!empty($page['child'])){
                $this->pageMapping($page['child'],$mapping, $domain);
            }
            $mapping['/'.PathSlugExtractor::getFullUrl($page['slug'], true)] = $domain.'/'.$page['slug'];
        }
    }

    /**
     * @throws Exception
     */
    public function createBlankPages(array &$mbPageList, $existingBrizyPages)
    {
        $this->createPage($mbPageList, $existingBrizyPages, false);
        $this->createPage($mbPageList, $existingBrizyPages, true);
    }

    /**
     * @throws Exception
     */
    public function createPage(array &$pageList, $existingBrizyPages, bool $hiddenPage){

        foreach ($pageList as $i => &$page) {
            if ($page['hidden'] === $hiddenPage) {
                $title = $page['name'];

                if (!empty($page['child'])) {
                    $this->createPage($page['child'], $existingBrizyPages, $hiddenPage);
                }

                // this will avoid creating the new page when a single pate is migated
                // on single page migratin the pages are not deleted
                if (isset($existingBrizyPages[$page['slug']])) {
                    continue;
                }

                // create the page if it is not found in the current page list
                //if (!isset($existingBrizyPages['listPages'][$this->buildPage])) {
                // create the page
                if ($page['landing'] == true) {
                    $newPage = $this->creteNewPage(
                        $page['slug'],
                        $page['name'],
                        $title,
                        $page['protectedPage'],
                        false,
                        $page['position'] == 1 && !$page['parent_id']
                    );

                    if ($newPage === false) {
                        Logger::instance()->warning('Failed created page', $page);
                    } else {
                        Logger::instance()->info('Success created page', $page);
                        $page['collection'] = $newPage;
                    }
                } else {
                    if (!empty($page['child']) && !$page['hidden']) {
                        foreach ($page['child'] as $child) {
                            if (!$child['hidden']) {
                                $page['collection'] = $child['collection'];
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    private function creteNewPage(
        $slug,
        $title,
        $seoTitle,
        $protectedPage = false,
        $setActivePage = true,
        $isHome = false
    ) {
        Logger::instance()->debug('Request to create a new page: '.$slug);
        $collectionItem = $this->QueryBuilder->createCollectionItem(
            $this->cache->get('mainCollectionType'),
            $slug,
            $title,
            $seoTitle,
            $protectedPage,
            $isHome
        );

        if ($isHome) {
            $this->brizyAPI->getProjectHomePage($this->projectID_Brizy, $collectionItem['id']);
        }

        $slug = $collectionItem['slug']; // the slug can be renamed as brizy has some restrictions like slug: blog.
        $this->getAllPage();

        $entities = $this->cache->get('ListPages');
        $entities[$slug] = $collectionItem['id'];
        $this->cache->set('ListPages', $entities);

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
     * @throws \Exception
     */
    public function getAllPage(): void
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

    /**
     * @throws Exception
     */
    public function getCollectionItem($slug)
    {
        $ListPages = $this->cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems) {
            if ($listSlug == $slug) {
                return $collectionItems;
            }
        }
        Logger::instance()->info('Page does not exist |  Slug: '.$slug);

        return false;
    }

    /**
     * @throws Exception
     */
    public function setCurrentPageOnWork($collectionItem): void
    {
        Logger::instance()->debug('Set the current page to work: '.$collectionItem);
        $this->cache->set('currentPageOnWork', $collectionItem);
    }

    /**
     * @param $existingBrizyPages
     * @return mixed
     * @throws Exception
     */
    public function deleteAllPages($existingBrizyPages)
    {
        // delete all naher
        Logger::instance()->debug('Delete all collection items', [count($existingBrizyPages)]);
        foreach ($existingBrizyPages as $slug => $uri) {
            try {
                $this->QueryBuilder->deleteCollectionItem($uri);
            } catch (\Exception $e) {
                Logger::instance()->warning('Failed to delete:'.$uri.' '.$e->getMessage(), $e->getTrace());
            }
        }
        return [];
    }

    /**
     * @throws GuzzleException
     */
    public function getSectionsFromPage(array $page)
    {
        Logger::instance()->info(
            'Getting MB page items for page: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']
        );

        $listOfSections = $this->parser->getSectionsPage($page['id']);
        if (!empty($listOfSections)) {
            $sections = [];
            $position = 1;
            foreach ($listOfSections as $section) {
                $items = [
                    'sectionId' => $section['id'],
                    'typeSection' => $section['typeSection'],
                    'position' => $position,
                    'category' => $section['category'],
                    'settings' => $section['settings'],
                    'head' => [],
                    'slide' => [],
                    'items' => [],
                ];

                $sectionItems = $this->parser->getItemsFromSection($section, true);

                switch ($section['category']) {
                    case 'gallery':
                        if (!empty($sectionItems['list'])) {
                            $itemsSubGallery = [
                                'sectionId' => $section['id'],
                                'typeSection' => 'sub-gallery-layout',
                                'position' => $section['position'],
                                'category' => $section['category'],
                                'settings' => $section['settings'],
                                'head' => [],
                                'slide' => [],
                                'items' => [],
                            ];

                            foreach ($sectionItems as $key => $Item) {
                                if ($key === 'slide') {
                                    $items['slide'] = array_merge($items['slide'], $Item);
                                    $sections[] = $items;
                                } else if ($key === 'list') {
                                    $position++;
                                    $itemsSubGallery['position'] = $position;
                                    $itemsSubGallery['items'] = array_merge($items['items'], $Item);
                                    $sections[] = $itemsSubGallery;
                                }
                            }
                        } else {
                            $items['slide'] = $sectionItems['slide'];
                            $sections[] = $items;
                        }
                        break;
                    default:
                        foreach ($sectionItems as $key => $Item) {
                            if ($key === 'head') {
                                $items['head'] = array_merge($items['head'], $Item);
                            } elseif ($key === 'slide') {
                                $items['slide'] = array_merge($items['slide'], $Item);
                            } elseif ($key === 'items') {
                                $items['items'] = array_merge($items['items'], $Item);
                            }  elseif (!empty($Item)) {
                                $items['items'][] = $Item;
                            }
                        }
                        $sections[] = $items;
                        break;
                }
                $position++;
            }
            $result = $sections;
        } else {
            Logger::instance()->info(
                'Empty parent page | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']
            );
            $result = false;
        }

        return $result;
    }
}
