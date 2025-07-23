<?php

namespace MBMigration;

use MBMigration\Core\Logger;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

class WaveProc
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl;
    private string $logFile;
    const ID_WORKSPACE = 22663015;

    public function __construct(array $projectUuids, int $batchSize = 3)
    {
        try {
            Logger::initialize(
                "",
                'debug',
                './migration_wave_proc.log'
            );
            Logger::instance()->info('--------WaveProc initialization started--------');
            $this->projectUuids = $projectUuids;
            $this->batchSize = $batchSize;
            $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost:8080/';
            $this->logFile = 'migration_results_'. date("d-m-Y_H") .'.json';
            Logger::instance()->info('WaveProc initialized with ' . count($projectUuids) . ' projects and batch size ' . $batchSize);
            Logger::instance()->debug('Base URL: ' . $this->baseUrl);
            Logger::instance()->debug('Log file: ' . $this->logFile);
        } catch (\Exception $e) {
            Logger::instance()->error('Error during WaveProc initialization: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    public function runMigrations(): void
    {
        Logger::instance()->info('Starting migration process for ' . count($this->projectUuids) . ' projects');

        $pending = $this->projectUuids;
        $activeHandles = [];
        $multiHandle = curl_multi_init();

        try {
            $results = $this->loadExistingResults();
            Logger::instance()->info('Loaded ' . count($results) . ' existing results');

            $totalProcessed = 0;

            while (!empty($pending) || !empty($activeHandles)) {
                // Add new handles up to batch size
                while (count($activeHandles) < $this->batchSize && !empty($pending)) {
                    $uuid = array_shift($pending);
                    try {
                        Logger::instance()->info('Processing project with UUID: ' . $uuid);
                        $url = $this->buildUrl($uuid);
                        Logger::instance()->debug('Request URL: ' . $url);

                        $ch = curl_init($url);
                        if ($ch === false) {
                            throw new \Exception('Failed to initialize cURL');
                        }

                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_multi_add_handle($multiHandle, $ch);
                        $activeHandles[$uuid] = $ch;
                        Logger::instance()->debug('Added project to active handles, remaining: ' . count($pending));
                    } catch (\Exception $e) {
                        Logger::instance()->error('Error initializing request for UUID ' . $uuid . ': ' . $e->getMessage(), ['exception' => $e]);
                    }
                }

                // Execute the handles
                try {
                    do {
                        $status = curl_multi_exec($multiHandle, $active);
                        if ($status > CURLM_OK && $status !== CURLM_CALL_MULTI_PERFORM) {
                            $errorMessage = "cURL multi error: " . curl_multi_strerror($status);
                            Logger::instance()->error($errorMessage);
                            break;
                        }
                    } while ($status === CURLM_CALL_MULTI_PERFORM);
                } catch (\Exception $e) {
                    Logger::instance()->error('Error executing cURL multi handle: ' . $e->getMessage(), ['exception' => $e]);
                }

                // Process completed requests
                foreach ($activeHandles as $uuid => $ch) {
                    try {
                        $info = curl_getinfo($ch);
                        if ($info['http_code'] !== 0) {
                            Logger::instance()->info('Received response for UUID: ' . $uuid . ' with HTTP code: ' . $info['http_code']);

                            $response = curl_multi_getcontent($ch);
                            $decode_response = json_decode($response, true);

                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $errorMessage = "JSON decode error for UUID " . $uuid . ": " . json_last_error_msg();
                                Logger::instance()->error($errorMessage,['value' => $response]);
                                continue;
                            }

                            $status = $info['http_code'] === 200 ? 'success' : 'error';

                            $results[$uuid] = [
                                'status' => $status,
                                'brizy_project_domain' => $decode_response['brizy_project_domain'] ?? 'unknown',
                                'brizy_project_id' => $decode_response['brizy_project_id'] ?? 'unknown',
                                'mb_project_domain' => $decode_response['mb_project_domain'] ?? 'unknown',
                                'mb_site_id' => $decode_response['mb_site_id'] ?? 'unknown',
                                'response' => $response
                            ];

                            if ($status === 'success') {
                                Logger::instance()->info('Migration successful for UUID: ' . $uuid);
                            } else {
                                Logger::instance()->warning('Migration failed for UUID: ' . $uuid . ' with HTTP code: ' . $info['http_code']);
                            }

                            curl_multi_remove_handle($multiHandle, $ch);
                            curl_close($ch);
                            unset($activeHandles[$uuid]);
                            $totalProcessed++;

                            try {
                                $this->saveResults($results);
                                Logger::instance()->debug('Results saved for UUID: ' . $uuid);
                            } catch (\Exception $e) {
                                Logger::instance()->error('Error saving results: ' . $e->getMessage(), ['exception' => $e]);
                            }
                        }
                    } catch (\Exception $e) {
                        Logger::instance()->error('Error processing response for UUID ' . $uuid . ': ' . $e->getMessage(), ['exception' => $e]);

                        // Clean up this handle even if there was an error
                        try {
                            curl_multi_remove_handle($multiHandle, $ch);
                            curl_close($ch);
                            unset($activeHandles[$uuid]);
                        } catch (\Exception $cleanupException) {
                            Logger::instance()->error('Error cleaning up handle: ' . $cleanupException->getMessage());
                        }
                    }
                }
            }

            Logger::instance()->info('Migration process completed. Total projects processed: ' . $totalProcessed);
        } catch (\Exception $e) {
            Logger::instance()->critical('Critical error in migration process: ' . $e->getMessage(), ['exception' => $e]);
        } finally {
            // Clean up
            try {
                curl_multi_close($multiHandle);
                Logger::instance()->debug('cURL multi handle closed');
            } catch (\Exception $e) {
                Logger::instance()->error('Error closing cURL multi handle: ' . $e->getMessage());
            }
        }
    }

    private function buildUrl(string $uuid): string
    {
        try {
            Logger::instance()->debug('Building URL for UUID: ' . $uuid);

            $params = http_build_query([
                'mb_project_uuid' => $uuid,
                'brz_project_id' => 0,
                'brz_workspaces_id' => self::ID_WORKSPACE,
                'mb_site_id' => '31383',
                'mb_secret' => 'b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q',
            ]);

            $url = "{$this->baseUrl}?{$params}";
            Logger::instance()->debug('URL built: ' . $url);

            return $url;
        } catch (\Exception $e) {
            Logger::instance()->error('Error building URL for UUID ' . $uuid . ': ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    private function loadExistingResults(): array
    {
        try {
            Logger::instance()->debug('Loading existing results from file: ' . $this->logFile);

            if (file_exists($this->logFile)) {
                Logger::instance()->debug('Results file exists, reading content');

                $data = file_get_contents($this->logFile);
                if ($data === false) {
                    throw new \Exception('Failed to read results file: ' . $this->logFile);
                }

                $decodedData = json_decode($data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Logger::instance()->warning('JSON decode error in results file: ' . json_last_error_msg());
                    return [];
                }

                $count = is_array($decodedData) ? count($decodedData) : 0;
                Logger::instance()->debug('Loaded ' . $count . ' results from file');

                return $decodedData ?? [];
            } else {
                Logger::instance()->debug('Results file does not exist, returning empty array');
                return [];
            }
        } catch (\Exception $e) {
            Logger::instance()->error('Error loading existing results: ' . $e->getMessage(), ['exception' => $e]);
            return [];
        }
    }

    private function saveResults(array $results): void
    {
        try {
            Logger::instance()->debug('Saving results to file: ' . $this->logFile);

            $existingResults = $this->loadExistingResults();
            $mergedResults = array_merge($existingResults, $results);

            Logger::instance()->debug('Merged results count: ' . count($mergedResults));

            $jsonData = json_encode($mergedResults, JSON_PRETTY_PRINT);
            if ($jsonData === false) {
                throw new \Exception('Failed to encode results to JSON: ' . json_last_error_msg());
            }

            $bytesWritten = file_put_contents($this->logFile, $jsonData);
            if ($bytesWritten === false) {
                throw new \Exception('Failed to write results to file: ' . $this->logFile);
            }

            Logger::instance()->debug('Successfully saved ' . $bytesWritten . ' bytes to results file');
        } catch (\Exception $e) {
            Logger::instance()->error('Error saving results: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }
}


$projectUuids = [
    "5ba0323e-5165-4f21-8166-2e50133d1904",
    "2e455ebf-cc06-4d74-93b3-e0bdd446413b",
    "3dc62f97-b0d1-4c35-b3fb-a739b1d27cee",
    "0b6dea5b-7289-4ad4-b7e7-9f0c99d2ba02",
    "53d3a6ba-f220-46dd-a5bd-6e1669b4c671",
    "3035955d-9a4c-4e85-94cb-e5dfabf4760a",
];


$migrationRunner = new WaveProc($projectUuids);
$migrationRunner->runMigrations();
