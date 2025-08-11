<?php

namespace MBMigration\Bridge;

use DateTime;
use Exception;
use MBMigration\ApplicationBootstrapper;
use MBMigration\Core\Config;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\DataSource\driver\MySQL;
use MBMigration\Layer\HTTP\RequestHandlerDELETE;
use MBMigration\Layer\HTTP\RequestHandlerGET;
use MBMigration\Layer\HTTP\RequestHandlerPOST;
use MBMigration\Layer\MB\MBProjectDataCollector;
use MBMigration\MigrationRunnerWave;
use MBMigration\WaveProc;
use Symfony\Component\HttpFoundation\Request;

class Bridge
{
    private Config $config;
    private MgResponse $mgResponse;
    private string $sourceProject;
    private Request $request;
    private array $allList = [];
    private int $preparedProject;
    private MySQL $db;
    private RequestHandlerGET $GET;
    private RequestHandlerPOST $POST;
    private RequestHandlerDELETE $DELETE;
    private array $listReport;
    private ApplicationBootstrapper $app;

    public function __construct(
        ApplicationBootstrapper $app,
        Config                  $config,
        Request                 $request
    )
    {
        $this->listReport = [];
        $this->app = $app;
        $this->config = $config;
        $this->request = $request;

        $this->GET = new RequestHandlerGET($request);
        $this->POST = new RequestHandlerPOST($request);
        $this->DELETE = new RequestHandlerDELETE($request);

        $this->mgResponse = new MgResponse();

        $this->db = $this->doConnectionToDB();
    }

    public function checkPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->GET->checkInputProperties(['source_project_id']);

            switch ($this->request->getMethod()) {
                case 'GET':
                    $this->preparedSearchByUUID($inputProperties['source_project_id']);
                    break;
            }

        } catch (\Exception $e) {
            $this->prepareResponseMessage($e->getMessage(), 'error', 400);
        }

        return $this;
    }

    public function addPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id', 'source_project_id', 'meta_data']);
        } catch (Exception $e) {
            $this->prepareResponseMessage($e->getMessage(), 'error', $e->getCode());
            return $this;
        }

        $result = $this->insertMigrationMapping($inputProperties['brz_project_id'], $inputProperties['source_project_id'], json_encode($inputProperties['meta_data']));

        $this->prepareResponseMessage(
            [
                'brz_project_id' => (int)$inputProperties['brz_project_id'],
                'source_project_id' => $inputProperties['source_project_id']
            ],
            'message');

        return $this;
    }

    public function addALLPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->POST->checkInputProperties(['list']);
        } catch (Exception $e) {
            $this->prepareResponseMessage(
                $e->getMessage(),
                'error',
                $e->getCode());
            return $this;
        }

        $inputProperties = $inputProperties['list'];

        foreach ($inputProperties as $value) {
            if (empty($value['brz_project_id']) || empty($value['source_project_ids'])) {
                $this->prepareResponseMessage('Value is not valid or empty. brz_project_id and source_project_id are required.',
                    'error',
                    404
                );
                return $this;
            }
        }

        $returnList = [];

        foreach ($inputProperties as $value) {
            $insertResult = $this->insertMigrationMapping(
                $value['brz_project_id'],
                $value['source_project_ids'],
                json_encode($value['changes_json'] ?? [])
            );

            if (empty($insertResult)) {
                $value['message'] = 'Potential insert error.';
            }
            $returnList[] = $value;
        }

        $this->prepareResponseMessage($returnList);

        return $this;
    }

    public function getSourceProject()
    {
        return $this->sourceProject;
    }

    public function setSourceProject($sourceProject)
    {
        $this->sourceProject = $sourceProject;

        return $this;
    }

    private function doConnectionToDB(): MySQL
    {
        $PDOconnection = new MySQL(
            Config::$mgConfigMySQL['dbUser'],
            Config::$mgConfigMySQL['dbPass'],
            Config::$mgConfigMySQL['dbName'],
            Config::$mgConfigMySQL['dbHost'],
        );

        return $PDOconnection->doConnect();
    }

    public function getMessageResponse(): MgResponse
    {
        return $this->mgResponse;
    }

    public function waveMigration(array $projectUuids, int $batchSize = 3)
    {
        try {
            $muuid = time() + random_int(999, 100000);

            $waveProc = new WaveProc($projectUuids, $this->db, $batchSize, $muuid);
            $waveProc->runMigrations();

            $this->mgResponse
                ->setMessage('migrated successfully')
                ->setStatusCode(200);

        } catch (\Exception $e) {
            $this->mgResponse
                ->setMessage($e->getMessage(), 'error')
                ->setStatusCode(400);
        }
    }


    public function getPreparedMappingList(): Bridge
    {
        try {
            $allList = $this->db->getAllRows('SELECT * FROM migrations_mapping');

            foreach ($allList as $value) {
                $this->allList[(int)$value['brz_project_id']] = $value['mb_project_uuid'];
            }

            $this->mgResponse
                ->setMessage($this->allList)
                ->setStatusCode(200);

        } catch (\Exception $e) {
            $this->mgResponse
                ->setMessage($e->getMessage(), 'error')
                ->setStatusCode(200);
        }

        return $this;
    }

    public function mappingList()
    {
        $getMethod = $this->request->getMethod();

        switch ($this->request->getMethod()) {
            case 'GET':
                return $this->getPreparedMappingList()
                    ->getMessageResponse();
            case 'POST':
                return $this->addPreparedProject()
                    ->getMessageResponse();
            case 'DELETE':
                return $this->delPreparedProject()
                    ->getMessageResponse();
        }
        return $this->getMessageResponse();
    }

    public function prepareResponseMessage($body, $type = 'value', $code = 200): void
    {
        $this->mgResponse
            ->setMessage($body, $type)
            ->setStatusCode($code);
    }

    public function insertMigrationMapping($brz_project_id, $source_project_id, $mata_data = '{}', $table = 'MG_prepare_mapping.migration_list_w9')
    {
        try {
            return $this->db->insert('migrations_mapping',
                [
                    'brz_project_id' => (int)$brz_project_id,
                    'mb_project_uuid' => $source_project_id,
                    'changes_json' => $mata_data
                ]);
        } catch (\Exception $e) {
            $this->prepareResponseMessage($e->getMessage(), 'error', 400);
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function checkPageChanges($mbProjectId, array $pageList): bool
    {
        try {
            $mgr_mapping = $this->searchMappingByUUID($mbProjectId);

            if (!empty($mgr_mapping['changes_json'])) {
                $changes_json = json_decode($mgr_mapping['changes_json'], true);
                if (empty($changes_json)) {
                    $changes_json = ['data' => date('Y-m-d')];
                } elseif (!isset($changes_json['data'])) {
                    $changes_json = ['data' => date('Y-m-d')];
                }
            } else {
                $changes_json = ['data' => date('Y-m-d')];
            }

            $this->checkProjectPageChanges($pageList, $changes_json['data'], $this->listReport);

            return true;
        } catch (\Exception $e) {
            $this->mgResponse
                ->setMessage($e->getMessage(), 'error')
                ->setStatusCode(400);
            return false;
        }
    }

    private function checkProjectPageChanges(array $dataPage, string $snapShotDate, &$listReport)
    {
        foreach ($dataPage as $page) {

            $result = $this->compareDate($page['updated_at'], $snapShotDate);

            if ($result) {
                $listReport[$page['slug']] = $page['updated_at'];
            }

            if (isset($page['child'])) {
                $this->checkProjectPageChanges($page['child'], $snapShotDate, $listReport);
            }
        }
    }

    function compareDate($projectDate, $snapShotDate): bool
    {
        try {
            $projectDate = new DateTime($projectDate);
            $snapShotDate = new DateTime($snapShotDate);

            $date1Only = $projectDate->format('Y-m-d');
            $date2Only = $snapShotDate->format('Y-m-d');

            if ($date1Only === $date2Only) {

                return true;
            }

            return !(($date1Only < $date2Only));
        } catch (\Exception $e) {

            return false;
        }
    }

    /**
     * @throws Exception
     */
    private function searchByUUID(string $inputProperties): int
    {
        try {
            $brzID = $this->db->find('SELECT brz_project_id FROM migrations_mapping WHERE mb_project_uuid = ?', [$inputProperties]);

            if (empty($brzID['brz_project_id'])) {
                throw new Exception('Project not found', 400);
            }

            return (int)$brzID['brz_project_id'];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    private function searchMappingByUUID(string $inputProperties): array
    {
        try {
            $mapping = $this->db->find('SELECT * FROM migrations_mapping WHERE mb_project_uuid = ?', [$inputProperties]);

            if (empty($mapping['brz_project_id'])) {
                throw new Exception('Project not found', 400);
            }

            return $mapping;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    private function preparedSearchByUUID($source_project_id)
    {
        try {
            $resultBrzId = $this->searchByUUID($source_project_id);

            $this->mgResponse
                ->setMessage($resultBrzId)
                ->setStatusCode(200);
        } catch (\Exception $e) {
            $this->mgResponse
                ->setMessage($e->getMessage(), 'error')
                ->setStatusCode(400);
        }
    }

    public function getReportPageChanges(): array
    {
        return $this->listReport;
    }

    public function addAllMappingList(): MgResponse
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                $this->mgResponse
                    ->setMessage('Input method handler was not found', 'error')
                    ->setStatusCode(404);
                break;
            case 'POST':
                return $this->addALLPreparedProject()
                    ->getMessageResponse();
        }
        return $this->getMessageResponse();
    }

    private function delPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->DELETE->checkInputProperties(['id']);
        } catch (Exception $e) {
            $this->prepareResponseMessage(
                $e->getMessage(),
                'error',
                $e->getCode());
            return $this;
        }

        $inputProperties = $inputProperties['id'];
        $returnList = [];

        foreach ($inputProperties as $value) {
            try {
                if (!$this->db->delete('migrations_mapping', 'id = ?', [(int)$value])) {
                    $value['value'] = $value;
                    $value['message'] = 'Potential delete error.';
                }
                $returnList[] = $value;
            } catch (\Exception $e) {
                $this->prepareResponseMessage($e->getMessage(), 'error', 400);
                return $this;
            }
        }
        $this->prepareResponseMessage($returnList);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function runMigration(): Bridge
    {
        $mgr_manual = (int)$this->request->get('mgr_manual');

        $mb_project_uuid = $this->request->get('mb_project_uuid');
        if (!isset($mb_project_uuid)) {
            throw new Exception('Invalid mb_project_uuid', 400);
        }

        $brz_project_id = $this->request->get('brz_project_id');
        if (!isset($brz_project_id)) {
            throw new Exception('Invalid brz_project_id', 400);
        }

        $brz_workspaces_id = (int)$this->request->get('brz_workspaces_id') ?? 0;
        $mb_page_slug = $this->request->get('mb_page_slug') ?? '';

        if (!$mgr_manual) {
            $mgr_manual = false;
        } else {
            $mgr_manual = true;
        }

        // Check if the project was manually migrated
        $brizyAPI = new BrizyAPI();
        $isManuallyMigrated = $brizyAPI->checkProjectManualMigration($brz_project_id);

        if ($isManuallyMigrated) {
            // Scenario 1: Project was manually migrated
            try {
                // Get the project ID from the UUID
                try {
                    $projectId = MBProjectDataCollector::getIdByUUID($mb_project_uuid);
                } catch (Exception $e) {
                    throw new Exception('Failed to get project ID: ' . $e->getMessage(), 400);
                }

                // Create an instance of MBProjectDataCollector to work independently
                $mbProjectDataCollector = new MBProjectDataCollector($projectId);

                // Get the project data
                $projectPages = $mbProjectDataCollector->getPages();

                if (empty($projectPages)) {
                    throw new Exception('No pages found for the project', 400);
                }

                // Get the migration date from the mapping
                $mgr_mapping = $this->searchMappingByUUID($mb_project_uuid);
                $migrationDate = date('Y-m-d');

                if (!empty($mgr_mapping['changes_json'])) {
                    $changes_json = json_decode($mgr_mapping['changes_json'], true);
                    if (!empty($changes_json) && isset($changes_json['data'])) {
                        $migrationDate = $changes_json['data'];
                    }
                }

                // Filter pages that were modified after the migration date
                $modifiedPages = [];
                $this->filterModifiedPages($projectPages, $migrationDate, $modifiedPages);

                // Format the response
                $formattedPages = [];
                foreach ($modifiedPages as $slug => $date_updated) {
                    $formattedPages[] = [
                        'slug' => $slug,
                        'date_updated' => $date_updated
                    ];
                }

                // Return the response
                $this->prepareResponseMessage([
                    'success' => true,
                    'pages' => $formattedPages
                ]);

                return $this;
            } catch (Exception $e) {
                $this->prepareResponseMessage([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 'error', 400);

                return $this;
            }
        } else {
            // Scenario 2: Default migration flow
            $result = $this->app->migrationFlow(
                $mb_project_uuid,
                $brz_project_id,
                $brz_workspaces_id,
                $mb_page_slug,
                false,
                $mgr_manual
            );

            if (!empty($result['mMigration']) && $result['mMigration'] === true) {
                $projectUUID = $this->app->getProjectUUDI();
                $pageList = $this->app->getPageList();

                if ($this->checkPageChanges($projectUUID, $pageList)) {
                    // to do, ned add return project details
                    $this->prepareResponseMessage([
                        'projectId' => $brz_project_id,
                        'uuid' => $projectUUID,
                        'report' => $this->getReportPageChanges()
                    ]);
                } else {
                    $result = $this->app->migrationFlow(
                        $mb_project_uuid,
                        $brz_project_id,
                        $brz_workspaces_id,
                        $mb_page_slug,
                        true,
                        $mgr_manual
                    );
                    $result['mgrClone'] = 'failed';
                    $this->prepareResponseMessage($result);
                }
            } else {
                if ($mgr_manual) {
                    $this->insertMigrationMapping(
                        $result['brizy_project_id'],
                        $result['mb_uuid'],
                        json_encode(['data' => $result['date']])
                    );
                }

                $this->prepareResponseMessage($result);
            }

            return $this;
        }
    }

    /**
     * Helper method to filter pages that were modified after the migration date
     *
     * @param array $pages The list of pages to check
     * @param string $migrationDate The date of the migration
     * @param array &$modifiedPages Reference to the array that will hold modified pages
     */
    private function filterModifiedPages(array $pages, string $migrationDate, array &$modifiedPages): void
    {
        foreach ($pages as $page) {
            if (isset($page['updated_at'])) {
                $result = $this->compareDate($page['updated_at'], $migrationDate);

                if ($result) {
                    $modifiedPages[$page['slug']] = $page['updated_at'];
                }
            }

            if (isset($page['child']) && !empty($page['child'])) {
                $this->filterModifiedPages($page['child'], $migrationDate, $modifiedPages);
            }
        }
    }

    public function migrationWave(): MgResponse
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                $this->mgResponse
                    ->setMessage('Input method handler was not found', 'error')
                    ->setStatusCode(404);
                break;
            case 'POST':
                return $this->runMigrationWave()
                    ->getMessageResponse();
        }
        return $this->getMessageResponse();
    }

    private function runMigrationWave(): Bridge
    {
        try {
            $inputProperties = $this->POST->checkInputProperties(['list_uuid', 'workspaces', 'batchSize', 'mgrManual']);
        } catch (Exception $e) {
            $this->prepareResponseMessage(
                $e->getMessage(),
                'error',
                $e->getCode());
            return $this;
        }

        $this->waveMigration($inputProperties['list_uuid']);

//        $migrationRunner = new MigrationRunnerWave($this->app, $this, $inputProperties['list_uuid'], $inputProperties['workspaces'], $inputProperties['batchSize'], $inputProperties['mgrManual']);
//        $migrationRunner->runMigrations();

//        $this->prepareResponseMessage('migrationWave completed');

        return $this;
    }

    public function clearWorkspace(): Bridge
    {
        $brizyApi = new BrizyAPI();

        $result = $brizyApi->getAllProjectFromContainer(0);

        foreach ($result as $value) {
            $brizyApi->deleteProject($value['id']);
        }

        $this->prepareResponseMessage(
            "Workspace cleared",
            'message'
        );
        return $this;
    }

    public function addTagManualMigrationFromDB(): Bridge
    {
        $brizyApi = new BrizyAPI();

        $result = $this->db->getAllRows('SELECT * FROM migrations_mapping WHERE `ignore` = 0');

        try {
            foreach ($result as $value) {
                $brizyApi->setLabelManualMigration(true, (int)$value['brz_project_id']);

            }
        } catch (\Exception $e) {

            $sas = $e->getMessage();
        }

        $this->prepareResponseMessage(
            "Workspace cleared",
            'message'
        );
        return $this;
    }

    public function delTagManualMigration(): MgResponse
    {
        $brizyApi = new BrizyAPI();

        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id']);
            $brizyApi->setLabelManualMigration(false, (int)$inputProperties['brz_project_id']);

            $this->prepareResponseMessage(
                "Tag deleted",
                'message'
            );
        } catch (\Exception $e) {
            $this->prepareResponseMessage(
                $e->getMessage(),
                'error',
                404
            );
        }

        return $this->getMessageResponse();
    }

    public function addTagManualMigration(): MgResponse
    {
        $brizyApi = new BrizyAPI();

        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id']);
            $brizyApi->setLabelManualMigration(true, (int)$inputProperties['brz_project_id']);

            $this->prepareResponseMessage(
                "Tag set",
                'message'
            );
        } catch (\Exception $e) {
            $this->prepareResponseMessage(
                $e->getMessage(),
                'error',
                404
            );
        }

        return $this->getMessageResponse();
    }

    public function setCloningLincMigration(): MgResponse
    {
        $brizyApi = new BrizyAPI();

        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id']);
            $brizyApi->setCloningLink(true, (int)$inputProperties['brz_project_id']);

            $this->prepareResponseMessage(
                "Tag set",
                'message'
            );
        } catch (\Exception $e) {
            $this->prepareResponseMessage(
                $e->getMessage(),
                'error',
                404
            );
        }

        return $this->getMessageResponse();
    }

    public function mApp_2(): MgResponse
    {
        //$this->addTagManualMigration();
        $brizyApi = new BrizyAPI();

        $dir1 = dirname(__DIR__) . '/../../public/migration_results_mapping.json';
//        $dir2 = dirname(__DIR__) . '/../../public/migration_results_08-05-2025_22.json';

        $fileCont1 = array_merge(
            json_decode(file_get_contents($dir1), true),
//            json_decode(file_get_contents($dir2), true)
        );
        try {
            foreach ($fileCont1['list'] as $key => $value) {
                if (empty($value['brz_project_id'])) {
                    continue;
                }

                $brizyApi->setCloningLink(true, (int)$value['brz_project_id']);

//                $result = $this->insertMigrationMapping($value['brizy_project_id'], $key, json_encode(['data' => '2025-05-13']));
            }

            $eee = 12;
        } catch (\Exception $e) {

            $Eee = $e->getMessage();
        }

        $this->prepareResponseMessage(
            "List added",
            'message'
        );

        return $this->getMessageResponse();
    }

    public function mApp(): MgResponse
    {
        //$this->addTagManualMigration();
        $brizyApi = new BrizyAPI();

        $dir1 = dirname(__DIR__) . '/../../public/migration_results_mapping.json';
//        $dir2 = dirname(__DIR__) . '/../../public/migration_results_08-05-2025_22.json';

        $fileCont1 = array_merge(
            json_decode(file_get_contents($dir1), true),
//            json_decode(file_get_contents($dir2), true)
        );
        try {
            foreach ($fileCont1['list'] as $key => $value) {
                if (empty($value['brz_project_id'])) {
                    continue;
                }

//                $brizyApi->setLabelManualMigration(true, (int)$value['brz_project_ids']);

                $brizyApi->setCloningLink(true, (int)$value['brz_project_id']);


//                $result = $this->insertMigrationMapping($value['brizy_project_id'], $key, json_encode(['data' => '2025-05-13']));
            }

            $eee = 12;
        } catch (\Exception $e) {

            $Eee = $e->getMessage();
        }

        $this->prepareResponseMessage(
            "List added",
            'message'
        );

        return $this->getMessageResponse();
    }

    public function doCloningProjects(): MgResponse
    {

        $brizyApi = new BrizyAPI();

        $inputProperties = $this->POST->checkInputProperties(['brz_project_id', 'workspace']);

        $brizyApi->cloneProject($inputProperties['brz_project_id'], $inputProperties['workspace']);

        $this->prepareResponseMessage(
            "Cloning completed",
            'message'
        );

        return $this->getMessageResponse();

    }

    public function checkAllProjectChanges(): MgResponse
    {
        try {
            $result = $this->db->getAllRows('SELECT * FROM migrations_mapping WHERE `ignore` = 0');

            $allMigrationProjects = [];

            foreach ($result as $migrations) {
                try {
                    $projectId = MBProjectDataCollector::getIdByUUID($migrations['mb_project_uuid']);

                    $mbProjectDataCollector = new MBProjectDataCollector($projectId);

                    $projectPages = $mbProjectDataCollector->getPages();


                    $migrationDate = date('Y-m-d');

                    if (!empty($migrations['changes_json'])) {
                        $changes_json = json_decode($migrations['changes_json'], true);
                        if (!empty($changes_json) && isset($changes_json['data'])) {
                            $migrationDate = $changes_json['data'];
                        }
                    }

                    $modifiedPages = [];
                    $this->filterModifiedPages($projectPages, $migrationDate, $modifiedPages);

                    $allMigrationProjects[$migrations['mb_project_uuid']] = $modifiedPages;

                } catch (Exception $e) {
                    throw new Exception('Failed to get project ID: ' . $e->getMessage(), 400);
                }
            }

            $this->prepareResponseMessage(
                $allMigrationProjects,
            );

        } catch (\Exception $e) {
            $eww = $e->getMessage();
        }


        return $this->getMessageResponse();
    }
}
