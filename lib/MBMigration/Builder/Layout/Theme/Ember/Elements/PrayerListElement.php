<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

class PrayerListElement extends \MBMigration\Builder\Layout\Common\Elements\Prayer\PrayerListElement
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
