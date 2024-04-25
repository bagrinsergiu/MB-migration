<?php

namespace MBMigration\Builder;

use Exception;
use HeadlessChromium\Exception\OperationTimedOut;
use MBMigration\Builder\Layout\Common\ThemeInterface;
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

    public function __construct(MBProjectDataCollector $MBProjectDataCollector, BrizyAPI $brizyAPI, QueryBuilder $QueryBuilder, LoggerInterface $logger, $projectID_Brizy)
    {
        $this->cache = VariableCache::getInstance();
        $this->brizyAPI = $brizyAPI;
        $this->QueryBuilder = $QueryBuilder;
        $this->projectID_Brizy = $projectID_Brizy;
        $this->logger = $logger;
        $this->parser = $MBProjectDataCollector;
    }

    /**
     * @throws ElementNotFound
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

        $layoutBasePath = dirname(__FILE__)."/Layout";

        $queryBuilder = $this->cache->getClass('QueryBuilder');

        $this->browser = BrowserPHP::instance($layoutBasePath);
        try {
            if ($design !== 'Anthem' && $design !== 'Solstice') {

                try {
                    $browserPage = $this->browser->openPage($url, $design);
                } catch (OperationTimedOut $e) {
                    Logger::instance()->critical($e->getMessage());
                    $this->browser = BrowserPHP::instance($layoutBasePath);
                    $browserPage = $this->browser->openPage($url, $design);
                }

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

                $themeContext = new ThemeContext(
                    $design,
                    $browserPage,
                    $brizyKit,
                    $brizyMenuEntity,
                    $brizyMenuItems,
                    $headItem,
                    $footerItem,
                    $fontFamily['kit'],
                    $fontFamily['Default'],
                    $themeElementFactory,
                    $mainCollectionType,
                    $itemsID,
                    $slug
                );

                /**
                 * @var ThemeInterface $_WorkClassTemplate ;
                 */
                $_WorkClassTemplate = new $workClass($themeContext);
                $brizySections = $_WorkClassTemplate->transformBlocks($preparedSectionOfThePage);

                $pageData = json_encode($brizySections);
                $queryBuilder = $this->cache->getClass('QueryBuilder');
                $queryBuilder->updateCollectionItem($itemsID, $slug, $pageData);
                Logger::instance()->info('Success Build Page : '.$itemsID.' | Slug: '.$slug);
                Logger::instance()->info('Completed in  : '.ExecutionTimer::stop());

                return true;

            } else {
                try {
                    $browserPage = $this->browser->openPage($url, $design);
                } catch (OperationTimedOut $e) {
                    Logger::instance()->critical($e->getMessage());
                    $this->browser = BrowserPHP::instance($layoutBasePath);
                    $browserPage = $this->browser->openPage($url, $design);
                }

                $_WorkClassTemplate = new $workClass($browserPage, $this->browser, $this->brizyAPI);
                if ($_WorkClassTemplate->build($preparedSectionOfThePage)) {
                    Logger::instance()->info('Success Build Page : '.$itemsID.' | Slug: '.$slug);
                    Logger::instance()->info('Completed in  : '.ExecutionTimer::stop());

                    return true;
                } else {
                    Logger::instance()->info('Fail Build Page: '.$itemsID.' | Slug: '.$slug);

                    return false;
                }
            }
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
    public function createBlankPages(array &$mbPages, $projectTitle, $existingBrizyPages)
    {
        foreach ($mbPages as $i => &$page) {
            $title = $projectTitle.' | '.$page['name'];

            if (!empty($page['child'])) {
                $this->createBlankPages($page['child'], $projectTitle, $existingBrizyPages);
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
                    Logger::instance()->debug('Success created page', $page);
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
            //}
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

    public function getItemsFromPage(array $page)
    {
        Logger::instance()->info(
            'Getting MB page items for page: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']
        );

        $child = $this->parser->getSectionsPage($page['id']);
        if (!empty($child)) {
            $sections = [];
            foreach ($child as $value) {
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
            Logger::instance()->info(
                'Empty parent page | ID: '.$page['id'].' | Name page: '.$page['name'].' | Slug: '.$page['slug']
            );
            $result = false;
        }

        return $result;
    }
}