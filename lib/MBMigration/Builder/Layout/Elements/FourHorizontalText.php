<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class FourHorizontalText extends Element
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
        return $this->FourHorizontalText($elementData);
    }

    protected function FourHorizontalText($sectionData)
    {

        Utils::log('Create full media', 1, "] [three-horizontal-text");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['three-horizontal-text'];
        $block = json_decode($decoded['main'], true);

        $objBlock = new ItemSetter($decoded['main']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } else {
            $defaultPalette = $this->cache->get('subpalette', 'parameter');
            $blockBg = $defaultPalette['subpalette1']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')){
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        foreach ($sectionData['items'] as $item) {

            if($item['group'] == 0){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceString($item['content'], $options);
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceString($item['content'], $options);
                    }
                }
            }
            if($item['group'] == 1){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
            if($item['group'] == 2){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(2)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(2)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
            if($item['group'] == 3){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(3)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(3)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }


}