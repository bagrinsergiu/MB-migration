<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Groups;

class SmallGroupsListElement extends \MBMigration\Builder\Layout\Common\Elements\Groups\SmallGroupsListElement
{
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
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 20,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

}
