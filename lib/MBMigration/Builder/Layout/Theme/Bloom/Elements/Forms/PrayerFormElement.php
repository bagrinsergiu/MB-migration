<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements\Forms;

class PrayerFormElement extends \MBMigration\Builder\Layout\Common\Elements\Prayer\PrayerFormElement
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 250;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
