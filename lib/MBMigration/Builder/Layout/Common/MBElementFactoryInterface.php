<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

interface MBElementFactoryInterface
{
    static public function instance($blockKit, BrowserPageInterface $browserPage): MBElementFactoryInterface;

    /**
     * @param $name
     * @param $mbElementData
     * @return ElementInterface
     *
     * @throw ElementNotFound
     */
    public function getElement($name): ElementInterface;
}