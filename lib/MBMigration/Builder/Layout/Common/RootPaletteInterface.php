<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

interface RootPaletteInterface
{
    public function getSubPaletteByName($name): array;
}
