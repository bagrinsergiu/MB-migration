<?php

namespace MBMigration\Builder;

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

    public function __construct(BrizyAPI $brizyAPI, LoggerInterface $logger)
    {
        $this->cache = VariableCache::getInstance();
        $this->brizyAPI = $brizyAPI;
        $this->logger = $logger;
    }

    /**
     * @throws ElementNotFound
     */
    public function run($preparedSectionOfThePage): bool
    {
        $itemsID = $this->cache->get('currentPageOnWork');
        $mainCollectionType = $this->cache->get('mainCollectionType');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];

        $fontFamily = FontsController::getFontsFamily();

        $url = PathSlugExtractor::getFullUrl($slug);

        $this->cache->set('CurrentPageURL', $url);

        $workClass = __NAMESPACE__.'\\Layout\\Theme\\'.$design.'\\'.$design;

        $layoutBasePath = dirname(__FILE__)."/Layout";

        $queryBuilder = $this->cache->getClass('QueryBuilder');

        if ($design !== 'Anthem' && $design !== 'Solstice') {
            $this->browser = BrowserPHP::instance($layoutBasePath,$this->logger);
            $browserPage = $this->browser->openPage($url, $design);
            $brizyKit = (new KitLoader($layoutBasePath))->loadKit($design);
            $layoutElementFactory = new LayoutElementFactory($brizyKit, $browserPage, $queryBuilder, $this->brizyAPI);
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

            $_WorkClassTemplate = new $workClass($themeContext);
            $brizySections = $_WorkClassTemplate->transformBlocks($preparedSectionOfThePage);

            $pageData = json_encode($brizySections);
            $queryBuilder = $this->cache->getClass('QueryBuilder');
            $queryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

            Logger::instance()->info('Success Build Page : '.$itemsID.' | Slug: '.$slug);
            $this->sendStatus($slug, ExecutionTimer::stop());

            return true;

        } else {
            $this->browser = BrowserPHP::instance($layoutBasePath, $this->logger);
            $browserPage = $this->browser->openPage($url, $design);
            $_WorkClassTemplate = new $workClass($browserPage, $this->browser, $this->brizyAPI);
            if ($_WorkClassTemplate->build($preparedSectionOfThePage)) {
                Logger::instance()->info('Success Build Page : '.$itemsID.' | Slug: '.$slug);
                $this->sendStatus($slug, ExecutionTimer::stop());

                return true;
            } else {
                Logger::instance()->info('Fail Build Page: '.$itemsID.' | Slug: '.$slug);

                return false;
            }
        }
    }

    private function sendStatus($pageName, $executeTime): void
    {
        if (Config::$devMode !== true) {
            return;
        }
        echo " => Current Page: {$pageName} | Status: ".json_encode(
                $this->cache->get('Status')
            )."| Time: $executeTime \n";
    }

    public function closeBrowser()
    {
        $this->browser->closeBrowser();
    }

}