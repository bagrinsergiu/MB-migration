<?php

namespace MBMigration\Builder;

use MBMigration\Browser\Browser;
use MBMigration\Builder\Layout\Common\LayoutElementFactory;
use MBMigration\Builder\Utils\ExecutionTimer;
use MBMigration\Builder\Layout\Common\KitLoader;
use MBMigration\Builder\Layout\Theme\Voyage\ElementFactory;
use MBMigration\Builder\Layout\Theme\Voyage\Voyage;
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

    public function run($preparedSectionOfThePage): bool
    {
        $itemsID = $this->cache->get('currentPageOnWork');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];

        $url = PathSlugExtractor::getFullUrl($slug);

        $dir = dirname(__FILE__)."/Layout/Theme";
        ExecutionTimer::start();
        set_time_limit(1200);
        $browser = Browser::instance($dir);
        $browserPage = $browser->openPage($url, $design);
        echo $slug;
        $this->cache->set('CurrentPageURL', $url);

        $workClass = __NAMESPACE__.'\\Layout\\Theme\\'.$design.'\\'.$design;


        $layoutBasePath = dirname(__FILE__)."/Layout";
        $browser = Browser::instance($layoutBasePath);
        $browserPage = $browser->openPage($url, $design);
        $brizyKit = (new KitLoader($layoutBasePath))->loadKit($design);
        $layoutElementFactory = new LayoutElementFactory($brizyKit,$browserPage);
        $themeElementFactory = $layoutElementFactory->getFactory($design);

        if ($design == 'Voyage') {
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
            file_put_contents(JSON_PATH."/fonts.json", json_encode($fontFamily));

            $_WorkClassTemplate = new Voyage(
                $url,
                $brizyKit,
                $menu,
                $headItem,
                $footerItem,
                $fontFamily,
                'lato',
                $themeElementFactory,
                $browser
            );
            $brizySections = $_WorkClassTemplate->transformBlocks($preparedSectionOfThePage);

            $pageData = json_encode($brizySections);
            $queryBuilder = $this->cache->getClass('QueryBuilder');
            $queryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

            Utils::log('Success Build Page : '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');
            $this->sendStatus();

            return true;
        } else {
            $_WorkClassTemplate = new $workClass($browserPage, $browser);
            if ($_WorkClassTemplate->build($preparedSectionOfThePage)) {
                Utils::log('Success Build Page : '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');
                $this->sendStatus($slug, ExecutionTimer::stop());

                return true;
            } else {
                Utils::log('Fail Build Page: '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');

                return false;
            }
        }


    }

    private function saveLayoutJson(string $pageData, string $pageName): void
    {
        $mainFolder = $this->cache->get('page', 'ProjectFolders');
        if (!is_dir($mainFolder)) {
            mkdir($mainFolder, 0777, true);
        }
        $json = json_encode($pageData);
        $fileFolder = $mainFolder.'/'.$pageName.'.json';
        file_put_contents($fileFolder, $json);
        Utils::log('Created json dump, page: '.$pageName, 1, 'saveLayoutJson');
    }

    private function sendStatus($pageName, $executeTime): void
    {
        if (Config::$devMode !== true) {
            return;
        }
        echo "=> Current Page: {$pageName} | Status: ".json_encode(
                $this->cache->get('Status')
            )."| Time: $executeTime \n";
    }

}