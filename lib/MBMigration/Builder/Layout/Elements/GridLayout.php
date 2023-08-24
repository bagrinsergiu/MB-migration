<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;

class GridLayout extends Element
{
    /**
     * @var VariableCache
     */
    private $cache;
    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    /**
     * @throws \DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->GridLayout($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function GridLayout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");

        $objItem    = new ItemBuilder();
        $objBlock   = new ItemBuilder();
        $objHead    = new ItemBuilder();
        $objRow     = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);
        $objRow->newItem($decoded['row']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
            $options = array_merge($options, ['bgColor' => $blockBg]);
        }

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];
            $options = array_merge($options, ['textColor' => $textColor]);
        }

        $objBlock->item(0)->setting('bgColorPalette', '');
        foreach ( $sectionData['head'] as $head){
            if ($head['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                    $show_header = $sectionData['settings']['sections']['list']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                    $show_body = $sectionData['settings']['sections']['list']['show_body'];
                }

                if ($head['item_type'] === 'title' && $show_header) {

                    if (isset($item['settings']['used_fonts'])) {
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center']);
                    $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($head['content'], $options)));

                }

                if ($head['item_type'] === 'body' && $show_body) {

                    if (isset($item['settings']['used_fonts'])) {
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                    $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($head['content'], $options)));
                }
            }
        }
        $objBlock->item()->addItem($objHead->get());

        if(!empty($sectionData['settings']['sections']['background'])) {
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }

        foreach ($sectionData['items'] as $section)
        {
            $objItem->newItem($decoded['item']);

            if(isset($section['item'])) {
                switch ($section['category']) {
                    case 'text':
                        if ($section['item_type'] == 'title') {
                            break;
                        }
                        if ($section['item_type'] == 'body') {
                            break;
                        }
                    case 'list':
                        foreach ($section['item'] as $sectionItem) {
                            if ($sectionItem['category'] == 'photo') {
                                $objItem->setting('bgImageSrc', $sectionItem['content']);
                                $objItem->setting('bgImageFileName', $sectionItem['imageFileName']);

                                if ($sectionItem['link'] != '') {
                                    $objItem->setting('linkType', 'external');
                                    $objItem->setting('linkExternal', '/' . $sectionItem['link']);
                                }
                            }
                            if ($sectionItem['category'] == 'text') {
                                if ($sectionItem['item_type'] == 'title') {
                                    if (isset($item['settings']['used_fonts'])){
                                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                                    }
                                    $objItem->item(1)->item(0)->setText($this->replaceString($sectionItem['content'], [ 'sectionType' => 'brz-tp-lg-paragraph', 'bgColor' => $blockBg]));
                                    $objItem->item(1)->item(0)->setting('typographyFontSize', 27);
                                }
                            }
                        }
                        break;
                }
            } else {
                if ($section['category'] == 'photo') {
                    $objItem->item(0)->item(0)->setting('imageSrc', $section['content']);
                    $objItem->item(0)->item(0)->setting('imageFileName', $section['imageFileName']);

                    if ($section['link'] != '') {
                        $objItem->item(0)->item(0)->setting('linkType', "external");
                        $objItem->item(0)->item(0)->setting('linkExternal', '/' . $section['link']);
                    }
                }
                if ($section['category'] == 'text') {

                    $show_header = true;
                    $show_body = true;

                    if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                        $show_header = $sectionData['settings']['sections']['list']['show_header'];
                    }
                    if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                        $show_body = $sectionData['settings']['sections']['list']['show_body'];
                    }

                    if ($section['item_type'] == 'title' && $show_header) {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $objItem->addItem($this->itemWrapperRichText($this->replaceString($section['content'], [ 'sectionType' => 'brz-tp-lg-heading1', 'bgColor' => $blockBg])));
                    }
                    if ($section['item_type'] == 'body' && $show_body) {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $objItem->addItem($this->itemWrapperRichText($this->replaceString($section['content'], [ 'sectionType' => 'brz-tp-lg-paragraph', 'bgColor' => $blockBg])));
                    }
                }
            }
            $objRow->addItem($objItem->get());
        }
        $objBlock->item()->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}