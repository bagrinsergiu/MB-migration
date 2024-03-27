<?php

namespace MBMigration\Builder;

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
use MBMigration\Core\Config;
use MBMigration\Layer\Brizy\BrizyAPI;
use Psr\Log\LoggerInterface;

class PageBuilder
{
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

    public function __construct(BrizyAPI $brizyAPI, LoggerInterface $logger)
    {
        $this->cache = VariableCache::getInstance();
        $this->brizyAPI = $brizyAPI;
        $this->logger = $logger;
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
        $fontController = new FontsController($brizyContainerId);

        $fontFamily = FontsController::getFontsFamily();

        $url = PathSlugExtractor::getFullUrl($slug);

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
}