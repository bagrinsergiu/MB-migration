<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventTileLayout extends \MBMigration\Builder\Layout\Common\Elements\Events\EventTileLayout
{
    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
