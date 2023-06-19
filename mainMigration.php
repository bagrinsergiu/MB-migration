<?php

use Brizy\Layer\DataSource\DBConnector;

require_once(__DIR__ . '/src/MigrationPlatform.php');
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

$ProjectId_MB    = 247;
$ProjectId_Brizy = 4306588;

if(isset($argv[1]))
{
    $projectId = json_decode($argv[1], true);
    mainLog('[RunMultiProc] ProjectID: ' . $projectId['MB_ID'] . '/' . $projectId['BZ_ID']);
}

$db = new DBConnector();
$StartProcess_Migration = new MigrationPlatform();

try {
    $ProjectId_Brizy = $StartProcess_Migration->initParameter($ProjectId_MB, $ProjectId_Brizy);
    mainLog('[StartMigration] ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy['ID'] . ', Migration uID: ' . $ProjectId_Brizy['UID']);
    $StartProcess_Migration->run();
    mainLog('[Success] Process OK, ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy['ID'] . ', Migration uID: ' . $ProjectId_Brizy['UID']);
} catch (Exception $e) {
    mainLog(' [Error] Process failed, ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy . ', Migration uID: ' . $ProjectId_Brizy['UID']);
}

function mainLog($inMessage): void
{
    $dirToLog = __DIR__ . '/log/logsAnalysis/statistic.log';
    $message = "[" . date('Y-m-d H:i:s') . "] " . $inMessage . "\n";
    file_put_contents($dirToLog, $message, FILE_APPEND);
}