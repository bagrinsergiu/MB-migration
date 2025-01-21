<?php

namespace MBMigration\Browser;

interface BrowserPageInterface
{
    public function evaluateScript($jsScript, $params): array;

    public function triggerEvent($eventNameMethod, $elementSelector, $params=[]): bool;

    public function setNodeAttribute($selector, array $attributes);

    public function setNodeStyles($selector, array $attributes);

    public function getPageScreen($prefix = ''): void;
}
