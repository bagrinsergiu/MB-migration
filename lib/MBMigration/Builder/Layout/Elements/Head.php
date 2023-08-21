<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\Layout\Anthem\Elements\ElementInterface;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Head extends Element implements ElementInterface
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
        $decoded = $this->jsonDecode['blocks']['menu'];
        $block = json_decode($decoded['main'], true);
        $lgoItem = $this->cache->get('header','mainSection');
        foreach ($lgoItem['items'] as $item)
        {
            if ($item['category'] = 'photo')
            {
                $logo['imageSrc'] = $item['content'];
                $logo['imageFileName'] = $item['imageFileName'];
            }
        }
        $itemMenu = json_decode($decoded['item'], true);

        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $logo['imageSrc'];
        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $logo['imageFileName'];
        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['menuSelected'] = $menuList['uid'];

        $itemsMenu = $this->creatingMenuTree($menuList['list'], $itemMenu);

        if($this->checkArrayPath($lgoItem, 'settings/color/subpalette')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($lgoItem['color']);
            $block['value']['items'][0]['value']['bgColorType'] = 'solid';
            $this->cache->set('flags', ['createdFirstSection'=> false, 'bgColorOpacity' => true]);
        } else {
            $color = $this->cache->get('nav-subpalette','subpalette');
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($color['bg']);
            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['subMenuBgColorHex'] = strtolower($color['bg']);
            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['subMenuColorHex'] = strtolower($color['nav-text']);
            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['colorHex'] = strtolower($color['nav-text']);
            $block['value']['items'][0]['value']['bgColorOpacity'] = 1;
            $block['value']['items'][0]['value']['tempBgColorOpacity'] = 0;
            $block['value']['items'][0]['value']['bgColorType'] = 'ungrouped';
            $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);
        }


        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'] = $itemsMenu;


        $block = $this->replaceIdWithRandom($block);
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
}