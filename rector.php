<?php
require_once './vendor/autoload.php';

use Rector\Config\RectorConfig;

return RectorConfig::configure()
     ->withPaths([
        __DIR__ . '/lib/MBMigration/',
    ])
    // register single rule
    ->withRules([
        //TypedPropertyFromStrictConstructorRector::class
        \Utils\Rector\Rector\UtilsLogsRector::class,
        \Utils\Rector\Rector\UtilsMessagePoolRector::class
    ])
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
         false,
         false
    );