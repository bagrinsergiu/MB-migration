<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\Utils\TextTools;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;

class MenuBuilder implements MenuBuilderInterface
{
    protected int $brizyProject;
    protected BrizyAPI $brizyApi;
    protected array $fonts;
    protected array $textTransform;

    public function __construct(int $brizyProject, BrizyAPI $brizyApi, array $fonts)
    {
        $this->brizyProject = $brizyProject;
        $this->brizyApi = $brizyApi;
        $this->fonts = $fonts;

        foreach ($fonts as $itemTextTransform) {
            if (isset($itemTextTransform['name']) && $itemTextTransform['name'] === 'main_nav') {
                $this->textTransform['mainMenu'] = $itemTextTransform['text_transform'] ?? 'none';
            }
            if (isset($itemTextTransform['name']) && $itemTextTransform['name'] === 'sub_nav') {
                $this->textTransform['subMenu'] = $itemTextTransform['text_transform'] ?? 'none';
            }
        }
    }

    public function createBrizyMenu($name, $menuItems): array
    {
        $data = [
            'project' => $this->brizyProject,
            'name' => $name,
            'data' => json_encode($menuItems),
        ];

        $result = $this->brizyApi->createMenu($data);

        return $result;
    }

    public function transformToBrizyMenu(array $menuItems): array
    {
        // filter hidden menu items
        $menuItems = array_values(array_filter($menuItems, fn($item) => isset($item['hidden']) && !$item['hidden']));

        $mainMenu = $this->getMainMenu($menuItems, $this->textTransform);

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
}
