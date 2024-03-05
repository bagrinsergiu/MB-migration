<?php

namespace MBMigration\Browser;

interface BrowserInterface
{
    public function openPage($url,$theme): BrowserPageInterface;
    public function closePage(): void;
}