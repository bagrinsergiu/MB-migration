<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements\Gallery;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem)
    {
        return $brizySectionItem;
//        return $brizySectionItem->getItemWithDepth(0,0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }
}
