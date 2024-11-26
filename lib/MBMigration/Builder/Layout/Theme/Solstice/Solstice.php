<?php

namespace MBMigration\Builder\Layout\Theme\Solstice;

use MBMigration\Builder\Layout\Common\AbstractTheme;

class Solstice extends AbstractTheme
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
