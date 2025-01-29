<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Groups;

class SmallGroupsListElement extends \MBMigration\Builder\Layout\Common\Elements\Groups\SmallGroupsListElement
{
    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
