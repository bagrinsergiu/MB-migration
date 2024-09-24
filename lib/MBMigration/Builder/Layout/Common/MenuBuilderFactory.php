<?php

namespace MBMigration\Builder\Layout\Common;

class MenuBuilderFactory
{
    static public function instanceOfThemeMenuBuilder($theme, $brizyProject, $brizyApi, $fonts)
    {
        switch ($theme) {
            case 'Anthem':
                return new \MBMigration\Builder\Layout\Theme\Anthem\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Bloom':
                return new \MBMigration\Builder\Layout\Theme\Bloom\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Voyage':
                return new \MBMigration\Builder\Layout\Theme\Voyage\MenuBuilder($brizyProject, $brizyApi, $fonts);
            default:
                return new MenuBuilder($brizyProject, $brizyApi, $fonts);
        }
    }
}
