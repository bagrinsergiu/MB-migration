<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

interface ThemeInterface
{
    public function transformBlocks(array $mbPageSections): BrizyComponent;

}