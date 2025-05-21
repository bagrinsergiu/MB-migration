<?php

namespace MBMigration\Bridge;

use Exception;
use MBMigration\ApplicationBootstrapper;
use MBMigration\Bridge\Interfaces\DatabaseManagerInterface;
use MBMigration\Bridge\Interfaces\MappingManagerInterface;
use MBMigration\Bridge\Interfaces\MigrationExecutorInterface;
use MBMigration\Bridge\Interfaces\PageChangeDetectorInterface;
use MBMigration\Bridge\Interfaces\ResponseHandlerInterface;
use MBMigration\Core\Config;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\HTTP\RequestHandlerDELETE;
use MBMigration\Layer\HTTP\RequestHandlerGET;
use MBMigration\Layer\HTTP\RequestHandlerPOST;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bridge class that coordinates between different components
 */
class Bridge
{
    private Config $config;
    private Request $request;
    private RequestHandlerGET $GET;
    private RequestHandlerPOST $POST;
    private RequestHandlerDELETE $DELETE;
    private ApplicationBootstrapper $app;

    // Service classes
    private DatabaseManagerInterface $databaseManager;
    private MappingManagerInterface $mappingManager;
    private ResponseHandlerInterface $responseHandler;
    private PageChangeDetectorInterface $pageChangeDetector;
    private MigrationExecutorInterface $migrationExecutor;

    public function __construct(
        ApplicationBootstrapper      $app,
        Config                       $config,
        Request                      $request,
        DatabaseManagerInterface     $databaseManager,
        ResponseHandlerInterface     $responseHandler,
        MappingManagerInterface      $mappingManager,
        PageChangeDetectorInterface  $pageChangeDetector,
        MigrationExecutorInterface   $migrationExecutor
    )
    {
        $this->app = $app;
        $this->config = $config;
        $this->request = $request;

        $this->GET = new RequestHandlerGET($request);
        $this->POST = new RequestHandlerPOST($request);
        $this->DELETE = new RequestHandlerDELETE($request);

        // Set service classes
        $this->databaseManager = $databaseManager;
        $this->responseHandler = $responseHandler;
        $this->mappingManager = $mappingManager;
        $this->pageChangeDetector = $pageChangeDetector;
        $this->migrationExecutor = $migrationExecutor;
    }

    /**
     * Check if a project is prepared for migration
     *
     * @return Bridge
     */
    public function checkPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->GET->checkInputProperties(['source_project_id']);

            switch ($this->request->getMethod()) {
                case 'GET':
                    try {
                        $brzProjectId = $this->mappingManager->findBrizyIdBySourceId($inputProperties['source_project_id']);
                        $this->responseHandler->success($brzProjectId);
                    } catch (Exception $e) {
                        $this->responseHandler->error($e->getMessage(), 400);
                    }
                    break;
            }
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 400);
        }

        return $this;
    }

    /**
     * Add a prepared project mapping
     *
     * @return Bridge
     */
    public function addPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id', 'source_project_id', 'meta_data']);

            $this->mappingManager->insertMapping(
                (int)$inputProperties['brz_project_id'],
                $inputProperties['source_project_id'],
                json_encode($inputProperties['meta_data'])
            );

            $this->responseHandler->success([
                'brz_project_id' => (int)$inputProperties['brz_project_id'],
                'source_project_id' => $inputProperties['source_project_id']
            ], 200);
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), $e->getCode() ?: 400);
        }

        return $this;
    }

    /**
     * Add multiple prepared project mappings
     *
     * @return Bridge
     */
    public function addALLPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->POST->checkInputProperties(['list']);
            $inputProperties = $inputProperties['list'];

            foreach ($inputProperties as $value) {
                if (empty($value['brz_project_id']) || empty($value['source_project_id'])) {
                    $this->responseHandler->error(
                        'Value is not valid or empty. brz_project_id and source_project_id are required.',
                        404
                    );
                    return $this;
                }
            }

            $returnList = [];

            foreach ($inputProperties as $value) {
                try {
                    $this->mappingManager->insertMapping(
                        (int)$value['brz_project_id'],
                        $value['source_project_id'],
                        json_encode($value['changes_json'] ?? [])
                    );
                } catch (Exception $e) {
                    $value['message'] = 'Potential insert error: ' . $e->getMessage();
                }
                $returnList[] = $value;
            }

            $this->responseHandler->success($returnList);
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), $e->getCode() ?: 400);
        }

        return $this;
    }

    /**
     * Get the response object
     *
     * @return MgResponse
     */
    public function getMessageResponse(): MgResponse
    {
        return $this->responseHandler->getResponse();
    }

    /**
     * Get the list of prepared mappings
     *
     * @return Bridge
     */
    public function getPreparedMappingList(): Bridge
    {
        try {
            $mappings = $this->mappingManager->getAllMappings();
            $this->responseHandler->success($mappings);
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 400);
        }

        return $this;
    }

    /**
     * Handle mapping list requests
     *
     * @return MgResponse
     */
    public function mappingList()
    {
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

    /**
     * Check if pages have changed
     *
     * @param string $mbProjectId The Ministry Brands project ID
     * @param array $pageList The list of pages
     * @return bool True if successful, false otherwise
     */
    public function checkPageChanges($mbProjectId, array $pageList): bool
    {
        try {
            $mapping = $this->mappingManager->findMappingBySourceId($mbProjectId);
            $snapshotDate = date('Y-m-d');

            if (!empty($mapping['changes_json'])) {
                $changes_json = json_decode($mapping['changes_json'], true);
                if (!empty($changes_json) && isset($changes_json['data'])) {
                    $snapshotDate = $changes_json['data'];
                }
            }

            $this->listReport = $this->pageChangeDetector->detectChanges($pageList, $snapshotDate);
            return true;
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 400);
            return false;
        }
    }

    /**
     * Get the report of page changes
     *
     * @return array
     */
    public function getReportPageChanges(): array
    {
        return $this->listReport ?? [];
    }

    /**
     * Add all mapping list
     *
     * @return MgResponse
     */
    public function addAllMappingList(): MgResponse
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                $this->responseHandler->error('Input method handler was not found', 404);
                break;
            case 'POST':
                return $this->addALLPreparedProject()
                    ->getMessageResponse();
        }
        return $this->getMessageResponse();
    }

    /**
     * Delete a prepared project mapping
     *
     * @return Bridge
     */
    private function delPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->DELETE->checkInputProperties(['id']);
            $inputProperties = $inputProperties['id'];
            $returnList = [];

            foreach ($inputProperties as $value) {
                try {
                    $result = $this->mappingManager->deleteMapping((int)$value);
                    if (!$result) {
                        $value['value'] = $value;
                        $value['message'] = 'Potential delete error.';
                    }
                    $returnList[] = $value;
                } catch (Exception $e) {
                    $this->responseHandler->error($e->getMessage(), 400);
                    return $this;
                }
            }
            $this->responseHandler->success($returnList);
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), $e->getCode() ?: 400);
        }

        return $this;
    }

    /**
     * Run a migration
     *
     * @return Bridge
     */
    public function runMigration(): Bridge
    {
        try {
            $mgr_manual = (bool)(int)$this->request->get('mgr_manual');

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

            $this->migrationExecutor->executeMigration(
                $mb_project_uuid,
                (int)$brz_project_id,
                $brz_workspaces_id,
                $mb_page_slug,
                $mgr_manual
            );
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 400);
        }

        return $this;
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
        $this->pageChangeDetector->filterModifiedPages($pages, $migrationDate, $modifiedPages);
    }

    /**
     * Handle migration wave requests
     *
     * @return MgResponse
     */
    public function migrationWave(): MgResponse
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                $this->responseHandler->error('Input method handler was not found', 404);
                break;
            case 'POST':
                return $this->runMigrationWave()
                    ->getMessageResponse();
        }
        return $this->getMessageResponse();
    }

    /**
     * Run a migration wave
     *
     * @return Bridge
     */
    private function runMigrationWave(): Bridge
    {
        try {
            $inputProperties = $this->POST->checkInputProperties(['list_uuid', 'workspaces', 'batchSize', 'mgrManual']);

            $migrationRunner = new MigrationRunnerWave(
                $this->app,
                $this,
                $inputProperties['list_uuid'],
                $inputProperties['workspaces'],
                $inputProperties['batchSize'],
                $inputProperties['mgrManual']
            );
            $migrationRunner->runMigrations();

            $this->responseHandler->success('migrationWave completed');
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), $e->getCode() ?: 400);
        }

        return $this;
    }

    /**
     * Clear a workspace
     *
     * @return Bridge
     */
    public function clearWorkspace(): Bridge
    {
        try {
            $workspaceId = (int)$this->request->get('workspace_id', 0);
            $this->migrationExecutor->clearWorkspace($workspaceId);
            $this->responseHandler->success("Workspace cleared");
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 400);
        }

        return $this;
    }

    public function addTagManualMigrationFromDB(): Bridge
    {
        $brizyApi = new BrizyAPI();

        $result = $this->db->getAllRows('SELECT * FROM migrations_mapping WHERE `ignore` = 0');

        try {
            foreach ($result as $value) {
                $brizyApi->setLabelManualMigration(true, (int) $value['brz_project_id']);

            }
        } catch (\Exception $e) {

            $sas= $e->getMessage();
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

    public function mApp(): MgResponse
    {
        //$this->addTagManualMigration();
        $brizyApi = new BrizyAPI();

        $dir1 = dirname(__DIR__) . '/../../public/migration_results_07-05-2025_21.json';
        $dir2 = dirname(__DIR__) . '/../../public/migration_results_08-05-2025_22.json';

        $fileCont1 = array_merge(
            json_decode(file_get_contents($dir1), true),
            json_decode(file_get_contents($dir2), true)
        );
        try{
            foreach ($fileCont1 as $key => $value) {
                if (empty($value['brizy_project_id'])) {
                    continue;
                }

                $brizyApi->setCloningLink(true, (int)$value['brizy_project_id']);

//                $result = $this->insertMigrationMapping($value['brizy_project_id'], $key, json_encode(['data' => '2025-05-13']));
            }

            $eee= 12;
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
}
