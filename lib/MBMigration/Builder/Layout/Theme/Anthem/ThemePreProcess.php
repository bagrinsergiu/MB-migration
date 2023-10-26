<?php

namespace MBMigration\Builder\Layout\Theme\Anthem;

use MBMigration\Builder\Layout\Theme\Anthem\Preprocess\NavMenuBuilder;
use MBMigration\Builder\VariableCache;

class ThemePreProcess
{

    public static function treeMenu()
    {
        $menu = new NavMenuBuilder();
        $menu->createMenuStructure();
    }

}