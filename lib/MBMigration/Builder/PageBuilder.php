<?php

namespace MBMigration\Builder;

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

    public function run($preparedSectionOfThePage): bool
    {
        $itemsID = $this->cache->get('currentPageOnWork');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];

        $url = PathSlugExtractor::getFullUrl($slug);

        $this->cache->set('CurrentPageURL', $url);

        $workClass = __NAMESPACE__.'\\Layout\\Theme\\'.$design.'\\'.$design;

        if ($design == 'Aurora') {
            $layoutBasePath = dirname(__FILE__)."/Layout";
            $brizyKit = (new KitLoader($layoutBasePath))->loadKit($design);
            $menu = $this->cache->get('menuList');
            $_WorkClassTemplate = new $workClass($brizyKit,$menu);
        } else {
            $_WorkClassTemplate = new $workClass();
        }

        if ($_WorkClassTemplate->build($preparedSectionOfThePage)) {
            Utils::log('Success Build Page : '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');
            $this->sendStatus();

            return true;
        } else {
            Utils::log('Fail Build Page: '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');

            return false;
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

    private function sendStatus(): void
    {
        if (Config::$devMode !== true) {
            return;
        }
        echo json_encode($this->cache->get('Status'))."\n";
    }

}