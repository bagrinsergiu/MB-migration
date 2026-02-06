<?php

use MBMigration\ApplicationBootstrapper;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
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
                // Логирование входящего запроса для отладки и мониторинга
                Logger::instance()->info('[Migration API] Received migration request', [
                    'mb_project_uuid' => $request->get('mb_project_uuid'),
                    'brz_project_id' => $request->get('brz_project_id'),
                    'wave_id' => $request->get('wave_id'),
                    'force_async' => $request->get('force_async'),
                    'has_webhook_params' => !empty($request->get('webhook_url')),
                    'method' => $request->getMethod(),
                    'path' => $request->getPathInfo(),
                    'query_params' => array_keys($request->query->all())
                ]);
                
                $mbProjectUuid = $request->get('mb_project_uuid');
                $brzProjectId = $request->get('brz_project_id');
                $waveId = $request->get('wave_id');
                $forceAsync = $request->get('force_async', false);
                
                // Преобразуем force_async в boolean
                if (is_string($forceAsync)) {
                    $forceAsync = in_array(strtolower($forceAsync), ['true', '1', 'yes', 'on']);
                }
                $forceAsync = (bool)$forceAsync;
                
                // Проверяем наличие параметров веб-хука для асинхронного режима
                $webhookUrl = $request->get('webhook_url');
                $webhookMbProjectUuid = $request->get('webhook_mb_project_uuid');
                $webhookBrzProjectId = $request->get('webhook_brz_project_id');
                
                // Определяем, нужен ли асинхронный режим
                $needsAsyncMode = false;
                $autoGenerated = false;
                
                // Вариант 1: Явно переданы параметры веб-хука
                if (!empty($webhookUrl) && !empty($webhookMbProjectUuid) && !empty($webhookBrzProjectId) 
                    && !empty($mbProjectUuid) && !empty($brzProjectId)) {
                    $needsAsyncMode = true;
                }
                // Вариант 2: Передан wave_id и настроен DASHBOARD_URL
                elseif (!empty($waveId) && !empty($mbProjectUuid) && !empty($brzProjectId)) {
                    if (Config::validateDashboardConfig()) {
                        // Автоматически формируем параметры веб-хука
                        $webhookUrl = rtrim(Config::$dashboardUrl, '/') . '/api/webhooks/migration-result';
                        $webhookMbProjectUuid = $mbProjectUuid;
                        $webhookBrzProjectId = (int)$brzProjectId;
                        $needsAsyncMode = true;
                        $autoGenerated = true;
                        
                        Logger::instance()->info('[Migration API] Auto-detected async mode by wave_id', [
                            'wave_id' => $waveId,
                            'mb_project_uuid' => $mbProjectUuid,
                            'brz_project_id' => $brzProjectId,
                            'dashboard_url' => Config::$dashboardUrl,
                            'webhook_url' => $webhookUrl
                        ]);
                    } else {
                        Logger::instance()->warning('[Migration API] wave_id provided but DASHBOARD_URL not configured', [
                            'wave_id' => $waveId,
                            'mb_project_uuid' => $mbProjectUuid,
                            'brz_project_id' => $brzProjectId
                        ]);
                    }
                }
                // Вариант 3: Явно указан force_async=true
                elseif ($forceAsync && !empty($mbProjectUuid) && !empty($brzProjectId)) {
                    if (Config::validateDashboardConfig()) {
                        // Используем автоматическое формирование параметров веб-хука
                        $webhookUrl = rtrim(Config::$dashboardUrl, '/') . '/api/webhooks/migration-result';
                        $webhookMbProjectUuid = $mbProjectUuid;
                        $webhookBrzProjectId = (int)$brzProjectId;
                        $needsAsyncMode = true;
                        $autoGenerated = true;
                        
                        Logger::instance()->info('[Migration API] Force async mode enabled', [
                            'mb_project_uuid' => $mbProjectUuid,
                            'brz_project_id' => $brzProjectId,
                            'dashboard_url' => Config::$dashboardUrl,
                            'webhook_url' => $webhookUrl
                        ]);
                    } else {
                        Logger::instance()->warning('[Migration API] force_async=true but DASHBOARD_URL not configured', [
                            'mb_project_uuid' => $mbProjectUuid,
                            'brz_project_id' => $brzProjectId
                        ]);
                    }
                }
                
                // Если нужен асинхронный режим, запускаем в фоне
                if ($needsAsyncMode) {
                    // Сохраняем параметры веб-хука в request для дальнейшего использования
                    if (!empty($webhookUrl)) {
                        $request->query->set('webhook_url', $webhookUrl);
                        $request->query->set('webhook_mb_project_uuid', $webhookMbProjectUuid);
                        $request->query->set('webhook_brz_project_id', $webhookBrzProjectId);
                    }
                    
                    // Сохраняем wave_id в request, если миграция запущена от имени wave
                    if (!empty($waveId)) {
                        $request->query->set('wave_id', $waveId);
                        Logger::instance()->info('[Migration API] Saving wave_id to request', [
                            'wave_id' => $waveId,
                            'mb_project_uuid' => $mbProjectUuid,
                            'brz_project_id' => $brzProjectId
                        ]);
                    }
                    
                    // Возвращаем 202 Accepted сразу
                    $response = new JsonResponse([
                        'status' => 'in_progress',
                        'message' => 'Миграция запущена',
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => (int)$brzProjectId,
                        'wave_id' => $waveId
                    ], 202);
                    
                    // Запускаем миграцию в фоне (результат будет отправлен через веб-хук)
                    // Используем register_shutdown_function для запуска после отправки ответа
                    register_shutdown_function(function() use ($bridge, $mbProjectUuid, $brzProjectId, $waveId) {
                        try {
                            Logger::instance()->info('[Migration API] Background migration started', [
                                'mb_project_uuid' => $mbProjectUuid,
                                'brz_project_id' => $brzProjectId,
                                'wave_id' => $waveId,
                                'pid' => getmypid()
                            ]);
                            
                            $bridge->runMigration();
                            
                            Logger::instance()->info('[Migration API] Background migration completed', [
                                'mb_project_uuid' => $mbProjectUuid,
                                'brz_project_id' => $brzProjectId,
                                'wave_id' => $waveId
                            ]);
                        } catch (Exception $e) {
                            Logger::instance()->error('[Migration API] Error in background migration', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                                'mb_project_uuid' => $mbProjectUuid,
                                'brz_project_id' => $brzProjectId,
                                'wave_id' => $waveId,
                                'pid' => getmypid()
                            ]);
                            
                            // Попытка вызвать веб-хук с ошибкой, если возможно
                            try {
                                $statusService = new \MBMigration\Core\MigrationStatusService();
                                $migrationStatus = $statusService->getStatus($mbProjectUuid, $brzProjectId);
                                if (!empty($migrationStatus) && !empty($migrationStatus['webhook_url'])) {
                                    $webhookService = new \MBMigration\Core\WebhookService();
                                    $webhookData = $webhookService->formatWebhookData(
                                        [],
                                        $migrationStatus['webhook_mb_project_uuid'] ?? $mbProjectUuid,
                                        (int)($migrationStatus['webhook_brz_project_id'] ?? $brzProjectId),
                                        'error',
                                        $e->getMessage()
                                    );
                                    $webhookService->callWebhook($migrationStatus['webhook_url'], $webhookData);
                                    
                                    Logger::instance()->info('[Migration API] Error webhook sent successfully', [
                                        'mb_project_uuid' => $mbProjectUuid,
                                        'brz_project_id' => $brzProjectId,
                                        'wave_id' => $waveId
                                    ]);
                                } else {
                                    Logger::instance()->debug('[Migration API] No webhook URL configured for error notification', [
                                        'mb_project_uuid' => $mbProjectUuid,
                                        'brz_project_id' => $brzProjectId,
                                        'wave_id' => $waveId
                                    ]);
                                }
                            } catch (Exception $webhookError) {
                                Logger::instance()->error('[Migration API] Failed to send error webhook', [
                                    'webhook_error' => $webhookError->getMessage(),
                                    'webhook_trace' => $webhookError->getTraceAsString(),
                                    'original_error' => $e->getMessage(),
                                    'mb_project_uuid' => $mbProjectUuid,
                                    'brz_project_id' => $brzProjectId,
                                    'wave_id' => $waveId
                                ]);
                            }
                        }
                    });
                    
                    return $response;
                }
                
                // Обычный синхронный режим (обратная совместимость)
                Logger::instance()->info('[Migration API] Starting sync migration', [
                    'mb_project_uuid' => $mbProjectUuid,
                    'brz_project_id' => $brzProjectId,
                    'wave_id' => $waveId
                ]);
                
                $response = $bridge->runMigration()
                    ->getMessageResponse();

                return new JsonResponse(
                    $response->getMessage(),
                    $response->getStatusCode()
                );
            } catch (Exception $e) {
                Logger::instance()->error('[Migration API] Error processing migration request', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'mb_project_uuid' => $request->get('mb_project_uuid') ?? null,
                    'brz_project_id' => $request->get('brz_project_id') ?? null,
                    'wave_id' => $request->get('wave_id') ?? null
                ]);
                
                if ($e->getCode() < 100) {
                    return new JsonResponse(['error' => $e->getMessage()], 404);
                }

                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
    }
};
