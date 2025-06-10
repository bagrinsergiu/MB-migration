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
//    "13d842c6-85d4-4340-9cad-3725521e54c3",
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

    '56db701c-3fd7-4cab-98c9-06c4ee4a7531',
    'f70435bd-3767-4360-96c7-e61bb4a29247',
    'daaf536a-0c24-40fd-9505-5cb549246eeb',
    '2ae8474d-52a0-4ee0-a377-c047aaaed3f5',
    '54be916f-f812-48ea-8950-d86cdbebc3d6',
    'fd39f326-2113-46c0-9c87-f915f79346a3',
    'a1e8c044-852f-46a4-b053-92cd29a6e171',
    '05b9a003-43e1-4696-9c3c-a6ced9775233',
    'c0275f97-e258-4c6e-8b2b-8592cb93a7a4',
    'cdc22b55-6ce0-4029-98c8-ecaa14139afa',
    '0094ac3b-c72d-4cbb-b70d-65b2b99fe532',
    '5c8adaad-2991-4056-bd60-ab549a01559d',
    'f2da753c-062a-486c-ada8-b2d53f40cbae',
    '2a3b1e5e-baa6-4280-8b1c-87cc64db4526',
    'a04cb1fd-fe49-42a0-ba72-96a6583aa1f6',
    'dfd2ae25-7be5-4e31-a894-b089dfacfc7c',
    'df3513a9-4266-4b67-8e1f-1d1d3dd27797',
    '95564c8a-697b-49f7-aeea-c21d3ba79741',
    '4288ac06-1c14-4332-b8b3-ecab50d15d29',
    '3485f29c-20fe-404c-ba7c-e11e4938bbdf',
    '9c985d8f-e3b8-4aa4-bd23-08927aa3d3df',
    'df952b91-7b0c-4e86-a8ef-4e1e14c7ca17',
    '5fddbb6a-2d38-4a19-8902-81f1a38d73ca',
    '869998dd-5dd1-41dc-950d-9c2ecd6c453a',
    '2d5db396-e72a-4901-812d-13b4207c6463',
    '154bb15b-54fb-48bf-b1a4-8c2e56d9437f',
    '4378a010-b715-4656-ab8a-b4da32594f7a',
    'db1e1c82-db9d-4add-bf60-ee0c8eb20d89',
    '544fc4ee-b85e-4d6b-8db8-abc74f3145d3',
    'e8347e24-6f30-4e80-ab1f-0a57b173fac8',
    '20489b1b-eae1-4c38-bf56-4b41260656d2',
    'a9bde2e7-e41f-4458-a805-c77e4ac93282',
    '8fd0f246-4f02-4df8-8490-5ba525c071e9',
    'e12a294d-1ac0-47ac-bbc0-1e371763d3ff',
    '638204ba-4806-4eed-abd2-56ee6dd80673',
    'c49911d0-93ef-4771-a6ae-d7c5db757485',
    '15e8ca4a-79b0-48ff-9077-88ee7cb1936a',
    '9d0a6b75-01a4-451f-9267-ce8905ee73ef',
    'f27b8bfc-e212-4fd5-a3d1-7ceef4e8daec',
    '2e982fb6-f37b-47a6-9a42-b63c03aec982',
    'ebdc4d1f-799a-44a9-b32d-f346eba1cbba',
    '8127f8ae-7265-4c05-b459-cde62e6b0555',
    'ceb1c0a9-f882-43d2-9522-202551f7a0ce',
    '0a4a7ca9-60cd-4ecf-a11c-b2f5dc943079',
    '2a38f1fd-e7bc-4d8d-8f3b-1d36a6aef07e',
    '457bdc41-9046-42c5-b948-1d76497321d3',
    'd9509a98-d2b2-40ff-8581-57203fc30f00',
    '40627428-1a73-43a0-8c26-3fc321486bef',
    '6b0319e3-147d-4668-beda-511157923ed5',
    '9cc82e8b-36da-430a-954d-816e2e469d15',
    '535d8c53-bdb1-4dbd-a5d1-b70be9b37173',
    'db0a860c-da51-48d9-b32d-448d68104d81',
    '085f1162-f309-482f-9b44-f0be5534121b',
    'ce9de546-527d-4baa-beb9-2dcfab372fea',
    'f8392479-15cf-498f-9987-b4bdc0275fd9',
    '414752e9-9baf-48e2-baa6-4813f98ed086',
    '155eb054-606f-4747-8ab8-ed0875fb5418',
    'c40330de-acdd-4a44-a42d-086fd5d50a4d',
    'e222f0ac-71cd-4f69-a0ba-0ce166f2dc6a',
    '33a9a1c9-39c9-42f8-9419-e5ee27fcad76',
    'f19d4a44-386e-4a64-aced-77cb2a07e437',
    'd28f1fdf-52ed-40a4-bbee-a5957c979536',
    'a8875889-60f5-404f-bb22-b4a0ef4a7c35',
    '2bacd329-47ab-4ef6-8787-8d1a8efad40d',
    'c9ebc8d4-f2d2-4513-b1ef-f8dd47dfc368',
    'a84dd71a-c65d-4c61-a93f-fd68ed86912c',
    'f9641c08-f900-40cd-b9d5-dd04088ce0fd',
    '44fdad4f-01d1-4aab-86d9-28f15dc81bb5',
    '914aaf1b-365b-4448-9e00-943ee6a86f6d',
    '6cc1377a-e75d-4fe8-98e9-8247bf453178',
    '235494b3-22c3-441c-be74-cccd4761201b',
    '53cd6677-e5f2-4353-8ab7-f026ed50245e',
    'ec059f2a-fe5d-4491-a6e5-c4edeb57d0a8',
    '45e57e9e-d799-4da8-90f7-df92a3363d87',
    'fc5613e2-52b6-4d8d-a2dd-75ed14510751',
    '562eb631-eeb9-4d8e-b8b4-e68fa49020fc',
    '7bab7024-06f6-4b8f-8d79-2bb3ffc68827',
    'f306fd5d-7cc5-4af7-b5c9-c83d894bf931',
    '36c9e6c5-c3e4-49bf-9ecd-0ce2c6ab5710',
];


$migrationRunner = new WaveProc($projectUuids);
$migrationRunner->runMigrations();
