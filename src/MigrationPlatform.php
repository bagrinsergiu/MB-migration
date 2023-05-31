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
    private string $projectId;
    private int $projectID_Brizy;
    private float $startTime;
    private string $graphApiBrizy;

    /**
     * @throws Exception
     */
    public function __construct(int $projectID_MB, int $projectID_Brizy)
    {
        $this->init($projectID_MB, $projectID_Brizy);

        $this->createProjectFolders();

        $parentPages = $this->parser->getParentPages();

        if (!$parentPages) {
            Utils::log('MB project not found, migration did not start, process completed without errors!', 1, "MAIN Foreach");

            $this->logFinalProcess($this->startTime);

            return;
        }

        $this->cache->set('settings', $this->parser->getSite());
        $this->cache->set('GraphApi_Brizy', $this->graphApiBrizy);
        $this->cache->set('graphToken', $this->brizyApi->getGraphToken($projectID_Brizy));

        $this->QueryBuilder = new QueryBuilder($this->cache);

        $mainSection = $this->parser->getMainSection();
        $this->updateColorSection($mainSection);
        Utils::log('Upload Logo menu', 1, 'createMenu');
        $mainSection = $this->uploadPicturesFromSections($mainSection);
        $this->cache->set('mainSection', $mainSection);

        $this->getAllPage();
        $this->createBlankPages($parentPages);
        $this->createMenuStructure();

        $this->launch($parentPages);

        Utils::log('Project migration completed successfully!', 6, 'PROCESS');

        $this->logFinalProcess($this->startTime);
    }

    private function init(int $projectID_MB, int $projectID_Brizy): void
    {
        Utils::log('-------------------------------------------------------------------------------------- []', 4, '');
        Utils::log('Start Process!', 4, 'MIGRATION');

        $this->cache = new VariableCache();
        $this->brizyApi = new BrizyAPI();

        $this->startTime = microtime(true);

        $this->projectId = $projectID_MB . '_' . $projectID_Brizy . '_';
        $migrationID = $this->brizyApi->getNameHash($this->projectId, 10);
        $this->projectId .= $migrationID;
        $this->projectID_Brizy = $projectID_Brizy;

        $this->cache->set('migrationID', $migrationID);
        Utils::log('Migration ID: ' . $migrationID, 4, 'MIGRATION');

        $errorDump       = new ErrorDump($this->projectId);

        $errorDump->setDate($this->cache);

        $this->graphApiBrizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $this->cache->set('projectId_MB', $projectID_MB);
        $this->cache->set('projectId_Brizy', $projectID_Brizy);

        $this->parser = new Parser($this->cache);
    }


    private function logFinalProcess(float $startTime): void
    {
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);

        Utils::log('Work time: ' . $this->Time($executionTime) . ' (seconds: ' . round($executionTime, 1). ')' , 1, 'PROCESS');
        Utils::log('END', 1, "PROCESS");
    }

    /**
     * @throws Exception
     */
    private function launch($parentPages): void
    {
        foreach ($parentPages as $pages) {

            if(!empty($pages['childs'])){
                $this->launch($pages['childs']);
            }

            if ($pages['slug'] != 'give') {continue;}
            $this->collector($pages);
        }
    }

    /**
     * @throws Exception
     */
    private function collector($pages): void
    {
        Utils::log('Take page | ID: ' . $pages['id'], 4, 'MAIN Foreach');

        $this->cache->set('tookPage', $pages);

        $preparedSectionOfThePage = $this->getItemsFromPage($pages);
        $preparedSectionOfThePage = $this->uploadPicturesFromSections($preparedSectionOfThePage);
        $preparedSectionOfThePage = $this->sortArrayByPosition($preparedSectionOfThePage);

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

        if($preparedSectionOfThePage) {
            $this->runPageBuilder($preparedSectionOfThePage);
        } else {
            Utils::log('Set default page template | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'Foreach');
            $this->runPageBuilder($preparedSectionOfThePage);
        }
    }

    private function getItemsFromPage(array $page): bool|array
    {
        Utils::log('Parent Page id: ' . $page['id'] . ' | Name page: ' . $page['name'] . ' | Slug: ' . $page['slug'], 1, 'getItemsFromPage');

        $child = $this->parser->getSectionsPage($page['id']);
        if(!empty($child)) {
            $items = [];
            foreach ($child as $value) {

                Utils::log('Collection of item id: ' .$value['id'].' -> Parent page id:'. $page['id'], 1, 'getItemsFromPage');
                $color = $this->getColorFromPalette('subpalette1');
                if($this->checkArrayPath($value, 'settings/sections/color/subpalette')) {
                    $color = $this->getColorFromPalette($value['settings']['sections']['color']['subpalette']);
                }
                if($this->checkArrayPath($value, 'settings/layout/color/subpalette')) {
                    $value['settings']['layout']['color'] = $this->getColorFromPalette($value['settings']['layout']['color']['subpalette']);
                }

                $items[] = [
                    'sectionId'     => $value['id'],
                    'typeSection'   => $value['typeSection'],
                    'position'      => $value['position'],
                    'category'      => $value['category'],
                    'color'         => $color,
                    'settings'      => $value['settings'],
                    'items'         => $this->parser->getSectionsItems($value, true)
                ];
            }
            $result = $items;
        } else {
            Utils::log('Empty parent page | ID: ' . $page['id'] . ' | Name page: ' . $page['name'] . ' | Slug: ' . $page['slug'], 1, 'getItemsFromPage');
            $result = false;
        }
        return $result;
    }

    private function createMenuStructure(): void
    {
        Utils::log('Create menu structure', 1, 'createMenuStructure');

        $parentPages = $this->cache->get('menuList');
        $mainMenu = $this->transformToBrizyMenu($parentPages['list']);

        $data = [
            'project'=>$this->projectID_Brizy,
            'name' => 'mainMenu',
            'data' => json_encode($mainMenu)
        ];

        $result = $this->brizyApi->createMenu($data);
        $this->cache->add('menuList', $result);
    }

    private function transformToBrizyMenu(array $parentMenu): array
    {
        $mainMenu = [];
        foreach ($parentMenu as $item) {
            $mainMenu[] = [
                "id" => $item['collection'],
                "items" => $this->transformToBrizyMenu($item['childs']),
                "isNewTab" => false,
                "label" => $item['name'],
                "uid"=> $this->getNameHash()
            ];
        }
        return $mainMenu;
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

    /**
     * @throws Exception
     */
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

        foreach ($collectionItems['page']['collection'] as $entity) {
                $entities[$entity['slug']] = $entity['id'];
        }
        $this->cache->set('ListPages', $entities);
    }

    private function setCurrentPageOnWork($collectionItem): void
    {
        Utils::log('Set the current page to work: ' . $collectionItem, 1, 'setCurrentPageOnWork');
        $this->cache->set('currentPageOnWork', $collectionItem);
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    private function runPageBuilder($preparedSectionOfThePage, $defaultPage = false): void
    {

        if(!$defaultPage)
        {
            Utils::log('Start Builder', 4, 'RunPageBuilder');
        }
        else
        {
            Utils::log('Start Builder | create default Page', 4, 'RunPageBuilder');
        }

        $PageBuilder = new ItemsBuilder($preparedSectionOfThePage, $this->cache, $defaultPage);

        if($PageBuilder)
        {
            Utils::log('Page created successfully!', 1, 'PageBuilder');
        }
    }

    /**
     * @throws Exception
     */
    private function createBlankPages(array &$parentPages): void
    {
        Utils::log('Start created pages', 1, 'createBlankPages');
        foreach ($parentPages as &$pages)
        {
            if(!empty($pages['childs'])) {
                $this->createBlankPages($pages['childs']);
            }
            if($pages['landing'] == true) {
                $newPage = $this->creteNewPage($pages['slug'], $pages['name']);
                if (!$newPage) {
                    Utils::log('Failed created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 2, 'createBlankPages');
                } else {
                    Utils::log('Success created pages | ID: ' . $pages['id'] . ' | Name page: ' . $pages['name'] . ' | Slug: ' . $pages['slug'], 1, 'createBlankPages');
                    $pages['collection'] = $newPage;
                }
            }
            else{
                $pages['collection'] = $pages['childs'][0]['collection'];
            }
        }
        $this->cache->set('menuList', [
            'color' => $this->cache->get('settings')['parameter']['palette'][0]['color'],
            'create' => false ,
            'list' => $parentPages
        ]);
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

    private function uploadPicturesFromSections(array $sectionsItems): array
    {
        Utils::log('Start upload image', 1, 'uploadPicturesFromSections');
        foreach ($sectionsItems as &$section)
        {
            if($this->checkArrayPath($section,'settings/sections/background/photo')) {
                if($section['settings']['sections']['background']['photo'] != null){
                    Utils::log('Found background image', 1, 'uploadPicturesFromSections');
                    $result = $this->brizyApi->createMedia($section['settings']['sections']['background']['photo'], $this->projectId);
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        Utils::log('Upload image response: ' . json_encode($result), 1, 'uploadPicturesFromSections');
                        $section['settings']['sections']['background']['photo'] = $result['name'];
                        $section['settings']['sections']['background']['filename'] = $result['filename'];
                        Utils::log('Success upload image fileName: ' . $result['filename'] . ' srcName: ' . $result['name'], 1, 'uploadPicturesFromSections');
                    }
                } else {
                    $this->checkItemForMediaFiles($section['items'], $section['typeSection']);
                }
            }
            $this->checkItemForMediaFiles($section['items'], $section['typeSection']);
        }
        return $sectionsItems;
    }

    private function checkItemForMediaFiles(&$section, $typeSection = ''): void
    {
        foreach($section as &$item)
        {
            if($item['category'] == 'photo' && $item['content'] != '') {
                $this->media($item, $typeSection);
            }
            if($item['category'] == 'list') {
                foreach($item['item'] as &$piece)
                {
                    if($piece['category'] == 'photo' && $piece['content'] != '') {
                        $this->media($piece, $typeSection);
                    }
                }
            }
        }
    }

    private function media(&$item, $section): void
    {
        Utils::log('Found new image', 1, 'media');
        $downloadImageURL = $this->getPisturesUrl($item['content'], $section);
        $result = $this->brizyApi->createMedia($downloadImageURL, $this->projectId);
        if($result){
            if(array_key_exists('status', $result)) {
                if($result['status'] == 201 ) {
                    $result = json_decode($result['body'], true);
                    Utils::log('Upload image response: ' . json_encode($result), 1, 'media');
                    $item['imageFileName'] = $result['filename'];
                    $item['content'] = $result['name'];
                    Utils::log('Success upload image fileName: ' . $result['filename'] . ' srcName: ' . $result['name'], 1, 'media');
                }
                else{
                    Utils::log('Unexpected answer: '. json_encode($result), 3, 'media');
                }
            }
            else{
                Utils::log('Bad response: '. json_encode($result), 3, 'media');
            }
        }
        else{
            Utils::log('The structure of the image is damaged', 3, 'media');
        }
    }

    private function sortArrayByPosition($array) {
        usort($array, function($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $array;
    }

    private function checkArrayPath($array, $path): bool
    {
        $keys = explode('/', $path);
        $current = $array;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                return false;
            }
            $current = $current[$key];
        }

        return true;
    }

    private function getNameHash($data = ''): string
    {
        $to_hash = $this->generateCharID() . $data;
        $newHash = hash('sha256', $to_hash);
        return substr($newHash, 0, 32);
    }

    private function generateCharID($length = 32): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    private function createProjectFolders(): void
    {
        $folds = [
            '/media/',
            '/log/dump/'
        ];
        foreach ($folds as $fold) {
            $path = __DIR__ . '/../tmp/' . $this->projectId . $fold;
            $this->createDirectory($path);
        }
    }

    private function createDirectory($directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            Utils::log('Create Directory: ' . $directoryPath, 1, 'createDirectory');
            mkdir($directoryPath, 0777, true);
        }
    }

    private function Time($seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds - ($hours * 3600)) / 60);
        $seconds = $seconds - ($hours * 3600) - ($minutes * 60);
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function updateColorSection(array &$mainSection)
    {
        foreach ($mainSection as $item => &$value)
        {
            if(is_array($value)){
                $this->updateColorSection($value);
            }
            if($item === 'subpalette')
            {
                $value = $this->getColorFromPalette($value);
            }
        }
    }

}