<?php

namespace MBMigration\Builder;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use HeadlessChromium\Exception\OperationTimedOut;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\Layout\Common\Concern\GlobalStylePalette;
use MBMigration\Builder\Layout\Common\DTO\PageDto;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\RootListFontFamilyExtractor;
use MBMigration\Builder\Layout\Common\RootPalettesExtractor;
use MBMigration\Builder\Layout\Common\RootPalettes;
use MBMigration\Builder\Layout\Common\ThemeInterface;
use MBMigration\Builder\Utils\ArrayManipulator;
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
use MBMigration\Analysis\PageQualityAnalyzer;
use Psr\Log\LoggerInterface;
use Throwable;

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
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $domain;
    /**
     * @var QueryBuilder
     */
    private $QueryBuilder;
    /**
     * @var MBProjectDataCollector
     */
    private $parser;
    /**
     * @var int
     */
    private $projectID_Brizy;
    /**
     * @var PageDto
     */
    private $pageDTO;
    /**
     * @var ArrayManipulator
     */
    private $ArrayManipulator;
    /**
     * @var bool
     */
    private $qualityAnalysisEnabled;
    /**
     * @var string|null
     */
    private $designName;

    public function __construct(
        MBProjectDataCollector $MBProjectDataCollector,
        BrizyAPI $brizyAPI,
        QueryBuilder $QueryBuilder,
        LoggerInterface $logger,
        $projectID_Brizy,
        $designName = null,
        bool $qualityAnalysis = false
    )
    {
        $this->cache = VariableCache::getInstance();
        $this->ArrayManipulator = new ArrayManipulator();
        $this->pageDTO = new PageDTO();
        $this->projectStyleDTO = new PageDTO();
        $this->brizyAPI = $brizyAPI;
        $this->QueryBuilder = $QueryBuilder;
        $this->projectID_Brizy = $projectID_Brizy;
        $this->logger = $logger;
        $this->parser = $MBProjectDataCollector;
        $this->qualityAnalysisEnabled = $qualityAnalysis;
        $this->designName = $designName;
    }

    /**     * @throws ElementNotFound
     * @throws Exception
     * []
     */
    public function run($preparedSectionOfThePage, $pageMapping): bool
    {
        $itemsID = $this->cache->get('currentPageOnWork');
        $brizyContainerId = $this->cache->get('container');
        $mainCollectionType = $this->cache->get('mainCollectionType');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];
        $pageId = $this->cache->get('tookPage')['id'];
        $fontController = new FontsController($brizyContainerId);
        $fontsFromProject= $fontController->getFontsFromProjectData();
        $previousFonts = $this->ArrayManipulator->getComparePreviousArray();

        if(!$this->ArrayManipulator->compareArrays($fontsFromProject))
        {
            // Extract detailed differences for better debugging
            $differences = $this->analyzeFontDifferences($previousFonts, $fontsFromProject);
            Logger::instance()->error('There is a difference in fonts', [
                'differences' => $differences,
                'saved_fonts_summary' => $this->getFontsSummary($previousFonts),
                'project_fonts_summary' => $this->getFontsSummary($fontsFromProject),
                'saved_full' => $previousFonts,
                'project_full' => $fontsFromProject
            ]);
        } else {
            Logger::instance()->info('Project fonts and migration fonts without damage');
        }

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
            } catch (Exception $e) {

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
            $projectID = $this->cache->get('projectId_Brizy');
            $RootPalettesExtracted = new RootPalettesExtractor($browserPage);
            $RootListFontFamilyExtractor = new RootListFontFamilyExtractor($browserPage);

            $this->handleFontUploadWithCache($fontController, $RootListFontFamilyExtractor);

            $fontFamilyS = $fontController->getFontsForSnippet();

            $fontFamily = FontsController::getFontsFamily();

            $fontFamily['kit'] = array_merge($fontFamily['kit'], $fontFamilyS);

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
                $this->cache->get('title','settings') ?? '',
                $fontController,
                $this->brizyAPI,
                $projectID
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
            $pd = FontsController::getProject_Data();

            $this->dumpPageDataCache($slug, $brizySections);

            $queryBuilder->updateCollectionItem($itemsID, $slug, $pageData);
            Logger::instance()->info('Success Build Page : '.$itemsID.' | Slug: '.$slug);
            Logger::instance()->info('Completed in  : '.ExecutionTimer::stop());
            $this->cache->update('Success', '++', 'Status');

            // После сборки каждой страницы очищаем скомпилированный кеш проекта Brizy,
            // чтобы новая версия HTML была доступна для просмотра и анализа
            try {
                Logger::instance()->info('[Migration] Clearing compiled cache for project after page build', [
                    'project_id_brizy' => $this->projectID_Brizy,
                    'slug' => $slug,
                    'item_id' => $itemsID,
                ]);
                $this->brizyAPI->clearCompileds($this->projectID_Brizy);
            } catch (\Exception $clearEx) {
                Logger::instance()->error('[Migration] Failed to clear compiled cache after page build', [
                    'project_id_brizy' => $this->projectID_Brizy,
                    'slug' => $slug,
                    'item_id' => $itemsID,
                    'error' => $clearEx->getMessage(),
                ]);
            }

            // Запускаем анализ качества миграции (после того как кеш был сброшен)
            $this->runQualityAnalysis($slug);

            return true;

        } catch (\Exception|Throwable|BadJsonProvided|ElementNotFound $e) {
            Logger::instance()->error('Fail Build Page: '.$itemsID.',Slug: '.$slug, [$itemsID, $slug]);
            Logger::instance()->error($e->getMessage());
            return false;
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
        $domain = $this->cache->get('brizyProjectDomain');
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
            $mapping['/'.PathSlugExtractor::getFullUrlById($page['id'], true)] = '/'.$page['slug'];
        }
    }

    /**
     * @throws Exception
     */
    public function createBlankPages(array &$mbPageList, $existingBrizyPages)
    {
        $this->cache->set('setHomePage', false);
        $this->createPage($mbPageList, $existingBrizyPages, false);
        $this->createPage($mbPageList, $existingBrizyPages, true);
    }

    /**
     * @throws Exception
     */
    public function createPage_(array &$pageList, $existingBrizyPages, bool $hiddenPage, $i = 0, $parent = null){
        foreach ($pageList as $i => &$page) {

            if (!empty($page['child'])) {
                $this->createPage($page['child'], $existingBrizyPages, $hiddenPage, $i, $page['parent_id']);
            }

            if ($page['hidden'] === $hiddenPage) {
                $title = $page['name'];

                // this will avoid creating the new page when a single pate is migated
                // on single page migratin the pages are not deleted
                if (isset($existingBrizyPages[$page['slug']])) {
                    continue;
                }

                // create the page if it is not found in the current page list
                //if (!isset($existingBrizyPages['listPages'][$this->buildPage])) {
                // create the page
                if ($page['landing'] == true) {
                    if(!$this->cache->get('setHomePage')) {
                        if ($i === 0 && $parent === null) {
                            $isHome = true;
                            $this->cache->set('setHomePage', true);
                        } elseif ($page['position'] == 1 && !$page['parent_id']) {
                            $isHome = true;
                            $this->cache->set('setHomePage', true);
                        } else {
                            $isHome = false;
                        }
                    } else {
                        $isHome = false;
                    }

                    $newPage = $this->creteNewPage(
                        $page['slug'],
                        $page['name'],
                        $title,
                        $page['protectedPage'],
                        false,
                        $isHome
                    );

                    if ($newPage === false) {
                        Logger::instance()->warning('Failed created page', $page);
                    } else {
                        $pageStatus = $hiddenPage ? "hidden" : "public";
                        Logger::instance()->info('Success created ' . $pageStatus . ' page', $page);
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

    public function createPage(array &$pageList, $existingBrizyPages, bool $hiddenPage, $i = 0, $parent = null)
    {
        if ($parent === null && !$this->cache->get('homePageSlug')) {
//            $homePage = $this->findHomePage($pageList);
            $homePage = $this->findHomePageRecursive($pageList);
            if ($homePage) {
                $this->cache->set('homePageSlug', $homePage['slug']);
            }
        }

        foreach ($pageList as $i => &$page) {
            if (!empty($page['child'])) {
                $this->createPage($page['child'], $existingBrizyPages, $hiddenPage, $i, $page['parent_id']);
            }

            if ($page['hidden'] !== $hiddenPage) {
                continue;
            }

            if (isset($existingBrizyPages[$page['slug']])) {
                continue;
            }

            $title = $page['name'];

            if ($page['landing'] == true) {
                $isHome = ($page['slug'] === $this->cache->get('homePageSlug'));
                try {
                    $newPage = $this->creteNewPage(
                        $page['slug'],
                        $page['name'],
                        $title,
                        $page['protectedPage'],
                        false,
                        $isHome
                    );

                    if ($newPage === false) {
                        Logger::instance()->warning('Failed to create page', $page);
                    } else {
                        $pageStatus = $hiddenPage ? "hidden" : "public";
                        Logger::instance()->info('Successfully created ' . $pageStatus . ' page', $page);
                        $page['collection'] = $newPage;
                    }

                } catch (\Exception $e) {
                    Logger::instance()->warning('Failed to create page', $page);
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

    public function findHomePage(array $pages): ?array {
        $candidates = array_filter($pages, function ($page) {
            return $page['parent_id'] === null && $page['landing'] === true;
        });

        if (empty($candidates)) {
            return null;
        }

        usort($candidates, function ($a, $b) {
            return $a['position'] <=> $b['position'];
        });

        return $candidates[0];
    }

    public function findHomePageRecursive(array $pages): ?array {
        $candidates = [];

        $this->collectLandingPages($pages, $candidates);

        if (empty($candidates)) {
            return null;
        }

        usort($candidates, function ($a, $b) {
            if ($a['level'] !== $b['level']) {
                return $a['level'] <=> $b['level'];
            }
            return $a['position'] <=> $b['position'];
        });

        return $candidates[0];
    }

    private function collectLandingPages(array $pages, array &$candidates, int $level = 0): void {
        foreach ($pages as $page) {
            if ($page['landing'] === true) {
                $page['level'] = $level;
                $candidates[] = $page;
            }
            if (!empty($page['child'])) {
                $this->collectLandingPages($page['child'], $candidates, $level + 1);
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
        $collectionTypes = $this->QueryBuilder->getCollectionTypes($this->projectID_Brizy);

        $this->emptyCheck(
            $collectionTypes,
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
            "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-="
        );
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
                    'gallery' => [],
                    'items' => [],
                ];

                $sectionItems = $this->parser->getItemsFromSection($section, true);

                switch ($section['category']) {
                    case 'gallery':
                        if (!empty($sectionItems['list']) && $this->checkSubGalleryLayout($sectionItems['list'])) {
                            $itemsSubGallery = [
                                'sectionId' => $section['id'],
                                'typeSection' => 'sub-gallery-layout',
                                'position' => $section['position'],
                                'category' => $section['category'],
                                'settings' => $section['settings'],
                                'head' => [],
                                'slide' => [],
                                'gallery' => [],
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
                            } elseif ($key === 'gallery') {
                                $items['gallery'] = array_merge($items['gallery'], $Item);
                            } elseif ($key === 'items') {
                                $items['items'] = array_merge($items['items'], $Item);
                            } elseif (!empty($Item)) {
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

    private function checkSubGalleryLayout(array $list): bool
    {
        if (empty($list)) {
            return false;
        }

        foreach ($list as $listObject) {
            if (empty($listObject['items'])) {
                continue;
            }

            foreach ($listObject['items'] as $item) {
                if ($item['category'] === 'photo' && !empty($item['content'])) {
                    return true;
                }

//                if ($item['category'] === 'text' && !empty($item['content'])) {
//                    $textContent = strip_tags($item['content']);
//                    $textContent = trim($textContent);
//                    if (!empty($textContent)) {
//                        return true;
//                    }
//                }
            }
        }

        return false;
    }

    public function dumpPageDataCache($name, BrizyPage $pageData)
    {
        $projectFolders = $this->cache->get('ProjectFolders');
        $fileName = $projectFolders['page']."/".$name.'.json';
        file_put_contents(
            $fileName,
            json_encode($pageData->jsonSerialize())
        );
    }

    /**
     * Handle font upload with caching to optimize performance
     *
     * @param FontsController $fontController
     * @param RootListFontFamilyExtractor $RootListFontFamilyExtractor
     * @return void
     * @throws \Exception
     */
    private function handleFontUploadWithCache(FontsController $fontController, RootListFontFamilyExtractor $RootListFontFamilyExtractor): void
    {
        $cache = VariableCache::getInstance();
        if  ( !empty($cache->get('settings')['fonts']) ) {
            return;
        }

        // Perform the actual font upload
        $fontController->upLoadCustomFonts($RootListFontFamilyExtractor);
    }

    /**
     * Запустить анализ качества миграции страницы
     * 
     * @param string $pageSlug Slug страницы
     * @return void
     */
    private function runQualityAnalysis(string $pageSlug): void
    {
        try {
            // BREAKPOINT 1: Проверка включения анализа
            $cacheData = $this->cache->getCache();
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 1: Checking if quality analysis should run =====", [
                'page_slug' => $pageSlug,
                'quality_analysis_enabled_param' => $this->qualityAnalysisEnabled,
                'env_quality_analysis_enabled' => $_ENV['QUALITY_ANALYSIS_ENABLED'] ?? 'not_set',
                'cache_keys_sample' => array_slice(array_keys($cacheData), 0, 10)
            ]);
            
            // Проверяем, включен ли анализ через параметр запроса или переменную окружения
            if (!$this->qualityAnalysisEnabled) {
                // Если параметр не передан или false, проверяем переменную окружения
                $analysisEnabled = $_ENV['QUALITY_ANALYSIS_ENABLED'] ?? false;
                Logger::instance()->debug("[Quality Analysis] Parameter disabled, checking env variable", [
                    'env_value' => $analysisEnabled,
                    'page_slug' => $pageSlug
                ]);
                if (!$analysisEnabled) {
                    Logger::instance()->info("[Quality Analysis] Analysis disabled, skipping", [
                        'page_slug' => $pageSlug,
                        'reason' => 'Both parameter and env variable are false/not set'
                    ]);
                    return;
                }
            }

            // BREAKPOINT 2: Получение данных из кэша
            $sourceUrl = $this->cache->get('CurrentPageURL');
            $brizyProjectDomain = $this->cache->get('brizyProjectDomain');
            $mbProjectUuid = $this->cache->get('mb_project_uuid') ?? $this->cache->get('projectId_MB');
            
            $cacheData = $this->cache->getCache();
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 2: Retrieved data from cache =====", [
                'source_url' => $sourceUrl,
                'brizy_project_domain' => $brizyProjectDomain,
                'mb_project_uuid' => $mbProjectUuid,
                'brizy_project_id' => $this->projectID_Brizy,
                'page_slug' => $pageSlug,
                'has_source_url' => !empty($sourceUrl),
                'has_brizy_domain' => !empty($brizyProjectDomain),
                'has_mb_uuid' => !empty($mbProjectUuid),
                'cache_keys_sample' => array_slice(array_keys($cacheData), 0, 10)
            ]);

            if (empty($sourceUrl) || empty($brizyProjectDomain)) {
                $cacheData = $this->cache->getCache();
                Logger::instance()->warning("[Quality Analysis] ===== BREAKPOINT 2 FAILED: Missing required URLs =====", [
                    'source_url' => $sourceUrl,
                    'brizy_domain' => $brizyProjectDomain,
                    'page_slug' => $pageSlug,
                    'available_cache_keys_sample' => array_slice(array_keys($cacheData), 0, 20)
                ]);
                return;
            }

            // BREAKPOINT 3: Формирование URL мигрированной страницы
            $migratedUrl = rtrim($brizyProjectDomain, '/') . '/' . ltrim($pageSlug, '/');
            
            // Получаем designName из кэша или используем сохраненное значение
            $designName = $this->designName;
            if (empty($designName)) {
                $settings = $this->cache->get('settings');
                $designName = $settings['design'] ?? 'default';
            }
            
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 3: URLs prepared, ready to start analysis =====", [
                'page_slug' => $pageSlug,
                'source_url' => $sourceUrl,
                'migrated_url' => $migratedUrl,
                'mb_project_uuid' => $mbProjectUuid,
                'brizy_project_id' => $this->projectID_Brizy,
                'brizy_project_domain' => $brizyProjectDomain,
                'design_name' => $designName
            ]);

            // Запускаем анализ
            $analyzer = new PageQualityAnalyzer($this->qualityAnalysisEnabled);
            $reportId = $analyzer->analyzePage(
                $sourceUrl,
                $migratedUrl,
                $pageSlug,
                (string)$mbProjectUuid,
                $this->projectID_Brizy,
                $designName
            );
            
            // BREAKPOINT 4: Результат анализа
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 4: Analysis completed =====", [
                'page_slug' => $pageSlug,
                'report_id' => $reportId,
                'has_report_id' => !empty($reportId)
            ]);

        } catch (\Exception $e) {
            // Не прерываем процесс миграции из-за ошибки анализа
            Logger::instance()->error("[Quality Analysis] ===== BREAKPOINT ERROR: Quality analysis failed (non-blocking) =====", [
                'page_slug' => $pageSlug,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Analyze differences between saved and project fonts
     * 
     * @param array $savedFonts
     * @param array $projectFonts
     * @return array
     */
    private function analyzeFontDifferences(array $savedFonts, array $projectFonts): array
    {
        $differences = [
            'deleted_families' => [],
            'added_families' => [],
            'changed_identifiers' => [],
            'sections_compared' => ['config', 'upload', 'google', 'blocks']
        ];

        // Compare each font section
        foreach (['config', 'upload', 'google', 'blocks'] as $section) {
            $savedData = $savedFonts[$section]['data'] ?? [];
            $projectData = $projectFonts[$section]['data'] ?? [];

            $savedFamilies = $this->extractFontFamiliesFromSection($savedData);
            $projectFamilies = $this->extractFontFamiliesFromSection($projectData);

            // Find deleted families
            foreach ($savedFamilies as $family => $identifier) {
                if (!isset($projectFamilies[$family])) {
                    $differences['deleted_families'][] = [
                        'section' => $section,
                        'family' => $family,
                        'identifier' => $identifier
                    ];
                } elseif ($projectFamilies[$family] !== $identifier) {
                    $differences['changed_identifiers'][] = [
                        'section' => $section,
                        'family' => $family,
                        'old_identifier' => $identifier,
                        'new_identifier' => $projectFamilies[$family]
                    ];
                }
            }

            // Find added families
            foreach ($projectFamilies as $family => $identifier) {
                if (!isset($savedFamilies[$family])) {
                    $differences['added_families'][] = [
                        'section' => $section,
                        'family' => $family,
                        'identifier' => $identifier
                    ];
                }
            }
        }

        return $differences;
    }

    /**
     * Extract font families from a section's data array
     * 
     * @param array $data
     * @return array
     */
    private function extractFontFamiliesFromSection(array $data): array
    {
        $families = [];
        foreach ($data as $item) {
            if (is_array($item) && isset($item['family'])) {
                $identifier = $item['id'] ?? $item['uuid'] ?? $item['brizyId'] ?? null;
                if ($identifier !== null) {
                    $families[$item['family']] = $identifier;
                }
            }
        }
        return $families;
    }

    /**
     * Get summary of fonts structure
     * 
     * @param array $fonts
     * @return array
     */
    private function getFontsSummary(array $fonts): array
    {
        $summary = [];
        foreach (['config', 'upload', 'google', 'blocks'] as $section) {
            $data = $fonts[$section]['data'] ?? [];
            $families = [];
            foreach ($data as $item) {
                if (is_array($item) && isset($item['family'])) {
                    $families[] = $item['family'];
                }
            }
            $summary[$section] = [
                'count' => count($families),
                'families' => $families
            ];
        }
        return $summary;
    }

}
