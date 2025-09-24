<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\Utils\TextTools;
use MBMigration\Core\Utils;
use MBMigration\Core\Logger;
use MBMigration\Layer\Brizy\BrizyAPI;

class MenuBuilder implements MenuBuilderInterface
{
    protected int $brizyProject;
    protected BrizyAPI $brizyApi;
    protected array $fonts;
    protected array $textTransform;

    public function __construct(int $brizyProject, BrizyAPI $brizyApi, array $fonts)
    {
        Logger::instance()->info('MenuBuilder constructor called', [
            'brizy_project' => $brizyProject,
            'fonts_count' => count($fonts),
            'brizy_api_class' => get_class($brizyApi)
        ]);

        $this->brizyProject = $brizyProject;
        $this->brizyApi = $brizyApi;
        $this->fonts = $fonts;

        foreach ($fonts as $itemTextTransform) {
            if (isset($itemTextTransform['name']) && $itemTextTransform['name'] === 'main_nav') {
                $this->textTransform['mainMenu'] = $itemTextTransform['text_transform'] ?? 'none';
                Logger::instance()->info('Main menu text transform configured', [
                    'transform' => $this->textTransform['mainMenu']
                ]);
            }
            if (isset($itemTextTransform['name']) && $itemTextTransform['name'] === 'sub_nav') {
                $this->textTransform['subMenu'] = $itemTextTransform['text_transform'] ?? 'none';
                Logger::instance()->info('Sub menu text transform configured', [
                    'transform' => $this->textTransform['subMenu']
                ]);
            }
        }

        Logger::instance()->info('MenuBuilder initialized successfully', [
            'brizy_project' => $this->brizyProject,
            'text_transforms' => $this->textTransform ?? []
        ]);
    }

    public function createBrizyMenu($name, $menuItems): array
    {
        Logger::instance()->info('MenuBuilder::createBrizyMenu called', [
            'name' => $name,
            'menu_items_count' => count($menuItems),
            'project' => $this->brizyProject
        ]);

        $data = [
            'project' => $this->brizyProject,
            'name' => $name,
            'data' => json_encode($menuItems),
        ];

        Logger::instance()->info('Calling Brizy API createMenu', [
            'data_keys' => array_keys($data),
            'data_json_length' => strlen($data['data'])
        ]);

        try {
            $result = $this->brizyApi->createMenu($data);

            Logger::instance()->info('Brizy menu created successfully', [
                'name' => $name,
                'result_keys' => is_array($result) ? array_keys($result) : 'not_array',
                'result_type' => gettype($result)
            ]);

            return $result;
        } catch (\Exception $e) {
            Logger::instance()->error('Error creating Brizy menu', [
                'name' => $name,
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);
            throw $e;
        }
    }

    public function transformToBrizyMenu(array $menuItems): array
    {
        Logger::instance()->info('MenuBuilder::transformToBrizyMenu called', [
            'input_menu_items_count' => count($menuItems),
            'text_transforms' => $this->textTransform ?? []
        ]);

        // filter hidden menu items
        $menuItems = array_values(array_filter($menuItems, fn($item) => isset($item['hidden']) && !$item['hidden']));

        Logger::instance()->info('Menu items filtered for visibility', [
            'filtered_count' => count($menuItems),
            'items_removed' => count($menuItems) - count(array_filter($menuItems, fn($item) => isset($item['hidden']) && !$item['hidden']))
        ]);

        $mainMenu = $this->getMainMenu($menuItems, $this->textTransform);

        Logger::instance()->info('Menu transformation completed', [
            'output_menu_items_count' => count($mainMenu),
            'transformation_successful' => is_array($mainMenu)
        ]);

        return $mainMenu;
    }

    /**
     * @param array $menuItems
     * @return array
     */
    protected function getMainMenu(array $menuItems, $textTransform, bool $itemChild = false): array
    {
        $mainMenu = [];

        foreach ($menuItems as $item) {

            $settings = json_decode($item['parentSettings'], true);

            $mainMenu[] = $this->createMenuItemData($settings, $item, $textTransform, $itemChild);
        }

        return $mainMenu;
    }


    /**
     * @param $settings
     * @param $item
     * @return array
     */
    protected function createMenuItemData($settings, $item, $textTransform, bool $itemChild = false ): array
    {
        if($itemChild){
            $textTransformMenu = $textTransform['subMenu'];
        } else {
            $textTransformMenu = $textTransform['mainMenu'];
        }

        $newItem = [
            "uid" => Utils::getNameHash(),
            "isNewTab" => $this->checkOpenInNewTab($settings),
            'isIndex'=>$item['position']==1 && !$item['parent_id'] && $item['landing'],
            "label" => TextTools::transformText($item['name'], $textTransformMenu),
            "items" => [],
        ];

        if ($settings && array_key_exists('external_url', $settings)) {
            $newItem['id'] = '';
            $newItem['type'] = "custom_link";
            $newItem['url'] = $settings['external_url'];
            $newItem['description'] = "";
        } else {
            if (empty($item['collection']) && count($item['child'])) {
                $item['collection'] = $item['child'][0]['collection'];
            }
            $newItem['id'] = $item['collection'];
        }

        if (isset($item['child']) && count($item['child'])) {
            $newItem["items"] = $this->getMainMenu($item['child'], $this->textTransform, true);
        }

        return $newItem;
    }


    protected function checkOpenInNewTab($settings): bool
    {
        if ($settings && array_key_exists('new_window', $settings)) {
            return $settings['new_window'];
        } else {
            return false;
        }
    }

    protected function removeHiddenElements(array $items): array {
        $filteredItems = [];

        foreach ($items as $item) {
            if (!$item['hidden']) {
                if (!empty($item['child'])) {
                    $item['child'] = $this->removeHiddenElements($item['child']);
                }
                $filteredItems[] = $item;
            }
        }

        return $filteredItems;
    }
}
