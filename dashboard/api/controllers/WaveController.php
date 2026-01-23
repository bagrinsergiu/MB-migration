<?php

namespace Dashboard\Controllers;

use Dashboard\Services\WaveService;
use Dashboard\Services\WaveLogger;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WaveController
{
    /** @var WaveService */
    private $waveService;

    public function __construct()
    {
        $this->waveService = new WaveService();
    }

    /**
     * POST /api/waves
     * Создать новую волну миграций
     */
    public function create(Request $request): JsonResponse
    {
        WaveLogger::startOperation('WaveController::create', [
            'method' => $request->getMethod(),
            'content_type' => $request->headers->get('Content-Type')
        ]);
        
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                $data = $request->request->all();
            }

            WaveLogger::debug("Получены данные запроса", [
                'has_name' => !empty($data['name']),
                'has_project_uuids' => !empty($data['project_uuids']),
                'project_uuids_count' => is_array($data['project_uuids'] ?? null) ? count($data['project_uuids']) : 0
            ]);

            // Валидация
            if (empty($data['name'])) {
                WaveLogger::error("Валидация: название волны пустое");
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Название волны обязательно'
                ], 400);
            }

            if (empty($data['project_uuids']) || !is_array($data['project_uuids'])) {
                WaveLogger::error("Валидация: список UUID проектов пустой или не массив");
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Список UUID проектов обязателен и должен быть массивом'
                ], 400);
            }

            // Очищаем UUID от пробелов и пустых значений
            $projectUuids = array_filter(
                array_map('trim', $data['project_uuids']),
                function($uuid) {
                    return !empty($uuid);
                }
            );

            if (empty($projectUuids)) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Список UUID проектов не может быть пустым'
                ], 400);
            }

            $batchSize = isset($data['batch_size']) ? (int)$data['batch_size'] : 3;
            $mgrManual = isset($data['mgr_manual']) ? (bool)$data['mgr_manual'] : false;

            WaveLogger::info("Вызов WaveService::createWave", [
                'name' => $data['name'],
                'projects_count' => count($projectUuids),
                'batch_size' => $batchSize,
                'mgr_manual' => $mgrManual
            ]);

            $result = $this->waveService->createWave(
                $data['name'],
                array_values($projectUuids),
                $batchSize,
                $mgrManual
            );

            WaveLogger::endOperation('WaveController::create', [
                'success' => true,
                'wave_id' => $result['wave_id'] ?? null
            ]);

            return new JsonResponse([
                'success' => true,
                'data' => $result
            ], 201);

        } catch (Exception $e) {
            WaveLogger::error("Ошибка в WaveController::create", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            WaveLogger::endOperation('WaveController::create', [
                'success' => false,
                'error' => $e->getMessage()
            ]);
            
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/waves
     * Получить список всех волн
     */
    public function list(Request $request): JsonResponse
    {
        // Убираем избыточное логирование для быстрого списка - логируем только ошибки
        try {
            $waves = $this->waveService->getWavesList();
            
            // Фильтрация по статусу (опционально)
            $statusFilter = $request->query->get('status');
            if ($statusFilter) {
                $waves = array_filter($waves, function($wave) use ($statusFilter) {
                    return $wave['status'] === $statusFilter;
                });
                $waves = array_values($waves); // Переиндексируем массив
            }

            return new JsonResponse([
                'success' => true,
                'data' => $waves,
                'count' => count($waves)
            ], 200);

        } catch (Exception $e) {
            WaveLogger::error("Ошибка в WaveController::list", ['error' => $e->getMessage()]);
            WaveLogger::endOperation('WaveController::list', ['success' => false, 'error' => $e->getMessage()]);
            
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/waves/:id
     * Получить детали волны
     */
    public function getDetails(Request $request, string $id): JsonResponse
    {
        // Убираем избыточное логирование для быстрого получения деталей - логируем только ошибки
        try {
            $details = $this->waveService->getWaveDetails($id);

            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Волна не найдена'
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'data' => $details
            ], 200);

        } catch (Exception $e) {
            // Логируем только ошибки
            WaveLogger::error("Ошибка в WaveController::getDetails", [
                'wave_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/waves/:id/status
     * Получить статус волны (быстрый запрос)
     */
    public function getStatus(string $id): JsonResponse
    {
        // Убираем избыточное логирование для быстрого статуса - логируем только ошибки
        try {
            $details = $this->waveService->getWaveDetails($id);

            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Волна не найдена'
                ], 404);
            }

            return new JsonResponse([
                'success' => true,
                'data' => [
                    'status' => $details['wave']['status'],
                    'progress' => $details['wave']['progress'],
                ]
            ], 200);

        } catch (Exception $e) {
            WaveLogger::error("Ошибка получения статуса волны", [
                'wave_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/waves/:id/migrations/:mb_uuid/restart
     * Перезапустить миграцию в волне
     */
    public function restartMigration(Request $request, string $id, string $mbUuid): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                $data = $request->request->all();
            }

            $result = $this->waveService->restartMigrationInWave($id, $mbUuid, $data);

            return new JsonResponse([
                'success' => true,
                'data' => $result['data'],
                'message' => 'Миграция успешно перезапущена'
            ], 200);

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/waves/:id/migrations/:mb_uuid/logs
     * Получить логи миграции
     */
    public function getMigrationLogs(Request $request, string $id, string $mbUuid): JsonResponse
    {
        try {
            $details = $this->waveService->getWaveDetails($id);

            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Волна не найдена'
                ], 404);
            }

            // Находим миграцию в волне
            $migration = null;
            foreach ($details['migrations'] as $m) {
                if ($m['mb_project_uuid'] === $mbUuid) {
                    $migration = $m;
                    break;
                }
            }

            if (!$migration) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена в волне'
                ], 404);
            }

            $brzProjectId = $migration['brz_project_id'] ?? 0;
            
            // Если brz_project_id = 0, логи еще не созданы
            if ($brzProjectId == 0) {
                return new JsonResponse([
                    'success' => true,
                    'data' => [
                        'logs' => ['Проект еще не создан, логи недоступны'],
                        'log_files' => [],
                        'brz_project_id' => 0,
                        'mb_uuid' => $mbUuid
                    ]
                ], 200);
            }

            // Получаем логи проекта в волне через новый метод
            try {
                $logs = $this->waveService->getProjectLogsInWave($id, $brzProjectId);
                
                return new JsonResponse([
                    'success' => true,
                    'data' => $logs
                ], 200);
            } catch (Exception $e) {
                // Если новый метод не нашел логи, пробуем старый метод
                $logs = $this->waveService->getMigrationLogs($id, $mbUuid, $brzProjectId);
                
                return new JsonResponse([
                    'success' => true,
                    'data' => $logs,
                    'note' => 'Использован старый метод получения логов'
                ], 200);
            }

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/waves/:id/migrations/:mb_uuid/lock
     * Удалить lock-файл миграции
     */
    public function removeMigrationLock(Request $request, string $id, string $mbUuid): JsonResponse
    {
        try {
            $details = $this->waveService->getWaveDetails($id);

            if (!$details) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Волна не найдена'
                ], 404);
            }

            // Находим миграцию в волне
            $migration = null;
            foreach ($details['migrations'] as $m) {
                if ($m['mb_project_uuid'] === $mbUuid) {
                    $migration = $m;
                    break;
                }
            }

            if (!$migration) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Миграция не найдена в волне'
                ], 404);
            }

            $brzProjectId = $migration['brz_project_id'] ?? 0;
            
            if ($brzProjectId == 0) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Проект еще не создан, lock-файл отсутствует'
                ], 400);
            }

            // Удаляем lock-файл
            $result = $this->waveService->removeMigrationLock($mbUuid, $brzProjectId);

            return new JsonResponse([
                'success' => true,
                'data' => $result,
                'message' => $result['message']
            ], 200);

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/waves/:id/mapping
     * Получить маппинг проектов для волны
     */
    public function getMapping(string $id): JsonResponse
    {
        try {
            $mapping = $this->waveService->getWaveMapping($id);

            return new JsonResponse([
                'success' => true,
                'data' => $mapping,
                'count' => count($mapping)
            ], 200);

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/waves/:id/logs
     * Получить логи волны
     */
    public function getWaveLogs(Request $request, string $id): JsonResponse
    {
        try {
            $logs = $this->waveService->getWaveLogs($id);
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
     * GET /api/waves/:id/projects/:brz_project_id/logs
     * Получить логи проекта в волне по brz_project_id
     */
    public function getProjectLogs(Request $request, string $id, int $brzProjectId): JsonResponse
    {
        try {
            $logs = $this->waveService->getProjectLogsInWave($id, $brzProjectId);
            return new JsonResponse([
                'success' => true,
                'data' => $logs
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/waves/:id/restart-all
     * Массовый перезапуск миграций в волне
     */
    public function restartAllMigrations(Request $request, string $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                $data = $request->request->all();
            }

            // Опционально: список UUID для перезапуска (если пустой - все миграции)
            $mbUuids = $data['mb_uuids'] ?? [];
            if (!empty($mbUuids) && !is_array($mbUuids)) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'mb_uuids должен быть массивом'
                ], 400);
            }

            $params = [];
            if (isset($data['mb_site_id'])) {
                $params['mb_site_id'] = $data['mb_site_id'];
            }
            if (isset($data['mb_secret'])) {
                $params['mb_secret'] = $data['mb_secret'];
            }

            $result = $this->waveService->restartAllMigrationsInWave($id, $mbUuids, $params);

            return new JsonResponse([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['results']
            ], 200);

        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
