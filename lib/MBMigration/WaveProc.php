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
                                'brizy_project_domain' => $decode_response["value"]['brizy_project_domain'] ?? 'unknown',
                                'brizy_project_id' => $decode_response["value"]['brizy_project_id'] ?? 'unknown',
                                'mb_project_domain' => $decode_response["value"]['mb_project_domain'] ?? 'unknown',
                                'mb_site_id' => $decode_response["value"]['mb_site_id'] ?? 'unknown',
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
    "e2080429-e41a-4f27-a634-0ba183aec4da",
    "5e332201-6805-411f-b562-6228e351b32e",
    "b934477e-52a7-42e6-8543-a6c8a31b9c14",
    "78577588-f79c-4fac-b938-71cb3c5753a2",
    "1f4d90c4-0063-4142-b952-198ec4032cbf",
    "14e58aa5-64b0-434a-883d-ee6038ff683a",
    "6cea411a-38e1-4416-9894-8c52ae22923e",
    "cb1787f0-4fdc-4438-844e-2caa2acf48f6",
    "5ba0323e-5165-4f21-8166-2e50133d1904",
    "2e455ebf-cc06-4d74-93b3-e0bdd446413b",
    "3dc62f97-b0d1-4c35-b3fb-a739b1d27cee",
    "0b6dea5b-7289-4ad4-b7e7-9f0c99d2ba02",
    "53d3a6ba-f220-46dd-a5bd-6e1669b4c671",
    "3035955d-9a4c-4e85-94cb-e5dfabf4760a",
    "03e9db44-f7bc-4183-9eba-18326327cb36",
    "f16141c3-828c-424f-b6e8-d433b83d655f",
    "68d4b556-a811-46aa-a950-a2bfd1ceda83",
    "e38715da-aba2-4d4d-bef3-a98c7df3c1da",
    "46a7303c-1335-491a-8e6f-cbe1b1894ff4",
    "d25008c2-0fe0-4371-ac56-530e166c9ac2",
    "f3aa226d-e081-45a7-bdf3-5e135f5c0254",
    "ccbc9215-33dd-4516-b56f-739aadd98191",
    "5b5f606d-651d-4dde-90bc-4c375d5378a6",
    "08c90d94-44ee-4368-b85f-957c7743b780",
    "49405c11-02bd-4ab6-8a3b-717fbda7ab03",
    "47152161-0b1e-4b02-8da3-2a2f9be0f965",
    "bbafbff1-b697-4be6-847b-3c604542c2a1",
    "7db97292-d2cb-446a-aeff-c9ecc2a1f180",
    "f9fb21a4-1fb2-4f43-a740-4421aa0046b6",
    "76aeb2c3-fddb-42b4-8e79-08a93891d5f4",
    "79193196-d18e-4c70-a177-96446e1d1a89",
    "d91c7e39-1f73-416a-8b79-45f470f560f3",
    "4fed25c1-63c2-48f6-b4b0-cb072886baf6",
    "503f359a-205f-41d1-a6df-747349bbdb61",
    "0f88c09f-8a9f-4d94-b2e6-595ca1aa430b",
    "09cc110d-2be1-4639-9dc1-ea1f4145f845",
    "b8ad1c83-2403-41f1-98ee-e7166eac0b16",
    "bc1c2799-8996-4b1c-a1d0-d16f14ebb533",
    "6666a5e9-44e2-454e-86f3-d460a04b4a78",
    "df527843-2cec-4549-870f-75be382b166d",
    "9734cc92-7b13-4f11-b060-3228707726e0",
    "60cf8770-dfae-4571-afce-365433395ae0",
    "4c9f7ae6-0136-4e83-bc47-756b641642e3",
    "0d8fb8e5-f5fd-480f-a752-3a984ca1c3cc",
    "38c567c3-f87c-427d-b32b-f8eeb4b06db1",
    "e5526915-6f0a-414a-b1ae-abb4922ae4ef",
    "43879b3d-b0e3-42fd-b24a-a455ae92d84e",
    "03132039-3a18-48da-9c1e-63bd55198f00",
    "a027927a-62e2-4511-ac1c-7f43c307c312",
    "cf3e2e34-0b9c-4856-b66d-f3bd6ea08a7c",
    "492ad8ad-6fb5-4154-8fae-194745665780",
    "020be7f8-9eea-4c91-86f0-111c9f9bc9bd",
    "519bd36d-56c9-48fd-a885-7959a86705f6",
    "45b59e58-b8d4-4c7c-9d5f-b9a46f2579a9",
    "f9b8caed-701d-4c96-b1b8-fd5fee190fb5",
    "36c310af-9c35-449e-943c-84c33712dced",
    "c11a0f31-45b9-4713-b2f2-d3282baa050a",
    "4b328887-ab7c-4c7e-902a-b7a892d2f2e9",
    "6178dec3-f3fa-4fd9-8ca0-84f5517179e1",
    "0171a11e-ba4b-4327-b012-cc4bc7b7bbda",
    "1cff402b-e15a-4eaf-9c75-59b227ef31b1",
    "2eca633b-c3a5-416c-b2e1-9c13c5cf3b35",
    "35657554-5ffe-4026-a074-b3919cc89143",
    "fd3fbc10-cf79-4721-b5c6-4d8fefd9f725",
    "cb7553fe-15c2-41ed-a595-dc4c6027763d",
    "4fbcb8c7-ff5b-44b2-8d6b-ee29690a6bd3",
    "dca14b90-42d1-4796-ad72-cf241a5edbe6",
    "816f9bb7-7a78-4ad1-b06f-981808518f42",
    "8df6d8d3-03f6-405c-bd07-297e9aefde7c",
    "15fcf1e0-0185-4196-b475-22dfee8e4451",
    "7b6d9ce3-2f6c-46d4-a2da-1d6950633238",
    "d881a0f9-9ec2-4188-b21e-a360c8a075c7",
    "7b68f1a2-95d2-4364-bfac-42cd59add258",
    "338b148c-e038-41ab-b4d3-69ef615ad1da",
    "0246468f-bc92-4036-9abf-118408d51e93",
    "7ecd9657-1770-40e1-99d9-cf79e17785b6",
    "e8641a39-0893-4496-b77b-01474ff195b4",
    "3fcb1a5b-4613-41d8-a29a-e6052c359b62",
    "94902553-f6c9-40dd-9605-51de9dc2577c",
    "74f52a0b-4d8f-45fd-b1ba-6b652225b6d6",
];


$migrationRunner = new WaveProc($projectUuids);
$migrationRunner->runMigrations();

$d = [
'e2080429-e41a-4f27-a634-0ba183aec4da' => 22665260,
'5e332201-6805-411f-b562-6228e351b32e' => 22665261,
'b934477e-52a7-42e6-8543-a6c8a31b9c14' => 22665259,
'78577588-f79c-4fac-b938-71cb3c5753a2' => 22665458,
'1f4d90c4-0063-4142-b952-198ec4032cbf' => 22665516,
'14e58aa5-64b0-434a-883d-ee6038ff683a' => 22665588,
'6cea411a-38e1-4416-9894-8c52ae22923e' => 22665643,
'cb1787f0-4fdc-4438-844e-2caa2acf48f6' => 22665710,
'5ba0323e-5165-4f21-8166-2e50133d1904' => 22665802,
'2e455ebf-cc06-4d74-93b3-e0bdd446413b' => 22665854,
'3dc62f97-b0d1-4c35-b3fb-a739b1d27cee' => 22665924,
'0b6dea5b-7289-4ad4-b7e7-9f0c99d2ba02' => 22678261,
'53d3a6ba-f220-46dd-a5bd-6e1669b4c671' => 22678264,
'3035955d-9a4c-4e85-94cb-e5dfabf4760a' => 22678955,
'03e9db44-f7bc-4183-9eba-18326327cb36' => 22665926,
'f16141c3-828c-424f-b6e8-d433b83d655f' => 22666037,
'68d4b556-a811-46aa-a950-a2bfd1ceda83' => 22666096,
'e38715da-aba2-4d4d-bef3-a98c7df3c1da' => 22666172,
'46a7303c-1335-491a-8e6f-cbe1b1894ff4' => 22666238,
'd25008c2-0fe0-4371-ac56-530e166c9ac2' => 22666375,
'f3aa226d-e081-45a7-bdf3-5e135f5c0254' => 22666451,
'ccbc9215-33dd-4516-b56f-739aadd98191' => 22666533,
'5b5f606d-651d-4dde-90bc-4c375d5378a6' => 22679136,
'08c90d94-44ee-4368-b85f-957c7743b780' => 22666667,
'49405c11-02bd-4ab6-8a3b-717fbda7ab03' => 22666753,
'47152161-0b1e-4b02-8da3-2a2f9be0f965' => 22666875,
'bbafbff1-b697-4be6-847b-3c604542c2a1' => 22666994,
'7db97292-d2cb-446a-aeff-c9ecc2a1f180' => 22667067,
'f9fb21a4-1fb2-4f43-a740-4421aa0046b6' => 22668132,
'76aeb2c3-fddb-42b4-8e79-08a93891d5f4' => 22668134,
'79193196-d18e-4c70-a177-96446e1d1a89' => 22668266,
'd91c7e39-1f73-416a-8b79-45f470f560f3' => 22679280,
'4fed25c1-63c2-48f6-b4b0-cb072886baf6' => 22668383,
'503f359a-205f-41d1-a6df-747349bbdb61' => 22668515,
'0f88c09f-8a9f-4d94-b2e6-595ca1aa430b' => 22668585,
'09cc110d-2be1-4639-9dc1-ea1f4145f845' => 22668708,
'b8ad1c83-2403-41f1-98ee-e7166eac0b16' => 22668778,
'bc1c2799-8996-4b1c-a1d0-d16f14ebb533' => 22668883,
'6666a5e9-44e2-454e-86f3-d460a04b4a78' => 22668967,
'df527843-2cec-4549-870f-75be382b166d' => 22669065,
'9734cc92-7b13-4f11-b060-3228707726e0' => 22669096,
'60cf8770-dfae-4571-afce-365433395ae0' => 22669169,
'4c9f7ae6-0136-4e83-bc47-756b641642e3' => 22669294,
'0d8fb8e5-f5fd-480f-a752-3a984ca1c3cc' => 22669296,
'38c567c3-f87c-427d-b32b-f8eeb4b06db1' => 22669461,
'e5526915-6f0a-414a-b1ae-abb4922ae4ef' => 22669559,
'43879b3d-b0e3-42fd-b24a-a455ae92d84e' => 22669622,
'03132039-3a18-48da-9c1e-63bd55198f00' =>	'crossroadsevart.org',
'a027927a-62e2-4511-ac1c-7f43c307c312' =>	22669844,
'cf3e2e34-0b9c-4856-b66d-f3bd6ea08a7c' =>	22669903,
'492ad8ad-6fb5-4154-8fae-194745665780' =>	22669959,
'020be7f8-9eea-4c91-86f0-111c9f9bc9bd' =>	22670144,
'519bd36d-56c9-48fd-a885-7959a86705f6' =>	22670297,
'45b59e58-b8d4-4c7c-9d5f-b9a46f2579a9' =>	22670381,
'f9b8caed-701d-4c96-b1b8-fd5fee190fb5' =>	22670453,
'36c310af-9c35-449e-943c-84c33712dced' =>	22670551,
'c11a0f31-45b9-4713-b2f2-d3282baa050a' =>	22670610,
'4b328887-ab7c-4c7e-902a-b7a892d2f2e9' =>	22670721,
'6178dec3-f3fa-4fd9-8ca0-84f5517179e1' =>	22670828,
'0171a11e-ba4b-4327-b012-cc4bc7b7bbda' =>	22670928,
'1cff402b-e15a-4eaf-9c75-59b227ef31b1' =>	22671024,
'2eca633b-c3a5-416c-b2e1-9c13c5cf3b35' =>	22671081,
'35657554-5ffe-4026-a074-b3919cc89143' =>	22671175,
'fd3fbc10-cf79-4721-b5c6-4d8fefd9f725' =>	22671244,
'cb7553fe-15c2-41ed-a595-dc4c6027763d' =>	22671354,
'4fbcb8c7-ff5b-44b2-8d6b-ee29690a6bd3' =>	22671421,
'dca14b90-42d1-4796-ad72-cf241a5edbe6' =>	22671524,
'816f9bb7-7a78-4ad1-b06f-981808518f42' =>	22671583,
'8df6d8d3-03f6-405c-bd07-297e9aefde7c' =>	22671759,
'15fcf1e0-0185-4196-b475-22dfee8e4451' =>	22671834,
'7b6d9ce3-2f6c-46d4-a2da-1d6950633238' =>	22671895,
'd881a0f9-9ec2-4188-b21e-a360c8a075c7' =>	22671956,
'7b68f1a2-95d2-4364-bfac-42cd59add258' =>	22672046,
'338b148c-e038-41ab-b4d3-69ef615ad1da' =>	22672098,
'0246468f-bc92-4036-9abf-118408d51e93' =>	22672172,
'7ecd9657-1770-40e1-99d9-cf79e17785b6' =>   '',
'e8641a39-0893-4496-b77b-01474ff195b4' =>	22672376,
'3fcb1a5b-4613-41d8-a29a-e6052c359b62' =>	22672488,
'94902553-f6c9-40dd-9605-51de9dc2577c' =>	22679638,
'74f52a0b-4d8f-45fd-b1ba-6b652225b6d6' =>	22679653,
];
