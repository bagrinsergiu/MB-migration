<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Text;

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
        return 0;
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
