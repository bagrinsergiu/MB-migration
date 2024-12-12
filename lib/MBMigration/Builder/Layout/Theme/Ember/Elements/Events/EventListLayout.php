<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventListLayout extends \MBMigration\Builder\Layout\Common\Elements\Events\EventListLayout
{
    protected function getDetailsComponent(BrizyComponent $brizySection)
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 95;
    }
}
