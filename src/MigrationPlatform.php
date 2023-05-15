<?php

use Brizy\Builder\VariableCache;
use Brizy\Core\ErrorDump;
use Brizy\Core\Utils;
use Brizy\Core\Config;
use Brizy\Layer\Brizy\BrizyAPI;
use Brizy\Layer\Graph\QueryBuilder;
use Brizy\Parser\Parser;
use Builder\ItemsBuilder;

require_once(__DIR__ . '/Core/core.php');
class MigrationPlatform
{
    private Parser $parser;
    private QueryBuilder $QueryBuilder;

    public function __construct(int $projectID_MB, int $projectID_Brizy)
    {
        Utils::log('-------------------------------------------------------------------------------------- []', 4, '');
        Utils::log('Start Process!', 4, 'MIGRATION');

        $cache     = new VariableCache();
        $brizyApi  = new BrizyAPI();
        $errorDump = new ErrorDump();

        $errorDump->setDate($cache);

        $GraphApi_Brizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $cache->set('projectId_MB', $projectID_MB);
        $cache->set('projectId_Brizy', $projectID_Brizy);

        $this->parser = new Parser($cache);

        $parentPages = $this->parser->getParentPages();

        if ($parentPages)
        {
            $cache->set('settings', $this->parser->getSite());
            $cache->set('GraphApi_Brizy', $GraphApi_Brizy);
            $cache->set('graphToken', $brizyApi->getGraphToken($projectID_Brizy));

            $this->QueryBuilder = new QueryBuilder($cache);

            $this->getAllPage($cache);

            $this->createBlankPages($parentPages, $cache);

            foreach ($parentPages as $pages)
            {
//                if ($pages['slug'] != 'about-us')
//                {
//                    continue;
//                }

                Utils::log('Take page | ID: ' . $pages['id'], 4, 'MAIN Foreach');

                $cache->set('tookPage', $pages);

                $preparedPage = $this->getItemsFromPage($pages);
                $currentParent = $parentPages[array_key_first($parentPages)];
                if ($pages['id'] === $currentParent['id']) {
                    $this->setCurrentPageOnWork($this->getCollectionItem("home", $cache), $cache);

                    if ($preparedPage) {
                        $this->runPageBuilder($preparedPage, $cache);
                    } else {
                        Utils::log('Set default page template | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'Foreach');
                        $this->runPageBuilder($preparedPage, $cache, true);
                    }
                } else {
                    $collectionItem = $this->getCollectionItem($pages['slug'], $cache);

                    if (!$collectionItem) {
                        $newPage = $this->creteNewPage($pages['slug'], $pages['name'], $cache);
                        if (!$newPage) {
                            Utils::log('Failed created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 2, 'creteNewPage');
                        } else {
                            Utils::log('Success created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'creteNewPage');
                            $collectionItem = $newPage;
                        }
                    }

                    $this->setCurrentPageOnWork($collectionItem, $cache);

                    if ($preparedPage) {
                        $this->runPageBuilder($preparedPage, $cache);
                    } else {
                        Utils::log('Set default page template | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'Foreach');
                        $this->runPageBuilder($preparedPage, $cache, true);
                    }
                }
            }
            Utils::log('Project migration completed successfully!', 6, 'PROCESS');
            Utils::log('END', 6, 'PROCESS');
        }
        else
        {
            Utils::log('MB project not found, migration did not start, process completed without errors!', 1, "MAIN Foreach");
            Utils::log('END', 1, "PROCESS");
        }
        //print_r($cache);
    }

    private function getItemsFromPage(array $page)
    {
        Utils::log('Parent Page id: ' . $page['id'] . ' | Name page: ' . $page['name'] . ' | Slug: ' . $page['slug'], 1, 'getItemsFromPage');
        $child = $this->parser->getChildFromPages($page['id']);

        if(!empty($child))
        {
            $items = [];

            foreach ($child as $sectionID)
            {
                Utils::log('Current Section id: ' . $sectionID['id'], 1, 'getItemsFromPage');
                $section = $this->parser->getSectionsPage($sectionID['id']);

                foreach ($section as $value)
                {
                    Utils::log('Collection of item id: ' .$value['id'].' from section id: '. $sectionID['id'], 1, 'getItemsFromPage');

                    $items[] =[
                        "typeSection" => $value['typeSection'],
                        "data"=>$this->parser->getSectionsItems($value, true)
                    ];
                }
            }
            $result = $items;
        } else if (empty($child)) {
            $sectionFromParent = $this->parser->getSectionsPage($page['id']);
            if(empty($sectionFromParent))
            {
                $result = false;
            } else {
                foreach ($sectionFromParent as $value)
                {
                    Utils::log('Collection of item id: ' .$value['id'].' -> section id: '. $sectionFromParent['id'] .'-> Parent page id:'. $page['id'], 1, 'getItemsFromPage');

                    $items[] =[
                        "typeSection" => $value['typeSection'],
                        $items[] = $this->parser->getSectionsItems($value, true)
                    ];
                }
                $result = $items;
            }

        }
        else
        {
            Utils::log('Empty parent page | ID: ' . $page['id'] . ' | Name page: ' . $page['name'] . ' | Slug: ' . $page['slug'], 1, 'getItemsFromPage');
            $result = false;
        }

        return $result;
    }
    private function getCollectionItem($slug, VariableCache $cache)
    {
        $ListPages = $cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems)
        {
            if($listSlug == $slug)
            {
                return $collectionItems;
            }
        }
        Utils::log('Page does not exist |  Slug: ' . $slug, 1, 'getCollectionItem');
        return false;
    }

    private function getAllPage(VariableCache $cache): void
    {
        $collectionTypes = $this->QueryBuilder->getCollectionTypes();

        $foundCollectionTypes = [];
        $entities = [];

        foreach ($collectionTypes as $collectionType) {
            if ($collectionType['slug'] == 'page') {
                $foundCollectionTypes[$collectionType['slug']] = $collectionType['id'];
                $cache->set('mainCollectionType', $collectionType['id']);
            }
        }

        $collectionItems = $this->QueryBuilder->getCollectionItems($foundCollectionTypes);

        foreach ($collectionItems as $collectionItem) {
            foreach ($collectionItem['collection'] as $entity) {
                $entities[$entity['slug']] = $entity['id'];
            }
        }
        $cache->set('ListPages', $entities);
    }

    private function setCurrentPageOnWork($collectionItem, VariableCache $cache): void
    {
        Utils::log('Set the current page to work: ' . $collectionItem, 1, 'setCurrentPageOnWork');
        $cache->set('currentPageOnWork', $collectionItem);
    }

    private function creteNewPage($slug, $title, VariableCache $cache, $setActivePage = true)
    {
        if($this->pageCheck($cache, $slug))
        {
            Utils::log('Request to create a new page: ' . $slug, 1, 'creteNewPage');
            $this->QueryBuilder->CreateCollectionItem($cache->get('mainCollectionType'), $slug, $title);
            $this->getAllPage($cache);
        }

        $mainCollectionItem = $this->getCollectionItem($slug, $cache);
        if($mainCollectionItem)
        {
            if($setActivePage)
            {
                $cache->set('currentPageOnWork', $mainCollectionItem);
                return $mainCollectionItem;
            }
            return $mainCollectionItem;
        }
        return false;
    }

    private function pageCheck(VariableCache $cache, $slug): bool
    {
        $ListPages = $cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems)
        {
            if($listSlug == $slug)
            {
              return false;
            }
        }
        return true;
    }

    private function runPageBuilder($preparedPage, VariableCache $cache , $defaultPage = false): void
    {

        if(!$defaultPage)
        {
            Utils::log('Start Builder', 4, 'RunPageBuilder');
        }
        else
        {
            Utils::log('Start Builder | create default Page', 4, 'RunPageBuilder');
        }

        $PageBuilder = new ItemsBuilder($preparedPage, $cache, $defaultPage);

        if($PageBuilder)
        {
            Utils::log('Page created successfully!', 1, 'PageBuilder');
        }
    }

    private function createBlankPages(array $parentPages, VariableCache $cache): void
    {
        Utils::log('Start created pages', 1, 'createBlankPages');

        foreach ($parentPages as &$pages)
        {
            $newPage = $this->creteNewPage($pages['slug'], $pages['name'], $cache, false);
            if (!$newPage) {
                Utils::log('Failed created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 2, 'createBlankPages');
            } else {
                Utils::log('Success created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'createBlankPages');
                $pages['collection'] = $newPage;
            }
        }
        $cache->set('menuList', ['create' => false , 'list' => $parentPages]);
    }

}