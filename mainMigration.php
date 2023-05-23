<?php

require_once(__DIR__ . '/src/MigrationPlatform.php');
//75776,
$ProjectId_MB    = 155;
$ProjectId_Brizy = 4305155;

$StartProcess_Migration = new MigrationPlatform($ProjectId_MB, $ProjectId_Brizy);