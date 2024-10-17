<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventListLayout extends \MBMigration\Builder\Layout\Common\Element\Events\EventListLayout
{
    protected function getDetailsComponent(BrizyComponent $brizySection)
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }
}
