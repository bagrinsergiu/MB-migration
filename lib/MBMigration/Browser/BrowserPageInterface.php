<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;

interface BrowserPageInterface
{
    public function evaluateScript($jsScript, $params): array;
    public function triggerEvent($eventNameMethod, $elementSelector): bool;

    public function setNodeAttribute($selector, array $attributes);

    public function setNodeStyles($selector, array $attributes);
}