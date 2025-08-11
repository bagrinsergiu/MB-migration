<?php

use MBMigration\ApplicationBootstrapper;
use MBMigration\WaveProc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response {
    switch ($request->getPathInfo()) {
        case '/health':
            return new JsonResponse(["status" => "success",], 200);
    }

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
        case '/changes/checkAll':
            $response = $bridge->checkAllProjectchanges();

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

            $projectUuids = ["f2c701b1-c16c-4bf0-b759-aa89d133c84c"];
            $migrationRunner = new WaveProc($projectUuids);
            $migrationRunner->runMigrations();

            $response = $bridge->migrationWave();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );

        case '/app':
            $response = $bridge->mApp();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/addTagManualMigration':
            $response = $bridge->addTagManualMigration();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/delTagManualMigration':
            $response = $bridge->delTagManualMigration();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/setCloningLink':
            $response = $bridge->setCloningLincMigration();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/cloning':
            $response = $bridge->doCloningProjects();
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
