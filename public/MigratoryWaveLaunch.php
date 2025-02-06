<?php

class MigrationRunner
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl = 'http://localhost:8080/';
    private string $logFile = 'migration_results.json';

    public function __construct(array $projectUuids, int $batchSize = 5)
    {
        $this->projectUuids = $projectUuids;
        $this->batchSize = $batchSize;
    }

    public function runMigrations(): void
    {
        $pending = $this->projectUuids;
        $activeHandles = [];
        $multiHandle = curl_multi_init();
        $results = $this->loadExistingResults();

        while (!empty($pending) || !empty($activeHandles)) {
            while (count($activeHandles) < $this->batchSize && !empty($pending)) {
                $uuid = array_shift($pending);
                $url = $this->baseUrl . "?mb_project_uuid=$uuid&brz_project_id=21915772&mb_page_slug=events&mb_site_id=31383&mb_secret=b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_multi_add_handle($multiHandle, $ch);
                $activeHandles[$uuid] = $ch;
            }

            do {
                $status = curl_multi_exec($multiHandle, $active);
                curl_multi_select($multiHandle);
            } while ($status === CURLM_CALL_MULTI_PERFORM);

            foreach ($activeHandles as $uuid => $ch) {
                $info = curl_getinfo($ch);
                if ($info['http_code'] !== 0) {
                    $response = curl_multi_getcontent($ch);
                    $decode_response = json_decode($response, true);
                    $results[$uuid] = [
                        'status' => $info['http_code'] === 200 ? 'success' : 'error',
                        'brizy_project_domain' => $decode_response['brizy_project_domain'],
                        'brizy_project_id' => $decode_response['brizy_project_id'],
                        'mb_project_domain' => $decode_response['mb_project_domain'],
                        'mb_site_id' => $decode_response['mb_site_id'],
                        'response' => $response,
                    ];
                    curl_multi_remove_handle($multiHandle, $ch);
                    curl_close($ch);
                    unset($activeHandles[$uuid]);
                    $this->saveResults($results);
                }
            }
        }

        curl_multi_close($multiHandle);
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
        file_put_contents($this->logFile, json_encode($results, JSON_PRETTY_PRINT));
    }
}

// list UUID
$projectUuids = [
    '9542db37-fdbd-4fcb-b294-a41e725c0984',
    '9ec95150-2c9e-42d8-91b0-ed995ee1dba7',
    '662fcf33-223a-4b03-bab1-ae66c0929800',
    '4c54bb42-4b12-4039-85cc-119a8e813a8c',
    '2e02b5f7-ba19-4266-b291-875c13d35867',
];

$migrationRunner = new MigrationRunner($projectUuids);
$migrationRunner->runMigrations();
