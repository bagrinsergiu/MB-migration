<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class GalleryLayout extends Element
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
     * @throws \Exception
     */
    public function getElement($elementData)
    {
        return $this->gallery_layout($elementData);
    }

    protected function gallery_layout(array $sectionData)
    {
        $bodyBgColor = '#ffffff';
        $rotatorSpeed = 5;


        Utils::log('Create bloc', 1, "gallery_layout");
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded['main'], true);
        $slide  = json_decode($decoded['item'], true);

        if(isset($sectionData['settings']['sections']['gallery']['transition']) && $sectionData['settings']['sections']['gallery']['transition'] !== 'Slide') {
            $block['value']['sliderTransition'] = 'off';
        } else {
            $block['value']['sliderAutoPlay'] = 'on';
            $block['value']['sliderAutoPlaySpeed'] = $rotatorSpeed;
        }

//        $block['value']['mobileSectionHeight'] = 20;
//        $block['value']['sectionHeight'] = 85;
//        $block['value']['sectionHeightSuffix'] = 'vh';
//        $block['value']['fullHeight'] = 'custom';
//        $block['value']['mobileSectionHeightSuffix'] = 'vh';
//        $block['value']['mobileFullHeight'] = 'custom';

        if(!empty($sectionData['style']['body']['background-color'])) {
            $bodyBgColor = $sectionData['style']['body']['background-color'];
        }

        $colorArrows = $this->getContrastColor($bodyBgColor);

        $block['value']['sliderArrowsColorHex'] = $colorArrows;
        $block['value']['sliderArrowsColorOpacity'] = 0.75;
        $block['value']['sliderArrowsColorPalette'] = '';

        $block['value']['hoverSliderArrowsColorHex'] = $colorArrows;
        $block['value']['hoverSliderArrowsColorOpacity'] = 1;
        $block['value']['hoverSliderArrowsColorPalette'] = '';

        $block['value']['sliderDotsColorHex'] = $colorArrows;
        $block['value']['sliderDotsColorOpacity'] = 0.75;
        $block['value']['sliderDotsColorPalette'] = '';

        $block['value']['hoverSliderDotsColorHex'] = $colorArrows;
        $block['value']['hoverSliderDotsColorOpacity'] = 1;
        $block['value']['hoverSliderDotsColorPalette'] = '';

        if (isset($sectionData['settings']['sections']['background']['video'])){

            $slide['value']['media'] = "video";
            $slide['value']['bgVideoType'] = "url";
            $slide['value']['bgVideoCustom'] = "";
            $slide['value']['bgVideo'] = $sectionData['settings']['sections']['background']['video'];
            $slide['value']['bgVideoLoop'] = "on";
            $slide['value']['linkType'] = "external";

            $this->insertElementAtPosition($block, 'value/items', $slide);
        } else {

            foreach ($sectionData['items'] as $item){
                if(!$item['uploadStatus']) {
                    continue;
                }

                if(!empty($sectionData['settings']['sections']['gallery']['max_width']) &&
                    !empty($sectionData['settings']['sections']['gallery']['max_height'])){
                    $slide['value']['bgImageWidth']  = $sectionData['settings']['sections']['gallery']['max_width'];
                    $slide['value']['bgImageHeight'] = $sectionData['settings']['sections']['gallery']['max_height'];
                }

//                $slide['value']['bgSize']          = 'contain';
                $slide['value']['bgImageSrc']      = $item['content'];
                $slide['value']['bgImageFileName'] = $item['imageFileName'];
                $slide['value']['customCSS'] = 'element{background:' . $bodyBgColor . '}';

                $this->insertElementAtPosition($block, 'value/items', $slide);
            }

        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function getContrastColor($hexColor): string
    {
        $hexColor = str_replace('#', '', $hexColor);

        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return $brightness > 125 ? '#000000' : '#FFFFFF';
    }
}