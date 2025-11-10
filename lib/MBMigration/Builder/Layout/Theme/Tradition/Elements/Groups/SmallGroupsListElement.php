<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Groups;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class SmallGroupsListElement extends \MBMigration\Builder\Layout\Common\Elements\Groups\SmallGroupsListElement
{
    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set_fullHeight('auto');
    }
}
