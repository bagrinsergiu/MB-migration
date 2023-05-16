<?php

require_once(__DIR__ . '/src/MigrationPlatform.php');

$ProjectId_MB    = 75776;
$ProjectId_Brizy = 4303928;

$StartProcess_Migration = new MigrationPlatform($ProjectId_MB, $ProjectId_Brizy);