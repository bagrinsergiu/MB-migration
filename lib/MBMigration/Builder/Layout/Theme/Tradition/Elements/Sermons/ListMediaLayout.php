<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Sermons;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Sermons\AbstractMediaLayout;

class ListMediaLayout extends AbstractMediaLayout
{
    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set_fullHeight('auto');
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "paddingTop" => 90,
            "paddingBottom" => 90,
            "mobilePaddingTop" => 60,
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
