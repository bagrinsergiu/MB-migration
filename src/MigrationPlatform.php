<?php

use Brizy\builder\VariableCache;
use Brizy\core\ErrorDump;
use Brizy\core\Utils;
use Brizy\core\Config;
use Brizy\layer\Brizy\BrizyAPI;
use Brizy\layer\Graph\QueryBuilder;
use Brizy\Parser\Parser;

require_once(__DIR__ . '/core/core.php');
class MigrationPlatform
{
    private $parser;
    /**
     * @var QueryBuilder
     */
    private $graphQueryBuildet;
    /**
     * @var QueryBuilder
     */
    private $QueryBuilder;

    public function __construct(int $projectID_MB, int $projectID_Brizy)
    {
        Utils::log('Start Process!', 1, 'MIGRATION');

        $cache      = new VariableCache();
        $brizyApi   = new BrizyAPI();
        $errorDump  = new ErrorDump();

        $errorDump->setDate($cache);

        $GraphApi_Brizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $cache->set('projectId_MB', $projectID_MB);
        $cache->set('projectId_Brizy', $projectID_Brizy);
        $cache->set('GraphApi_Brizy', $GraphApi_Brizy);
        $cache->set('graphToken', $brizyApi->getGraphToken($projectID_Brizy));

        $this->parser = new Parser($cache);
        $this->QueryBuilder = new QueryBuilder($cache);

        $parentPages = $this->parser->getParentPages();

        foreach ($parentPages as $pages)
        {
            var_dump($this->getItemsFromPage($pages['id']));
        }


        $this->getAllPage($cache); //получаем лист всех страниц
        //var_dump($cache);
        $crated = $this->creteNewPage($cache); // создаем новую страницу
        var_dump($crated);
        //$this->getAllPage($cache);
        var_dump($cache);
    }

    private function parsPage(VariableCache $cache)
    {
        return $this->parser->getSite();
    }

    private function getItemsFromPage($page)
    {
        Utils::log('Parent Page id:' . $page, 1, 'Foreach');
        $child = $this->parser->getChildFromPages($page);
        $items = [];
        foreach ($child as $sectionID)
        {
            Utils::log('Section id:' . $sectionID['id'], 1, 'Foreach');
            $section = $this->parser->getSectionsPage($sectionID['id']);
            foreach ($section as $value)
            {
                $items[] = $this->parser->getSectionsItems($value['id'], true);
            }
        }
        return $items;
    }

    private function getAllPage(VariableCache $cache)
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
        return $entities;
    }

    private function creteNewPage(VariableCache $cache, $slug, $title)
    {
        if($this->pageCheck($cache, $slug))
        {
            $this->QueryBuilder->CreateCollectionItem($cache->get('mainCollectionType'), $slug, $title);
        }
        $updatedList = $this->getAllPage($cache);
        foreach ($updatedList as $listSlug => $mainCollectionItem)
        {
            if($listSlug == $slug)
            {
                $cache->set('currentPageOnWork', $mainCollectionItem);
                return $mainCollectionItem;
            }
        }
        return false;
    }

    private function setCurrentPageOnWork($collectionItem,VariableCache $cache)
    {
        $cache->set('currentPageOnWork', $collectionItem);
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

    private function getCollectionItem(VariableCache $cache, $slug)
    {
        $ListPages = $cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems)
        {
            if($listSlug == $slug)
            {
                return $collectionItems;
            }
        }
        return false;
    }

    private function getPage()
    {
    }

}