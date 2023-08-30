<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Core\Utils;

abstract class Element extends Layout
{
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
        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
            $options = array_merge($options, ['bgColor' => $blockBg]);
        }
    }

    protected function setOptionsForTextColor(array $sectionData, array &$options)
    {
        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];
            $options = array_merge($options, ['textColor' => $textColor]);
        }
    }

    protected function backgroundImages(ItemBuilder $objBlock, array $sectionData, array &$options)
    {
        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background Images', 1, "backgroundImages");

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
    }

    protected function setOptionsForUsedFonts(array $item, array &$options)
    {
        if (isset($item['settings']['used_fonts'])){
            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
        }
        $options = array_merge($options, ['fontType' => $item['item_type']]);
    }

    protected function showHeader($sectionData)
    {
        $show_header = true;
        if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
            $show_header = $sectionData['settings']['sections']['text']['show_header'];
        }
        return $show_header;
    }
    protected function showBody($sectionData)
    {
        $show_header = true;
        if($this->checkArrayPath($sectionData, 'settings/sections/text/show_body')){
            $show_header = $sectionData['settings']['sections']['text']['show_body'];
        }
        return $show_header;
    }

}