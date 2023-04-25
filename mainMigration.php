<?php

require_once(__DIR__ . '/src/MigrationPlatform.php');

$ProjectId_MB    = 299;
$ProjectId_Bryzi = 4303928;

$StartProcess_Migration = new MigrationPlatform($ProjectId_MB, $ProjectId_Bryzi);