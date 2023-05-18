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
    private BrizyAPI $brizyApi;
    private VariableCache $cache;

    public function __construct(int $projectID_MB, int $projectID_Brizy)
    {
        Utils::log('-------------------------------------------------------------------------------------- []', 4, '');
        Utils::log('Start Process!', 4, 'MIGRATION');

        $this->cache     = new VariableCache();
        $this->brizyApi  = new BrizyAPI();
        $errorDump       = new ErrorDump();

        $errorDump->setDate($this->cache);

        $GraphApi_Brizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $this->cache->set('projectId_MB', $projectID_MB);
        $this->cache->set('projectId_Brizy', $projectID_Brizy);

        $this->parser = new Parser($this->cache);

        $parentPages = $this->parser->getParentPages();

        if ($parentPages)
        {
            $this->cache->set('settings', $this->parser->getSite());
            $this->cache->set('GraphApi_Brizy', $GraphApi_Brizy);
            $this->cache->set('graphToken', $this->brizyApi->getGraphToken($projectID_Brizy));

            $this->QueryBuilder = new QueryBuilder($this->cache);

            $this->getAllPage();

            $this->createBlankPages($parentPages);

            foreach ($parentPages as $pages)
            {
                if ($pages['slug'] != 'fellowship')
                {
                    continue;
                }

                Utils::log('Take page | ID: ' . $pages['id'], 4, 'MAIN Foreach');

                $this->cache->set('tookPage', $pages);

                $preparedPage = $this->getItemsFromPage($pages);
                $currentParent = $parentPages[array_key_first($parentPages)];

                $preparedPage = $this->uploadPicturesFromSections($preparedPage);

                if ($pages['id'] === $currentParent['id']) {
                    $this->setCurrentPageOnWork($this->getCollectionItem("home"));
                    $preparedPage = $this->sortArrayByPosition($preparedPage);
                    if ($preparedPage) {
                        $this->runPageBuilder($preparedPage);
                    } else {
                        Utils::log('Set default page template | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'Foreach');
                        $this->runPageBuilder($preparedPage);
                    }
                } else {
                    $collectionItem = $this->getCollectionItem($pages['slug']);

                    if (!$collectionItem) {
                        $newPage = $this->creteNewPage($pages['slug'], $pages['name']);
                        if (!$newPage) {
                            Utils::log('Failed created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 2, 'creteNewPage');
                        } else {
                            Utils::log('Success created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'creteNewPage');
                            $collectionItem = $newPage;
                        }
                    }

                    $this->setCurrentPageOnWork($collectionItem);

                    if ($preparedPage) {
                        $this->runPageBuilder($preparedPage);
                    } else {
                        Utils::log('Set default page template | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'Foreach');
                        $this->runPageBuilder($preparedPage);
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
    }

    private function sortArrayByPosition($array) {
        usort($array, function($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $array;
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
                    $color = '';
                    if(isset($value['settings']['color']['subpalette']))
                    {
                        $color = $this->getColorFromPalette($value['settings']['color']['subpalette'], $this->cache);
                    }

                    $items[] =[
                        'typeSection'   => $value['typeSection'],
                        'position'      => $value['position'],
                        'settings'      => $value['settings'],
                        'color'         => $color,
                        'items'         => $this->parser->getSectionsItems($value, true)
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
                    Utils::log('Collection of item id: ' .$value['id'].' -> Parent page id:'. $page['id'], 1, 'getItemsFromPage');
                    $color = '';
                    if(isset($value['settings']['color']['subpalette']))
                    {
                        $color = $this->getColorFromPalette($value['settings']['color']['subpalette'], $this->cache);
                    }

                    $items[] =[
                        'typeSection'   => $value['typeSection'],
                        'position'      => $value['position'],
                        'settings'      => $value['settings'],
                        'color'         => $color,
                        'items'         => $sectionsItems = $this->parser->getSectionsItems($value, true)
                    ];
                }
                $result = $items;
            }
        } else{
            Utils::log('Empty parent page | ID: ' . $page['id'] . ' | Name page: ' . $page['name'] . ' | Slug: ' . $page['slug'], 1, 'getItemsFromPage');
            $result = false;
        }

        return $result;
    }

    private function getColorFromPalette(string $color)
    {
        $parameter = $this->cache->get('settings')['parameter'];
        $map = [
            'subpalette1' => 'color1',
            'subpalette2' => 'color2',
            'subpalette3' => 'color3',
            'subpalette4' => 'color4',
            'subpalette5' => 'color5',
            'subpalette6' => 'color6'
        ];
        foreach ($parameter['palette'] as $palette)
        {
            if ($palette['tag'] == $map[$color])
            {
                return $palette['color'];
            }
        }
        return false;
    }

    private function getCollectionItem($slug)
    {
        $ListPages = $this->cache->get('ListPages');
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

    private function getAllPage(): void
    {
        $collectionTypes = $this->QueryBuilder->getCollectionTypes();

        $foundCollectionTypes = [];
        $entities = [];

        foreach ($collectionTypes as $collectionType) {
            if ($collectionType['slug'] == 'page') {
                $foundCollectionTypes[$collectionType['slug']] = $collectionType['id'];
                $this->cache->set('mainCollectionType', $collectionType['id']);
            }
        }

        $collectionItems = $this->QueryBuilder->getCollectionItems($foundCollectionTypes);

        foreach ($collectionItems as $collectionItem) {
            foreach ($collectionItem['collection'] as $entity) {
                $entities[$entity['slug']] = $entity['id'];
            }
        }
        $this->cache->set('ListPages', $entities);
    }

    private function setCurrentPageOnWork($collectionItem): void
    {
        Utils::log('Set the current page to work: ' . $collectionItem, 1, 'setCurrentPageOnWork');
        $this->cache->set('currentPageOnWork', $collectionItem);
    }

    private function creteNewPage($slug, $title, $setActivePage = true)
    {
        if($this->pageCheck($slug))
        {
            Utils::log('Request to create a new page: ' . $slug, 1, 'creteNewPage');
            $this->QueryBuilder->CreateCollectionItem($this->cache->get('mainCollectionType'), $slug, $title);
            $this->getAllPage();
        }

        $mainCollectionItem = $this->getCollectionItem($slug);
        if($mainCollectionItem)
        {
            if($setActivePage)
            {
                $this->cache->set('currentPageOnWork', $mainCollectionItem);
                return $mainCollectionItem;
            }
            return $mainCollectionItem;
        }
        return false;
    }

    private function pageCheck($slug): bool
    {
        $ListPages = $this->cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems)
        {
            if($listSlug == $slug)
            {
              return false;
            }
        }
        return true;
    }

    private function runPageBuilder($preparedPage, $defaultPage = false): void
    {

        if(!$defaultPage)
        {
            Utils::log('Start Builder', 4, 'RunPageBuilder');
        }
        else
        {
            Utils::log('Start Builder | create default Page', 4, 'RunPageBuilder');
        }

        $PageBuilder = new ItemsBuilder($preparedPage, $this->cache, $defaultPage);

        if($PageBuilder)
        {
            Utils::log('Page created successfully!', 1, 'PageBuilder');
        }
    }

    private function createBlankPages(array $parentPages): void
    {
        Utils::log('Start created pages', 1, 'createBlankPages');

        foreach ($parentPages as &$pages)
        {
            $newPage = $this->creteNewPage($pages['slug'], $pages['name'], $this->cache, false);
            if (!$newPage) {
                Utils::log('Failed created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 2, 'createBlankPages');
            } else {
                Utils::log('Success created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'createBlankPages');
                $pages['collection'] = $newPage;
            }
        }
        $this->cache->set('menuList', ['create' => false , 'list' => $parentPages]);
    }

    private function getPisturesUrl($nameImage, $type)
    {
        $folder = [ 'gallery-layout' => '/gallery/slides/' ];

        if(array_key_exists($type, $folder)){
            $folderload = $folder[$type];
        } else {
            $folderload = '/site-images/';
        }

        $uuid = $this->cache->get('settings')['uuid'];
        $prefix = substr($uuid, 0, 2);
        $url = "https://s3.amazonaws.com/media.cloversites.com/" . $prefix . '/' . $uuid . $folderload . $nameImage;
        Utils::log('Created url pictures: ' . $url .' Type folder ' . $type, 1, 'getPisturesUrl');
        return $url;
    }

    private function uploadPicturesFromSections(array $sectionsItems)
    {
        Utils::log('Start upload pictures', 1, 'uploadPicturesFromSections');
        foreach ($sectionsItems as &$section)
        {
            if(array_key_exists('background', $section['settings']))
            {
                Utils::log('Found background pictures', 1, 'uploadPicturesFromSections');
                $result = $this->brizyApi->createMedia($section['settings']['background']['photo']);
                if($result){
                    $result = json_decode($result['body'], true);
                    $section['settings']['background']['photo'] = $result['name'];
                    $section['settings']['background']['filename'] = $result['filename'];
                }
            } else {
                foreach($section['items'] as &$item)
                {
                    if($item['category'] == 'photo' && $item['content'] != '')
                    {
                        $downloadImageURL = $this->getPisturesUrl($item['content'], $section['typeSection']);
                        $result = $this->brizyApi->createMedia($downloadImageURL);
                        if($result){

                            $result = json_decode($result['body'], true);
                            Utils::log('Upload pictures response: ' . $result['body'], 1, 'uploadPicturesFromSections');
                            $item['imageFileName'] = $result['filename'];
                            $item['content'] = $result['name'];
                            Utils::log('Upload pictures fileName: ' . $result['filename'] . ' srcName: ' . $result['name'], 1, 'uploadPicturesFromSections');

                        }
                        else{
                            Utils::log('The structure of the picture is damaged', 3, 'uploadPicturesFromSections');
                        }
                    }
                }
            }
        }
        return $sectionsItems;
    }

}