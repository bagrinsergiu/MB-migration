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
        $settingSite = $this->db->requestArray("SELECT id, name, title, settings, uuid, design_uuid, favicon from sites WHERE id = " . $this->siteId);
        $designSite = $this->db->requestArray("SELECT * from designs WHERE uuid = '".$settingSite[0]['design_uuid']."'");
        return [
            'name'      => $settingSite[0]['name'],
            'title'     => $settingSite[0]['title'],
            'design'    => $designSite[0]['name'],
            'uuid'      => $settingSite[0]['uuid'],
            'parameter' => json_decode($settingSite[0]['settings'], true),
            'favicon'   => $settingSite[0]['favicon']
        ];
    }

    public function getMainSection()
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

    public function getParentPages(): array
    {
        Utils::log('Get parent pages', 1, 'getParentPages');
        $result = [];
        $requestPageSite = $this->db->request("SELECT id, slug, name, position, settings, landing FROM pages WHERE site_id = " . $this->siteId . " AND parent_id IS NULL ORDER BY parent_id ASC, position");

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
                'childs' => $this->getChaildsPages($pageSite['id'])
            ];
        }

        $result[0]['slug'] = 'home';

        return $result;
    }

    private function getChaildsPages($parenId): array
    {
        Utils::log('Get child pages', 1, 'getChaildsPages');
        $result = [];

        $pagesSite = $this->db->request("SELECT id, slug, name, position, settings, hidden, landing FROM pages WHERE site_id = " . $this->siteId . " and parent_id = " . $parenId . " ORDER BY position asc");

        foreach($pagesSite as $pageSite) {
            if ($pageSite['hidden'] == false) {
                $result[] = [
                    'id'             => $pageSite['id'],
                    'slug'           => $pageSite['slug'],
                    'name'           => $pageSite['name'],
                    'collection'     => '',
                    'position'       => $pageSite['position'],
                    'landing'       => $pageSite['landing'],
                    'parentSettings' => $pageSite['settings'],
                    'childs'         => $this->getChaildsPages($pageSite['id'])
                ];
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
            return $this->cache->get($sectionId['id']);
        }

        $requestItemsFromSection = $this->db->request("SELECT * FROM items WHERE 'group' is not null and section_id = " . $sectionId['id']);
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
                'parent_id' => $sectionsItems['parent_id'],
                'settings'  => $settings,
                'link'      => $this->getItemLink($sectionsItems['id']),
                'content'   => $sectionsItems['content'],
            ];
        }
        $this->cache->set($sectionId['id'], $result);

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
        $result = $this->manipulator->groupArrayByParentId($this->cache->get($id));

        return $result;
    }

}