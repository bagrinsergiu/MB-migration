<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Sermons;

use MBMigration\Builder\Layout\Theme\Ember\Elements\Sermons\AbstractMediaLayout;

class GridMediaLayout extends AbstractMediaLayout
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 75;
    }

}
