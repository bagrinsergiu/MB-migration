<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class AccordionLayout extends Element
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
        return $this->AccordionLayout($elementData);
    }

    /**
     * @throws \DOMException
     */
    protected function AccordionLayout(array $sectionData) {
        Utils::log('Create bloc', 1, "] [grid_layout");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['accordion-layout'];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();
        $objList = new ItemSetter();

        $objBlock->newItem($decoded['main']);
        $objList->newItem($decoded['list']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objList->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
            $objList->item(0)->setting('navIcon', 'filled');
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

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


        if($blockHead) {
            //$objBlock->item(0)->addItem($objHead->get());
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
                    if ($item['item_type'] === 'accordion_title') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-left', 'upperCase' => 'brz-capitalize-on']);
                        $objItem->setting('labelText', $this->replaceString($item['content'], $options)['text']);
                    }

                    if ($item['item_type'] === 'accordion_body') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }

                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }

            }
            $objList->item(0)->addItem($objItem->get());
        }
        $objBlock->item(0)->addItem($objList->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}