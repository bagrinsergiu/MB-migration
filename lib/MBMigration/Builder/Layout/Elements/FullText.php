<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class FullText extends Element
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
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->FullText($elementData);
    }

    protected function FullText(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [full_text");

        $options = [];
        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-text'];

        $objBlock->newItem($decoded['main']);

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

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [right_media");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
                $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
                if ($opacity <= 0.3) {
                    $options = array_merge($options, ['textColor' => '#000000']);
                }
                $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
            $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {

            Utils::log('Set background', 1, $this->layoutName . "] [full_text");

            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
        }

        $objBlock->item(0)->item(0)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_header = $sectionData['settings']['sections']['text']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_body = $sectionData['settings']['sections']['text']['show_body'];
                }

                if ($item['item_type'] == 'title' && $show_header) {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-left', 'upperCase' => 'brz-capitalize-on']);
                    $objBlock->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if ($item['item_type'] == 'body' && $show_body) {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                    $objBlock->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}