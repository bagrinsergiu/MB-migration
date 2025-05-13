<?php

namespace MBMigration;

use Exception;
use MBMigration\Bridge\Bridge;
use Symfony\Component\HttpFoundation\Request;

class MigrationRunnerWave
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl;
    private string $logFile;
    private int $workspaceID;
    private bool $mgrManual;
    private Bridge $bridge;
    private ApplicationBootstrapper $app;

    public function __construct(
        ApplicationBootstrapper $app,
        Bridge                  $bridge,
        array                   $projectUuids,
        int                     $workspaceId,
        int                     $batchSize = 3,
        int                     $mgrManual = 0
    )
    {
        $this->workspaceID = $workspaceId;
        $this->mgrManual = (bool)$mgrManual;

        $this->bridge = $bridge;
        $this->app = $app;

        $this->projectUuids = $projectUuids;
        $this->batchSize = $batchSize;
        $this->baseUrl = 'http://localhost:8080/';
        $this->logFile = dirname(__DIR__) . '/../var/wave/migration_wave_result_' . date("d-m-Y_H") . '.json';
    }

    public function runMigrations(): void
    {
        $pending = $this->projectUuids;
        $activeHandles = [];
        $results = $this->loadExistingResults();

                while (count($activeHandles) < $this->batchSize && !empty($pending)) {
                    $uuid = array_shift($pending);
                    $activeHandles ++;

                    try {
                        $decodedResponse = $this->app->migrationFlow(
                            $uuid,
                            0,
                            $this->workspaceID,
                            '',
                            true,
                            true
                        );
                    } catch (\Exception $e) {
                        $ed = $e->getMessage();
                    }

                    $results[$uuid] = [
                        'brizy_project_domain' => $decodedResponse['brizy_project_domain'] ?? null,
                        'brizy_project_id' => $decodedResponse['brizy_project_id'] ?? null,
                        'mb_project_domain' => $decodedResponse['mb_project_domain'] ?? null,
                        'mb_site_id' => $decodedResponse['mb_site_id'] ?? null,
                        'response' => $decodedResponse ?? null,
                        'erroeMessage' => $ed ?? ''
                    ];

                    $this->saveResults($results, $uuid, $decodedResponse['brizy_project_id'] ?? null);
                    $activeHandles --;
                }
    }


    public function runMigrations__old(): void
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
                            'response' => $decode_response
                        ];

                        curl_multi_remove_handle($multiHandle, $ch);
                        curl_close($ch);
                        unset($activeHandles[$uuid]);
                        $this->saveResults($results, $uuid, $decode_response['brizy_project_id']);
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
            'brz_workspaces_id' => $this->workspaceID,
            'mb_site_id' => '31383',
            'mb_secret' => 'b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q',
            'mgr_manual' => $this->mgrManual,
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

    public function saveResults(array $results, $mb_site_uuid, $brizy_project_id): void
    {
        $existingResults = $this->loadExistingResults();

        if ($this->mgrManual) {
            try {
                $this->bridge->insertMigrationMapping(
                    $brizy_project_id,
                    $mb_site_uuid,
                    json_encode(['date' => date('Y-m-d')])
                );
            } catch (Exception $e) {
                $results['mgr_message'] = ['error' => $e->getMessage()];
            }
        }

        $mergedResults = array_merge($existingResults, $results);

        file_put_contents($this->logFile, json_encode($mergedResults, JSON_PRETTY_PRINT));
    }

}

