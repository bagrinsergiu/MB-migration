<?php

namespace MBMigration\Builder\Layout\Theme\Ember;

use DOMDocument;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\AbstractTheme;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Ember extends AbstractTheme
{
    public function getThemeIconSelector(): string
    {
        return "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
    }

    public function getThemeButtonSelector(): string
    {
        return ".sites-button:not(.nav-menu-button)";
    }


}