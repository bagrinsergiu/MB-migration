<?php

namespace MBMigration\Builder;

use MBMigration\Browser\Browser;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\LayoutElementFactory;
use MBMigration\Builder\Layout\Common\ThemeContext;
use MBMigration\Builder\Utils\ExecutionTimer;
use MBMigration\Builder\Layout\Common\KitLoader;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;

class PageBuilder
{
    private $cache;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->cache = VariableCache::getInstance();
    }

    /**
     * @throws ElementNotFound
     * @throws \Exception
     */
    public function run($preparedSectionOfThePage): bool
    {
        $itemsID = $this->cache->get('currentPageOnWork');
        $mainCollectionType = $this->cache->get('mainCollectionType');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];

        $fontFamily = $this->getFontsFamily();

        $url = PathSlugExtractor::getFullUrl($slug);

        $this->cache->set('CurrentPageURL', $url);

        $workClass = __NAMESPACE__.'\\Layout\\Theme\\'.$design.'\\'.$design;

        $layoutBasePath = dirname(__FILE__)."/Layout";
        $browser = Browser::instance($layoutBasePath);
        $browserPage = $browser->openPage($url, $design);

        $browserPage->globalsEval();
        $queryBuilder = $this->cache->getClass('QueryBuilder');

//        file_put_contents(JSON_PATH."/htmlPage.html", file_get_contents($url));
        if ($design !== 'Anthem') {
            $brizyKit = (new KitLoader($layoutBasePath))->loadKit($design);
            $layoutElementFactory = new LayoutElementFactory($brizyKit, $browserPage, $queryBuilder);
            $themeElementFactory = $layoutElementFactory->getFactory($design);
            $menu = $this->cache->get('menuList');
            $headItem = $this->cache->get('header', 'mainSection');
            $footerItem = $this->cache->get('footer', 'mainSection');
            $fonts = $this->cache->get('fonts', 'settings');
            foreach ($fonts as $font) {
                if ($font['name'] === 'primary') {
                    $fontFamily['Default'] = $font['uuid'];
                } else {
                    $fontFamily[$font['fontFamily']] = $font['uuid'];
                }
            }

//          file_put_contents(JSON_PATH."/fonts.json", json_encode($fontFamily));
            $themeContext = new ThemeContext(
                $design,
                $browserPage,
                $brizyKit,
                $menu,
                $headItem,
                $footerItem,
                $fontFamily['kit'],
                $fontFamily['Default'],
                $themeElementFactory,
                $mainCollectionType,
                $itemsID
            );

            $_WorkClassTemplate = new $workClass($themeContext);
            $brizySections = $_WorkClassTemplate->transformBlocks($preparedSectionOfThePage);

            $pageData = json_encode($brizySections);
            $queryBuilder = $this->cache->getClass('QueryBuilder');
            $queryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

            Utils::log('Success Build Page : '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');
            $this->sendStatus($slug, ExecutionTimer::stop());
            $browser->closePage();

            return true;

        } else {
            $_WorkClassTemplate = new $workClass($browserPage, $browser);
            if ($_WorkClassTemplate->build($preparedSectionOfThePage)) {
                Utils::log('Success Build Page : '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');
                $this->sendStatus($slug, ExecutionTimer::stop());
//                $browser->closePage();
                return true;
            } else {
                Utils::log('Fail Build Page: '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');
                $browser->closePage();

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

    private function getFontsFamily(): array
    {
        $fontFamily = [];
        $cache = VariableCache::getInstance();
        $fonts = $cache->get('fonts', 'settings');
        foreach ($fonts as $font) {
            if ($font['name'] === 'primary') {
                $fontFamily['Default'] = $font['uuid'];
            } else {
                $fontFamily['kit'][$font['fontFamily']] = $font['uuid'];
            }
        }

        return $fontFamily;
    }

}