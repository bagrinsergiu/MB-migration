<?php

namespace MBMigration\Builder\Layout\Theme\Bloom;

use DOMDocument;
use Exception;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\AnthemElementsController;
use MBMigration\Builder\Layout\Common\AbstractTheme;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\ThemeContextInterface;
use MBMigration\Builder\Layout\Common\ThemeInterface;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Bloom extends AbstractTheme
{
    public function getThemeIconSelector(): string
    {
        return "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
    }

    public function getThemeButtonSelector(): string
    {
        return ".sites-button:not(.nav-menu-button)";
    }

    public function getThemeMenuItemSelector(): string
    {
        return "#main-navigation>ul>li:not(.selected) a";
    }

    public function getThemeParentMenuItemSelector(): string
    {
        return "#main-navigation>ul>li.has-sub a:first-child";
    }


    public function getThemeSubMenuItemSelector(): string
    {
        return "#main-navigation>ul>li.has-sub ul a:first-child";
    }
}