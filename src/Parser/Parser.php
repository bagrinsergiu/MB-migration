<?php
namespace Brizy\Parser;

use Brizy\Builder\Utils\ArrayManipulator;
use Brizy\Builder\VariableCache;
use Brizy\core\Utils;
use Brizy\layer\MySql\DBConnect;

class Parser
{
    private $items;
    private $db;
    private $siteId;
    private $cache;
    private $monipulator;


    public function __construct(VariableCache $cache)
    {
        $this->cache       = $cache;

        $this->db          = new DBConnect();
        $this->monipulator = new ArrayManipulator();

        $this->siteId      = $this->cache->get('projectId_MB');

    }

    public function getSite()
    {
        $settingSite = $this->db->requestArray("SELECT `id`, `name`, `title`, `settings`, `design_uuid`, `favicon` from `sites` WHERE id = " . $this->siteId);
        $designSite = $this->db->requestArray("SELECT * from `designs` WHERE uuid ='".$settingSite['design_uuid']."'");
        return [
            'name' => $settingSite['name'],
            'title' => $settingSite['title'],
            'design' => $designSite['name'],
            'favicon' => $settingSite['favicon']
        ];
    }

    public function getParentPages()
    {
        $result = [];
        $requestPageSite = $this->db->request("SELECT `id`, `slug`, `name`, `position` FROM pages WHERE site_id = " . $this->siteId . " AND parent_id IS NULL ORDER BY parent_id ASC, `position`");
        if($requestPageSite->num_rows != 0)
        {
            while($pageSite = mysqli_fetch_array($requestPageSite))
            {
                $result[] = [
                    'id'    => $pageSite['id'],
                    'slug'  => $pageSite['slug'],
                    'name'  => $pageSite['name'],
                    'position'  => $pageSite['position']
                    ];
            }
        }
        else
        {
            Utils::log('MB project pages not found', 2, "getParentPages");
            $result = false;
        }
        return $result;
    }

    public function getChildFromPages($parenId)
    {
        $result = [];

        $requestPageSite = $this->db->request("SELECT id, position FROM pages WHERE site_id = " . $this->siteId . " and parent_id = " . $parenId . " ORDER BY `position` asc");
        while($pageSite = mysqli_fetch_array($requestPageSite))
        {
            $result[] = [
                'id'    => $pageSite['id'],
                'position'  => $pageSite['position']
            ];
        }

        return $result;
    }
    public function getSectionFromParentPage($parenId)
    {
        $result = [];

        $requestSections = $this->db->request("SELECT id, section_layout_uuid, category, position FROM sections WHERE page_id  = " . $parenId);
        while($pageSections = mysqli_fetch_array($requestSections))
        {
            $result[] = [
                'id'           => $pageSections['id'],
                'type_section' => $pageSections['category'],
                'position'     => $pageSections['position'],
            ];
        }
        return $result;
    }
    public function getSectionsPage($id)
    {
        $result = [];
        $requestSections = $this->db->request("SELECT id, section_layout_uuid, category, position FROM sections WHERE page_id  = " . $id);
        while($pageSections = mysqli_fetch_array($requestSections))
        {
            $typeSectionLayoutUuid = $this->db->requestArray("SELECT name FROM section_layouts WHERE `uuid`  = '" . $pageSections['section_layout_uuid'] . "'");
            $result[] = [
              'id'           => $pageSections['id'],
              'category' => $pageSections['category'],
              'typeSection' => $typeSectionLayoutUuid['name'],
              'position'     => $pageSections['position'],
            ];
        }
        return $result;
    }
    public function getSectionsItems($sectionId, $assembly = false)
    {
        $result = [];

        if($this->cache->exist($sectionId['id']))
        {
            Utils::log('Get item from cache | Section id: '. $sectionId['id'], 1, 'getSectionsItems');
            return $this->cache->get($sectionId['id']);
        }

        $requestItemsFromSection = $this->db->request("SELECT * FROM items WHERE `group` is not null and section_id = " . $sectionId['id']);
        while($sectionsItems = mysqli_fetch_array($requestItemsFromSection))
        {
            Utils::log('Get item | id: ' .$sectionsItems['id'].' from section id: '. $sectionId['id'], 1, 'getSectionsItems');
            $result[] = [
                'id'        => $sectionsItems['id'],
                'category'  => $sectionsItems['category'],
                'item_type' => $sectionsItems['item_type'],
                'order_by'  => $sectionsItems['order_by'],
                'parent_id' => $sectionsItems['parent_id'],
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

    private function assemblySection($id)
    {
        $result = $this->monipulator->groupArrayByParentId($this->cache->get($id));

        return $result;
    }

}