<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventGridLayout extends \MBMigration\Builder\Layout\Common\Element\Events\EventGridLayout
{
    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }
}
