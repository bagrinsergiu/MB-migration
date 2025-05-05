<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements\Text;

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

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent;
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function handleItemRowComponent(BrizyComponent $brizyComponent): void
    {
        $brizyComponent
            ->addPadding(20,0,20,0)
            ->addMobilePadding(10)
            ->addHeightStyle();
    }

    protected function handleColumItemComponent(BrizyComponent $brizyComponent): void
    {
        $brizyComponent
            ->addMargin(0,5,0,5)
            ->addHeightStyle();
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 50,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 50,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

}
