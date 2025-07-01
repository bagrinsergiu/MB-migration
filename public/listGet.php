<?php

$contentString1 = json_decode(file_get_contents('migration_results_04-06-2025_13.json'), true);
$contentString2 = json_decode(file_get_contents('migration_results_04-06-2025_15.json'), true);
$contentString3 = json_decode(file_get_contents('migration_results_04-06-2025_16.json'), true);
$contentString4 = json_decode(file_get_contents('migration_results_05-06-2025_15.json'), true);


$contentArray = array_merge($contentString1, $contentString2, $contentString3,  $contentString4);

$result = [];

foreach ($contentArray as $value) {
    if(!empty($value['response'])){

        $migrateResult = json_decode($value['response'], true);
        $migrateResult = $migrateResult['value'];


        $migrateValues = [
            'source_project_ids' =>  $migrateResult['mb_uuid'],
            'brz_project_id'  =>  $migrateResult['brizy_project_id'],
            'changes_json' => json_encode(['data' => date('Y-m-d')]),
        ];

        $result['list'][] = $migrateValues;
    }
}

$jsonResult = json_encode($result);


file_put_contents('./migration_results_mapping.json', $jsonResult);








exit;
