<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class TabsLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\TabsLayoutElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTopTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getTabTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0)
            ->addMobileMargin([0,5,0,5]);
    }

    protected function afterTransformTabs(BrizyComponent $brizySection): void
    {
        $brizySection->getItemWithDepth(0)
            ->addMobilePadding([0,0,0,0])
            ->addMobileMargin([0,0,0,0])
            ->addMargin(
                0,
                -20,
                0,
                -20);
    }

    protected function afterTransformToTabsItem(BrizyComponent $brizyTabSection)
    {
        $brizyTabSection->addMobileMargin([0, 10, 0, 10]);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed
     */
    protected function getTabContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
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
