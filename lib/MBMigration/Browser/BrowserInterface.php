<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;

interface BrowserInterface
{
    public function openPage($url,$theme): BrowserPageInterface;
    public function closePage(): void;
}