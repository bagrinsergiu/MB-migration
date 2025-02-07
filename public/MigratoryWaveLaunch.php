<?php

class MigrationRunner
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl;
    private string $logFile = 'migration_results.json';

    public function __construct(array $projectUuids, int $batchSize = 5)
    {
        $this->projectUuids = $projectUuids;
        $this->batchSize = $batchSize;
        $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost:8080/';
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
            'mb_site_id' => '31383',
            'mb_secret' => getenv('MB_SECRET') ?: 'b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q',
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
    '9542db37-fdbd-4fcb-b294-a41e725c0984',
'9ec95150-2c9e-42d8-91b0-ed995ee1dba7',
'662fcf33-223a-4b03-bab1-ae66c0929800',
'4c54bb42-4b12-4039-85cc-119a8e813a8c',
'2e02b5f7-ba19-4266-b291-875c13d35867',
'15744cd0-c744-4a44-a8c6-97936307a4b2',
'b67a86fd-4e26-453a-8576-e697b3acc0a9',
'9bf3733d-2f8d-49a5-9aab-ffc66a72e3a1',
'3b9969c1-cc16-440f-9bff-177bfd8745d3',
'3ed28422-9d54-450c-96fc-e6d63fa0044d',
'48cd822e-be11-45f2-badd-6ce98c87c47d',
'78fa2522-ae2b-4715-a2ce-be74bfaaa69a',
'56a45591-c391-4d53-b402-0049080f6f3d',
'cabe6fe7-36c3-4e63-932f-78b9e5c024b5',
'8ad435ba-0e8f-4f78-813c-e712699c3827',
'5598188b-70f5-4358-8180-c0f641b7f0ba',
'fa893a95-0a91-4b7a-bd11-d1481b3a31b8',
'c8b646f1-de2f-42e7-9242-dc8a32396d54',
'e2272a55-eff6-4afa-8684-bb85b6e93334',
'32d644fd-2eb0-4518-b097-9c31b27085ce',
'4ae5ca51-b777-405a-9739-f5fcc8babfe7',
'7940b87a-4108-4c39-a641-d4f558863910',
'a467ba8d-689a-4119-b5db-2bc9de4fab68',
'c0ec5d96-87e4-4d6b-8a7c-a46a43980302',
'01136f36-32bf-4749-8fbd-b2cfd93b81a2',
'3e24cd5c-0b7b-432e-b237-24e6aba58928',
'18f5a948-c720-4ed6-b532-4a73dc562ca3',
'11431acf-98af-4cad-84ca-1e4c1ae98f23',
'6f64457c-c57f-4475-8efb-fce0f77b889c',
'a11df8bd-e9ad-4f7e-acca-3976cde72ee0',
'700b5d0a-67ce-438d-86a0-2b741e288833',
'2e8ccaa7-3b8e-44f1-936d-26b3a6c1027b',
'984bffd8-7cd7-4c97-8f44-11729c28bb6c',
'09c06c39-19e0-4441-9cdb-148c1f0879f2',
'33bcf485-369a-42e8-8dca-5fd1f8a13c90',
'636ce442-c3c7-4e30-97f8-2b9a8214eeed',
'07f96796-e413-428d-83fa-949d9be1d2d3',
'9843849b-99f6-478a-92ee-62481e013e80',
'30ca32df-8886-46fb-9db6-05c6ab29441b',
'c2ad30cd-e71e-40b5-8b25-8507a4f43fc3',
'b40abcfc-a391-413e-941a-ec64d63612b7',
'8addbfad-bb99-4569-9ba9-49b7449d9f74',
'2dbaa2b5-ce1a-4742-bdc2-86302a6dea26',
'b1df967d-e057-49f6-9f82-d5c50b0f2e5f',
'f95d6312-25f8-454b-b75a-35ed8e65ca09',
'60f76b5f-9ed2-4d16-bb6c-ca09400af205',
'ba6bc827-4cad-4cdf-9eec-6c40efdb0d02',
'07b25b9c-9dd9-4b93-b15c-7ef8b3fba171',
'b3be65f8-a644-49d7-8d91-e6b45822c3eb',
'48cc395d-be1a-4fdd-9540-dfd693f9f790',
];

$migrationRunner = new MigrationRunner($projectUuids);
$migrationRunner->runMigrations();
