<?php

namespace MBMigration\Builder\Layout\Elements;

use Exception;
use MBMigration\Builder\Checking;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

abstract class Element extends LayoutUtils
{

    use checking;
    /**
     * @throws Exception
     */
    protected function initData()
    {
        Utils::log('initData!', 4, 'Main Layout');
        return $this->loadKit();
    }

    protected function backgroundParallax(ItemBuilder $objBlock, array $sectionData)
    {
        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
                $objBlock->item(0)->setting('bgColorOpacity', 0);
            }
        }
    }

    protected function backgroundColor(ItemBuilder $objBlock, array $sectionData, &$options)
    {

        $color = JS::StylesColorExtractor($options['sectionID'], $options['currentPageURL']);

        if($color){
            $objBlock->item(0)->setting('bgColorHex', $color);
        } else if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } else {
            $defaultPalette = $this->cache->get('subpalette', 'parameter');
            $blockBg = $defaultPalette['subpalette1']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {

            $fadeMode = $sectionData['settings']['sections']['background']['fadeMode'];
            $blendMode = $sectionData['settings']['sections']['background']['blendMode'];
            $photoOption = $sectionData['settings']['sections']['background']['photoOption'];

            $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
            if ($opacity <= 0.3) {
                $options = array_merge($options, ['textColor' => '#000000']);
            }
            if(!$fadeMode == 'none' && !$blendMode == 'none'){
                $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                $objBlock->item(0)->setting('bgColorType', 'none');
            } else if ($photoOption == 'parallax-scroll' or $photoOption == 'parallax-fixed') {
                $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                $objBlock->item(0)->setting('bgColorType', 'none');
            }  else if ($photoOption == 'fill') {
                $objBlock->item(0)->setting('bgColorOpacity', 1);
                $objBlock->item(0)->setting('bgColorType', 'none');
            } else {
                $objBlock->item(0)->setting('bgColorOpacity', 1);
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        $options = array_merge($options, ['bgColor' => $blockBg]);
    }

/**
 *
 */
    protected function setOptionsForTextColor(array $sectionData, array &$options)
    {
        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];
            $options = array_merge($options, ['textColor' => $textColor]);
        }
    }

/**
 *
*/
    protected function backgroundImages(ItemBuilder $objBlock, array $sectionData, array &$options)
    {
        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background Images', 1, "backgroundImages");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }
        }
    }

/**
 *
 */
    protected function setOptionsForUsedFonts(array $item, array &$options)
    {
        if (isset($item['settings']['used_fonts'])){
            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
        }
        $options = array_merge($options, ['fontType' => $item['item_type']]);
    }

/**
 *
 */
    protected function getFontsFamily(): array
    {
        $fontFamily = [];
        $cache = VariableCache::getInstance();
        $fonts = $cache->get('fonts', 'settings');
        foreach ($fonts as $font) {
            $fontFamily[$font['fontFamily']] = $font['uuid'];
        }
        return $fontFamily;
    }

/**
 *
 */
    protected function defaultOptionsForElement($element, &$options)
    {
           $loadOptions = json_decode($element['options'], true);
               $positionOption = [
                   'title' => $loadOptions['title']['textPosition'],
                   'body' => $loadOptions['body']['textPosition']
               ];
           $options = array_merge($options, ['textPosition' => $positionOption]);
    }

/**
 *
*/
    protected function defaultTextPosition($element, &$options)
    {
        if(!empty($options['textPosition'])){
            switch ($element['item_type']){
                case "title":
                case "accordion_title":
                    $mainPosition = $options['textPosition']['title'];
                    break;
                case "body":
                case "accordion_body":
                    $mainPosition = $options['textPosition']['body'];
                    break;
                default:
                    $mainPosition = 'brz-text-lg-left';
            }
            $options = array_merge($options, ['mainPosition' => $mainPosition]);
        }
    }


/**
*
*/
    protected function textType($item, &$options, $type = 'detect')
    {
        if(!empty($item['fontType']) && $type == 'detect'){
            switch ($item['fontType']){
                case "title":
                case "accordion_title":
                    $sectionType = 'brz-tp-lg-heading1';
                    break;
                case "body":
                case "accordion_body":
                    $sectionType = 'brz-tp-lg-paragraph';
                    break;
                default:
                    $sectionType = 'brz-tp-lg-paragraph';
            }
        } elseif ($type == 'title') {
            $sectionType = 'brz-tp-lg-heading1';
        } else {
            $sectionType = 'brz-tp-lg-paragraph';
        }
        $options = array_merge($options, ['sectionType' => $sectionType]);
    }

    protected function showHeader($sectionData)
    {
        $show_header = true;
        $sectionCategory = $sectionData['category'];
        $path = "settings/sections/" . $sectionCategory . "/show_header";
        if($this->checkArrayPath($sectionData, $path)){
            $show_header = $sectionData['settings']['sections'][$sectionCategory]['show_header'];
        }
        return $show_header;
    }

    protected function showBody($sectionData)
    {
        $show_header = true;
        $sectionCategory = $sectionData['category'];
        if($this->checkArrayPath($sectionData, "settings/sections/" . $sectionCategory . "/show_body")){
            $show_header = $sectionData['settings']['sections'][$sectionCategory]['show_body'];
        }
        return $show_header;
    }

    protected function createCollectionItems($mainCollectionType, $slug, $title)
    {
        Utils::log('Create Detail Page: ' . $title, 1, $this->layoutName . "] [createDetailPage");
        if($this->pageCheck($slug)) {
            $QueryBuilder = $this->cache->getClass('QueryBuilder');
            $createdCollectionItem = $QueryBuilder->createCollectionItem($mainCollectionType, $slug, $title);
            return $createdCollectionItem['id'];
        } else {
            $ListPages = $this->cache->get('ListPages');
            foreach ($ListPages as $listSlug => $collectionItems) {
                if ($listSlug == $slug) {
                    return $collectionItems;
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function createDetailPage($itemsID, $slug, string $elementName) {
        $itemsData = [];
        $jsonDecode = $this->initData();
        $QueryBuilder = $this->cache->getClass('QueryBuilder');

        if($this->checkArrayPath($jsonDecode, "dynamic/$elementName")){
            $decoded = $jsonDecode['dynamic']['$elementName'];
        } else {
            throw new Exception('Element not found');
        }

        $itemsData['items'][] = $this->cache->get('menuBlock');
        $itemsData['items'][] = json_decode($decoded['detail'], true);
        $itemsData['items'][] = $this->cache->get('footerBlock');

        $pageData = json_encode($itemsData);

        $QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);
    }

    protected function generalParameters($objBlock, &$options, $sectionData)
    {
        $options = [
            'position' => $sectionData['settings']['pagePosition'],
            'currentPageURL' => $this->cache->get('CurrentPageURL'),
            'sectionID' => $sectionData['sectionId'],
            'fontsFamily' => $this->getFontsFamily()
        ];

        $padding = JS::StylesPaddingExtractor($options['sectionID'], $options['currentPageURL']);

        if(!empty($padding)){
            $objBlock->item(0)->setting('bgColorPalette', '');
            $objBlock->item(0)->setting('colorPalette', '');
            $objBlock->item(0)->setting('paddingBottom', $padding['padding-bottom']);
            $objBlock->item(0)->setting('paddingTop', $padding['padding-top']);
            $objBlock->item(0)->setting('paddingLeft', $padding['padding-left']);
            $objBlock->item(0)->setting('paddingRight', $padding['padding-right']);
        }
    }


    protected function insertItemInArray(array $array, array $item, $index): array
    {
        if ($index >= 0 && $index <= count($array)) {
            $left = array_slice($array, 0, $index);
            $right = array_slice($array, $index);
            $result = array_merge($left, [$item], $right);
        } else {
            $result = array_merge($array, [$item]);
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    protected function itemWrapperRichText($content, array $settings = [], $associative = false)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--richText'];
        $block = new ItemBuilder($decoded);
        $block->item(0)->setText($content);
        $result = $block->get();
        if (!empty($settings)) {
            foreach ($settings as $key => $value) {
                $block->item(0)->setting($key, $value);
            }
        }
        if (!$associative) {
            return $result;
        }
        return json_decode(json_encode($result), true);
    }

}