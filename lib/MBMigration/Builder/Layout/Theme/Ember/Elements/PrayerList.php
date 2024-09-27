<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

class PrayerList extends \MBMigration\Builder\Layout\Common\Element\PrayerList
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
