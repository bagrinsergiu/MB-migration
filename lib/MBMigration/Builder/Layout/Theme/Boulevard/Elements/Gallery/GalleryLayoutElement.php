<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Gallery;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{

    protected function getSlideLocatiuon(BrizyComponent $brizySectionItem)
    {
        return $brizySectionItem->getItemWithDepth(0,0,0);
    }

    protected function getSlideImageComponent(BrizyComponent $brizySectionItem)
    {
        return $brizySectionItem;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    protected function getHeightSlideStyl(): string
    {
        return "custom";
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
