<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventListLayout extends \MBMigration\Builder\Layout\Common\Elements\Events\EventListLayout
{
    protected function getDetailsComponent(BrizyComponent $brizySection)
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 95;
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
