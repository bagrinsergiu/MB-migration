<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements\Prayer;

class PrayerList extends \MBMigration\Builder\Layout\Common\Element\Prayer\PrayerList
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
