<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use Exception;
use MBMigration\Core\Logger;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Element;
use MBMigration\Builder\VariableCache;

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
     * @throws Exception
     */
    public function getElement($elementData)
    {
        return $this->gallery_layout($elementData);
    }

    protected function gallery_layout(array $sectionData)
    {
        $bodyBgColor = '#ffffff';
        $rotatorSpeed = 5;


        Logger::instance()->info('Create bloc');
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
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }
}