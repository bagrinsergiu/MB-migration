<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMDocument;
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

    protected function backgroundVideo(ItemBuilder $objBlock, array $sectionData)
    {
        if($this->checkArrayPath($sectionData, 'settings/sections/background/video')) {

            $videoUrl = $sectionData['settings']['sections']['background']['video'];

            $objBlock->item(0)->setting('media', 'video');
            $objBlock->item(0)->setting('bgVideoType', 'url');
            $objBlock->item(0)->setting('bgVideo', $videoUrl);
        }
    }

    protected function backgroundColor(ItemBuilder $objBlock, array $sectionData, &$options)
    {
        $style = JS::StylesColorExtractor($options['sectionID'], $options['currentPageURL']);

        $objBlock->item(0)->setting('bgColorHex', $style['background-color']);
        $options['bgColor'] = $style['background-color'];
        if(!empty($style['border-bottom-color'])) {
            $options['borderColorHex'] = $style['border-bottom-color'];
        }

        $objBlock->item(0)->setting('bgColorOpacity', $style['opacity']);
        $objBlock->item(0)->setting('bgColorType', 'none');
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

        if (!empty($settings)) {
            foreach ($settings as $key => $value) {
                $block->item(0)->setting($key, $value);
            }
        }
        $result = $block->get();
        if (!$associative) {
            return $result;
        }
        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function embedCode($content)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--embedCode'];
        $block = new ItemBuilder($decoded);
        $block->item(0)->setCode($content);
        $result = $block->get();
        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function button($options, $position)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--button'];
        $block = new ItemBuilder($decoded);
        foreach ($options as $key => $value) {
            $block->item()->setting($key, $value);
        }
        $block->setting('horizontalAlign', $position);
        $result = $block->get();
        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperColumn(array $element)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--column'];
        $block = new ItemBuilder($decoded['main']);
        $block->addItem($element);
        $result = $block->get();
        return json_decode(json_encode($result), true);
    }

    protected function wrapperLine(array $options = [])
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global'];
        $line = new ItemBuilder($decoded['wrapper--line']);
        if(!empty($options)){
            foreach ($options as $key => $value) {
                $line->item()->setting($key, $value);
            }
        }
        $result = $line->get();

        return json_decode(json_encode($result), true);
    }

    protected function wrapperImage(array $element, $wrapper)
    {
        $block = new ItemBuilder($wrapper);
        foreach ($element as $key => $value) {
            $block->item()->setting($key, $value);
        }
        $result = $block->get();
        return json_decode(json_encode($result), true);
    }

    protected function wrapperRow(array $element)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--row'];
        $block = new ItemBuilder($decoded['main']);
        $block->addItem($element);
        $result = $block->get();
        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperIcon($items, $aline)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--icon'];
        $objColum = new ItemBuilder($decoded['main']);
        $objIcon = new ItemBuilder();

        foreach ($items as $settings) {
            $objIcon->newItem($decoded['item']);

            $objIcon->setting('name', $this->getIcoNameByUrl($settings['linkExternal']));
            $objIcon->setting('customSize', 26);

            foreach ($settings as $key => $value) {
                $objIcon->setting($key, $value);
            }

            $objColum->item()->addItem($objIcon->get());
        }
        $objColum->setting('horizontalAlign', $aline);

        $result = $objColum->get();
        return json_decode(json_encode($result), true);
    }

    function findEmbeddedPasteDivs($html): array
    {
        $result = [];

        $dom = new DOMDocument();

        $dom->loadHTML($html);

        $divs = $dom->getElementsByTagName('div');
        foreach ($divs as $div) {
            if ($div->hasAttribute('class') && $div->getAttribute('class') === 'embedded-paste') {
                $dataSrc = $div->getAttribute('data-src');
                $escapedDataSrc = str_replace('"', '\\"', $dataSrc);
                $div->setAttribute('data-src', $escapedDataSrc);

                $result[] = $dom->saveHTML($div);
            }
        }

        return $result;
    }

}