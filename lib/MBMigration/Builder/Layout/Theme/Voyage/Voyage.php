<?php

namespace MBMigration\Builder\Layout\Theme\Voyage;

use Exception;
use MBMigration\Browser\Browser;
use MBMigration\Browser\BrowserInterface;
use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\Layout\Common\AbstractTheme;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\ThemeContext;
use MBMigration\Builder\Layout\Common\ThemeContextInterface;
use MBMigration\Builder\Layout\Common\ThemeElementFactoryInterface;
use MBMigration\Builder\Layout\Common\ThemeInterface;
use MBMigration\Builder\Layout\ElementsController;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Voyage extends AbstractTheme
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