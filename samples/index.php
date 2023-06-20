<?php

use MBMigration\MigrationPlatform;

require_once(__DIR__ . '/lib/MigrationPlatform.php');

$ProjectId_MB    = (int)$argv[1];
$ProjectId_Brizy = (int)$argv[2];

$StartProcess_Migration = new MigrationPlatform();

try {
    $ProjectId_Brizy = $StartProcess_Migration->initParameter($ProjectId_MB, $ProjectId_Brizy);
    mainLog('[StartMigration] ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy['ID'] . ', Migration uID: ' . $ProjectId_Brizy['UID']);
    $StartProcess_Migration->run();
    mainLog('[Success] Process OK, ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy['ID'] . ', Migration uID: ' . $ProjectId_Brizy['UID']);
} catch (Exception $e) {
    mainLog(' [Error] Process failed, ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy . ', Migration uID: ' . $ProjectId_Brizy['UID']);
}