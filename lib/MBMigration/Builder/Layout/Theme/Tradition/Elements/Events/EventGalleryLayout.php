<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class EventGalleryLayout extends \MBMigration\Builder\Layout\Common\Elements\Events\EventGalleryLayout
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set_fullHeight('auto');
    }
}
