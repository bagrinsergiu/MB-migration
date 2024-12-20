<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyPage;

interface ThemeInterface
{
    public function transformBlocks(array $mbPageSections): BrizyPage;

    public function beforeTransformBlocks(BrizyPage $page, array $mbPageSections): BrizyPage;

    public function afterTransformBlocks(BrizyPage $page, array $mbPageSections): BrizyPage;

    public function getThemeIconSelector(): string;
    public function getThemeButtonSelector(): string;
}
