<?php

namespace MBMigration\Builder\Menu;

use Exception;
use MBMigration\Builder\Layout\Common\MenuBuilderFactory;
use MBMigration\Core\Logger;
use MBMigration\Browser\BrowserPagePHP;
use MBMigration\Builder\PageController;
use MBMigration\Builder\Utils\TextTools;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;

class MenuHandler
{

    private BrizyAPI $brizyApi;
    private VariableCache $cache;
    /**
     * @var mixed|null
     */
    private $projectID_Brizy;
    private BrowserPagePHP $browserPage;

    public function __construct(BrowserPagePHP $browserPage)
    {
        $this->cache = VariableCache::getInstance();
        
        $this->browserPage = $browserPage;
        
        $this->brizyApi = $this->cache->getClass('brizyApi');
        $this->projectID_Brizy = $this->cache->get('projectId_Brizy');
    }

    /**
     * @throws Exception
     */
    public static function createMenuList(): void
    {
        $cache = VariableCache::getInstance();

        Logger::instance()->info('Create menu structure');

        $parentPages = $cache->get('menuList');

        $design = $cache->get('design');
        $brizyProject = $cache->get('projectId_Brizy');
        $fonts = $cache->get('fonts', 'settings');
        $brizyApi = $cache->get('brizyApi');

        $menuBuilder = MenuBuilderFactory::instanceOfThemeMenuBuilder($design, $brizyProject, $brizyApi, $fonts);
        $menuStructure = $menuBuilder->transformToBrizyMenu($parentPages['list']);
        $result = $menuBuilder->createBrizyMenu('mainMenu', $menuStructure);

        $cache->set('brizyMenuItems', $menuStructure);

        $cache->set('menuList', [
            'id' => $result['id'] ?? null,
            'uid' => $result['uid'] ?? null,
            'name' => $result['name'] ?? null,
            'create' => false,
            'list' => $parentPages['list'],
            'data' => $result['data'] ?? '',
        ]);
    }

    /**
     * @throws Exception
     */
    public function createMenuStructure($selector)
    {
        Logger::instance()->info('Create menu structure');

        $parentPages = $this->cache->get('menuList');

        $textTransform = $this->StyleTextTransformExtractor($this->browserPage, $selector);

        $mainMenu = $this->transformToBrizyMenu($parentPages['list'], $textTransform);

        $data = [
            'project' => $this->projectID_Brizy,
            'name' => 'mainMenu',
            'data' => json_encode($mainMenu),
        ];

        $result = $this->brizyApi->createMenu($data);

        $menuList = [
            'id' => $result['id'] ?? null,
            'uid' => $result['uid'] ?? null,
            'name' => $result['name'] ?? null,
            'create' => false,
            'list' => $parentPages['list'],
            'data' => $result['data'] ?? '',
        ];

        $this->cache->set('menuList', $menuList);

        return $menuList;
    }



    private function transformToBrizyMenu(array $parentMenu, $textTransform = 'none'): array
    {
        $mainMenu = [];

        foreach ($parentMenu as $item) {
            if (isset($item['hidden'])) {
                if ($item['hidden']) {
                    continue;
                }
            }
            $settings = json_decode($item['parentSettings'], true);

            if (array_key_exists('external_url', $settings)) {
                $mainMenu[] = [
                    'id' => '',
                    "uid" => Utils::getNameHash(),
                    "isNewTab" => $this->checkOpenInNewTab($settings),
                    "label" => TextTools::transformText($item['name'], $textTransform),
                    "type" => "custom_link",
                    'url' => $settings['external_url'],
                    "description" => "",
                    "items" => $this->transformToBrizyMenu($item['child'], $textTransform),
                ];
            } else {
                if (empty($item['collection'])) {
                    $item['collection'] = $item['child'][0]['collection'];
                }
                $mainMenu[] = [
                    "id" => $item['collection'],
                    "uid" => Utils::getNameHash(),
                    "isNewTab" => $this->checkOpenInNewTab($settings),
                    "label" => TextTools::transformText($item['name'], $textTransform),
                    "items" => $this->transformToBrizyMenu($item['child'], $textTransform),
                ];
            }
        }

        return $mainMenu;
    }

    private function StyleTextTransformExtractor($browserPage, $selector)
    {
        $style = [];
        $textTransform = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'styleProperties' => [
                    'text-transform',
                ],
                'families' => [],
                'defaultFamily' => [],
            ]
        );
        if(isset($textTransform['data']['text-transform'])){
            return $textTransform['data']['text-transform'];
        } else {
            return 'none';
        }
    }

    private function checkOpenInNewTab($settings): bool
    {
        if (array_key_exists('new_window', $settings)) {
            return $settings['new_window'];
        } else {
            return false;
        }
    }
}