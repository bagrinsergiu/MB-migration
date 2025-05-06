<?php

use MBMigration\ApplicationBootstrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response
{
    $app = new ApplicationBootstrapper($context, $request);

    try {
        $config = $app->doInnitConfig();
    } catch (Exception $e) {
        if ($e->getCode() < 100) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
        return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
    }

    $bridge = new MBMigration\Bridge\Bridge($config, $request);

    switch ($request->getPathInfo()) {
        case '/health':
            return new JsonResponse(["status" => "success",], 200);
        case '/mapping':
            $response = $bridge
                ->checkPreparedProject()
                ->getMessageResponse();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/mapping/list':
            $response = $bridge->mappingList();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/mapping/list/all':
            $response = $bridge->addAllMappingList();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/migration_log':
            try {
                return new JsonResponse($app->getMigrationLogs());
            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
        default:
            try {
                $mgr_manual = (int) $request->get('mgr_manual');

                if(empty($mgr_manual)){
                    $mgr_manual = false;
                } else {
                    $mgr_manual = true;
                }

                $result = $app->migrationNormalFlow(false, $mgr_manual);

                if(!empty($result['mMigration']) && $result['mMigration'] === true) {
                    $projectUUID = $app->getProjectUUDI();
                    $pageList = $app->getPageList();

                    if($bridge->checkPageChanges($projectUUID, $pageList)){

                        // to do, ned add return project details

                        return new JsonResponse(
                            $bridge->getReportPageChanges(),
                            200
                        );
                    } else {
                        $result = $app->migrationNormalFlow(true, $mgr_manual);
                        $result['mgrClone'] = 'failed';
                        return new JsonResponse($result, 200);
                    }

                } else {
                    if($mgr_manual){
                        $bridge->insertMigrationMapping(
                            $result['brizy_project_id'],
                            $result['mb_uuid'],
                            json_encode(['data' => $result['date']])
                        );
                    }

                    return new JsonResponse($result, 200);
                }
            } catch (Exception $e) {
                if ($e->getCode() < 100) {
                    return new JsonResponse(['error' => $e->getMessage()], 404);
                }

                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
    }
};
