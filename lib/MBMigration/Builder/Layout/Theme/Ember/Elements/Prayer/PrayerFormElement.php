<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Prayer;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class PrayerFormElement extends \MBMigration\Builder\Layout\Common\Elements\Prayer\PrayerFormElement
{
    public function getFormComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return parent::getSectionItemComponent($brizySection);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
