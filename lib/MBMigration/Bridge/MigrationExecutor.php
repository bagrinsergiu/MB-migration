<?php

namespace MBMigration\Bridge;

use Exception;
use MBMigration\ApplicationBootstrapper;
use MBMigration\Bridge\Interfaces\MigrationExecutorInterface;
use MBMigration\Bridge\Interfaces\ResponseHandlerInterface;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\MB\MBProjectDataCollector;
use MBMigration\MigrationRunnerWave;

/**
 * Handles migration execution
 */
class MigrationExecutor implements MigrationExecutorInterface
{
    private ApplicationBootstrapper $app;
    private MappingManager $mappingManager;
    private PageChangeDetector $pageChangeDetector;
    private ResponseHandler $responseHandler;

    /**
     * Initialize the migration executor
     *
     * @param ApplicationBootstrapper $app The application bootstrapper
     * @param MappingManager $mappingManager The mapping manager
     * @param PageChangeDetector $pageChangeDetector The page change detector
     * @param ResponseHandler $responseHandler The response handler
     */
    public function __construct(
        ApplicationBootstrapper $app,
        MappingManager $mappingManager,
        PageChangeDetector $pageChangeDetector,
        ResponseHandler $responseHandler
    ) {
        $this->app = $app;
        $this->mappingManager = $mappingManager;
        $this->pageChangeDetector = $pageChangeDetector;
        $this->responseHandler = $responseHandler;
    }

    /**
     * Execute a migration
     *
     * @param string $mbProjectUuid The Ministry Brands project UUID
     * @param int $brzProjectId The Brizy project ID
     * @param int $brzWorkspacesId The Brizy workspace ID
     * @param string $mbPageSlug The Ministry Brands page slug
     * @param bool $isManual Whether the migration is manual
     * @return ResponseHandlerInterface The response handler
     */
    public function executeMigration(
        string $mbProjectUuid,
        int $brzProjectId,
        int $brzWorkspacesId = 0,
        string $mbPageSlug = '',
        bool $isManual = false
    ): ResponseHandlerInterface {
        try {
            // Check if the project was manually migrated
            $brizyAPI = new BrizyAPI();
            $isManuallyMigrated = $brizyAPI->checkProjectManualMigration($brzProjectId);

            if ($isManuallyMigrated) {
                return $this->handleManuallyMigratedProject($mbProjectUuid);
            } else {
                return $this->handleStandardMigration(
                    $mbProjectUuid,
                    $brzProjectId,
                    $brzWorkspacesId,
                    $mbPageSlug,
                    $isManual
                );
            }
        } catch (Exception $e) {
            return $this->responseHandler->error($e->getMessage(), 400);
        }
    }

    /**
     * Handle a manually migrated project
     *
     * @param string $mbProjectUuid The Ministry Brands project UUID
     * @return ResponseHandler The response handler
     */
    private function handleManuallyMigratedProject(string $mbProjectUuid): ResponseHandler
    {
        try {
            // Get the project ID from the UUID
            try {
                $projectId = MBProjectDataCollector::getIdByUUID($mbProjectUuid);
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
            $mgr_mapping = $this->mappingManager->findMappingBySourceId($mbProjectUuid);
            $migrationDate = date('Y-m-d');

            if (!empty($mgr_mapping['changes_json'])) {
                $changes_json = json_decode($mgr_mapping['changes_json'], true);
                if (!empty($changes_json) && isset($changes_json['data'])) {
                    $migrationDate = $changes_json['data'];
                }
            }

            // Filter pages that were modified after the migration date
            $modifiedPages = [];
            $this->pageChangeDetector->filterModifiedPages($projectPages, $migrationDate, $modifiedPages);

            // Format the response
            $formattedPages = $this->pageChangeDetector->formatChangedPagesForResponse($modifiedPages);

            // Return the response
            return $this->responseHandler->success([
                'success' => true,
                'pages' => $formattedPages
            ]);
        } catch (Exception $e) {
            return $this->responseHandler->error($e->getMessage(), 400);
        }
    }

    /**
     * Handle a standard migration
     *
     * @param string $mbProjectUuid The Ministry Brands project UUID
     * @param int $brzProjectId The Brizy project ID
     * @param int $brzWorkspacesId The Brizy workspace ID
     * @param string $mbPageSlug The Ministry Brands page slug
     * @param bool $isManual Whether the migration is manual
     * @return ResponseHandler The response handler
     */
    private function handleStandardMigration(
        string $mbProjectUuid,
        int $brzProjectId,
        int $brzWorkspacesId,
        string $mbPageSlug,
        bool $isManual
    ): ResponseHandler {
        $result = $this->app->migrationFlow(
            $mbProjectUuid,
            $brzProjectId,
            $brzWorkspacesId,
            $mbPageSlug,
            false,
            $isManual
        );

        if (!empty($result['mMigration']) && $result['mMigration'] === true) {
            $projectUUID = $this->app->getProjectUUDI();
            $pageList = $this->app->getPageList();

            try {
                $changedPages = $this->pageChangeDetector->detectChanges($pageList, date('Y-m-d'));

                return $this->responseHandler->success([
                    'projectId' => $brzProjectId,
                    'uuid' => $projectUUID,
                    'report' => $changedPages
                ]);
            } catch (Exception $e) {
                // If page change detection fails, run the migration again
                $result = $this->app->migrationFlow(
                    $mbProjectUuid,
                    $brzProjectId,
                    $brzWorkspacesId,
                    $mbPageSlug,
                    true,
                    $isManual
                );

                if (!empty($result['mMigration']) && $result['mMigration'] === true) {
                    return $this->responseHandler->success([
                        'projectId' => $brzProjectId,
                        'uuid' => $mbProjectUuid
                    ]);
                } else {
                    return $this->responseHandler->error('Migration failed', 400);
                }
            }
        } else {
            return $this->responseHandler->error('Migration failed', 400);
        }
    }

    /**
     * Run a migration wave
     *
     * @param int $waveId The wave ID
     * @return ResponseHandlerInterface The response handler
     */
    public function runMigrationWave(int $waveId): ResponseHandlerInterface
    {
        try {
            $migrationRunnerWave = new MigrationRunnerWave($waveId);
            $result = $migrationRunnerWave->run();

            return $this->responseHandler->success($result);
        } catch (Exception $e) {
            return $this->responseHandler->error($e->getMessage(), 400);
        }
    }

    /**
     * Clear a workspace
     *
     * @param int $workspaceId The workspace ID
     * @return ResponseHandlerInterface The response handler
     */
    public function clearWorkspace(int $workspaceId): ResponseHandlerInterface
    {
        try {
            $brizyAPI = new BrizyAPI();
            $result = $brizyAPI->clearWorkspace($workspaceId);

            return $this->responseHandler->success($result);
        } catch (Exception $e) {
            return $this->responseHandler->error($e->getMessage(), 400);
        }
    }
}
