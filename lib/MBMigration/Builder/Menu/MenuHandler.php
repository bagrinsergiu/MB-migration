<?php

namespace MBMigration\Builder\Menu;

use MBMigration\Core\Logger;
use MBMigration\Browser\BrowserPagePHP;
use MBMigration\Builder\PageBuilder;
use MBMigration\Builder\Utils\TextTools;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;

class MenuHandler
{

    /**
     * @var BrizyAPI
     */
    private $brizyApi;
    /**
     * @var VariableCache
     */
    private $cache;
    /**
     * @var mixed|null
     */
    private $projectID_Brizy;
    /**
     * @var PageBuilder
     */
    private $browserPage;

    public function __construct(BrowserPagePHP $browserPage)
    {
        $this->cache = VariableCache::getInstance();
        
        $this->browserPage = $browserPage;
        
        $this->brizyApi = $this->cache->getClass('brizyApi');
        $this->projectID_Brizy = $this->cache->get('projectId_Brizy');
    }

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
            'brizy.StyleExtractor.js',
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