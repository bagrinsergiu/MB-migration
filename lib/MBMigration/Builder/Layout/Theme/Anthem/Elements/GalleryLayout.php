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
        Utils::log('Create bloc', 1, "gallery_layout");
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded['main'], true);
        $slide  = json_decode($decoded['item'], true);

        if($sectionData['settings']['sections']['gallery']['transition'] !== 'Slide') {
            $block['value']['sliderTransition'] = 'off';
        } else {
            $block['value']['sliderAutoPlay'] = 'on';
        }
        $block['value']['bgSize'] = 'contain';

        foreach ($sectionData['items'] as $item){
                if(!$item['uploadStatus']) {
                    continue;
                }

                if(!empty($sectionData['settings']['sections']['gallery']['max_width']) &&
                    !empty($sectionData['settings']['sections']['gallery']['max_height'])){
                    $slide['value']['bgImageHeight']    = $sectionData['settings']['sections']['gallery']['max_height'];
                    $slide['value']['bgImageWidth']     = $sectionData['settings']['sections']['gallery']['max_width'];
                }

                $slide['value']['bgImageFileName'] = $item['imageFileName'];
                $slide['value']['bgImageSrc']      = $item['content'];

                $this->insertElementAtPosition($block, 'value/items', $slide);
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

}