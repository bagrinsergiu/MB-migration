<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventListLayout extends \MBMigration\Builder\Layout\Common\Element\EventListLayout
{
    protected function getDetailsComponent(BrizyComponent $brizySection)
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }
}