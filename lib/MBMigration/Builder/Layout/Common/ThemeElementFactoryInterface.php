<?php

namespace MBMigration\Builder\Layout\Common;


use MBMigration\Browser\BrowserPagePHP;

interface ThemeElementFactoryInterface
{
    /**
     * @param $name
     * @return ElementInterface
     */
    public function getElement($name, BrowserPagePHP $browserPage): ElementInterface;
}
