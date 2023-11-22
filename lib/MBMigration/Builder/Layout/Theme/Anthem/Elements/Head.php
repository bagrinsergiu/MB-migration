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
    /**
     * @var mixed
     */
    private $activePage;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    public function getElement(array $elementData = [])
    {
        $this->activePage = $elementData['activePage'];
        $result = $this->Menu($elementData['menu']);

        return $result;
    }

    private function Menu($menuList)
    {
        Utils::log('Create block menu', 1, "] [createMenu");

        $options = [];

        $this->cache->set('currentSectionData', $menuList);
        $headItem = $this->cache->get('header', 'mainSection');
        $section = $this->jsonDecode['blocks']['menu'];

        $treePages = $this->cache->get('ParentPages');
        $deepSlug = PathSlugExtractor::findDeepestSlug($treePages);
        $url = PathSlugExtractor::getFullUrl($deepSlug['slug']);

        $objBlock = new ItemBuilder();
        $objBlock->newItem($section['main']);

        $this->creatingMenu($objBlock, $menuList, $section, $this->activePage);

        $this->generalParameters(
            $objBlock,
            $options,
            $headItem,
            [
                'padding-top' => -15,
                'padding-left' => -20,
            ]
        );

        $options['currentPageURL'] = $url;

        $this->setImageLogo($objBlock, $headItem, $options);

        $this->setColorBackground($objBlock, $options);

        $this->setParseOptions($objBlock, $options);

        $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);

        $block = $this->replaceIdWithRandom($objBlock->get());
        $this->cache->set('menuBlock', json_encode($block));

        return json_decode(json_encode($block), true);
    }

    private function creatingMenuTree($menuList, $blockMenu): array
    {
        $treeMenu = [];
        foreach ($menuList as $item) {
            $blockMenu['value']['itemId'] = $item['collection'];
            $blockMenu['value']['title'] = $item['name'];
            if ($item['slug'] == 'home') {
                $blockMenu['value']['url'] = '/';
            } else {
                $blockMenu['value']['url'] = $item['slug'];
            }
            if ($item['slug'] === $this->activePage) {
                $blockMenu['value']['current'] = true;
            } else {
                $blockMenu['value']['current'] = false;
            }
            $blockMenu['value']['items'] = $this->creatingMenuTree($item['child'], $blockMenu);
            if ($item['landing'] == false) {
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

    private function setImageLogo(ItemBuilder $objBlock, $headItem, $options): void
    {
        $imageLogo = [];

        foreach ($headItem['items'] as $item) {
            if ($item['category'] = 'photo') {
                $imagesStyle = JS::imageStylesExtractor($options['sectionID'], $options['currentPageURL']);

                if (!empty($imagesStyle)) {
                    $imageLogo['width'] = $imagesStyle;
                }

                $imageLogo['imageSrc'] = $item['content'];
                $imageLogo['imageFileName'] = $item['imageFileName'];
                $imageLogo['imageWidth'] = $item['settings']['image']['width'];
                $imageLogo['imageHeight'] = $item['settings']['image']['height'];
            }
        }

        if (!empty($imageLogo['imageWidth']) && !empty($imageLogo['imageHeight'])) {
            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageHeight', $imageLogo['imageHeight']);
            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageWidth', $imageLogo['imageWidth']);
        }
        if (!empty($imageLogo['width'])) {
            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('width', $imageLogo['width']);
        }
        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('horizontalAlign', 'center');
        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('mobileHorizontalAlign', 'left');

        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('mobileMarginLeft', -15);
        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('mobileMarginTop', -10);
        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('mobileMarginLeftSuffix', 'px');
        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('mobileMarginTopSuffix', 'px');

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $imageLogo['imageSrc']);
        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageFileName', $imageLogo['imageFileName']);

    }

    private function setColorBackground(ItemBuilder $objBlock, $options)
    {
        $color = JS::StylesColorExtractor($options['sectionID'], $options['currentPageURL']);
        $objBlock->item(0)->setting('paddingType', 'grouped');
        $objBlock->item(0)->setting('padding', 10);
        $objBlock->item(0)->setting('paddingType', 'grouped');
        $objBlock->item(0)->setting('bgColorOpacity', $color['opacity']);
    }

    private function setParseOptions(ItemBuilder $objBlock, $options)
    {
        $result = JS::stylesMenuExtractor($options['sectionID'], $options['currentPageURL'], $options['fontsFamily']);
        $this->cache->set('menuStyles', $result);
        foreach ($result as $key => $value) {
            $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting($key, $value);
        }
    }
}