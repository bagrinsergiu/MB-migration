<?php

namespace MBMigration\Builder\Layout\Theme\Aurora;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\ThemeElementFactoryInterface;

class ElementFactory extends AbstractThemeElementFactory
{
    static public function instance($blockKit, BrowserPageInterface $browserPage): ThemeElementFactoryInterface
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        return $instance = new self($blockKit, $browserPage);
    }

    public function getElement($name): ElementInterface
    {
        switch ($name) {
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}