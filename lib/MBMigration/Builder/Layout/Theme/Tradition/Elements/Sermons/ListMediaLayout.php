<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Sermons;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Sermons\AbstractMediaLayout;

class ListMediaLayout extends AbstractMediaLayout
{
    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set_fullHeight('auto');
    }
}
