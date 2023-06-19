<?php
require_once(__DIR__ . '/src/MigrationPlatform.php');

$ProjectId_MB    = 29629;
$ProjectId_Brizy = 4306600;

$StartProcess_Migration = new MigrationPlatform();

try {
    $ProjectId_Brizy = $StartProcess_Migration->initParameter($ProjectId_MB, $ProjectId_Brizy);
    mainLog('[StartMigration] ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy['ID'] . ', Migration uID: ' . $ProjectId_Brizy['UID']);
    $StartProcess_Migration->run();
    mainLog('[Success] Process OK, ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy['ID'] . ', Migration uID: ' . $ProjectId_Brizy['UID']);
} catch (Exception $e) {
    mainLog(' [Error] Process failed, ProjectID: ' . $ProjectId_MB . '/' . $ProjectId_Brizy . ', Migration uID: ' . $ProjectId_Brizy['UID']);
}