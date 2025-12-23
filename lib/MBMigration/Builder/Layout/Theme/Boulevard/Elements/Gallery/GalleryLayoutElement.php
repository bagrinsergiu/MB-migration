<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Gallery;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{

    protected function getSlideLocation(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0,0);
    }

    protected function getSlideImageComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function customizationSlide(BrizyComponent $brizySectionItem): BrizyComponent
    {
        $brizySectionItem->getValue()
            ->set_sliderPaddingType('ungrouped')
            ->set_sliderPadding(0)
            ->set_sliderPaddingSuffix("px")
            ->set_sliderPaddingTop(10)
            ->set_sliderPaddingTopSuffix("px")
            ->set_sliderPaddingRight(70)
            ->set_sliderPaddingRightSuffix("px")
            ->set_sliderPaddingBottom(30)
            ->set_sliderPaddingBottomSuffix("px")
            ->set_sliderPaddingLeft(15)
            ->set_sliderPaddingLeftSuffix("px");

        return $brizySectionItem;
    }

    protected function customizationSection(BrizyComponent $brizySectionItem):BrizyComponent
    {
        $brizySectionItem->getValue()
            ->set_paddingType('ungrouped')
            ->set_paddingTop(0)
            ->set_paddingBottom(0);

        return $brizySectionItem;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    protected function getHeightSlideStyl(): string
    {
        return "custom";
    }

    protected function getMobileBgSizeType(): string
    {
        return "custom";
    }
    function getMobileBgSize(): string
    {
        return "contain";
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
