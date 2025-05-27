<?php

namespace MBMigration\Builder\Layout\Theme\Dusk\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GridLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\GridLayoutElement
{
    protected function getItemsPerRow(): int
    {
        return 3;
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        $brizyComponent
            ->getItemWithDepth(0)
            ->addPadding(0,0,10,0);

        return $brizyComponent
            ->getItemWithDepth(0,0);
    }

    protected function getDonationsButtonOptions(): array
    {
        return [
            'mobilePaddingTop' => 10,
            'mobilePaddingRight' => 0,
            'mobilePaddingBottom' => 10,
            'mobilePaddingLeft' => 0,
        ];
    }

    protected function handleItemRowComponent(BrizyComponent $brizyComponent):void
    {
        $brizyComponent
            ->addPadding(20,0,20,0)
            ->addMobilePadding(10);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getTypeItemImageComponent(): string
    {
        return 'image';
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
            "mobilePaddingBottom" => 20,
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

