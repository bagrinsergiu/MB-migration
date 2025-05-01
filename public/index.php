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
        case '/migration_log':
            try {
                return new JsonResponse($app->getMigrationLogs());
            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
        default:
            try {

                return new JsonResponse($app->migrationNormalFlow());
            } catch (Exception $e) {

                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
    }

};
