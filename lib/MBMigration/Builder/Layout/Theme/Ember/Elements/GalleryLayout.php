<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GalleryLayout extends \MBMigration\Builder\Layout\Common\Element\GalleryLayout
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem)
    {
        return $brizySectionItem;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }
}