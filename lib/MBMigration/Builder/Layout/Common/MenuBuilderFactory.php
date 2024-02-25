<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\Layout\Theme\Voyage\MenuBuilder;

class MenuBuilderFactory
{
    static public function instanceOfThemeMenuBuilder($theme, $brizyProject, $brizyApi, $fonts)
    {
        switch ($theme) {
            case 'Voyage':
                return new MenuBuilder($brizyProject, $brizyApi, $fonts);
            default:
                return new \MBMigration\Builder\Layout\Common\MenuBuilder($brizyProject, $brizyApi, $fonts);
        }
    }
}