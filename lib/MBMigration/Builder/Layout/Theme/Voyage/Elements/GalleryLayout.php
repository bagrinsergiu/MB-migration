<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

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

        foreach ($sectionData['items'] as $item){
                if(!$item['uploadStatus']) {
                    continue;
                }

                $slide['value']['bgImageFileName'] = $item['imageFileName'];
                $slide['value']['bgImageSrc']      = $item['content'];

                $this->insertElementAtPosition($block, 'value/items', $slide);
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

}