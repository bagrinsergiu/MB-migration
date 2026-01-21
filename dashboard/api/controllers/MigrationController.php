<?php

namespace Dashboard\Controllers;

use Dashboard\Services\MigrationService;
use Dashboard\Services\ApiProxyService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MigrationController
{
    /**
     * @var MigrationService
     */
    private $migrationService;
    /**
     * @var ApiProxyService
     */
    private $apiProxy;

    public function __construct()
    {
        $this->migrationService = new MigrationService();
        $this->apiProxy = new ApiProxyService();
    }

    /**
     * GET /api/migrations
     * Получить список миграций
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $filters = [
                'status' => $request->query->get('status'),
                'mb_project_uuid' => $request->query->get('mb_project_uuid'),
                'brz_project_id' => $request->query->get('brz_project_id'),
            ];

            // Убираем пустые фильтры
            $filters = array_filter($filters, function($value) {
                return $value !== null && $value !== '';
            });

            $migrations = $this->migrationService->getMigrationsList($filters);

            return new JsonResponse([
                'success' => true,
                'data' => $migrations,
                'count' => count($migrations)
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id
     * Получить детали миграции
     */
    public function getDetails(Request $request, int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);

            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'data' => $details
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/migrations/run
     * Запустить миграцию
     */
    public function run(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                $data = $request->request->all();
            }

            // Валидация обязательных полей (mb_site_id и mb_secret могут быть из настроек)
            $required = ['mb_project_uuid', 'brz_project_id'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => "Обязательное поле отсутствует: {$field}"
                    ], 400);
                }
            }
            
            // Проверяем, что mb_site_id и mb_secret либо переданы, либо есть в настройках
            $dbService = new \Dashboard\Services\DatabaseService();
            $settings = $dbService->getSettings();
            if (empty($data['mb_site_id']) && empty($settings['mb_site_id'])) {
                return new JsonResponse([
                    'success' => false,
                    'error' => "mb_site_id должен быть указан либо в запросе, либо в настройках"
                ], 400);
            }
            if (empty($data['mb_secret']) && empty($settings['mb_secret'])) {
                return new JsonResponse([
                    'success' => false,
                    'error' => "mb_secret должен быть указан либо в запросе, либо в настройках"
                ], 400);
            }

            $result = $this->migrationService->runMigration($data);

            // Если миграция не успешна, возвращаем ошибку
            if (!$result['success']) {
                $errorMessage = 'Миграция завершилась с ошибкой';
                if (isset($result['data']['error'])) {
                    $errorMessage = is_string($result['data']['error']) ? $result['data']['error'] : json_encode($result['data']['error']);
                } elseif (isset($result['data']['message'])) {
                    $errorMessage = is_string($result['data']['message']) ? $result['data']['message'] : json_encode($result['data']['message']);
                } elseif (isset($result['raw_data']['error'])) {
                    $errorMessage = is_string($result['raw_data']['error']) ? $result['raw_data']['error'] : json_encode($result['raw_data']['error']);
                }
                
                $httpCode = isset($result['http_code']) ? (int)$result['http_code'] : 400;
                return new JsonResponse([
                    'success' => false,
                    'error' => $errorMessage,
                    'data' => $result['data'] ?? null
                ], $httpCode);
            }

            $httpCode = isset($result['http_code']) ? (int)$result['http_code'] : 200;
            
            // Формируем ответ
            $responseData = [
                'success' => $result['success'] ?? false,
                'data' => $result['data'] ?? null
            ];
            
            // Убеждаемся, что данные есть
            if ($result['success'] && empty($responseData['data'])) {
                $responseData['data'] = $result['raw_data'] ?? null;
            }
            
            // Создаем и возвращаем JsonResponse
            $jsonResponse = new JsonResponse($responseData, $httpCode);
            return $jsonResponse;
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/migrations/:id/restart
     * Перезапустить миграцию
     */
    public function restart(Request $request, int $id): JsonResponse
    {
        try {
            // Получаем данные миграции
            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mapping = $details['mapping'];
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                $data = $request->request->all();
            }

            // Получаем настройки по умолчанию
            $dbService = new \Dashboard\Services\DatabaseService();
            $defaultSettings = $dbService->getSettings();

            // Используем данные из маппинга, если не переданы новые
            // Для mb_site_id и mb_secret используем настройки по умолчанию, если не переданы
            $params = [
                'mb_project_uuid' => $data['mb_project_uuid'] ?? $mapping['mb_project_uuid'],
                'brz_project_id' => $data['brz_project_id'] ?? $mapping['brz_project_id'],
                'mb_site_id' => !empty($data['mb_site_id']) ? (int)$data['mb_site_id'] : ($defaultSettings['mb_site_id'] ?? null),
                'mb_secret' => !empty($data['mb_secret']) ? $data['mb_secret'] : ($defaultSettings['mb_secret'] ?? null),
                'brz_workspaces_id' => !empty($data['brz_workspaces_id']) ? (int)$data['brz_workspaces_id'] : null,
                'mb_page_slug' => !empty($data['mb_page_slug']) ? $data['mb_page_slug'] : null,
                'mgr_manual' => !empty($data['mgr_manual']) ? (int)$data['mgr_manual'] : 0,
                'quality_analysis' => isset($data['quality_analysis']) ? (bool)$data['quality_analysis'] : false,
            ];
            
            // Если перезапускаем с анализом качества, помечаем старые результаты как устаревшие
            if (!empty($params['quality_analysis'])) {
                try {
                    $qualityReport = new \MBMigration\Analysis\QualityReport();
                    $qualityReport->archiveOldReports($id);
                } catch (Exception $e) {
                    // Логируем, но не прерываем перезапуск миграции
                    error_log("Failed to archive old quality reports: " . $e->getMessage());
                }
            }

            // Проверяем, что mb_site_id и mb_secret либо переданы, либо есть в настройках
            if (empty($params['mb_site_id'])) {
                return new JsonResponse([
                    'success' => false,
                    'error' => "mb_site_id должен быть указан либо в запросе, либо в настройках"
                ], 400);
            }
            if (empty($params['mb_secret'])) {
                return new JsonResponse([
                    'success' => false,
                    'error' => "mb_secret должен быть указан либо в запросе, либо в настройках"
                ], 400);
            }

            $result = $this->migrationService->runMigration($params);

            return new JsonResponse([
                'success' => $result['success'],
                'data' => $result['data'],
                'http_code' => $result['http_code'],
                'message' => 'Миграция перезапущена'
            ], $result['http_code']);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id/status
     * Получить статус миграции
     */
    public function getStatus(int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);

            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'data' => [
                    'status' => $details['status'],
                    'mapping' => $details['mapping'],
                    'result' => $details['result']
                ]
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/migrations/:id/lock
     * Удалить lock-файл миграции
     */
    public function removeLock(Request $request, int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            $result = $this->migrationService->removeMigrationLock($mbUuid, $brzProjectId);

            return new JsonResponse([
                'success' => $result['success'],
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/migrations/:id/kill
     * Убить процесс миграции
     */
    public function killProcess(Request $request, int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            $data = json_decode($request->getContent(), true);
            $force = isset($data['force']) ? (bool)$data['force'] : false;

            $result = $this->migrationService->killMigrationProcess($mbUuid, $brzProjectId, $force);

            return new JsonResponse([
                'success' => $result['success'],
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id/process
     * Получить информацию о процессе миграции (мониторинг)
     */
    public function getProcessInfo(Request $request, int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            $result = $this->migrationService->getMigrationProcessInfo($mbUuid, $brzProjectId);

            return new JsonResponse([
                'success' => $result['success'],
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/migrations/:id/cache
     * Удалить кэш-файл миграции
     */
    public function removeCache(Request $request, int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            $result = $this->migrationService->removeMigrationCache($mbUuid, $brzProjectId);

            return new JsonResponse([
                'success' => $result['success'],
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/migrations/:id/reset-status
     * Сбросить статус миграции на pending
     */
    public function resetStatus(Request $request, int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            $result = $this->migrationService->resetMigrationStatus($mbUuid, $brzProjectId);

            return new JsonResponse([
                'success' => $result['success'],
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/migrations/:id/hard-reset
     * Hard reset миграции: удаляет lock-файл, cache-файл, убивает процесс и сбрасывает статус
     */
    public function hardReset(Request $request, int $id): JsonResponse
    {
        try {
            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            $result = $this->migrationService->hardResetMigration($mbUuid, $brzProjectId);

            return new JsonResponse([
                'success' => $result['success'],
                'data' => $result
            ], $result['success'] ? 200 : 500);
        } catch (Exception $e) {
            error_log("Hard reset controller exception: " . $e->getMessage());
            error_log("Hard reset controller stack trace: " . $e->getTraceAsString());
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
                'exception' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        } catch (\Throwable $e) {
            error_log("Hard reset controller throwable: " . $e->getMessage());
            error_log("Hard reset controller stack trace: " . $e->getTraceAsString());
            return new JsonResponse([
                'success' => false,
                'error' => 'Критическая ошибка: ' . $e->getMessage(),
                'exception' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * POST /api/migrations/:id/rebuild-page
     * Пересобрать конкретную страницу
     */
    public function rebuildPage(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                $data = $request->request->all();
            }

            $pageSlug = $data['page_slug'] ?? null;
            
            if (!$pageSlug) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Параметр page_slug обязателен'
                ], 400);
            }

            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            // Получаем параметры из настроек или из данных миграции
            $params = [
                'mb_project_uuid' => $mbUuid,
                'brz_project_id' => $brzProjectId,
                'mb_page_slug' => $pageSlug,
                'mgr_manual' => 0,
                'quality_analysis' => true, // Включаем анализ качества при пересборке
            ];

            // Получаем настройки из БД или используем значения по умолчанию
            $dbService = new \Dashboard\Services\DatabaseService();
            $settings = $dbService->getSettings();
            
            if ($settings) {
                $params['mb_site_id'] = $settings['mb_site_id'] ?? null;
                $params['mb_secret'] = $settings['mb_secret'] ?? null;
                $params['brz_workspaces_id'] = $settings['brz_workspaces_id'] ?? null;
            }

            // Если параметры переданы в запросе, используем их
            if (isset($data['mb_site_id'])) {
                $params['mb_site_id'] = $data['mb_site_id'];
            }
            if (isset($data['mb_secret'])) {
                $params['mb_secret'] = $data['mb_secret'];
            }
            if (isset($data['brz_workspaces_id'])) {
                $params['brz_workspaces_id'] = $data['brz_workspaces_id'];
            }

            // Запускаем пересборку страницы через ApiProxyService
            $result = $this->apiProxy->runMigration($params);

            return new JsonResponse([
                'success' => true,
                'data' => [
                    'message' => 'Пересборка страницы запущена',
                    'page_slug' => $pageSlug,
                    'migration_id' => $id
                ]
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id/logs
     * Получить логи миграции
     */
    public function getMigrationLogs(Request $request, int $id): JsonResponse
    {
        try {
            $migrationService = new \Dashboard\Services\MigrationService();
            $logs = $migrationService->getMigrationLogs($id);
            
            return new JsonResponse([
                'success' => true,
                'data' => ['logs' => $logs],
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/migrations/:id/rebuild-page-no-analysis
     * Пересобрать конкретную страницу миграции без анализа качества
     */
    public function rebuildPageNoAnalysis(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                $data = $request->request->all();
            }

            $pageSlug = $data['page_slug'] ?? null;
            
            if (!$pageSlug) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Параметр page_slug обязателен'
                ], 400);
            }

            $details = $this->migrationService->getMigrationDetails($id);
            
            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена'
                ], 404);
            }

            $mbUuid = $details['mapping']['mb_project_uuid'];
            $brzProjectId = $details['mapping']['brz_project_id'];

            // Получаем параметры из настроек или из данных миграции
            $params = [
                'mb_project_uuid' => $mbUuid,
                'brz_project_id' => $brzProjectId,
                'mb_page_slug' => $pageSlug,
                'mgr_manual' => 0,
                'quality_analysis' => false, // Отключаем анализ качества
            ];

            // Получаем настройки из БД или используем значения по умолчанию
            $dbService = new \Dashboard\Services\DatabaseService();
            $settings = $dbService->getSettings();
            
            if ($settings) {
                $params['mb_site_id'] = $settings['mb_site_id'] ?? null;
                $params['mb_secret'] = $settings['mb_secret'] ?? null;
                $params['brz_workspaces_id'] = $settings['brz_workspaces_id'] ?? null;
            }

            // Если параметры переданы в запросе, используем их
            if (isset($data['mb_site_id'])) {
                $params['mb_site_id'] = $data['mb_site_id'];
            }
            if (isset($data['mb_secret'])) {
                $params['mb_secret'] = $data['mb_secret'];
            }
            if (isset($data['brz_workspaces_id'])) {
                $params['brz_workspaces_id'] = $data['brz_workspaces_id'];
            }

            // Запускаем пересборку страницы через ApiProxyService без анализа
            $result = $this->apiProxy->runMigration($params);

            return new JsonResponse([
                'success' => true,
                'data' => [
                    'message' => 'Пересборка страницы запущена (без анализа качества)',
                    'page_slug' => $pageSlug,
                    'migration_id' => $id
                ]
            ], 200);
        } catch (Exception $e) {
            error_log("Error rebuilding page without analysis: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
                'details' => [
                    'migration_id' => $id,
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine(),
                    'type' => get_class($e)
                ]
            ], 500);
        }
    }
}
