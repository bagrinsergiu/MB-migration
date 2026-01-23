<?php

namespace MBMigration\Bridge;

use Exception;
use MBMigration\ApplicationBootstrapper;
use MBMigration\Bridge\Interfaces\ResponseHandlerInterface;
use MBMigration\Layer\HTTP\RequestHandlerPOST;
use MBMigration\MigrationRunnerWave;

/**
 * Class MigrationWaveManager
 * Handles operations related to running migration waves
 */
class MigrationWaveManager
{
    private ResponseHandlerInterface $responseHandler;
    private RequestHandlerPOST $POST;
    private ApplicationBootstrapper $app;
    private Bridge $bridge;

    /**
     * MigrationWaveManager constructor
     *
     * @param ApplicationBootstrapper $app The application bootstrapper
     * @param Bridge $bridge The bridge instance
     * @param ResponseHandlerInterface $responseHandler The response handler
     * @param RequestHandlerPOST $POST The POST request handler
     */
    public function __construct(
        ApplicationBootstrapper $app,
        Bridge $bridge,
        ResponseHandlerInterface $responseHandler,
        RequestHandlerPOST $POST
    ) {
        $this->app = $app;
        $this->bridge = $bridge;
        $this->responseHandler = $responseHandler;
        $this->POST = $POST;
    }

    /**
     * Handle migration wave requests
     *
     * @return MgResponse The response
     */
    public function migrationWave(): MgResponse
    {
        try {
            $inputProperties = $this->POST->checkInputProperties(['list_uuid', 'workspaces', 'batchSize', 'mgrManual']);

            $migrationRunner = new MigrationRunnerWave(
                $this->app,
                $this->bridge,
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

        return $this->responseHandler->getResponse();
    }
}
