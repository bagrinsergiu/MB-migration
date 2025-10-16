<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Events;

class EventGalleryLayout extends \MBMigration\Builder\Layout\Common\Elements\Events\EventGalleryLayout
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 75;
    }
    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
       return 25;
    }
}
