<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements\Prayer;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class PrayerForm extends \MBMigration\Builder\Layout\Common\Element\Prayer\PrayerForm
{
    public function getFormComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return parent::getSectionItemComponent($brizySection);
    }
}
