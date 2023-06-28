<?php
namespace MBMigration\Builder;

use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use MBMigration\Layer\Graph\QueryBuilder;

class PageBuilder
{
    private  $cache;
    private  $QueryBuilder;

    /**
     * @throws \Exception
     */
    public function __construct($preparedSectionOfThePage, VariableCache $cache, $defaultPage = false)
    {
        $this->cache = $cache;
        $this->QueryBuilder = new QueryBuilder($cache);

        $itemsID = $this->cache->get('currentPageOnWork');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];

        $workClass = __NAMESPACE__ . '\\Layout\\' . $design . '\\' . $design;
        $_WorkClassTemplate = new $workClass($cache);

        if(!$defaultPage)
        {
            $itemsData = [];
            $menuBlock = json_decode($cache->get('menuBlock'),true);
            $itemsData['items'][] = $menuBlock;
            Utils::log('Current Page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');
            $this->cache->update('createdFirstSection',false, 'flags');
            $this->cache->update('Current', '++', 'Status');
            foreach ($preparedSectionOfThePage as $section)
            {
                $blockData = $_WorkClassTemplate->callMethod($section['typeSection'], $section, $slug);

                if($blockData === true) {
                    $itemsData['items'][] = json_decode($this->cache->get('callMethodResult'));
                } else {
                    if (!empty($blockData) && $blockData !== "null") {
                        $decodeBlock = json_decode($blockData, true);
                        $itemsData['items'][] = $decodeBlock;
                    } else {
                        Utils::log('CallMethod return null. input data: ' . json_encode($section) . ' | Slug: '.$slug, 2, 'PageBuilder');
                    }
                }
            }
            $this->sendStatus();

            $itemsData['items'][] = json_decode($cache->get('footerBlock'),true);

            $pageData = json_encode($itemsData);

            Utils::log('Request to send content to the page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');

            $this->saveLayoutJson($pageData, $slug);

            $this->QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

            Utils::log('Content added to the page successfully: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');
            return true;
        }
        else
        {
            Utils::log('Build default Page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');
            $_WorkClassTemplate->callMethod('create-Default-Page');
            return true;
        }
        return false;
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

    private function sendStatus(): void
    {
        if(Config::$devMode !== true){return;}
        echo json_encode($this->cache->get('Status')) . "\n";
    }

}