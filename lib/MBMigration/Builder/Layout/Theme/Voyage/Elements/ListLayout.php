<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class ListLayout extends \MBMigration\Builder\Layout\Common\Element\ListLayout
{
    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent,string $photoPosition): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 1 : 0);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent, string $photoPosition): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0, 0);
    }

    protected function afterTransformItem(ElementContextInterface $data, BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 50,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 50,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
