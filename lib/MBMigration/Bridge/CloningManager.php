<?php

namespace MBMigration\Bridge;

use Exception;
use MBMigration\Bridge\Interfaces\ResponseHandlerInterface;
use MBMigration\Core\Factory\LoggerFactory;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\HTTP\RequestHandlerPOST;

/**
 * Class CloningManager
 * Handles operations related to cloning projects
 */
class CloningManager
{
    private ResponseHandlerInterface $responseHandler;
    private RequestHandlerPOST $POST;

    /**
     * CloningManager constructor
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
     * Set cloning link for migration
     *
     * @return MgResponse The response
     */
    public function setCloningLinkMigration(): MgResponse
    {
        $logger = LoggerFactory::createDefault('CloningManager');
        $brizyApi = new BrizyAPI($logger);

        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id']);
            $brizyApi->setCloningLink(true, (int)$inputProperties['brz_project_id']);

            $this->responseHandler->success("Cloning link set");
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 404);
        }

        return $this->responseHandler->getResponse();
    }

    /**
     * Process multiple projects for cloning
     *
     * @param string $filePath1 Path to the first JSON file
     * @param string $filePath2 Path to the second JSON file
     * @return MgResponse The response
     */
    public function processMultipleProjects(string $filePath1, string $filePath2): MgResponse
    {
        $logger = LoggerFactory::createDefault('CloningManager');
        $brizyApi = new BrizyAPI($logger);

        try {
            $fileCont1 = array_merge(
                json_decode(file_get_contents($filePath1), true),
                json_decode(file_get_contents($filePath2), true)
            );

            foreach ($fileCont1 as $key => $value) {
                if (empty($value['brizy_project_id'])) {
                    continue;
                }

                $brizyApi->setCloningLink(true, (int)$value['brizy_project_id']);
            }

            $this->responseHandler->success("Cloning links set for multiple projects");
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 404);
        }

        return $this->responseHandler->getResponse();
    }

    /**
     * Clone projects
     *
     * @return MgResponse The response
     */
    public function cloneProjects(): MgResponse
    {
        $logger = LoggerFactory::createDefault('CloningManager');
        $brizyApi = new BrizyAPI($logger);

        try {
            $inputProperties = $this->POST->checkInputProperties(['brz_project_id', 'workspace']);
            $brizyApi->cloneProject($inputProperties['brz_project_id'], $inputProperties['workspace']);

            $this->responseHandler->success("Cloning completed");
        } catch (Exception $e) {
            $this->responseHandler->error($e->getMessage(), 404);
        }

        return $this->responseHandler->getResponse();
    }
}
