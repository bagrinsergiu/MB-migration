<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Prayer;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class PrayerFormElement extends \MBMigration\Builder\Layout\Common\Elements\Prayer\PrayerFormElement
{
    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set_fullHeight('auto');
    }
}
