<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Events;

class EventGalleryLayout extends \MBMigration\Builder\Layout\Common\Element\Events\EventGalleryLayout
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
       return 25;
    }
}