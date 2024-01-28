<?php

namespace MBMigration\Layer\MB;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\DebugBackTrace;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Utils\ArrayManipulator;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use MBMigration\Layer\DataSource\DBConnector;

class MBProjectDataCollector
{
    private $db;
    private $siteId;
    private $cache;
    private $manipulator;
    /**
     * @var FontsController
     */
    private $fontsController;
    /**
     * @var mixed|null
     */
    private $projectId;
    /**
     * @var mixed|null
     */
    private $container;

    use DebugBackTrace;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        Utils::log('Initialization', 4, 'Parser Module');
        $this->cache = VariableCache::getInstance();

        $this->siteId = $this->cache->get('projectId_MB');
        $this->projectId = $this->cache->get('projectId_Brizy');
        $this->container = $this->cache->get('container');

        $this->db = new DBConnector();
        $this->manipulator = new ArrayManipulator();
        $this->fontsController = new FontsController($this->container);

        Utils::log('READY', 4, 'Parser Module');
    }


    /**
     * @throws Exception
     */
    public function getDesignSite()
    {
        Utils::log('Get Design', 1, 'getDesignSite');
        $settingSite = $this->db->requestArray("SELECT design_uuid from sites WHERE id = ".$this->siteId);
        if (empty($settingSite)) {
            Utils::MESSAGES_POOL(self::trace(0).'Message: MB project not found');
            Utils::log('MB project not found', 3, 'getSite');

            return false;
        }
        $designSite = $this->db->requestArray(
            "SELECT name from designs WHERE uuid = '".$settingSite[0]['design_uuid']."'"
        );

        return $designSite[0]['name'];
    }

    /**
     * @throws Exception
     */
    public static function getIdByUUID($projectUUID_MB)
    {

        self::checkUUID($projectUUID_MB);

        Utils::log('Get id by uuId', 1, 'getIdByUUID');

        $db = new DBConnector();
        $settingSite = $db->requestArray("SELECT id from sites WHERE uuid = '".$projectUUID_MB."'");
        if (empty($settingSite)) {
            Utils::MESSAGES_POOL(self::trace(0).'Message: MB project not found');
            Utils::log('MB project not found', 3, 'getSite');

            throw new Exception("MB project not found with uuid: $projectUUID_MB");
        }

        return $settingSite[0]['id'];
    }

    /**
     * @throws Exception
     */
    private static function checkUUID($uuid)
    {
        Utils::log('Unique id check', 1, 'checkUUID');
        $uuidPattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';

        if (!preg_match($uuidPattern, $uuid)) {
            Utils::MESSAGES_POOL(self::trace(0)."Invalid UUID: $uuid");
            throw new Exception("Invalid UUID: $uuid");
        }
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function getSite()
    {
        Utils::log('Get site', 1, 'getSite');

        $siteData = $this->db->requestOne(
            "
            SELECT
                s.id,
                s.uuid,
                s.name,
                s.title,
                d.name as design,
                s.settings as parameter,
                s.favicon,
                s.palette_uuid,
                s.font_theme_uuid
            FROM sites s
                JOIN designs d ON d.uuid = s.design_uuid
            WHERE s.id={$this->siteId}
            "
        );

        if (empty($siteData)) {
            Utils::MESSAGES_POOL(self::trace(0).'Message: MB project not found');
            Utils::log('MB project not found', 3, 'getSite');

            return false;
        }

        $parameter = json_decode($siteData['parameter'], true);
        if (!array_key_exists('palette', $parameter)) {
            $parameter['palette'] = $this->getPalettes($siteData['palette_uuid']);
        }

        $siteData['parameter'] = $parameter;
        $siteData['fonts'] = $this->getFonts($parameter, $siteData['font_theme_uuid']);

        return $siteData;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function getFonts($settings, $fontThemeUUID, $migrationDefaultFonts = 'poppins'): array
    {
        if (array_key_exists('theme', $settings)) {
            $fontsIds = array_map(function ($font) {
                return $font['font_id'];
            }, $settings['theme']);

            if(count($fontsIds)==0)
                return $settings['theme'];

            $ids = implode(',',$fontsIds);

            $settingSite = $this->db->request("SELECT id, name, family from fonts WHERE id in ({$ids})");

            $fontData = array();
            foreach($settingSite as $val) {
                $fontData[$val['id']] = $val;
            }

            foreach ($settings['theme'] as $i=>$font) {
                $settings['theme'][$i]['font_name'] = $fontData[$font['font_id']]['name'];
                $settings['theme'][$i]['font_family'] = $this->transLiterationFontFamily($fontData[$font['font_id']]['family']);
            }

            $settings['theme'] = $this->processFonts($settings['theme'],$migrationDefaultFonts);
            return $settings['theme'];
        } else {
            return $this->getDefaultFont($fontThemeUUID, $migrationDefaultFonts);
        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getDefaultFont($fontThemeUUID, $migrationDefaultFonts)
    {
        $dbFontStyles = $this->db->request(
        //"select display_name, name, font_id, font_size, text_transform, letter_spacing, position, bold, italic FROM font_theme_styles WHERE font_theme_id IN(SELECT id from font_themes WHERE uuid = '$fontThemeUUID') ORDER BY position"
            "
            select fts.display_name,
                   fts.name,
                   fts.font_id,
                   fts.font_size,
                   fts.text_transform,
                   fts.letter_spacing,
                   fts.position,
                   fts.bold,
                   fts.italic,
                   f.name as font_name,
                   f.family as font_family
            FROM font_theme_styles fts
                JOIN public.font_themes ft on ft.id = fts.font_theme_id and ft.uuid='{$fontThemeUUID}'
                JOIN public.fonts f on f.id = fts.font_id
            ORDER BY fts.position
            "
        );

        return $this->processFonts($dbFontStyles, $migrationDefaultFonts);
    }

    private function processFonts($dbFontStyles, $migrationDefaultFonts) {
        $fontStyles = [];
        foreach ($dbFontStyles as $font) {
            $font['fontName'] = $font['font_name'];
            $font['fontFamily'] = $font['font_family'];
            $font['uuid'] = null; //$this->fontsController->upLoadFont($font['font_name']);
            $fontStyles[$font['font_name']] = $font;

        }

        $fontStyles[$migrationDefaultFonts] = [
            'name' => 'primary',
            'fontName' => $migrationDefaultFonts,
            'fontFamily' => $this->transLiterationFontFamily($migrationDefaultFonts),
            'uuid' => null,
        ];

        // upload and add fonts to project
        $fontStyles = $this->fontsController->addFontsToBrizyProject($fontStyles);

        return $fontStyles;
    }

    /**
     * @throws GuzzleException
     */
    public function primaryDefaultFonts(&$fontStyle, $name)
    {
        $fontStyle[] = [
            'name' => 'primary',
            'fontName' => $name,
            'fontFamily' => $this->transLiterationFontFamily($name),
            'uuid' => $this->fontsController->upLoadFont($name),
        ];
    }


    /**
     * @throws Exception
     */
    public function getMainSection(): array
    {
        Utils::log('Get main Section', 1, 'getMainSection');
        $result = [];
        $requestMainSections = $this->db->request(
            "SELECT * FROM sections WHERE site_id =  ".$this->siteId." and (page_id isnull or page_id = 0) ORDER BY position"
        );

        foreach ($requestMainSections as $mainSection) {
            $requestItemsFromMainSection = $this->db->request(
                "SELECT * FROM items WHERE section_id =  ".$mainSection['id']
            );
            $item = [];
            foreach ($requestItemsFromMainSection as $itemsFromMainSections) {
                $item[] = [
                    'sectionId' => $itemsFromMainSections['id'],
                    'category' => $itemsFromMainSections['category'],
                    'position' => $itemsFromMainSections['order_by'],
                    'settings' => json_decode($itemsFromMainSections['settings'], true),
                    'content' => $itemsFromMainSections['content'],
                ];
            }
            $settings = json_decode($mainSection['settings'], true);

            $result[$mainSection['category']] = [
                'sectionId' => $mainSection['id'],
                'typeSection' => "main",
                'category' => $requestItemsFromMainSection[0]['category'],
                'settings' => $settings,
                'items' => $item,
            ];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function getPalettes($paletteUUID): ?array
    {
        $palette = null;

        if ($paletteUUID === null) {
            return $palette;
        }

        $palettes = $this->db->requestArray("SELECT id from palettes WHERE uuid = '$paletteUUID'");
        $colorsKit = $this->db->requestArray(
            "SELECT * from colors WHERE palette_id = '".$palettes[0]['id']."' ORDER BY position"
        );
        foreach ($colorsKit as $color) {
            $palette[] = [
                'tag' => $color['tag'],
                'color' => $color['color'],
            ];
        }

        return $palette;
    }

    public function getPages(): array
    {
        Utils::log('Get parent pages', 1, 'getParentPages');
        $result = [];

        $allPages = $this->db->request(
            " SELECT id,
                       slug,
                       name,
                       parent_id,
                       ''                 as collection,
                       position,
                       settings as parentSettings,
                       landing,
                       hidden,
                       (CASE WHEN (password_protected IS NULL OR password_protected IS FALSE) THEN false ELSE true END) as protectedPage
                FROM pages
                WHERE site_id = {$this->siteId}"
        );

        $allPages = array_map(function ($page) {
            $page['parentSettings'] = $page['parentsettings'];
            $page['protectedPage'] = $page['protectedpage'];
            unset($page['parentsettings']);
            unset($page['protectedpage']);
            $this->cache->update('Total', '++', 'Status');
            return $page;
        }, $allPages);

        function getPagesByParent($parent, $allpages)
        {
            $pages = array_filter($allpages, function ($page) use ($parent) {
                return $page['parent_id'] == $parent;
            });

            foreach ($pages as $i=>$page) {
                $pages[$i]['child'] = getPagesByParent($page['id'], $allpages);
            }

            usort($pages, function($a, $b)
            {
                return $a['position'] <=> $b['position'];
            });

            return $pages;
        }

        $result = getPagesByParent(null,$allPages);
        $this->cache->set('ParentPages', $result);
        return $result;

    }

    /**
     * @deprecated Very slow implementation. Use getPages.
     *
     * @throws Exception
     */
    public function getParentPages(): array
    {
        Utils::log('Get parent pages', 1, 'getParentPages');
        $result = [];
        $requestPageSite = $this->db->request(
            "SELECT id, slug, name, position, settings, landing, hidden, password_protected FROM pages WHERE site_id = ".$this->siteId." AND parent_id IS NULL ORDER BY parent_id ASC, position"
        );

        if (empty($requestPageSite)) {
            Utils::log('MB project pages not found', 2, 'getParentPages');

            return $result;
        }

        foreach ($requestPageSite as $pageSite) {
//            if ($pageSite['hidden'] === true && ($pageSite['slug'] !== 'home' || $pageSite['slug'] !== 'welcome')) {
//                continue;
//            }
            if ($pageSite['password_protected'] === null) {
                $pageSite['password_protected'] = false;
            }

            $result[] = [
                'id' => $pageSite['id'],
                'slug' => $pageSite['slug'],
                'name' => $pageSite['name'],
                'collection' => '',
                'position' => $pageSite['position'],
                'landing' => $pageSite['landing'],
                'hidden' => $pageSite['hidden'],
                'protectedPage' => $pageSite['password_protected'],
                'parentSettings' => $pageSite['settings'],
                'child' => $this->getChildPages($pageSite['id']),
            ];
            $this->cache->update('Total', '++', 'Status');
        }

        $this->cache->set('ParentPages', $result);

        return $result;
    }

    /**
     * @throws Exception
     */
    private function getChildPages($parentId): array
    {
        Utils::log('Get child pages', 1, 'getChildPages');
        $result = [];

        $pagesSite = $this->db->request(
            "SELECT id, slug, name, position, settings, hidden, landing, password_protected FROM pages WHERE site_id = ".$this->siteId." AND hidden = 'false' AND  parent_id = ".$parentId." ORDER BY position asc"
        );

        foreach ($pagesSite as $pageSite) {
            if ($pageSite['hidden'] === false) {
                $result[] = [
                    'id' => $pageSite['id'],
                    'slug' => $pageSite['slug'],
                    'name' => $pageSite['name'],
                    'collection' => '',
                    'position' => $pageSite['position'],
                    'landing' => $pageSite['landing'],
                    'protectedPage' => $pageSite['password_protected'] ?? false,
                    'parentSettings' => $pageSite['settings'],
                    'child' => $this->getChildPages($pageSite['id']),
                ];
                $this->cache->update('Total', '++', 'Status');
            }
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function getChildFromPages($parenId): array
    {
        Utils::log('Get child from pages', 1, 'getChildFromPages');
        $result = [];

        $pagesSite = $this->db->request(
            "SELECT id, position FROM pages WHERE site_id = ".$this->siteId." and parent_id = ".$parenId." ORDER BY position asc"
        );

        foreach ($pagesSite as $pageSite) {
            $result[] = [
                'id' => $pageSite['id'],
                'position' => $pageSite['position'],
            ];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function getSectionsPage($id): array
    {
        $result = [];
        $i = 0;
        $requestSections = $this->db->request(
        //"SELECT id, section_layout_uuid, category, position, settings FROM sections WHERE page_id  = ".$id." ORDER BY position asc"
            "
            SELECT s.id,
                   s.section_layout_uuid,
                   s.category,
                   s.position,
                   s.settings,
                   sl.name as typeSection,
                   sl.category as categoryLayout,
                   sl.settings as settingsLayout
            FROM sections s
            JOIN public.section_layouts sl on s.section_layout_uuid = sl.uuid
            WHERE s.page_id = {$id}
            ORDER BY s.position asc;
            "
        );
        foreach ($requestSections as $pageSections) {
//            $typeSectionLayoutUuid = $this->db->requestArray(
//                "SELECT name, category, settings FROM section_layouts WHERE uuid  = '".$pageSections['section_layout_uuid']."'"
//            );
            $settings = json_decode($pageSections['settings'], true);

            if (!array_key_exists('color', $settings)) {
                $settings['color'] = $this->cache->get('subpalette', 'parameter')['subpalette1'];
            }

            $result[] = [
                'id' => $pageSections['id'],
                'categoryLayout' => $pageSections['categorylayout'],
                'category' => $pageSections['category'],
                'typeSection' => $pageSections['typesection'],
                'position' => $pageSections['position'],
                'settings' => [
                    'pagePosition' => $i,
                    'sections' => $settings,
                    'layout' => json_decode($pageSections['settingslayout'], true),
                ],
            ];
            $i++;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function getAllProjectsID()
    {
        $request = 'SELECT id, uuid FROM "public"."sites" WHERE ("setup_step" = \'final\') AND ("site_type" = \'user\') AND ("directory_name" IS NOT NULL) AND ("archived_at" IS NULL) AND ("design_uuid" = \'8405e015-b796-4e14-896f-7991da379e77\')';

        return $this->db->request($request);
    }

    /**
     * @throws Exception
     */
    private function getItemLink(int $itemId): array
    {
        Utils::log('Check link for item: '.$itemId, 1, 'getItemLink');
        $requestLinkIdToPages = $this->db->request("SELECT * FROM links WHERE item_id  = ".$itemId);
        if (!empty($requestLinkIdToPages)) {
            if (!is_null($requestLinkIdToPages[0]['page_id'])) {
                $requestItemLink = $this->db->request(
                    "SELECT slug FROM pages WHERE id  = ".$requestLinkIdToPages[0]['page_id']
                );
                Utils::log('Get link for item: '.$requestItemLink[0]['slug'], 1, 'getItemLink');

                return [
                    'detail' => $requestItemLink[0]['slug'],
                    'new_window' => false,
                ];
            } else {
                if ($requestLinkIdToPages[0]['category'] === 'link') {
                    $newWindow = false;
                    if (!empty($requestLinkIdToPages[0]['settings'])) {
                        $settings = json_decode($requestLinkIdToPages[0]['settings'], true);
                        if (!empty($settings['new_window'])) {
                            $newWindow = $settings['new_window'];
                        }
                    }

                    return [
                        'detail' => $requestLinkIdToPages[0]['detail'],
                        'new_window' => $newWindow,
                    ];
                }
            }
        }

        return [
            'detail' => '',
            'new_window' => false,
        ];
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getSectionsItems($sectionId, $assembly = false)
    {
        $result = [];
        if ($this->cache->exist($sectionId['id'])) {
            Utils::log('Get item from cache | Section id: '.$sectionId['id'], 1, 'getSectionsItems');

            return $this->cache->get($sectionId['id'], 'Sections');
        }

        $requestItemsFromSection = $this->db->request(
        //'SELECT * FROM items WHERE "group" is not null and section_id = '.$sectionId['id'].' ORDER BY parent_id DESC, order_by'
            "
            SELECT
                i.*,
            
                CASE
                WHEN l.page_id is not null THEN p.slug
                ELSE l.detail
                END AS link,
            
                CASE
                WHEN l.page_id is not null THEN null
                ELSE l.settings->>'new_window'
                END AS new_window
            
            FROM items i
            LEFT JOIN public.links l on i.id = l.item_id
            LEFT JOIN public.pages p on l.page_id = p.id
            WHERE i.group is not null
              and i.section_id = {$sectionId['id']}
            ORDER BY i.parent_id DESC, order_by    
            "
        );
        foreach ($requestItemsFromSection as $sectionsItems) {
            Utils::log(
                'Get item | id: '.$sectionsItems['id'].' from section id: '.$sectionId['id'],
                1,
                'getSectionsItems'
            );
            $settings = '';
            $uploadedFont = [];
            if ($settings = json_decode($sectionsItems['settings'], true)) {
                if (isset($settings['used_fonts'])) {
                    foreach ($settings['used_fonts'] as $fontName) {
                        $defaultFont = $this->cache->get('fonts', 'settings');
                        foreach ($defaultFont as $font) {
                            if ($font['fontName'] === $fontName) {
                                $settings['used_fonts'] = ['fontName' => $font['fontName'], 'uuid' => $font['uuid']];
                                continue 2;
                            }
                        }

                        $settingSite = $this->getFont($fontName);
                        $uploadedFont[] = [
                            'fontName' => $fontName,
                            'fontFamily' => $this->transLiterationFontFamily($settingSite[0]['family']),
                            'uuid' => $this->fontsController->upLoadFont($fontName),
                        ];

                        $defaultFont = array_merge($defaultFont, $uploadedFont);
                        $this->cache->set('fonts', $defaultFont, 'settings');
                        $settings['used_fonts'] = $uploadedFont;
                    }
                }
            }

            // $link = $this->getItemLink($sectionsItems['id']);
            $result[] = [
                'id' => $sectionsItems['id'],
                'category' => $sectionsItems['category'],
                'item_type' => $sectionsItems['item_type'],
                'order_by' => $sectionsItems['order_by'],
                'group' => $sectionsItems['group'],
                'parent_id' => $sectionsItems['parent_id'],
                'settings' => $settings,
                'link' => $sectionsItems['link'],
                'new_window' => $sectionsItems['new_window'] ? true : false,
                'content' => $sectionsItems['content'],
            ];
        }
        $this->cache->set($sectionId['id'], $result, 'Sections');

        if ($assembly) {
            $result = $this->assemblySection($sectionId['id'], $sectionId['category']);
        }

        return $result;
    }

    /**
     * @param $fontName
     * @return array|false
     * @throws Exception
     */
    protected function getFont($fontName)
    {
        static $settingSite = null;

        if ($settingSite) {
            return $settingSite;
        }

        $settingSite = $this->db->request("SELECT family from fonts WHERE name = '$fontName'");

        return $settingSite;
    }

    private function isJsonString($string): bool
    {
        if ($string == null) {
            return false;
        }
        try {
            json_decode($string);

            return (json_last_error() == JSON_ERROR_NONE);
        } catch (Exception $e) {
            return false;
        }
    }

    protected function generateCharID(int $length = 32): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    private function assemblySection($id, $section): array
    {
        return $this->manipulator->groupArrayByParentId($this->cache->get($id, 'Sections'), $section);
    }

    private function transLiterationFontFamily($family): string
    {
        $inputString = str_replace(["'", ' '], ['', '_'], $family);

        $inputString = str_replace(',', '', $inputString);

        return strtolower($inputString);
    }

}