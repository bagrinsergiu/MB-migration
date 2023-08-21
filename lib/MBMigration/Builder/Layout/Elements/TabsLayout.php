<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class TabsLayout extends Element
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
        return $this->TabsLayout($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function TabsLayout(array $sectionData) {
        Utils::log('Create bloc', 1, "] [tabs_layout");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['tabs-layout'];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();
        $objRow = new ItemSetter();

        $objBlock->newItem($decoded['main']);
        $objRow->newItem($decoded['row']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objRow->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
            $objRow->item(0)->setting('navIcon', 'filled');
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
                $objBlock->item(0)->setting('bgColorOpacity', 0);
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        }

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            $background = $this->getKeyRecursive('background', 'sections', $sectionData);

            if(isset($background['photo']) && isset($background['filename'])) {
                $objBlock->item(0)->setting('bgImageSrc', $background['photo']);
                $objBlock->item(0)->setting('bgImageFileName', $background['filename']);
            }
            if(isset($background['opacity']) ) {
                $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($background['opacity']));
                $objBlock->item(0)->setting('tempBgColorOpacity', $this->colorOpacity($background['opacity']));
            }
        }

        $blockHead = false;

        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            $show_header = true;
            $show_body = true;

            if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                $show_header = $sectionData['settings']['sections']['text']['show_header'];
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                $show_body = $sectionData['settings']['sections']['text']['show_body'];
            }

            if ($headItem['item_type'] === 'title' && $show_header) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                $objBlock->item(0)->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], $options)));
            }

            if ($headItem['item_type'] === 'body' && $show_body) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                $objBlock->item(0)->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], $options)));
            }
        }

        foreach ($sectionData['items'] as $section) {

            $objItem->newItem($decoded['item']);

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    //$objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    //$objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    //$objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'tab_title') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objItem->setting('labelText', $this->replaceString($item['content'], $options)['text']);
                    }

                    if ($item['item_type'] === 'tab_body') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }

            }

            $objRow->item(0)->addItem($objItem->get());
            $objRow->item(0)->setting('contentBgColorHex', $blockBg);
        }
        $objBlock->item(0)->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}