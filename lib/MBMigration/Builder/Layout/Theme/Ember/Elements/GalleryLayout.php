<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

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