<?php

namespace MBMigration\Builder\Layout\Common;


interface ThemeElementFactoryInterface
{
    /**
     * @param $name
     * @return ElementInterface
     */
    public function getElement($name): ElementInterface;
}