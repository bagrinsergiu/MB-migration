<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class ListLayout extends Element
{
    /**
     * @var VariableCache
     */
    protected $cache;
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
        return $this->ListLayout($elementData);
    }

    /**
     * @throws \DOMException
     */
    protected function ListLayout(array $sectionData) {
        Utils::log('Create bloc', 1, "list_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['list-layout'];

        $options = [];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objHead = new ItemBuilder();
        $objImage = new ItemBuilder();
        $objRow = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);
        $objImage->newItem($decoded['image']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

//        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
//            Utils::log('Set background', 1, "] [list_layout");
//
//            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
//                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
//                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
//                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
//            }
//            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
//                if ($this->checkArrayPath($sectionData, 'settings/sections/background/fadeMode')) {
//                    if ($sectionData['settings']['sections']['background']['fadeMode'] !== 'none'){
//                        $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
//                        if ($opacity <= 0.3) {
//                            $options = array_merge($options, ['textColor' => '#000000']);
//                        }
//                        $objBlock->item(0)->setting('bgColorOpacity', $opacity);
//                    } else {
//                        $objBlock->item(0)->setting('bgColorOpacity', 1);
//                    }
//                }
//                $objBlock->item(0)->setting('bgColorType', 'none');
//            }
//        }

        $blockHead = false;
        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            $show_header = true;
            $show_body = true;

            if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                $show_header = $sectionData['settings']['sections']['list']['show_header'];
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                $show_body = $sectionData['settings']['sections']['list']['show_body'];
            }

            if ($headItem['item_type'] === 'title' && $show_header) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                $objHead->item(0)->item(0)->item(0)->setText($this->replaceString($headItem['content'], $options));
            }

            if ($headItem['item_type'] === 'body' && $show_body) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                $objHead->item(0)->item(2)->item(0)->setText($this->replaceString($headItem['content'], $options));
            }
        }

        if($blockHead) {
            $objBlock->item(0)->addItem($objHead->get());
        }

        foreach ($sectionData['items'] as $section) {
            $objRow->newItem($decoded['row']);
            $objItem->newItem($decoded['item']);
            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    $objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    $objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    $objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'title') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }

                    if ($item['item_type'] === 'body') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                        $objItem->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
            $objRow->addItem($objItem->get());
            $objBlock->item(0)->addItem($objRow->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}