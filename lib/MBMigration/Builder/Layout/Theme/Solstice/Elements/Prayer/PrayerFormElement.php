<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements\Prayer;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class PrayerFormElement extends \MBMigration\Builder\Layout\Common\Elements\Prayer\PrayerFormElement
{
    public function getFormComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return parent::getSectionItemComponent($brizySection);
    }
}
