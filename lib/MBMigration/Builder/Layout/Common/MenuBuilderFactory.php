<?php

namespace MBMigration\Builder\Layout\Common;

class MenuBuilderFactory
{
    static public function instanceOfThemeMenuBuilder($theme, $brizyProject, $brizyApi, $fonts)
    {
        switch ($theme) {
            case 'Anthem':
                return new \MBMigration\Builder\Layout\Theme\Anthem\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'August':
                return new \MBMigration\Builder\Layout\Theme\August\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Aurora':
                return new \MBMigration\Builder\Layout\Theme\Aurora\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Bloom':
                return new \MBMigration\Builder\Layout\Theme\Bloom\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Boulevard':
                return new \MBMigration\Builder\Layout\Theme\Boulevard\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Dusk':
                return new \MBMigration\Builder\Layout\Theme\Dusk\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Ember':
                return new \MBMigration\Builder\Layout\Theme\Ember\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Hope':
                return new \MBMigration\Builder\Layout\Theme\Hope\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Majesty':
                return new \MBMigration\Builder\Layout\Theme\Majesty\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Serene':
                return new \MBMigration\Builder\Layout\Theme\Serene\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Solstice':
                return new \MBMigration\Builder\Layout\Theme\Solstice\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Tradition':
                return new \MBMigration\Builder\Layout\Theme\Tradition\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Voyage':
                return new \MBMigration\Builder\Layout\Theme\Voyage\MenuBuilder($brizyProject, $brizyApi, $fonts);
            case 'Zion':
                return new \MBMigration\Builder\Layout\Theme\Zion\MenuBuilder($brizyProject, $brizyApi, $fonts);
            default:
                return new MenuBuilder($brizyProject, $brizyApi, $fonts);
        }
    }
}
