<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Head extends Element
{
    /**
     * @var VariableCache
     */
    private $cache;

    /**
     * @var mixed
     */
    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    public function getElement(array $elementData = []): bool
    {
        return $this->Menu($elementData);
    }

    private function Menu($menuList): bool
    {
        Utils::log('Create block menu', 1, "] [createMenu");

        $this->cache->set('currentSectionData', $menuList);
        $headItem = $this->cache->get('header','mainSection');
        $section = $this->jsonDecode['blocks']['menu'];

        $objBlock = new ItemBuilder();
        $objBlock->newItem($section['main']);

        $this->setImageLogo($objBlock, $headItem);

        $this->creatingMenu($objBlock, $menuList, $section);

        if($this->checkArrayPath($headItem, 'settings/color/subpalette')) {
            $objBlock->item(0)->setting('bgColorPalette', '');
            $objBlock->item(0)->setting('bgColorHex', $headItem['color']);
            $objBlock->item(0)->setting('bgColorType', 'solid');

            $this->cache->set('flags', ['createdFirstSection'=> false, 'bgColorOpacity' => true]);
        } else {
            $this->setColorBackground($objBlock);
            $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        $this->cache->set('menuBlock', json_encode($block));

        return true;
    }

    private function creatingMenuTree($menuList, $blockMenu): array
    {
        $treeMenu = [];
        foreach ($menuList as $item)
        {
            $blockMenu['value']['itemId'] = $item['collection'];
            $blockMenu['value']['title'] = $item['name'];
            if($item['slug'] == 'home') {
                $blockMenu['value']['url'] = '/';
            } else {
                $blockMenu['value']['url'] = $item['slug'];
            }
            $blockMenu['value']['items'] = $this->creatingMenuTree($item['child'], $blockMenu);
            if($item['landing'] == false){
                $blockMenu['value']['url'] = $blockMenu['value']['items'][0]['value']['url'];
            }

            $encodeItem = json_encode($blockMenu);

            $blockMenu['value']['id'] = $this->getNameHash($encodeItem);

            $treeMenu[] = $blockMenu;
        }
        return $treeMenu;
    }

    private function creatingMenu(ItemBuilder $objBlock, $menuList, $section): void
    {
        $itemMenu = json_decode($section['item'], true);
        $itemsMenu = $this->creatingMenuTree($menuList['list'], $itemMenu);

        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->addItem($itemsMenu);
        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('menuSelected', $menuList['uid']);
    }

    private function setImageLogo(ItemBuilder $objBlock, $headItem): void
    {
        $imageLogo = [];

        foreach ($headItem['items'] as $item) {
            if ($item['category'] = 'photo') {
                $imageLogo['imageSrc'] = $item['content'];
                $imageLogo['imageFileName'] = $item['imageFileName'];
            }
        }

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $imageLogo['imageSrc']);
        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageFileName', $imageLogo['imageFileName']);
    }

    private function setColorBackground(ItemBuilder $objBlock)
    {
        $color = $this->cache->get('nav-subpalette','subpalette');

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('bgColorHex', $color['bg']);
        $objBlock->item(0)->setting('bgColorOpacity', 1);
        $objBlock->item(0)->setting('tempBgColorOpacity', 1);
        $objBlock->item(0)->setting('bgColorType', 'ungrouped');

        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('subMenuBgColorHex', $color['bg']);
        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('subMenuColorHex', $color['nav-text']);
        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('colorHex', $color['nav-text']);

    }
}