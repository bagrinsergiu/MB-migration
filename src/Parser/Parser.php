<?php
namespace Brizy\Parser;

use Brizy\Builder\Utils\ArrayManipulator;
use Brizy\Builder\VariableCache;
use Brizy\core\Utils;
use Brizy\Layer\DataSource\DBConnector;
use Exception;

class Parser
{
    private DBConnector $db;
    private int $siteId;
    private VariableCache $cache;
    private ArrayManipulator $manipulator;


    public function __construct(VariableCache $cache)
    {
        Utils::log('Initialization', 4, 'Parser Module');
        $this->cache       = $cache;

        $this->db          = new DBConnector();
        $this->manipulator = new ArrayManipulator();

        $this->siteId      = (int)$this->cache->get('projectId_MB');
        Utils::log('READY', 4, 'Parser Module');
    }

    public function getSite(): array
    {
        Utils::log('Get site', 1, 'getSite');
        $settingSite = $this->db->requestArray("SELECT id, name, title, settings, uuid, design_uuid, favicon, palette_uuid from sites WHERE id = " . $this->siteId);
        $designSite = $this->db->requestArray("SELECT * from designs WHERE uuid = '".$settingSite[0]['design_uuid']."'");

        $settings = json_decode($settingSite[0]['settings'], true);
        if(!array_key_exists('palette', $settings)){
            $settings['palette'] = $this->getPalettes($settingSite[0]['palette_uuid']);
        }

        return [
            'name'      => $settingSite[0]['name'],
            'title'     => $settingSite[0]['title'],
            'design'    => $designSite[0]['name'],
            'uuid'      => $settingSite[0]['uuid'],
            'parameter' => $settings,
            'favicon'   => $settingSite[0]['favicon']
        ];
    }

    public function getMainSection(): array
    {
        Utils::log('Get main Section', 1, 'getParentPages');
        $result = [];
        $requestMainSections = $this->db->request("SELECT * FROM sections WHERE site_id =  " . $this->siteId . " and (page_id isnull or page_id = 0) ORDER BY position");

        foreach($requestMainSections as $mainSection)
        {
            $requestItemsFromMainSection = $this->db->request("SELECT * FROM items WHERE section_id =  " . $mainSection['id']);
            $item = [];
            foreach ($requestItemsFromMainSection as $itemsFromMainSections)
            {
                $item[] = [
                    'id'        => $itemsFromMainSections['id'],
                    'category'  => $itemsFromMainSections['category'],
                    'position'  => $itemsFromMainSections['order_by'],
                    'settings'  => json_decode($itemsFromMainSections['settings'], true),
                    'content'   => $itemsFromMainSections['content']
                ];
            }
            $settings = json_decode($mainSection['settings'], true);

            $result[$mainSection['category']] = [
                'typeSection'   => "main",
                'category'      => $requestItemsFromMainSection[0]['category'],
                'settings'      => $settings,
                'items'         => $item,
            ];
        }
        return $result;
    }

    private function getPalettes($paletteUUID): ?array
    {
        $palette = null;

        if($paletteUUID === null ){
            return $palette;
        }

        $palettes = $this->db->requestArray("SELECT id from palettes WHERE uuid = '$paletteUUID'");
        $colorsKit = $this->db->requestArray("SELECT * from colors WHERE palette_id = '".$palettes[0]['id']."' ORDER BY position");
        foreach ($colorsKit as $color){
            $palette[] = [
                'tag' => $color['tag'],
                'color' => $color['color']
            ];
        }
        return $palette;
    }

    public function getParentPages(): array
    {
        Utils::log('Get parent pages', 1, 'getParentPages');
        $result = [];
        $requestPageSite = $this->db->request("SELECT id, slug, name, position, settings, landing FROM pages WHERE site_id = " . $this->siteId . " AND hidden = 'false' AND parent_id IS NULL ORDER BY parent_id ASC, position");

        if (empty($requestPageSite)) {
            Utils::log('MB project pages not found', 2, 'getParentPages');

            return $result;
        }

        foreach ($requestPageSite as $pageSite) {
            $result[] = [
                'id' => $pageSite['id'],
                'slug' => $pageSite['slug'],
                'name' => $pageSite['name'],
                'collection' => '',
                'position' => $pageSite['position'],
                'landing' => $pageSite['landing'],
                'parentSettings' => $pageSite['settings'],
                'child' => $this->getChildPages($pageSite['id'])
            ];
            $this->cache->update('Total', '++', 'Status');
        }

        $result[0]['slug'] = 'home';

        return $result;
    }

    private function getChildPages($parentId): array
    {
        Utils::log('Get child pages', 1, 'getChildPages');
        $result = [];

        $pagesSite = $this->db->request("SELECT id, slug, name, position, settings, hidden, landing FROM pages WHERE site_id = " . $this->siteId . " AND hidden = 'false' AND  parent_id = " . $parentId . " ORDER BY position asc");

        foreach($pagesSite as $pageSite) {
            if ($pageSite['hidden'] === false) {
                $result[] = [
                    'id'             => $pageSite['id'],
                    'slug'           => $pageSite['slug'],
                    'name'           => $pageSite['name'],
                    'collection'     => '',
                    'position'       => $pageSite['position'],
                    'landing'       => $pageSite['landing'],
                    'parentSettings' => $pageSite['settings'],
                    'child'         => $this->getChildPages($pageSite['id'])
                ];
                $this->cache->update('Total', '++', 'Status');
            }
        }
        return $result;
    }

    public function getChildFromPages($parenId): array
    {

        Utils::log('Get child from pages', 1, 'getChildFromPages');
        $result = [];

        $pagesSite = $this->db->request("SELECT id, position FROM pages WHERE site_id = " . $this->siteId . " and parent_id = " . $parenId . " ORDER BY position asc");

        foreach($pagesSite as $pageSite)
        {
            $result[] = [
                'id'    => $pageSite['id'],
                'position'  => $pageSite['position']
            ];
        }
        return $result;
    }

    public function getSectionsPage($id): array
    {
        $result = [];
        $requestSections = $this->db->request("SELECT id, section_layout_uuid, category, position, settings FROM sections WHERE page_id  = " . $id . " ORDER BY position asc");
        foreach ($requestSections as $pageSections)
        {
            $typeSectionLayoutUuid = $this->db->requestArray("SELECT name, category, settings FROM section_layouts WHERE uuid  = '" . $pageSections['section_layout_uuid'] . "'");

            $result[] = [
                'id'             => $pageSections['id'],
                'categoryLayout' => $typeSectionLayoutUuid[0]['category'],
                'category'       => $pageSections['category'],
                'typeSection'    => $typeSectionLayoutUuid[0]['name'],
                'position'       => $pageSections['position'],
                'settings'       => [
                    'sections' => json_decode($pageSections['settings'], true),
                    'layout'   => json_decode($typeSectionLayoutUuid[0]['settings'], true)
                ]
            ];
        }
        return $result;
    }

    private function getItemLink(int $itemId): string
    {
        $requestLinkIdToPages = $this->db->request("SELECT page_id FROM links WHERE item_id  = " . $itemId);
        if(!empty($requestLinkIdToPages) && !empty($requestLinkIdToPages[0]['page_id'])){

            $requestItemLink = $this->db->request("SELECT slug FROM pages WHERE id  = " . $requestLinkIdToPages[0]['page_id']);
            Utils::log('Get link for item: '. $requestItemLink[0]['slug'], 1, 'getItemLink');
            return $requestItemLink[0]['slug'];
        }
        return '';
    }

    public function getSectionsItems($sectionId, $assembly = false)
    {
        $result = [];
        if($this->cache->exist($sectionId['id']))
        {
            Utils::log('Get item from cache | Section id: '. $sectionId['id'], 1, 'getSectionsItems');
            return $this->cache->get($sectionId['id'], 'Sections');
        }

        $requestItemsFromSection = $this->db->request("SELECT * FROM items WHERE 'group' is not null and section_id = " . $sectionId['id'] . " ORDER BY parent_id DESC, order_by");
        foreach($requestItemsFromSection as $sectionsItems)
        {
            Utils::log('Get item | id: ' .$sectionsItems['id'].' from section id: '. $sectionId['id'], 1, 'getSectionsItems');
            $settings = '';
            if($this->isJsonString($sectionsItems['settings']))
            {
                $settings = json_decode($sectionsItems['settings'], true);
            }


            $result[] = [
                'id'        => $sectionsItems['id'],
                'category'  => $sectionsItems['category'],
                'item_type' => $sectionsItems['item_type'],
                'order_by'  => $sectionsItems['order_by'],
                'group'  => $sectionsItems['group'],
                'parent_id' => $sectionsItems['parent_id'],
                'settings'  => $settings,
                'link'      => $this->getItemLink($sectionsItems['id']),
                'content'   => $sectionsItems['content'],
            ];
        }
        $this->cache->set($sectionId['id'], $result, 'Sections');

        if($assembly)
        {
            return $this->assemblySection($sectionId['id']);
        }

        return $result;
    }

    private function isJsonString($string): bool
    {
        if($string == null){
            return false;
        }
        try {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        } catch (Exception $e) {
            return false;
        }
    }

    private function assemblySection($id): array
    {
        return $this->manipulator->groupArrayByParentId($this->cache->get($id, 'Sections'));
    }

}