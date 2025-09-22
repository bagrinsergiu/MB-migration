<?php

namespace MBMigration;

use MBMigration\Core\Logger;
use MBMigration\Layer\DataSource\driver\MySQL;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

class WaveProc
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl;
    private string $logFile;
    const ID_WORKSPACE = 22925473;
    private MySQL $DB;
    /**
     * @var mixed|null
     */
    private $muuid;

    public function __construct(array $projectUuids, int $batchSize = 3, $muuid = null)
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
            $this->logFile = 'migration_results_' . date("d-m-Y_H") . '.json';
            try {
                $this->DB = new MySQL(
                    'admin',
                    'Vuhodanasos2',
                    'MG_prepare_mapping',
                    'mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com',
                );
                $this->DB->doConnect();
                Logger::instance()->info('DB connection initialized');
            } catch (\Exception $e) {
                Logger::instance()->error('Error initializing DB connection: ' . $e->getMessage(), ['exception' => $e]);
            }
            $this->muuid = $muuid;

            Logger::instance()->info('WaveProc initialized with ' . count($projectUuids) . ' projects and batch size ' . $batchSize);
            Logger::instance()->debug('Base URL: ' . $this->baseUrl);
            Logger::instance()->debug('Log file: ' . $this->logFile);
        } catch (\Exception $e) {
            Logger::instance()->error('Error during WaveProc initialization: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    /**
     * Get already migrated project UUIDs from the database
     *
     * @return array Array of already migrated project UUIDs
     */
    private function getAlreadyMigratedProjects(): array
    {
        try {
            Logger::instance()->info('Checking for already migrated projects');

            // Create placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($this->projectUuids), '?'));

            // Only check for projects that are in our current batch
            $sql = "SELECT mb_project_uuid FROM migration_result_list WHERE mb_project_uuid IN ($placeholders)";

            $migratedProjects = $this->DB->getAllRows($sql, $this->projectUuids);

            $migratedUuids = [];
            foreach ($migratedProjects as $project) {
                $migratedUuids[] = $project['mb_project_uuid'];
            }

            Logger::instance()->info('Found ' . count($migratedUuids) . ' already migrated projects out of ' . count($this->projectUuids));
            return $migratedUuids;
        } catch (\Exception $e) {
            Logger::instance()->error('Error getting already migrated projects: ' . $e->getMessage(), ['exception' => $e]);
            return [];
        }
    }

    public function runMigrations(): void
    {
        Logger::instance()->info('Starting migration process for ' . count($this->projectUuids) . ' projects');

        // Get already migrated projects
        $alreadyMigratedUuids = $this->getAlreadyMigratedProjects();

        // Filter out already migrated projects
        $filteredUuids = array_diff($this->projectUuids, $alreadyMigratedUuids);
        Logger::instance()->info('After filtering, ' . count($filteredUuids) . ' projects need migration');

        $pending = $filteredUuids;
        $activeHandles = [];
        $multiHandle = curl_multi_init();

        try {
//            $results = $this->loadExistingResults();
//            Logger::instance()->info('Loaded ' . count($results) . ' existing results');

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
                                Logger::instance()->error($errorMessage, ['value' => $response]);

                                curl_multi_remove_handle($multiHandle, $ch);
                                curl_close($ch);
                                unset($activeHandles[$uuid]);
                                $totalProcessed++;
                                continue;
                            }


                            $status = $info['http_code'] === 200 ? 'success' : 'error';

                            $p_result = [
                                'status' => $status,
                                'brizy_project_domain' => $decode_response["value"]['brizy_project_domain'] ?? 'unknown',
                                'brizy_project_id' => $decode_response["value"]['brizy_project_id'] ?? 'unknown',
                                'mb_project_domain' => $decode_response["value"]['mb_project_domain'] ?? 'unknown',
                                'mb_site_id' => $decode_response["value"]['mb_site_id'] ?? 'unknown',
                                'response' => $response
                            ];

                            $results[$uuid] = $p_result;

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
                                $this->insert('migration_result_list', [
                                    'migration_uuid' => $this->muuid,
                                    'brz_project_id' => $p_result['brizy_project_id'],
                                    'brizy_project_domain' => $p_result['brizy_project_domain'],
                                    'mb_project_uuid' => $uuid,
                                    'result_json' => json_encode($p_result)
                                ]);

//                                $this->saveResults($results);
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

    public function insert(string $table, array $data)
    {
        try {
            return $this->DB->insert($table, $data);
        } catch (\Exception $e) {
            Logger::instance()->error('Error inserting into table ' . $table . ': ' . $e->getMessage(), ['exception' => $e]);
            return null;
        }
    }
}

$projectUuids = [
"84d47e95-4cc9-4922-a676-bfa0bae2ca35",
"1b3725b9-3cda-4e88-beaa-2211f70d74f4",
"86cc9a80-51a7-48a7-a80c-593ccfdf0eb1",
"921b5ebc-2f75-41e2-950a-20249ce2bbf9",
"2dcdef70-c3ed-462a-bcda-d6c7d4bded3c",
"36758d8b-5b37-4a14-9611-4c0b90e4f048",
"b2d3e8ab-ad42-46bc-b313-ee0c55871a6a",
"44aae763-495b-4a3e-8d69-476074e1bff8",
"65b2e15c-566d-4666-a5d9-932f8ccb4a09",
"ddab4b13-8513-43ec-bb35-d1659409ec8c",
"e0c04032-970a-43a2-89d4-05947d374c00",
"877a4971-26f0-460d-86bc-481e196e2aab",
"6b7dbdce-1a5f-4dbe-a47e-f46541f6ce6a",
"0ef4123b-7c1e-4ca4-82c1-e13998186d28",
"24a63112-d847-405f-87cb-9d8049691016",
"5a81b7e0-e080-4788-8ff6-d29707ea8009",
"52d04556-0874-4444-8f65-98009fc193f7",
"bc865ebe-3b58-43ac-b854-2cec66bfb498",
"f56f2db7-efba-42b5-9f0c-186ac2e04977",
"95ce9657-6153-48c5-a6af-47f5c5618c76",
"751b026e-8a90-4ebc-8a38-abb71b299f7d",
"bd37edaa-63ed-44b9-ba9d-92bb71f2fbbd",
"22f338ec-8185-49e3-8138-e9cc350a6ea4",
"d54c0e9f-71a0-4c1e-aa27-3ae8a363436b",
"a17db1d8-26d6-4915-9c38-279c54eae52b",
"8ace41f4-4559-435c-b286-ed9d26df7150",
];

$muuid = time() + random_int(999, 100000);

$migrationRunner = new WaveProc($projectUuids, 5, $muuid);

//$migrationRunner->insert('migration_result_list', [
//    'migration_uuid' => 121212121,
//    'brz_project_id' => 987,
//    'mb_project_uuid' => 'asdasd111',
//    'result_json' => '{}'
//]);

$migrationRunner->runMigrations();
