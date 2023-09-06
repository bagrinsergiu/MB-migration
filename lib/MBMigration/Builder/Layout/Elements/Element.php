<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\VariableCache;
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
        } else {
            $defaultPalette = $this->cache->get('subpalette', 'parameter');
            $blockBg = $defaultPalette['subpalette1']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        }
        $options = array_merge($options, ['bgColor' => $blockBg]);
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

    protected function defaultOptionsForElement($element, &$options)
    {
       if(!empty($element['options'])){
           $positionOption = [];

           $options = json_decode($element['options'], true);

           if(!empty($options['title'])){
               $positionOption = ['title' => $options['title']];
           }

           if(!empty($options['body'])){
                $positionOption = ['body' => $options['body']];
           }

           $options = array_merge($options, ['defTextPosition' => $positionOption]);
       }
    }

/**
 *
*/
    protected function defaultTextPosition($element, &$options)
    {
        if(!empty($element['item_type']) && !empty($options['defTextPosition'])){

            switch ($element['item_type']){
                case "title":
                case "accordion_title":
                    $mainPosition = $options['defTextPosition']['title'];
                    break;
                case "body":
                case "accordion_body":
                    $mainPosition = $options['defTextPosition']['body'];
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

    protected function showHeader($sectionData, $options = 'text')
    {
        $show_header = true;
        $path = "settings/sections/$options/show_header";
        if($this->checkArrayPath($sectionData, $path)){
            $show_header = $sectionData['settings']['sections'][$options]['show_header'];
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