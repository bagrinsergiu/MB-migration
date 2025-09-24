<?php

namespace MBMigration;

use MBMigration\Core\Logger;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\DataSource\driver\MySQL;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

class mappingUtils
{
    private MySQL $DB;
    private BrizyAPI $brizyApi;

    public function __construct()
    {
        // Initialize logger to a local file for this utility
        Logger::initialize('', 'info', './create_migration_mapping.log');

        // Use same DB config as in CreateMigrationMapping for consistency
        $this->DB = (new MySQL(

        ))->doConnect();

        $this->brizyApi = new BrizyAPI();
    }

    /**
     * Process given list of MB project UUIDs: for each UUID that exists in DB mapping,
     * call setLabelManualMigration(true, brz_project_id) and setCloningLink(true, brz_project_id).
     * Returns a summary array.
     */
    public function process(array $projectUuids): array
    {
        $summary = [
            'total' => count($projectUuids),
            'found' => 0,
            'updated' => 0,
            'not_found' => [],
            'errors' => [],
        ];

        if (empty($projectUuids)) {
            Logger::instance()->warning('mappingUtils.process called with empty projectUuids');
            return $summary;
        }

        foreach ($projectUuids as $uuid) {
            try {
                $row = $this->DB->find('SELECT brz_project_id FROM migrations_mapping WHERE mb_project_uuid = ?', [$uuid]);
                $brzId = (int)($row['brz_project_id'] ?? 0);
                if ($brzId <= 0) {
                    $summary['not_found'][] = $uuid;
                    continue;
                }
                $summary['found']++;

                // Perform the two operations
                $this->brizyApi->setLabelManualMigration(true, $brzId);
                $this->brizyApi->setCloningLink(true, $brzId);
                $summary['updated']++;
            } catch (\Exception $e) {
                $summary['errors'][] = ['uuid' => $uuid, 'error' => $e->getMessage()];
                Logger::instance()->error('Failed to update project for UUID ' . $uuid . ': ' . $e->getMessage());
            }
        }

        Logger::instance()->info('mappingUtils.process finished', $summary);
        return $summary;
    }
}

$projectUuids = [
    'f9d24e4c-da93-4eaa-b8ac-634e33ddb9eb',
    '8ac4fbec-ca72-492b-a5b5-57ed3a98e59e',
    'a0967b20-3233-422e-92fb-6dadda04423a',
    '3a7f58f8-d578-4b1d-b6d0-a1bff7edb3e5',
    'bd356e40-e35a-450a-a0de-77b5cf30572f',
    '86a84f92-9194-407d-a4de-eed5857fb804',
    '39133869-325e-4c1c-91f4-050caec0bbb3',
    'de06812e-05bb-4d12-923a-900e7128639b',
    '8d80886c-63ea-4e83-a396-01af676dc965',
    '20031d71-aee5-4811-8ea3-036533d33dbe',
    '296bbed2-6d1e-4b6d-8a31-7a205f971a53',
    'a4c3e436-208f-4141-9d53-5998f94d5f24',
    '66d3033d-409c-48b6-8c0d-6093edaa7c6f',
    'e2484855-f39d-43ac-8a5a-9b69d5357b5d',
    'c1b34224-2fbe-4589-82cf-b052f183299e',
    '882b229e-c794-49a9-8d61-e00f449e5d6c',
    '7eb17c64-26bc-4854-847c-96e5bc6242b8',
    'a76b4d00-9566-401b-adae-8592d361a89a',
    '1426243e-f674-4a06-acd5-9409857e13dd',
    'c97efe85-0a8c-479f-8247-8dba67c4e6ca',
    '40b7d076-8f77-4f75-bef1-d1ab11b639fe',
    '16ddaa78-8ab2-4b5d-8b2e-8d4a34d02376',
    '2fdd484d-7f39-4342-97e9-bdcdde48b067',
    'bc8c1c58-09fb-4b6f-862e-c0b12692c614',
    'f8d63f93-82ac-4e89-961e-1e48e72931d8',
    '380eb312-7e82-4ee5-b705-1fc92fdd189f',
    '32ba187e-9f8a-46e9-b010-ff79e4fac7d6',
    '41b5d92c-08ad-4f0a-95b2-c19079689e2a',
    '0175fe9e-ae7f-4b9b-b5cd-419ab19cedb5',
    'ea675c5b-f292-48b7-9cd0-9db068026e42',
    '66ac2b1b-a5a4-4c00-88be-292b34c750e7',
    'c59f57f9-0d37-4889-8708-3b64c5529e40',
    'b18085b0-6409-4205-b8e2-eeb79c00274f',
    '6d514fef-e9c9-4809-86e4-4c39ec78cd96',
    '5032c670-f379-4757-9035-1ab4aa194a17',
    '83df7503-7ff7-45f4-b067-c51649192502',
    'ded4e164-7243-4313-a960-049672fad556',
    'e1a8c650-0b5d-41d6-9bda-ad0223b55b32',
    '8c186a07-984f-4893-b0e0-d4a21c64a977',
    '1d5a09ae-231b-4ba4-ab37-b43dea01dfb0',
    '3c8e9ea0-160c-4ba1-8bfe-ef1614c1449a',
    '0fa6c9db-47a2-433a-b641-9b168291787f',
    'd520e09a-c50d-4277-a19a-1c7db267c063',
    '8201804d-12a6-45c2-825c-90d4026ae159',
    '40b9943d-01f0-4de8-8383-8a42be3b429e',
    'e2e8fb09-421d-4fb6-94a6-b77112c09297',
    '2d82a56a-5699-4b4c-ac7c-55ac6fbbaebe',
    'b56b45d8-c631-44a5-b6b8-0f57200cf312',
    'b545e7b6-0633-41cd-8cca-3137c3711538',
    'caf25238-09eb-42cd-a21b-fc0064ea8cd2',
    'b077eacf-b402-445f-bcad-fdbdaa486dd3',
    '2da5f7ef-f69f-4c7a-9cb1-a609a2445283',
    '0d6b4eb1-5abd-4481-8212-a5fa80631423',
    '942f9346-2590-4167-a1b3-17ff9d8aa875',
    '2cafa09f-2403-465c-aa70-48102a8b1faf',
    '69260a30-f1ca-48c0-80ed-9bf6b67543d3',
    'c194cf2a-514c-42e2-8497-1b25a99462a4',
    'dce18e65-7635-4000-9fc5-650e86f17959',
    '20c26c35-0d2e-4eb2-9949-8c0aa477fb60',
    '75659c40-f64c-4ef7-a802-893a31b851dc',
    '82f74545-a214-4a1e-91ef-c6446d797708',
    '76e46fcb-e1f5-42d0-b3c3-af67cb283433',
    'eb1f90f1-12b1-4249-87c8-9afb77c09471',
    'c8e9f7bd-ca2a-410e-b34c-a53c5c8d55a0',
    'fcde9149-39c5-4859-9d95-bb93aac81543',
    'da866f9a-e69a-4360-9073-1bb5e80a7c22',
    '0aee517d-2095-42ea-bcc8-997e2d34f95c',
    'ce921800-d0a9-45f4-b5cd-ffe74821ebd0',
    'b383d17e-8c2f-4b8b-83d0-3162394f6d54',
    '5505dd93-29ba-4396-885a-77046830a83c',
    '02f3bcd4-f733-427a-b4e7-6e339a65392b',
    '5cc0413f-fd0e-4d3c-a468-23d8267a93f4',
    '5f373a9f-8895-4859-9cd4-67bc0846873c',
    '441f3776-0a7f-4fcf-a317-2db27f29d575',
    'ecc8263c-d559-4763-88a7-fa9deb9f1477',
    'fa00dedb-d72c-4755-891c-acbce158b1f8',
    'dfedc3e3-1842-454d-8cae-4cabdbb4c600',
    'ae9b0e2b-bfd6-4152-b560-07814a3ad0fa',
    'd2dca7a2-747f-491d-8130-8f3c606da81c',
    'a1bda5eb-a380-45ab-a5de-109763817ad3',
    'a3e00c28-4ea0-4cb8-a59f-743956cfc427',
    'eed1c7c9-11b0-4a19-8c88-e6bbd1ef450e',
    'd63d76ff-2ce9-4a9f-96d7-944b724b639d',
    '6d460699-7623-477b-81a3-0ca21f4e5bb6',
    '5c5700e3-29fa-494a-910a-e58500501c52',
    'ff1ac5ee-8445-4ec7-bee6-184a3aeef05f',
    '5f16dfee-448c-45b5-90cd-f0f39f031066',
    '9d1226b2-f26b-43ca-b53b-f8cc55c4d2be',
    'ca295f8e-1fd9-4eea-871f-7a3092b419ea',
    '05172b13-c198-48f7-8fa6-132ed5a09180',
    '5a4ffc37-45a3-4688-a74d-b5fa704c33f4',
    'd448023c-6452-4063-8830-f0ccff4fbb61',
    '187532aa-87ec-46ee-86a4-844db7c41207',
    '7e7b0be0-eb9c-4ac3-8c0d-6d789a5f97a9',
    '8fcd3700-d6d3-40fa-bb22-b86181046197',
    'aa505843-4b1f-4743-acf2-88ea2b33a1f9',
    'cdd7bd44-4444-4c05-9487-be680bc37674',
    '9bb65a53-bb58-4eca-99f5-039d88f63d9b',
    '33f778d8-9117-49ff-a70b-b1197f29d148',
    '053517c5-ff49-4033-9173-bbb2d2cb523a',
    'b879b901-8ed2-4081-b53c-881283c27730',
    '4d15221b-d9fd-46e7-b312-148c6b6c3229',
    '1a3768d3-91c2-4f55-ba0e-73e2f433725e',
    'f6013e73-ff38-4561-af82-4aa7281ffb9e',
    'cf3cc6b7-ce06-43d3-9091-05b9d4c9d219',
    '5f60ecb7-bf86-4629-b7e6-7b794909fb26',
    'abc65808-9739-4afb-8e8c-3113ebfd9fba',
    '6286fdef-dd1d-4e41-b3c1-628daf908bd4',
    '0dea34b6-1bf2-4390-99c4-0cfb9b902626',
    '991a977e-29e1-4352-b193-d4a39de47175',
    'a521b988-4e30-4d48-9912-f7f5ecf463f3',
    'cd1becd3-b1bb-4626-b41e-67053ff8ba68',
    '6bef16eb-a48c-4300-a03f-c93db5053138',
    '763db47d-19eb-456a-9515-30e97f759a5f',
    '6d8337ee-086c-4ff1-80fe-21143b94fa34',
    '67329f90-9fc2-49eb-a891-931d33a7b615',
    'c7a61c37-e69d-45c7-a833-9f66745527bb',
    'fe021944-a370-452a-8aca-9fb17ca97c49',
    '188a506a-9d49-4854-bc09-ca501d7f8448',
    '1a519826-9ff0-4243-a432-0615bde047d4',
    'd40097a6-0ac5-4b6a-8521-e7124c8fb412',
    '05d5ca09-ff07-49f1-bd1b-14206f09b6f4',
    '4ce903f0-7792-4474-b296-61e484d411b9',
    'd371bc1e-f883-4c15-990e-4c18ce62a469',
    'ba7a0be6-4b0f-46fb-91f3-fa09e464e917',
    'd9f8ec43-1930-4f1c-a6c7-fafbfc608637',
    '6d566290-cd68-44c5-b04b-a5648e9ab726',
    '9c0186f8-fb6d-4660-aeed-9f1adccefeb7',
    'be72bbaa-d432-4cd0-a09c-c5e6497fc98a',
    'eb8e326c-f4dc-45dd-b515-d796fe215fc3',
    '966c6f89-3308-4b83-90de-1b865952cfdf',
    '9c999b39-1aab-4976-b2bf-a1fedcdde5aa',
    '51daddaa-9de0-4e60-a5d9-9d23383cd758',
    '2bdbe849-44ca-43d6-b5cd-d436cb6ea4bc',
    '16cc1830-82bf-42af-b894-b15e8f952090',
    'c24d54fc-a102-47ee-afad-fda2b7c3f45f',
    'e87d8835-444b-4834-bcd9-4f9eb1220822',
    '7c3a4a9a-fc74-4d00-b713-342979f368d0',
    'a73d64b1-b5cc-4da6-bbf1-b328255a199e',
    'cb71f45e-9414-4235-a3ba-925c1469c0aa',
    '6d1f2d32-060c-44b5-a19c-f5ef8437c0a2',
    'dc6bcad3-d527-4dee-bf12-36e890392d7b',
    'b3dfc62d-698e-47d2-a512-1eb174c57e4d',
    'a5f2f244-af93-4090-ab38-fe4c897cc7ac',
    '17920aaf-2f3e-426f-bb36-3f952ea414de',
    'bfc4283b-dd35-42ae-8f04-7a0dc6186b68',
    '27cf166f-bc2c-4c85-9df6-d660dd97ab90',
    'd3369f4a-e2d4-468f-9f0c-d1d405302749',
    '60a45405-3722-4542-95d8-ed9ff7c3f298',
    'dc4c7326-0296-499e-ace2-259f548a40bd',
    '4bbc6f20-1bb7-43a3-bf5c-e14eef034d99',
    '7653c780-74a8-46d9-b5c3-d88c5dd3b687',
];


$util = new mappingUtils();

$util->process($projectUuids);
