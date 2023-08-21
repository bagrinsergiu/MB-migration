<?php

namespace MBMigration\Builder\Layout\Elements;

use DOMException;
use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class LeftMedia extends Element
{

    private $cache;
    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    /**
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->LeftMedia($elementData);
    }

    protected function LeftMedia(array $sectionData)
    {
        Utils::log('Create bloc', 1, "] [left_media");
        $options = [];
        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['left-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        }

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [left_media");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') && $sectionData['settings']['sections']['background']['filename'] !== ''){
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/background/photo') && $sectionData['settings']['sections']['background']['photo'] !== '') {
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }

            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
                $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
                if ($opacity <= 0.3) {
                    $options = array_merge($options, ['textColor' => '#000000']);
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/background/fadeMode') && $sectionData['settings']['sections']['background']['fadeMode'] !== 'none') {
                    $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                }
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);

                if($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageWidth', $item['settings']['image']['width']);
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageHeight', $item['settings']['image']['height']);
                }

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title') {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-left', 'upperCase' => 'brz-capitalize-on']);

                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if($item['item_type']=='body') {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);

                    $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}