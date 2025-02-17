<?php

class MigrationRunner
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl;
    private string $logFile;

    public function __construct(array $projectUuids, int $batchSize = 5)
    {
        $this->projectUuids = $projectUuids;
        $this->batchSize = $batchSize;
        $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost:8080/';
        $this->logFile = 'migration_results_'. date("Y-m-d_H") .'.json';
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
    'c5064e5d-2982-47ce-86ef-6f89f3f7a249',
    '9e46158d-cfc5-4d03-8b99-d76460d4945c',
    'c8f713a2-cc99-4b70-93e3-42964c8de26d',
    '001fa8d2-aa54-4b91-ab64-d2dbaa415314',
    '58ca5f3b-3a08-47d8-8c92-2bb67c222414',
    '4bfb4922-0185-48b7-9d93-ca700009aa8a',
    'ecfd489d-5943-4275-8435-6b8ed4af24b5',
    '0b1f369d-ff4b-49f6-9c03-cbacd96daf7f',
    '5e5b5d4a-bffc-469e-93ba-dcc83646c29f',
    'df215a8c-96d5-4f44-8fee-549745631403',
    'dd984e2c-f3ef-4bb4-9188-dbec024eeb92',
    '2d02adbb-e412-471b-943d-27ada4557491',
    '053f277a-8e09-437c-9b28-9b8c26fc3919',
    '7460807e-02db-4fb6-bbf3-3af3f4f288a0',
    '949bab89-e353-4e2a-a9f4-3c23e1c1ea8c',
    '67c7a0ae-6cef-4f66-8858-a8842f3c52b5',
    'd1720353-a4a9-4278-ad83-2218854e8835',
    '4290589d-6624-4a2b-bcd5-4098a402b7d4',
    '7f19a190-4b6c-4db6-a268-d6fb276275fb',
    '00936a44-725f-4ec1-8448-379cbbbd3a48',
    'adb29726-f0a6-42c9-8a97-316699be2108',
    'f9952e30-1e02-4919-8f62-8b6ce82dea56',
    '43615d28-395e-45a5-b634-17ec1a1ce1dc',
    'bf533fe3-1904-48ab-8c00-486b796d30cf',
    '54685721-bcf9-4283-b7e1-138faf070a82',
    'bf5799e8-5df7-4e99-899d-f309e154d3d8'
];

$migrationRunner = new MigrationRunner($projectUuids);
$migrationRunner->runMigrations();
