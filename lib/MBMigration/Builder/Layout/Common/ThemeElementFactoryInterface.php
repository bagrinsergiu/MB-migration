<?php

namespace MBMigration\Builder\Layout\Common;


use MBMigration\Browser\BrowserPage;

interface ThemeElementFactoryInterface
{
    /**
     * @param $name
     * @return ElementInterface
     */
    public function getElement($name, BrowserPage $browserPage): ElementInterface;
}