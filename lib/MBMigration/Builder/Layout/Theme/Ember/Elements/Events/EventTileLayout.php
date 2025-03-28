<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventTileLayout extends \MBMigration\Builder\Layout\Common\Elements\Events\EventTileLayout
{
    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0);
    }

    protected function getMobileTopMarginOfTheFirstElement(): int
    {
        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();

        return (int) $dtoPageStyle['headerHeight'];
    }

}
