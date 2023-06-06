<?php

require_once(__DIR__ . '/src/MigrationPlatform.php');
//75776   - http://www.troycsc.org/
//4303928  - https://grapefruit4303928.brizy.org/

//155  - http://lifewf.church/welcome
//4305155 - https://mang4305155.brizy.org/

//175  - https://dovecreekchurch.org/welcome
//4303748 -  https://raspberry4303748.brizy.org/

//256 https://eternalrock.org/
//4305682  https://mang4305682.brizy.org/

$ProjectId_MB    = 75776;
$ProjectId_Brizy = 4303928;

$StartProcess_Migration = new MigrationPlatform($ProjectId_MB, $ProjectId_Brizy);