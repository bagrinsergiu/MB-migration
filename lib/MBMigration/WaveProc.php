<?php

namespace MBMigration;

class WaveProc
{
    private array $projectUuids;
    private int $batchSize;
    private string $baseUrl;
    private string $logFile;
    const ID_WORKSPACE = 22509458;

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
    "13d842c6-85d4-4340-9cad-3725521e54c3",
//    "3648f55a-aacf-46e4-bf3a-36a52190eafe",
//    "c9a79857-d275-4b5c-a786-037e05e14e2f",
//    "d16ebb98-6467-43c8-9867-12a1d0755962",
//    "1d14c402-df9f-4cf8-8c09-9daebd455384",
//    "d6a94724-1379-4035-bd1d-626c228b7608",
//    "a55b11e7-f462-494e-8cec-5ecd3f59b2b9",
//    "bd1034d0-4e24-4929-b5d6-079270bb02fe",
//    "0ee39bc6-eda3-415f-a65c-e0181d41fc45",
//    "c4a89a0d-a166-4321-892c-b2a630791298",
//    "e1e4698c-b516-414a-ad05-09689a2a3834",
//    "3da6e8a4-8dd9-4fe2-9f73-7f7a5f97c83e",
//    "7e759570-19ef-4ca6-a882-4b64056adbbb",
//    "e096d43c-6180-428f-b5de-8958f869a840",
//    "a41d0c14-895a-4118-9d88-3d532c5deac3",
//    "4c47a2cb-e91e-4adc-8a95-74263557f10a",
//    "441076fd-7fdb-4f1b-a220-7985c0c39e35",
//    "4af325d4-764e-45bd-ad6b-759aef1c86e7",
//    "8a0fe683-64e9-4f09-9f7c-42cac3f21262",
//    "4b41358b-51a9-4303-8614-f534c1f94b45",
//    "688c0048-c8f6-483a-808a-d7edf02b4b38",
//    "58704796-afff-43fc-a968-3f42d3ec2cf3",
//    "730903ef-3ee5-4691-96cc-81e61e1cdc2b",
//    "668cedec-e5f2-43d8-89a9-307160272d55",
//    "4742f703-c0bc-4c55-9c87-ec0c4931b1b7",
//    "d8a778fd-ff5b-4ac7-ab81-02fcdcbea458",
//    "f8787e0b-f3ff-491a-8adb-879ebecaa0e8",
//    "756b8cc9-0051-4c96-9e0e-f65b2291c196",
//    "01414d4b-73ca-4284-8c65-82fb888bf66b",
//    "6dc30819-24b1-4fb3-a07b-a6b3dfeed865",
//    "7a451f0d-5e16-4cf8-95f0-b2f3dd6f0dbc",
//    "5e186add-8e53-4ede-aef3-8276592d0983",
//    "34dcc1d6-8004-4d0a-8708-76b2d56116ac",
//    "3e640e18-8ebc-48c0-8c5f-d40ab7878819",
//    "78118538-7726-4d9a-823d-2f4576c95748",
//    "5782d6de-375f-4f2a-bef6-b70d93a1f444",
//    "984245a4-addf-4c95-9176-6bf67f625731",
//    "f16ca68d-d867-4302-bc7b-d1e4f9376914",
//    "041c1619-f247-4fb4-a4e0-edb6fda34c08",
//    "deafe295-0eb9-4cd4-995c-8ecb3e1ad9dc",
//    "d3a63e26-1d62-48b6-8916-66527f90a81c",
//    "63f19288-d524-4af1-8269-fd95e38ebf75",
//    "de9a8fe6-9685-4bd3-8218-c409e230335c",
//    "23d7725a-358b-4627-897e-22d5b9c29dba",
//    "ada58a46-4303-476f-8e23-17e3f879d3bd",
//    "a7e723b6-b141-4899-83e7-461d4b947401",
//    "f315e419-642a-46bc-91fd-06d74a91d64c",
//    "bd77de44-d4ee-4b41-b74b-73ff2107e893",
//    "a48aa4cc-76fa-4732-998e-8259f473f64f",
//    "c7afb547-019f-4a85-bed2-56291a46ff78",
//    "1cbdc891-4434-4a63-b805-398f3031fd8f",
//    "4a740c97-f2ea-4e23-b210-575ef660bd23",
//    "6cab878f-51b2-47aa-a41e-feeac8889108",
//    "b6099ea7-cb2c-4028-a3a9-cad2dace5dbb",
//    "3f065c61-192a-4554-b851-e42397bca58a",
//    "ad53336a-1e7c-4c8e-9785-067fb3a8206b",
//    "79ea99f8-d01f-4d03-b7be-b77dae95c412",
//    "4f4a0127-1e1a-44cb-88ce-2cb9e51b61ec",
//    "c0b62912-f5f4-4614-850d-0e4ae94259aa",
//    "a0927c7d-752a-4e07-a6ba-9a6052f31805",
//    "b5b0e3f0-26e4-4655-8d12-1f8d84adf0fa",
//    "9ec3ff8b-f5ec-4229-8c57-f884fa9302cf",
//    "49b597d1-be69-4502-8fc2-312e89defe61",
//    "42a355f5-5bfa-4af7-abd0-e6b168af5bf4",
//    "3c3123bb-497d-4f98-8f7f-ba478430102a",
//    "0594aa3f-47b8-4115-a930-03c044efa699",
//    "d8afdafd-660f-4272-8e9f-5b5bd74f16cd",
//    "3e7895b6-9689-4abe-955a-fd9546a5a886",
//    "c8215956-7e33-40d5-b9c5-4af71dcc1008",
//    "c126e302-fd26-41ab-b274-b734dc7f793e",
//    "1846e59e-3b2d-4562-b981-0c42c2fc8969",
//    "8a0c9148-8587-44ff-8972-772749aa03de",
//    "34fd728c-82c1-4a6c-bd3b-2a07f83c3d44",
//    "f20f7899-2b33-4a64-a319-c2fe3f8c5f87",
//    "34740b93-2d8f-46be-a4a4-e7f157fcde83",
//    "19d5cd8f-b131-4e4d-bb63-261c7d03aa65",
//    "3272e34d-d5b5-4c4a-818b-d645ba86eb3c",
//    "60b359f6-2674-4f56-b0e1-007b7f1dd535",
//    "376d1570-160f-4d6d-b3e5-b453ad4b2bc1",
//    "d175eb84-8b80-4993-9965-4cd2246a6b99",
//    "9b931b86-4f33-4f69-a00b-c969cb3c872e",
//    "58b688d2-04c7-4c15-ae77-fabf9a1a25bb",
//    "0592f336-b2f7-44b8-a650-c917b2cf1734",
//    "42972945-cb79-420a-b0e8-8b7b6ff95f63",
//    "69d80829-c5c2-4fe0-93f7-c705c07c4e45",
//    "f62a78e7-3d32-4e1e-b847-64e3469d71d6",
//    "660bded8-6e1e-4e4e-bcfd-491ea7b4c63a",
//    "c958804b-fb89-44a5-b15e-273f86c45725",
//    "12e448ee-fb88-4a88-a3c2-ececad9275d2",
//    "24b84e35-27ef-4ea3-892b-f8442f30c773",
//    "9b8e0e93-5ac6-40c3-8ff2-c570d2d6d3a7",
//    "bb4b01a3-41d1-4215-b4e7-8813987a69e0",
//    "fd1606c5-07d5-41e1-afe5-e47f0df1a267",
//    "7ab46fa1-7875-4ac6-bd68-8d4fba26044c",
//    "75090a2d-24a1-444c-a35c-8fdccbe24f13",
//    "ee8b579e-0a9a-4c4d-b9ff-013ce6b9c46a",
//    "ce5d225f-248b-4b91-a9c9-681e20701906",
//    "d5a943e7-0eb5-453e-97f2-37660d54b981",
//    "e1c23b3e-4562-4c4f-b95d-e0cc3ab3a64d",
//    "be1a0489-ff5a-4b06-a593-431d60c432db",
//    "2e3d100e-c8de-4645-b4c7-9fbb8e0f8f1f",
//    "4f67a5f1-9f8c-405b-a56c-6a22fc59c09e",
//    "e5e1b98d-03be-446f-99b4-770b09966d6f",
//    "7d0e6e97-2a8d-4b71-b2cb-e118b79e790e",
//    "d96b2b85-07ed-47e5-97c4-48e9d5ae1c41",
//    "ca8037a2-274e-44ed-86d3-68b097c6d1d0",
//    "da0ebce1-4b7c-4be5-9e10-1888fa8d14ce",
//    "7bb3ae4e-1ef9-465f-9708-32b788ebfef1",
//    "c0e12fa3-25c3-40c3-a731-1d1d59d482d7",
//    "c9f155f3-bfd9-4a36-8e5f-4b2f60c1533c",
//    "89e75920-8802-464c-92cc-dcf9934ea5c6",
//    "0f3cecf1-50d0-403c-b8ed-abe4db6c105f",
//    "ef351b90-f7f5-4627-8604-b703bd76462e",
//    "f5943ab1-98e4-46fd-b738-b70b3b426d72",
//    "de76f5db-4f70-4177-8e62-c1e6833e5eb7",
//    "482d2a44-9588-49d2-b327-1e6a02c11f14",
//    "e95b44a7-79a5-4487-ae0b-eef2167a2fbe",
//    "0f79a7a2-e382-426f-a321-9f709a4f3efb",
//    "72d15c4c-3831-407f-86b7-fd37c366f9a3",
//    "702aab63-c9c1-4e2d-97a3-878b73e3273e",
//    "f2a3f35e-48fd-43c3-ae11-79d69ab2c6e5",
//    "6b4c41d8-54f3-42de-8188-cb65fc2a7e31"
  ];



$migrationRunner = new WaveProc($projectUuids);
$migrationRunner->runMigrations();
