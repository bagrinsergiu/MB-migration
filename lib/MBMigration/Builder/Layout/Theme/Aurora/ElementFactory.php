<?php

namespace MBMigration\Builder\Layout\Theme\Aurora;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\ThemeElementFactoryInterface;
use MBMigration\Layer\Brizy\BrizyAPI;

class ElementFactory extends AbstractThemeElementFactory
{
    static public function instance($blockKit, BrizyAPI $brizyAPI): ThemeElementFactoryInterface
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        return $instance = new self($blockKit,  $brizyAPI);
    }

    public function getElement($name, BrowserPage $browserPage): ElementInterface
    {
        switch ($name) {
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}