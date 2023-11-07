<?php
namespace MBMigration\Builder;

use MBMigration\Browser\Browser;
use MBMigration\Builder\Utils\ExecutionTimer;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;

class PageBuilder
{
    private  $cache;

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

        $workClass = __NAMESPACE__ . '\\Layout\\Theme\\' . $design . '\\' . $design;

        $_WorkClassTemplate = new $workClass($browserPage, $browser);

        if($_WorkClassTemplate->build($preparedSectionOfThePage)) {
            Utils::log('Success Build Page : ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');
            $this->sendStatus($slug, ExecutionTimer::stop());
            return true;
        } else {
            Utils::log('Fail Build Page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');
            return false;
        }
    }

    private function saveLayoutJson(string $pageData, string $pageName): void
    {
        $mainFolder = $this->cache->get('page','ProjectFolders');
        if(!is_dir($mainFolder)) {
            mkdir($mainFolder, 0777, true);
        }
        $json = json_encode($pageData);
        $fileFolder =  $mainFolder . '/' . $pageName . '.json';
        file_put_contents($fileFolder, $json);
        Utils::log('Created json dump, page: '. $pageName, 1, 'saveLayoutJson' );
    }

    private function sendStatus($pageName, $executeTime): void
    {
        if(Config::$devMode !== true){return;}
        echo "=> Current Page: {$pageName} | Status: " . json_encode($this->cache->get('Status')) . "| Time: $executeTime \n";
    }

}