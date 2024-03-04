<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class PrayerForm extends \MBMigration\Builder\Layout\Common\Element\PrayerForm
{
    public function getFormComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return parent::getSectionItemComponent($brizySection);
    }
}