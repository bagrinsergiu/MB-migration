<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GalleryLayout extends \MBMigration\Builder\Layout\Common\Element\GalleryLayout
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem)
    {
//        return $brizySectionItem->getItemWithDepth(0,0);
        return $brizySectionItem;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }
}
