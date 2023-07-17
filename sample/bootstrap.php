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


//256 https://eternalrock.org/
//4305682  https://mang4305682.brizy.org/

// 49345 - www.ovidchurch.com
// 4303852 - https://pear4303852.brizy.org/

// 29629  - https://arborbaptist.com
// 4306600 - https://pomegranate4306600.brizy.org/

// 247
// 4306588

// 1211 - http://bradbolandentertainment.com/
// 4306658 - https://banana4306658.brizy.org/

// 30565 - https://thechurchesofrome.com
// 4306661 - https://quince4306661.brizy.org

// https://beta1.brizydemo.com/
// https://beta1.brizy.cloud



// 2775 - arlingtonfirst.org
// 4308345

//===
// 2715 - fbctkids.com
// 4316396 - https://passionfruit4316396.brizy.org/

// 12531 - oilbelt.com
// 4316411 - https://apple4316411.brizy.org/

// 8003 - https://mvcoakdale.com/
// 4316438 - https://grapefruit4316438.brizy.org/

// 77436 - http://firstchristianchurchwc.cloversites.com
//4316910

//10286 - nfbaptistchurch.com
//4316931

$ProjectId_MB    = 30565;
$ProjectId_Brizy = 4317990;

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
    //'urlJsonKit' => 'https://bitblox-develop.s3.amazonaws.com'
];

try {
    $config = new Config(
        'https://beta1.brizydemo.com',
        __DIR__,
        'ZDRmZjIxMzc4M2Y0YmMzZjg5ZmE5YmE4OTUyOTVjMzNkZmFhNmRlZTMwNjliNzIwODhlM2I0MmEwNTlkNGIwMA',
        $db,
        $settings);
} catch (Exception $e) {
    echo "Message: " . $e->getMessage();
}

$MigrationPlatform = new MigrationPlatform($config);

$status = $MigrationPlatform->start($ProjectId_MB, $ProjectId_Brizy);
if($status) {
    echo $MigrationPlatform->getLogs();
} else {
    echo 'Error: '. $MigrationPlatform->getLogs();
}