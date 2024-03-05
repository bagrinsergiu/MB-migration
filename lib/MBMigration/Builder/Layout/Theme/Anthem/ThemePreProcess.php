<?php

namespace MBMigration\Builder\Layout\Theme\Anthem;

use MBMigration\Builder\Layout\Theme\Anthem\Preprocess\NavMenuBuilder;

class ThemePreProcess
{

    public static function treeMenu()
    {
        $menu = new NavMenuBuilder();
        $menu->createMenuStructure();
    }

}