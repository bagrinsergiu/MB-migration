<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Preprocess;

use MBMigration\Builder\Utils\TextTools;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class NavMenuBuilder
{
    private $cache;
    private $brizyApi;
    private $projectID_Brizy;

    public function __construct()
    {
        $this->cache = VariableCache::getInstance();
        $this->brizyApi = $this->cache->getClass('brizyApi');
        $this->projectID_Brizy = $this->cache->get('projectId_Brizy');
    }

    public function createMenuStructure(): void
    {
        Utils::log('Create menu structure', 1, 'createMenuStructure');

        $parentPages = $this->cache->get('menuList');
        $mainMenu = $this->transformToBrizyMenu($parentPages['list']);

        $data = [
            'project' => $this->projectID_Brizy,
            'name' => 'mainMenu',
            'data' => json_encode($mainMenu)
        ];

        $result = $this->brizyApi->createMenu($data);
        $this->cache->add('menuList', $result);
    }

    private function transformToBrizyMenu(array $parentMenu): array
    {
        $mainMenu = [];
        $textTransform = '';

        $settingsTextTransform = $this->cache->get('fonts', 'settings');
        foreach ($settingsTextTransform as $itemTextTransform){
            if ($itemTextTransform['name'] === 'main_nav') {
                $textTransform = $itemTextTransform['text_transform'];
            }
        }

        foreach ($parentMenu as $item) {
            if($item['hidden'] === true) {
                continue;
            }
            $settings = json_decode($item['parentSettings'], true);
            if (array_key_exists('external_url', $settings)) {
                $mainMenu[] = [
                    'id' => '',
                    "items" => [],
                    "isNewTab" => $this->checkOpenInNewTab($settings),
                    "label" => TextTools::transformText($item['name'], $textTransform),
                    "type" => "custom_link",
                    'url' => $settings['external_url'],
                    "uid" => Utils::getNameHash(),
                    "description" => ""
                ];
            } else {
                if(empty($item['collection'])){
                    $item['collection'] = $item['child'][0]['collection'];
                }
                $mainMenu[] = [
                    "id" => $item['collection'],
                    "items" => [],
                    "isNewTab" => $this->checkOpenInNewTab($settings),
                    "label" => TextTools::transformText($item['name'], $textTransform),
                    "uid" => Utils::getNameHash()
                ];
            }
        }
        return $mainMenu;
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