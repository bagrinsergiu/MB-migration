<?php

namespace Dashboard\Services;

use Exception;

/**
 * MigrationService
 * 
 * Бизнес-логика для работы с миграциями
 */
class MigrationService
{
    /**
     * @var DatabaseService
     */
    private $dbService;
    /**
     * @var ApiProxyService
     */
    private $apiProxy;

    public function __construct()
    {
        $this->dbService = new DatabaseService();
        $this->apiProxy = new ApiProxyService();
    }

    /**
     * Получить список всех миграций с объединенными данными
     * 
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function getMigrationsList(array $filters = []): array
    {
        $mappings = $this->dbService->getMigrationsList();
        $results = $this->dbService->getMigrationResults(1000);

        // Объединяем данные
        $migrations = [];
        foreach ($mappings as $mapping) {
            $mbUuid = $mapping['mb_project_uuid'];
            $brzId = $mapping['brz_project_id'];

            // Ищем результат миграции
            $result = null;
            foreach ($results as $res) {
                if ($res['mb_project_uuid'] === $mbUuid && $res['brz_project_id'] == $brzId) {
                    $result = $res;
                    break;
                }
            }

            $resultData = $result ? json_decode($result['result_json'] ?? '{}', true) : null;
            
            $migration = [
                'id' => $brzId,
                'mb_project_uuid' => $mbUuid,
                'brz_project_id' => $brzId,
                'created_at' => $mapping['created_at'],
                'updated_at' => $mapping['updated_at'],
                'changes_json' => json_decode($mapping['changes_json'] ?? '{}', true),
                'status' => $this->determineStatus($result),
                'result' => $resultData,
                'migration_uuid' => $result['migration_uuid'] ?? null,
                // Дополнительные поля из результата миграции
                'brizy_project_domain' => $resultData['brizy_project_domain'] ?? null,
                'mb_project_domain' => $resultData['mb_project_domain'] ?? null,
                'mb_site_id' => $resultData['mb_site_id'] ?? null,
                'mb_product_name' => $resultData['mb_product_name'] ?? null,
                'theme' => $resultData['theme'] ?? null,
                'progress' => $resultData['progress'] ?? null,
                'migration_id' => $resultData['migration_id'] ?? null,
                'date' => $resultData['date'] ?? null,
            ];

            // Применяем фильтры
            if ($this->matchesFilters($migration, $filters)) {
                $migrations[] = $migration;
            }
        }

        return $migrations;
    }

    /**
     * Определить статус миграции
     * 
     * @param array|null $result
     * @return string
     */
    private function determineStatus(?array $result, ?array $mapping = null): string
    {
        // Сначала проверяем статус из changes_json в mapping (это обновляется wrapper скриптом)
        if ($mapping && !empty($mapping['changes_json'])) {
            $changesJson = is_string($mapping['changes_json']) 
                ? json_decode($mapping['changes_json'], true) 
                : $mapping['changes_json'];
            
            if (isset($changesJson['status'])) {
                if ($changesJson['status'] === 'completed') {
                    return 'completed';
                }
                if ($changesJson['status'] === 'in_progress') {
                    return 'in_progress';
                }
                if ($changesJson['status'] === 'error') {
                    return 'error';
                }
            }
        }
        
        // Затем проверяем статус из result (таблица migration_result_list)
        if (!$result) {
            return 'pending';
        }

        $resultData = json_decode($result['result_json'] ?? '{}', true);
        
        // Проверяем статус в данных результата (может быть в value.status)
        $status = null;
        if (isset($resultData['value']['status'])) {
            $status = $resultData['value']['status'];
        } elseif (isset($resultData['status'])) {
            $status = $resultData['status'];
        }
        
        if ($status) {
            if ($status === 'success') {
                return 'success';
            }
            if ($status === 'error' || $status === 'failed') {
                return 'error';
            }
        }

        // Если есть данные о прогрессе, считаем что в процессе
        if (isset($resultData['value']['progress']) || isset($resultData['progress']) || 
            isset($resultData['value']['brizy_project_id']) || isset($resultData['brizy_project_id'])) {
            return 'in_progress';
        }

        return 'pending';
    }

    /**
     * Проверить соответствие фильтрам
     * 
     * @param array $migration
     * @param array $filters
     * @return bool
     */
    private function matchesFilters(array $migration, array $filters): bool
    {
        if (isset($filters['status']) && $migration['status'] !== $filters['status']) {
            return false;
        }

        if (isset($filters['mb_project_uuid']) && 
            strpos($migration['mb_project_uuid'], $filters['mb_project_uuid']) === false) {
            return false;
        }

        if (isset($filters['brz_project_id']) && 
            $migration['brz_project_id'] != $filters['brz_project_id']) {
            return false;
        }

        return true;
    }

    /**
     * Запустить миграцию
     * 
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function runMigration(array $params): array
    {
        // Получаем настройки по умолчанию
        $defaultSettings = $this->dbService->getSettings();
        
        // Используем настройки по умолчанию, если параметры не переданы
        if (empty($params['mb_site_id']) && !empty($defaultSettings['mb_site_id'])) {
            $params['mb_site_id'] = $defaultSettings['mb_site_id'];
        }
        if (empty($params['mb_secret']) && !empty($defaultSettings['mb_secret'])) {
            $params['mb_secret'] = $defaultSettings['mb_secret'];
        }
        
        // Создаем запись в БД сразу при запуске, чтобы избежать 404
        $brzProjectId = (int)($params['brz_project_id'] ?? 0);
        $mbProjectUuid = $params['mb_project_uuid'] ?? '';
        
        if ($brzProjectId > 0 && !empty($mbProjectUuid)) {
            try {
                // Создаем/обновляем маппинг сразу при запуске
                $this->dbService->upsertMigrationMapping(
                    $brzProjectId,
                    $mbProjectUuid,
                    [
                        'status' => 'in_progress',
                        'started_at' => date('Y-m-d H:i:s'),
                        'mb_site_id' => $params['mb_site_id'] ?? null,
                        'mb_page_slug' => $params['mb_page_slug'] ?? null,
                        'mb_secret' => isset($params['mb_secret']) ? '***' : null, // Не сохраняем секрет
                    ]
                );
            } catch (Exception $e) {
                // Логируем ошибку, но не прерываем выполнение
                error_log("Ошибка создания записи миграции: " . $e->getMessage());
            }
        }
        
        try {
            // Запускаем через прокси
            $result = $this->apiProxy->runMigration($params);
            
            // Проверяем, что результат содержит необходимые данные
            if (!isset($result['success']) || !isset($result['data'])) {
                return [
                    'success' => false,
                    'http_code' => 500,
                    'data' => ['error' => 'Некорректный ответ от API миграции'],
                    'raw_data' => $result
                ];
            }
            
            // Если миграция не успешна, возвращаем ошибку сразу
            if (!$result['success']) {
                $errorMessage = 'Миграция завершилась с ошибкой';
                if (isset($result['data']['error'])) {
                    $errorMessage = is_string($result['data']['error']) ? $result['data']['error'] : json_encode($result['data']['error']);
                } elseif (isset($result['raw_data']['error'])) {
                    $errorMessage = is_string($result['raw_data']['error']) ? $result['raw_data']['error'] : json_encode($result['raw_data']['error']);
                }
                
                // Обновляем статус в БД на ошибку
                if ($brzProjectId > 0 && !empty($mbProjectUuid)) {
                    try {
                        $this->dbService->upsertMigrationMapping(
                            $brzProjectId,
                            $mbProjectUuid,
                            [
                                'status' => 'error',
                                'error' => $errorMessage,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]
                        );
                    } catch (Exception $e) {
                        error_log("Ошибка обновления статуса миграции: " . $e->getMessage());
                    }
                }
                
                return [
                    'success' => false,
                    'http_code' => isset($result['http_code']) ? (int)$result['http_code'] : 400,
                    'data' => ['error' => $errorMessage],
                    'raw_data' => $result['raw_data'] ?? ['error' => $errorMessage]
                ];
            }
            
            $migrationData = $result['data'];
            
            // Проверяем, что данные миграции есть
            if (empty($migrationData)) {
                return [
                    'success' => false,
                    'http_code' => 500,
                    'data' => ['error' => 'Пустой ответ от API миграции'],
                    'raw_data' => $result
                ];
            }
        } catch (Exception $e) {
            // Если произошла ошибка в прокси, обновляем статус в БД
            if ($brzProjectId > 0 && !empty($mbProjectUuid)) {
                try {
                    $this->dbService->upsertMigrationMapping(
                        $brzProjectId,
                        $mbProjectUuid,
                        [
                            'status' => 'error',
                            'error' => $e->getMessage(),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                } catch (Exception $dbEx) {
                    error_log("Ошибка обновления статуса миграции: " . $dbEx->getMessage());
                }
            }
            
            // Если произошла ошибка в прокси, возвращаем её
            return [
                'success' => false,
                'http_code' => 400,
                'data' => ['error' => $e->getMessage()],
                'raw_data' => ['error' => $e->getMessage()]
            ];
        }

        // Сохраняем результат в migration_result_list
        if (isset($migrationData['brizy_project_id']) && isset($migrationData['mb_uuid'])) {
            $migrationUuid = time() . random_int(100, 999); // Генерируем UUID для миграции
            
            try {
                $this->dbService->saveMigrationResult([
                    'migration_uuid' => $migrationUuid,
                    'brz_project_id' => (int)$migrationData['brizy_project_id'],
                    'brizy_project_domain' => $migrationData['brizy_project_domain'] ?? '',
                    'mb_project_uuid' => $migrationData['mb_uuid'],
                    'result_json' => json_encode($migrationData)
                ]);
            } catch (Exception $e) {
                // Логируем ошибку, но не прерываем выполнение
                error_log("Ошибка сохранения результата миграции: " . $e->getMessage());
            }
        }

        // Сохраняем/обновляем маппинг в migrations_mapping с финальными данными
        if ($result['success'] && isset($migrationData['brizy_project_id']) && isset($migrationData['mb_uuid'])) {
            try {
                $this->dbService->upsertMigrationMapping(
                    (int)$migrationData['brizy_project_id'],
                    $migrationData['mb_uuid'],
                    [
                        'date' => $migrationData['date'] ?? date('Y-m-d'),
                        'status' => $migrationData['status'] ?? 'success',
                        'mb_site_id' => $migrationData['mb_site_id'] ?? $params['mb_site_id'] ?? null,
                        'mb_product_name' => $migrationData['mb_product_name'] ?? null,
                        'theme' => $migrationData['theme'] ?? null,
                        'migration_id' => $migrationData['migration_id'] ?? null,
                    ]
                );
            } catch (Exception $e) {
                error_log("Ошибка сохранения маппинга: " . $e->getMessage());
            }
        }

        return $result;
    }

    /**
     * Получить детали миграции
     * 
     * @param int $brzProjectId
     * @return array|null
     * @throws Exception
     */
    public function getMigrationDetails(int $brzProjectId): ?array
    {
        $mapping = $this->dbService->getMigrationById($brzProjectId);
        if (!$mapping) {
            return null;
        }

        $result = $this->dbService->getMigrationResultByUuid($mapping['mb_project_uuid']);

        $resultData = $result ? json_decode($result['result_json'] ?? '{}', true) : null;
        
        // Парсим changes_json из mapping
        $changesJson = [];
        if (!empty($mapping['changes_json'])) {
            $changesJson = is_string($mapping['changes_json']) 
                ? json_decode($mapping['changes_json'], true) 
                : $mapping['changes_json'];
        }
        
        // Определяем статус: сначала проверяем changes_json из mapping, потом result
        $status = $this->determineStatus($result, $mapping);
        
        // Извлекаем данные из value, если они там находятся
        $migrationValue = null;
        if ($resultData) {
            $migrationValue = $resultData['value'] ?? $resultData;
        }
        
        return [
            'mapping' => $mapping,
            'result' => $result ? [
                'migration_uuid' => $result['migration_uuid'] ?? null,
                'result_json' => $resultData,
            ] : null,
            'result_data' => $migrationValue, // Добавляем извлеченные данные из value
            'status' => $status,
            'migration_uuid' => $result['migration_uuid'] ?? null,
            'brizy_project_domain' => $migrationValue['brizy_project_domain'] ?? $resultData['brizy_project_domain'] ?? $changesJson['brizy_project_domain'] ?? null,
            'mb_project_domain' => $migrationValue['mb_project_domain'] ?? $resultData['mb_project_domain'] ?? $changesJson['mb_project_domain'] ?? null,
            'progress' => $migrationValue['progress'] ?? $resultData['progress'] ?? null,
            'warnings' => $migrationValue['message']['warning'] ?? $resultData['message']['warning'] ?? [],
        ];
    }
}
