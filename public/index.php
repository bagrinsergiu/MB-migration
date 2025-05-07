<?php

use MBMigration\ApplicationBootstrapper;
use MBMigration\MigrationRunnerWave;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response {
    $app = new ApplicationBootstrapper($context, $request);

    try {
        $config = $app->doInnitConfig();
    } catch (Exception $e) {
        if ($e->getCode() < 100) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
        return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
    }

    $bridge = new MBMigration\Bridge\Bridge($app, $config, $request);

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
        case '/utils/clearWorkspace':
            try {
                return new JsonResponse(
                    $bridge->clearWorkspace()
                        ->getMessageResponse()
                );
            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
        case '/migration/wave':
            $response = $bridge->migrationWave();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        default:
            try {
                $response = $bridge->runMigration()
                    ->getMessageResponse();

                return new JsonResponse(
                    $response->getMessage(),
                    $response->getStatusCode()
                );
            } catch (Exception $e) {
                if ($e->getCode() < 100) {
                    return new JsonResponse(['error' => $e->getMessage()], 404);
                }

                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
    }
};
