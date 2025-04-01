<?php

class MigrationRunner
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl;
    private string $logFile;
    const ID_WORKSPACE = 22353065;

    public function __construct(array $projectUuids, int $batchSize = 3)
    {
        $this->projectUuids = $projectUuids;
        $this->batchSize = $batchSize;
        $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost:8080/';
        $this->logFile = 'migration_results_'. date("d-m-Y_H") .'.json';
    }

    public function runMigrations(): void
    {
        $pending = $this->projectUuids;
        $activeHandles = [];
        $multiHandle = curl_multi_init();
        $results = $this->loadExistingResults();

        try {
            while (!empty($pending) || !empty($activeHandles)) {
                while (count($activeHandles) < $this->batchSize && !empty($pending)) {
                    $uuid = array_shift($pending);
                    $url = $this->buildUrl($uuid);
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_multi_add_handle($multiHandle, $ch);
                    $activeHandles[$uuid] = $ch;
                }

                do {
                    $status = curl_multi_exec($multiHandle, $active);
                    if ($status > CURLM_OK && $status !== CURLM_CALL_MULTI_PERFORM) {
                        error_log("cURL multi error: " . curl_multi_strerror($status));
                        break;
                    }
                } while ($status === CURLM_CALL_MULTI_PERFORM);

                foreach ($activeHandles as $uuid => $ch) {
                    $info = curl_getinfo($ch);
                    if ($info['http_code'] !== 0) {
                        $response = curl_multi_getcontent($ch);
                        $decode_response = json_decode($response, true);

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            error_log("JSON decode error: " . json_last_error_msg());
                            continue;
                        }

                        $results[$uuid] = [
                            'status' => $info['http_code'] === 200 ? 'success' : 'error',
                            'brizy_project_domain' => $decode_response['brizy_project_domain'],
                            'brizy_project_id' => $decode_response['brizy_project_id'],
                            'mb_project_domain' => $decode_response['mb_project_domain'],
                            'mb_site_id' => $decode_response['mb_site_id'],
                            'response' => $response
                        ];

                        curl_multi_remove_handle($multiHandle, $ch);
                        curl_close($ch);
                        unset($activeHandles[$uuid]);
                        $this->saveResults($results);
                    }
                }
            }
        } finally {
            curl_multi_close($multiHandle);
        }
    }

    private function buildUrl(string $uuid): string
    {
        $params = http_build_query([
            'mb_project_uuid' => $uuid,
            'brz_project_id' => 0,
            'brz_workspaces_id' => self::ID_WORKSPACE,
            'mb_site_id' => '31383',
            'mb_secret' => 'b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q',
        ]);

        return "{$this->baseUrl}?{$params}";
    }

    private function loadExistingResults(): array
    {
        if (file_exists($this->logFile)) {
            $data = file_get_contents($this->logFile);
            return json_decode($data, true) ?? [];
        }
        return [];
    }

    private function saveResults(array $results): void
    {
        $existingResults = $this->loadExistingResults();
        $mergedResults = array_merge($existingResults, $results);

        file_put_contents($this->logFile, json_encode($mergedResults, JSON_PRETTY_PRINT));
    }
}


$projectUuids = [
    '5fcebbc1-5fa4-4e44-abaa-28a698fdf84b',
    '551677bd-127d-41cf-83d9-611bb9d82eb2',
    '92f39480-0a14-4290-b611-1d5c59d5ce3d',
    'cbdc74db-696f-49b8-8546-b2fb24d41c73',
    'a95c5ac8-d666-4a9b-b1f0-670968b0ba94',
    '422c5ca8-58da-4450-80fb-2cd8675b51a9',
    '3d42eacb-44c6-40ec-b70a-a3a46ee7477f',
    'f9619ecb-c240-4b9b-8630-3975b8895322',
    '5b1e5881-8db2-4ff4-83b8-ce09ccbe4035',
    'f1ae2630-54d9-436c-8199-6a2ba9685f05',
    '0947581a-9ded-4645-9875-94dd2e697494',
    '632bca28-059f-45db-8e22-466d4ebf0564',
    '72e55e7c-a66f-42ec-8c00-e87bba09f585',
    '8ee2d888-5119-4e5a-b148-51a4a8d2fa57',
    '8a52a136-1bd6-41d3-b435-cfd29cafe407',
    'a4b51d10-ec4e-47fd-bbaf-2e231fb690c3',
    'e44bd7fa-4a24-4966-ba5c-a2e62fd1001c',
    '4b71855a-a589-403f-b5a5-e37d3496dabc',
    'da1988d3-e76c-4463-8a2d-106ab4d42784',
    '235d2a55-88d1-43fa-8551-dada3444ed1a',
    'c809fb1f-acdd-44a1-96bc-6013f94263d0',
    'd5bd3c52-676f-4dac-840a-1f9d3354deda',
    'a1d5e871-7809-4c9a-a9f7-1bd32541f087',
    '49e03255-7777-4a37-9fba-d456a9ca1f4d',
    '2a2185b4-9d72-4b01-b66f-e5a114bdf4ca',
    'a75423a6-0917-439e-981a-917c82979b14',
    'f95d6312-25f8-454b-b75a-35ed8e65ca09',
    '1a7db709-bed0-403b-818b-d7d1284643e9',
    '8b12081b-76a3-4fe9-9631-51d8d98e9a8f',
    '5f4000d2-a64d-4050-a431-25282a901ac5',
    '2149db53-9987-4754-afa1-de5777096cf1',
    '1d1fbe57-7b87-409f-870b-d86d1d015e57',
    '2072bb50-0e6f-4169-a311-3c3d04b28813',
    '4aced3eb-eadc-42b6-a7d8-66e8b6baf8b4',
    '9d818fef-e159-423c-aba8-0cadc0ba6e98',
    'a79ac9ed-bbea-4627-8abb-8a167bf98d84',
    'd99fd7c2-33f5-49f9-9451-ae168dbc29b0',
    'eeec1499-3d93-450a-a704-9f4c5448e50a',
    '1932366c-2983-474d-8089-3e8cd5a12b51',
    '34c5dd05-d78a-4005-b9aa-dbf9b6a74f4f',
    'fd9bd167-ccc1-43d1-a848-287c06fae564',
    '43036e40-c9ed-4953-a842-8d1242837af5',
    '3269213e-e755-4e80-91ea-a32064c7d678',
    '8eec2a02-820f-4821-9c4a-84d1eaf58401',
    '5e670367-50b5-4301-aff1-2652b064d0cc',
    'aa1e20cc-8f45-4042-bca2-cb5b0ff0c25b',
    '9b105e07-b11c-4521-ac40-7eddd4ece28a',
    'a654c304-26cc-4d5d-903f-5dd5a5b85b0c',
    '962f53c7-81c2-43e7-9322-eaaf2747a53d',
    '08306d8c-3b5d-4c91-afbd-01450ee75242',
    '26d27a7c-e81b-461c-a49b-5cfe78c478ab',
    '45d7774b-aedc-447e-aa3e-a3a79b655300',
    '2340d599-14da-4fb4-8603-75a09ba2c041',
    '55cc9193-267a-40fa-a913-9471056374ed',
    '4e99b6e2-e9a2-4a24-9541-7fea18d36c8c',
    '2287b239-62fd-497f-8501-0bba55d72c0d',
    'f5918d65-37e1-463f-868b-026eb6dff8e5',
    'dbc48f0f-b1c1-4162-a619-8ac9ed611124',
    '6284483c-73e4-460a-aa15-90fe44fd81eb',
    '809c8c0d-ed1f-4be4-aa2b-03aa1cbc056d',
    '31748516-caa1-47ed-95c7-152433d6e559',
    'dc1b4fee-f83f-4c5f-a879-692bcfd5e3b9',
    '0c8bcf89-fd6d-4656-a12c-686ffa8803b4',
    '166a339c-d686-42fc-b669-03b8e01db1ce',
    '008921fc-4d63-4c2f-a7c6-ed8bce62e408',
    '06590fc7-b4e0-4e6e-9c9b-2734826838ad',
    'f64b837b-29fe-4f65-ae30-51d60d9d6340'
];

$migrationRunner = new MigrationRunner($projectUuids);
$migrationRunner->runMigrations();
