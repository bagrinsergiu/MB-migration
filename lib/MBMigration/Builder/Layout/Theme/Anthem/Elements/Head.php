<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Browser\BrowserPage;
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

    private $browser;
    /**
     * @var array
     */
    private $fontFamily;

    const SELECTOR = "#main-navigation li:not(.selected) a";
    private $browserPage;

    public function __construct($jsonKitElements, $browser)
    {
        $this->browser = $browser;
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
        $this->fontFamily = $this->getFontsFamily();
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

        $this->browserPage = $this->browser->openPage($url, 'Anthem');

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

        $this->setParseOptions($objBlock, $options, [
            'borderRadius' => 10,
        ]);

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

                if ($item['link'] != '') {
                    $imageLogo['link'] = $item['link'];
                } else {
                    $imageLogo['link'] = $this->cache->get('ParentPages')[0]['slug'];
                }

                if(isset($item['new_window']) && $item['new_window']){
                    $imageLogo['new_window'] = 'on';
                } else {
                    $imageLogo['new_window'] = 'off';
                }

            }
        }

        if ($imageLogo['link'] != '') {

            $urlComponents = parse_url($imageLogo['link']);

            if(!empty($urlComponents['host'])) {
                $slash = '';
            } else {
                $slash = '/';
            }

            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkType', 'external');
            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternal', $slash . $imageLogo['link']);
            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternalBlank', $imageLogo['new_window']);
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

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('mobileWidth', 40);          // in %
        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('mobileHeight', 100);          // in %
        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('mobileMarginLeft', -10);    // in px
        $objBlock->item(0)->item(0)->item(0)->item(0)->setting('mobileMarginTop', 0);       // in px

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('mobileWidthSuffix', '%');
        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('mobileHeightSuffix', '%');
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
        $objBlock->item(0)->setting('bgColorHex', $color['background-color']);
        $objBlock->item(0)->setting('bgColorType', 'solid');
    }

    private function setParseOptions(ItemBuilder $objBlock, $options, array $defOptions = [])
    {
        $this->browserPage->ExtractHoverMenu(self::SELECTOR);

        $result = $this->ExtractMenuStyle($this->browserPage, $options['sectionID']);

        $result['data'] = array_merge_recursive($result['data'], $defOptions);
        $this->cache->set('menuStyles', $result['data']);
        foreach ($result['data'] as $key => $value) {
            $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setting($key, $value);
        }

        $options = [
//            'borderColorHex' => $result['data']['colorHex'] ?? '#d4d4d4',
//            'borderWidthType' => "ungrouped",
//            'borderStyle' => 'solid',
//            'borderColorOpacity' => 0.25,
//            'borderWidth' => 1,
//            'borderTopWidth' => 0,
//            'borderBottomWidth' => 1,
//            'borderRightWidth' => 0,
//            'borderLeftWidth' => 0,

            'boxShadow' => 'on',
            'boxShadowColorOpacity' => 0.25,
            'boxShadowColorHex' => $result['data']['colorHex'] ?? '#d4d4d4',
            'boxShadowColorPalette' => '',
            'boxShadowBlur' => 10,
            'boxShadowSpread' => 0,
            'boxShadowVertical' => 0,
            'boxShadowHorizontal' => 0,
        ];

        foreach ($options as $key => $value) {
            $objBlock->item(0)->setting($key, $value);
        }
    }

    private function ExtractMenuStyle($browserPage, int $sectionId): array
    {
        return $browserPage->evaluateScript(
            'Menu.js',
            [
                'SELECTOR' => '[data-id="'.$sectionId.'"]',
                'FAMILIES' => $this->fontFamily['kit'],
                'DEFAULT_FAMILY' => $this->fontFamily['Default'],
            ]
        );
    }

    protected function getFontsFamily(): array
    {
        $fontFamily = [];
        $cache = VariableCache::getInstance();
        $fonts = $cache->get('fonts', 'settings');
        foreach ($fonts as $font) {
            if ($font['name'] === 'primary') {
                $fontFamily['Default'] = $font['uuid'];
            } else {
                $fontFamily['kit'][$font['fontFamily']] = $font['uuid'];
            }
        }

        return $fontFamily;
    }

    private function convertColor($color): string
    {

        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            return $color;
        }

        if (preg_match('/rgba\((\d+), (\d+), (\d+), ([0-9]*\.?[0-9]+)\)/', $color, $matches)) {
            $r = $matches[1];
            $g = $matches[2];
            $b = $matches[3];

            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        if (preg_match_all('/\d+/', $color, $matches)) {
            if (count($matches[0]) !== 3) {
                return $color;
            }
            list($r, $g, $b) = $matches[0];

            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        return $color;
    }
}