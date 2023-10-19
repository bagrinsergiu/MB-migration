<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;

interface BrowserPageInterface
{
    public function runScript($jsScript, $params): array;
}