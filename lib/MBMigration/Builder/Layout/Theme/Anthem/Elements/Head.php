<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Builder\Utils\UrlBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class Head extends Element
{
    /**
     * @var VariableCache
     */
    protected $cache;

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

        $options = [];

        $this->cache->set('currentSectionData', $menuList);
        $headItem = $this->cache->get('header','mainSection');
        $section = $this->jsonDecode['blocks']['menu'];

        $treePages = $this->cache->get('ParentPages');
        $deepSlug = PathSlugExtractor::findDeepestSlug($treePages);
        $url = PathSlugExtractor::getFullUrl($deepSlug['slug']);

        $objBlock = new ItemBuilder();
        $objBlock->newItem($section['main']);

        $this->setImageLogo($objBlock, $headItem);

        $this->creatingMenu($objBlock, $menuList, $section);

        $this->generalParameters($objBlock, $options, $headItem);

        $options['currentPageURL'] = $url;

        $this->setColorBackground($objBlock, $options);

        $this->setParseOptions($objBlock, $options);

        $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);

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

        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->addItem($itemsMenu);
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('menuSelected', $menuList['uid']);
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

    private function setColorBackground(ItemBuilder $objBlock, $options)
    {
        $color = JS::StylesColorExtractor($options['sectionID'], $options['currentPageURL']);

        $objBlock->item(0)->setting('bgColorHex', $color); // maim bg
        $objBlock->item(0)->setting('colorHex', '#000000');
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('colorHex','#000000' ); // main text
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('menuBgColorHex','#ffffff' ); // main text
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('hoverColorHex','#323232' ); // main text
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('subMenuColorHex', "#000000"); //sub menu text
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('subMenuBgColorHex', '#ababab'); //sub menu bg
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('hoverSubMenuColorHex', '#d5d5d5'); //sub menu bg
        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting('hoverSubMenuBgColorHex', '#838383'); //sub menu bg


        $objBlock->item(0)->setting('bgColorPalette', '');

        $objBlock->item(0)->setting('bgColorOpacity', 1);
        $objBlock->item(0)->setting('tempBgColorOpacity', 1);
        $objBlock->item(0)->setting('bgColorType', 'ungrouped');
    }

    private function setParseOptions(ItemBuilder $objBlock, $options)
    {
        $result = JS::stylesMenuExtractor($options['sectionID'], $options['currentPageURL'], $options['fontsFamily']);

        foreach ($result as $key => $value) {
            $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting($key, $value);
        }

    }
}