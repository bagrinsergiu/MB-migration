<?php

namespace MBMigration\Builder\Layout\Elements;

use DOMException;
use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class FullMedia extends Element
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
        return $this->FullMedia($elementData);
    }

    /**
     * @throws DOMException
     */
    protected function FullMedia(array $sectionData)
    {
        Utils::log('Create full media', 1, "] [full_media");

        $objBlock = new ItemSetter();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');
        $objBlock->item(0)->setting('bgAttachment','none');
        $objBlock->item(0)->setting('bgColorOpacity', 1);

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photo') &&
            $this->checkArrayPath($sectionData, 'settings/sections/background/filename'))
        {
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')){
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_header = $sectionData['settings']['sections']['text']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_body = $sectionData['settings']['sections']['text']['show_body'];
                }

                if($item['item_type']=='title' && $show_header) {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], [ 'sectionType' => 'brz-tp-lg-heading1', 'bgColor' => $blockBg]));
                }
                if($item['item_type']=='body' && $show_body) {

                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], [ 'sectionType' => 'brz-tp-lg-paragraph', 'bgColor' => $blockBg]));
                }
            }
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item()->item()->item()->item(3)->item()->setting('imageSrc', $item['content']);
                $objBlock->item()->item()->item()->item(3)->item()->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}