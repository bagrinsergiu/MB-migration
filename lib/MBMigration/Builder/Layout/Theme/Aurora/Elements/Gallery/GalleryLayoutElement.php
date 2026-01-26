<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Gallery;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0,0);
        //return $brizySectionItem;
    }

    protected function getSlideVideoComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType"=> "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function sectionIndentations(BrizyComponent $section)
    {
    }

}
