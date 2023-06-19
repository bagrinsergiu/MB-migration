<?php

use Brizy\Layer\DataSource\DBConnector;

require_once(__DIR__ . '/src/Core/Utils.php');
require_once(__DIR__ . '/src/Core/Config.php');
require_once(__DIR__ . '/src/Layer/DataSource/DBConnector.php');
require_once(__DIR__ . '/src/Layer/DataSource/driver/PostgresSQL.php');



$db = new DBConnector();

//$params = json_encode(['MB_ID'=>,'BZID']);

//runProcesses($params);

$sites = $db->request("select site_id from domains where site_id in(select id from sites where design_uuid = '4fb6dea7-5eb3-4737-af44-7b6f565d6137' ORDER BY random())");

$randSites = selectRandomElements($sites, 5);
foreach($randSites as $site) {
    $params = json_encode(['MB_ID' => $site['site_id'],'BZ_ID' => 0]);
    runProcesses($params);
    echo 'Start '.  $site['site_id'] . "\n";
}
echo 'ok';

function runProcesses($params): void
{
    $phpExecutable = 'start /B D:\SOFT\OSPanel\modules\php\PHP_8.1\php.exe -c D:\SOFT\OSPanel\modules\php\PHP_8.1\php.ini';
    $file = ' D:\SOFT\OSPanel\domains\MB-migration\mainMigration.php';
    $command = $phpExecutable . ' ' . $file . ' ' . json_encode($params);
    $result = exec($command);
}

function selectRandomElements($array, $count) {
    $randomElements = array();

    $arrayCount = count($array);
    if ($arrayCount <= $count) {
        return $array;
    }
    $randomIndexes = array_rand($array, $count);
    foreach ($randomIndexes as $index) {
        $randomElements[] = $array[$index];
    }
    return $randomElements;
}
