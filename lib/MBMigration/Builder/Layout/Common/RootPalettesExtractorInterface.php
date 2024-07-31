<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

interface RootPalettesExtractorInterface
{
    public function ExtractRootPalettes(): RootPalettes;
}
