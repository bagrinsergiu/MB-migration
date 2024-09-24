<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventTileLayout extends \MBMigration\Builder\Layout\Common\Element\Events\EventTileLayout
{
    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0);
    }
}
