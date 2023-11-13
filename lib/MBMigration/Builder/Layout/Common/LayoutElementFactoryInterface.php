<?php

namespace MBMigration\Builder\Layout\Common;

interface LayoutElementFactoryInterface
{
    /**
     * @param $design
     * @param $mbElementData
     * @return ElementInterface
     *
     * @throw ElementNotFound
     */
    public function getFactory($design): ThemeElementFactoryInterface;
}