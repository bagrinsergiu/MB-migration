<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;

interface ThemeInterface
{
    public function transformBlocks(array $mbPageSections): BrizyPage;

}