<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Sermons;

use MBMigration\Builder\Layout\Theme\Ember\Elements\Sermons\AbstractMediaLayout;

class GridMediaLayout extends AbstractMediaLayout
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 75;
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
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

}
