<?php

use MBMigration\Core\Config;
use MBMigration\MigrationPlatform;

$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
require $composerAutoload;

//247
//4306588

// 26321
//

//17096 - antioch-cc.org
//4307095

//75776   - http://www.troycsc.org/
//4303928  - https://grapefruit4303928.brizy.org/

//155  - http://lifewf.church/welcome
//4305155 - https://mang4305155.brizy.org/

//175  - https://dovecreekchurch.org/welcome
//4303930 -  https://kiwi4303930.brizy.org/find-us

//256 https://eternalrock.org/
//4305682  https://mang4305682.brizy.org/

// 49345 - www.ovidchurch.com
// 4303852 - https://pear4303852.brizy.org/

// 29629  - https://arborbaptist.com
// 4306600 - https://pomegranate4306600.brizy.org/

// 247
// 4306588

$ProjectId_MB    = 155;
$ProjectId_Brizy = 4306588;

$db = [
    'dbType' => "postgresql",
    'dbHost' => "localhost",
    'dbPort' => 50000,
    'dbName' => 'api_production',
    'dbUser' => 'brizy_contractor',
    'dbPass' => 'Lg$8AON5^Dk9JLBR2023iUu'
];

$settings = [
    'urlJsonKit' => 'https://bitblox-develop.s3.amazonaws.com'
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
} catch (Exception $e) {
    echo "Message: " . $e->getMessage();
}