<?php

use MBMigration\ApplicationBootstrapper;
use MBMigration\WaveProc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response {
    $pathInfo = $request->getPathInfo();

    switch ($pathInfo) {
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
            case '/projects/makePro':
            try {
                return new JsonResponse(
                    $bridge->makeAllProjectsPRO()
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
        case '/migration-status':
            try {
                $mbProjectUuid = $request->get('mb_project_uuid');
                $brzProjectId = $request->get('brz_project_id');

                if (empty($mbProjectUuid) || empty($brzProjectId)) {
                    return new JsonResponse([
                        'error' => 'Missing required parameters: mb_project_uuid and brz_project_id are required'
                    ], 400);
                }

                $statusService = new \MBMigration\Core\MigrationStatusService();
                $status = $statusService->getStatus($mbProjectUuid, (int)$brzProjectId);

                if ($status === null) {
                    return new JsonResponse([
                        'error' => 'Миграция не найдена',
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => (int)$brzProjectId
                    ], 404);
                }

                return new JsonResponse($status, 200);
            } catch (Exception $e) {
                return new JsonResponse([
                    'error' => $e->getMessage()
                ], 500);
            }
        default:
            try {
                // Проверяем наличие параметров веб-хука для асинхронного режима
                $webhookUrl = $request->get('webhook_url');
                $webhookMbProjectUuid = $request->get('webhook_mb_project_uuid');
                $webhookBrzProjectId = $request->get('webhook_brz_project_id');
                $mbProjectUuid = $request->get('mb_project_uuid');
                $brzProjectId = $request->get('brz_project_id');

                // Если переданы параметры веб-хука, возвращаем 202 Accepted и запускаем миграцию
                if (!empty($webhookUrl) && !empty($webhookMbProjectUuid) && !empty($webhookBrzProjectId) 
                    && !empty($mbProjectUuid) && !empty($brzProjectId)) {
                    
                    // Возвращаем 202 Accepted сразу
                    $response = new JsonResponse([
                        'status' => 'in_progress',
                        'message' => 'Миграция запущена',
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => (int)$brzProjectId
                    ], 202);
                    
                    // Запускаем миграцию в фоне (результат будет отправлен через веб-хук)
                    // Используем register_shutdown_function для запуска после отправки ответа
                    register_shutdown_function(function() use ($bridge) {
                        try {
                            $bridge->runMigration();
                        } catch (Exception $e) {
                            \MBMigration\Core\Logger::instance()->error('Error in background migration', [
                                'error' => $e->getMessage()
                            ]);
                        }
                    });
                    
                    return $response;
                }

                // Обычный синхронный режим
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
