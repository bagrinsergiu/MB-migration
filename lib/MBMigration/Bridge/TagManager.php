<?php

namespace MBMigration\Bridge;

use Exception;
use MBMigration\Bridge\Interfaces\ResponseHandlerInterface;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\HTTP\RequestHandlerPOST;

/**
 * Class TagManager
 * Handles operations related to managing tags for manual migration
 */
class TagManager
{
    private ResponseHandlerInterface $responseHandler;
    private RequestHandlerPOST $POST;

    /**
     * TagManager constructor
     *
     * @param ResponseHandlerInterface $responseHandler The response handler
     * @param RequestHandlerPOST $POST The POST request handler
     */
    public function __construct(
        ResponseHandlerInterface $responseHandler,
        RequestHandlerPOST $POST
    ) {
        $this->responseHandler = $responseHandler;
        $this->POST = $POST;
    }

    /**
     * Add tag for manual migration from database
     *
     * @param array $mappings The mappings from the database
     * @return MgResponse The response
     */
    public function addTagManualMigrationFromDB(array $mappings): MgResponse
    {
        $brizyApi = new BrizyAPI();

        try {
            foreach ($mappings as $value) {
                $brizyApi->setLabelManualMigration(true, (int) $value['brz_project_id']);
            }
        } catch (Exception $e) {
            // Log error if needed
        }

        $this->responseHandler->success("Tags added successfully");
        return $this->responseHandler->getResponse();
    }

    /**
     * Delete tag for manual migration
     *
     * @return MgResponse The response
     */
    public function delTagManualMigration(): MgResponse
    {
        $brizyApi = new BrizyAPI();

        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id']);
            $brizyApi->setLabelManualMigration(false, (int)$inputProperties['brz_project_id']);

            $this->responseHandler->success("Tag deleted");
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 404);
        }

        return $this->responseHandler->getResponse();
    }

    /**
     * Add tag for manual migration
     *
     * @return MgResponse The response
     */
    public function addTagManualMigration(): MgResponse
    {
        $brizyApi = new BrizyAPI();

        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id']);
            $brizyApi->setLabelManualMigration(true, (int)$inputProperties['brz_project_id']);

            $this->responseHandler->success("Tag set");
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 404);
        }

        return $this->responseHandler->getResponse();
    }
}
