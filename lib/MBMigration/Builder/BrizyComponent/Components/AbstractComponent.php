<?php

namespace MBMigration\Builder\BrizyComponent\Components;

use MBMigration\Browser\BrowserPageInterface;

abstract class AbstractComponent
{
    public function __construct($brizyKit)
    {
        $this->brizyKit = $brizyKit;
    }

}
