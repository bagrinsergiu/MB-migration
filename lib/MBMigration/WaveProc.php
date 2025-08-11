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
    const ID_WORKSPACE = 22700013;
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
    "f2c701b1-c16c-4bf0-b759-aa89d133c84c",
    "e1b8f452-0881-4322-ad40-60e1389332f9",
    "f1ddb8bc-3c69-4487-9149-0915e2dfbda0",
    "cc61b469-bb84-4c53-9731-7e2d3502e004",
    "dc2136df-5b48-4eef-8975-7be224b7cc08",
    "394fc857-96d3-439e-96fa-d15168e928b6",
    "19b7eef3-f0fe-4f3d-bd9e-f7d6b2b9aa20",
    "679d9f54-5bd2-4413-b0fe-80bb946607a4",
    "123a7df2-934a-4a57-83d7-8ff6ce71472f",
    "67571786-7072-47a7-958b-c7a23b6a8e6c",
    "8eee9a60-89d3-48b2-9936-5685b3cd436c",
    "fd5ae3f4-bba7-4596-92c7-40777851259c",
    "ec9c2687-338a-484f-9ac0-6b5bb2bdf91b",
    "4bd51774-d87b-47dd-9797-a20ca0065a18",
    "0fee69a7-b77c-4ddf-97ff-7fa02a225b80",
    "44816950-e46d-4ce8-b82e-2c87549b696d",
    "95903e8b-be37-4db9-87ca-4a0e497785e9",
    "49c6283f-c34e-48cf-b8c8-27b68aafdffe",
    "5d8deb81-b7d4-450e-a43e-ca23dfe8f796",
    "044775af-75ef-4abf-a4d2-a21f050cc709",
    "c26e1756-ae87-47a4-9927-b78c58b1c7ff",
    "7401d571-553e-4404-b61a-6d9dc76b1aab",
    "19bd49be-824a-4f18-a07c-17247680fed6",
    "fcdcda31-90dd-4d9e-8256-2b8bc499f91b",
    "925c4f26-3ced-4b09-b073-7ef45e6e8ebd",
    "e5aa2136-ed0e-4d4f-9f2a-127b8182c528",
    "a1c0aad4-7264-4df5-82c8-d44c8ec31914",
    "85d9447e-42e8-4581-9686-9bb53673267b",
    "3db38aa7-c7f6-40f3-b67b-60150a68332d",
    "52a9b5f2-a581-4409-9cf7-9261826b4b7c",
    "d8ccf226-41a0-46fb-82fb-09f9cf9d6352",
    "03349a97-9976-427d-94c9-12be4ef6f717",
    "2c1d2257-c4f0-4b8a-a888-8f988cc7695c",
    "a87b70aa-7e27-469d-9c3a-441aab491891",
    "91223030-e2e4-4dfe-956e-8cee0937afba",
    "f27aca29-3d28-4de0-8372-5285dfc445fc",
    "c718cc2b-7468-4ac5-b62e-f4ca0f4c96a0",
    "4b2b974a-a34a-433a-b2ce-4464092f1fc6",
    "49be5d88-c5dc-4c42-a626-2d888e88a317",
    "fe702c06-e318-48ad-87ff-bf2236322467",
    "8c841023-2777-499c-b966-d222a3d406c1",
    "19b5f5b6-7744-47ff-8a81-bf4b2342476c",
    "3f19a44f-0aa0-475c-81c1-22ed386517f8",
    "1675c01b-bf7f-4ae5-8ad1-946b44df8a57",
    "26c90804-8042-4aef-8152-c92c4f847937",
    "d0469543-4807-4e3b-b218-f0737e5e0a38",
    "e39400e6-4ada-4ba2-b39b-235259f655a1",
    "ef52b52c-6275-4bc2-957d-827effb5c8c6",
    "39e17572-7246-4b07-9a30-0998c5450941",
    "4f30761d-95dd-4770-a014-2b8fa7c91800",
    "64193a11-e048-43a8-8451-ea09f4d65dae",
    "285e7d0b-73c9-426b-80b0-37a918245bab",
    "f073e87d-c11b-42ca-8ef7-f62845fb05e1",
    "37823faa-f989-4696-ba4e-1288988c61cc",
    "817fb187-0cca-47b2-b28c-39b1a4f8acc7",
    "9a3ff69f-e924-4116-9472-ad48901495c7",
    "41caa86b-7e1e-420e-acc3-4b18bbb3e405",
    "7e3b0475-4dec-42c9-a3b8-06902c149cbd",
    "91d85c09-121d-4cbc-97b8-e93497ef02aa",
    "5f1ca0d7-8998-44e7-a128-33ebd0eb2624",
    "a2fa5e02-031e-49cb-bdc1-36e125be9336",
    "5b567367-6a32-45ed-8c93-366816281a43",
    "2e038220-6dbd-4f23-bb55-4be20d6f928b",
    "b4b9eec3-d6ab-40ab-9d80-7173cc5dd71b",
    "998231cb-6c09-46e1-b6be-90d48857367d",
    "337b7ed9-1065-4a03-84b1-c83b2c47ec08",
    "8bbe1918-30a5-4b8d-802a-02cfa0da47b6",
    "a124cb8e-fa4c-4a65-9264-f831056e7471",
    "2738ab00-08fe-41ff-95ea-502533d0528f",
    "d96d1674-3c75-46b5-8b80-b988fafa0ce4",
    "a8773eb9-009b-43d2-be83-fc26cfaf8d32",
    "cc23c6a2-75b2-4c76-98d7-00b3f45e0229",
    "7924f483-7221-443a-997c-c5cb9044d4f4",
    "69d0ccdc-435d-4e65-a74c-9adc4102dc59",
    "4f4ba7ec-5b44-40fd-9a83-e35db8994c2f",
    "5464be77-875e-4c1a-bdb1-3ddf365d3039",
    "4b2a8393-3cdc-4fef-9a66-5ed55a87339c",
    "ea388fdb-8ae3-4f2c-9717-d06552b884b9",
    "23c7b57c-f53d-4656-9246-ad3827074520",
];

$muuid = time() + random_int(999, 100000);

$migrationRunner = new WaveProc($projectUuids, 3, 1754581047 ?? $muuid);

//$migrationRunner->insert('migration_result_list', [
//    'migration_uuid' => 121212121,
//    'brz_project_id' => 987,
//    'mb_project_uuid' => 'asdasd111',
//    'result_json' => '{}'
//]);

$migrationRunner->runMigrations();
