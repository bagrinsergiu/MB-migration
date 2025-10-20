<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Events;

class EventGalleryLayout extends \MBMigration\Builder\Layout\Common\Elements\Events\EventGalleryLayout
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 75;
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
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
