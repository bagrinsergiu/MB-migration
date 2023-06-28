<?php

use MBMigration\Core\Config;
use MBMigration\MigrationPlatform;

$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
require $composerAutoload;

// 155 - http://lifewf.church/welcome

// 29629 - arborbaptist.com
// 4306588 - https://pineapple4306588.brizy.org/

// 1211 - http://bradbolandentertainment.com
// 4306658 - https://banana4306658.brizy.org/

// 30565 - www.thechurchesofrome.com
// 4306661 -

// 512 - http://shepherdoftheheart.org/
// 4306634 - https://grapefruit4306634.brizy.org/


$ProjectId_MB    = 512;
$ProjectId_Brizy = 4306634;

$db = [
        'dbType' => "postgresql",
        'dbHost' => "localhost",
        'dbPort' => 50000,
        'dbName' => 'api_production',
        'dbUser' => 'brizy_contractor',
        'dbPass' => 'Lg$8AON5^Dk9JLBR2023iUu'
];

$settings = [
    'devMode' => true,
    'debugMode' => true
];

$settingsR = [
    'devMode' => true,
    'debugMode' => true,
    'urlJsonKit'=> 'https://bitblox-develop.s3.amazonaws.com'
];

try {
    $config = new Config(
        'https://beta1.brizy.cloud',
        __DIR__,
        'ZDRmZjIxMzc4M2Y0YmMzZjg5ZmE5YmE4OTUyOTVjMzNkZmFhNmRlZTMwNjliNzIwODhlM2I0MmEwNTlkNGIwMA',
        $db,
        $settings);
} catch (Exception $e) {
    echo "Message: " . $e->getMessage();
}

$MigrationPlatform = new MigrationPlatform($config);

try {
    $MigrationPlatform->start($ProjectId_MB, $ProjectId_Brizy);
    echo $MigrationPlatform->getLogs();
} catch (Exception $e) {
    echo $MigrationPlatform->getLogs();
}