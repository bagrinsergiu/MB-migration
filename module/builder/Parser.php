<?php
namespace Builder;

use Layer\DBConnect;
class Parser
{
    private $items;
    private $db;
    private $siteId;
    private $cache;

    public function __construct($id)
    {
        $this->siteId = $id;
        $this->cache = new VariableCache();
        $this->monipulator = new ArrayManipulator();
        $this->db = new DBConnect();
    }

    public function getSite()
    {
        return $this->db->requestArray("SELECT `id`, `name`, `title`, `settings`, `design_uuid`, `favicon` from `sites` WHERE id = " . $this->siteId);
    }

    public function getParentPages()
    {
        $result = [];

        $requestPageSite = $this->db->request("SELECT `id`, `slug`, `name` FROM pages WHERE site_id = " . $this->siteId . " AND parent_id IS NULL ORDER BY parent_id ASC, `position`");

        while($pageSite = mysqli_fetch_array($requestPageSite))
        {
            $result[] = [
                'id'    => $pageSite['id'],
                'slug'  => $pageSite['slug'],
                'name'  => $pageSite['name']
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
            $result[] = [
              'id'           => $pageSections['id'],
              'type_section' => $pageSections['category'],
              'position'     => $pageSections['position'],
            ];
        }
        return $result;

    }
    public function getSectionsItems($sectionId, $assembly = false)
    {
        $result = [];

        if($this->cache->exist($sectionId))
        {
            return $this->cache->get($sectionId);
        }

        $requestItemsFromSection = $this->db->request("SELECT * FROM items WHERE section_id = " . $sectionId);
        while($sectionsItems = mysqli_fetch_array($requestItemsFromSection))
        {
            $result[] = [
                'id'        => $sectionsItems['id'],
                'category'  => $sectionsItems['category'],
                'item_type' => $sectionsItems['item_type'],
                'order_by'  => $sectionsItems['order_by'],
                'parent_id' => $sectionsItems['parent_id'],
                'content'   => $sectionsItems['content'],
            ];
        }
        $this->cache->set($sectionId, $result);

        if($assembly)
        {
            return $this->assemblySection($sectionId);
        }

        return $result;
    }

    private function assemblySection($id)
    {
        $result = $this->monipulator->groupArrayByParentId($this->cache->get($id));

        return $result;
    }

}