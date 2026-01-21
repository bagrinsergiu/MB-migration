<?php

namespace Dashboard\Services;

use Exception;
use MBMigration\Layer\DataSource\driver\MySQL;

/**
 * DatabaseService
 * 
 * КРИТИЧЕСКИ ВАЖНО: Все операции записи (INSERT, UPDATE, DELETE) 
 * только в базу mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com
 */
class DatabaseService
{
    // Разрешенный хост для записи (строгая проверка)
    private const ALLOWED_WRITE_HOST = 'mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com';

    /** @var MySQL|null */
    private $writeConnection = null;

    /**
     * Получить настройки подключения из переменных окружения
     * 
     * @return array
     * @throws Exception
     */
    private function getDbConfig(): array
    {
        $host = $_ENV['MG_DB_HOST'] ?? getenv('MG_DB_HOST');
        $dbName = $_ENV['MG_DB_NAME'] ?? getenv('MG_DB_NAME');
        $user = $_ENV['MG_DB_USER'] ?? getenv('MG_DB_USER');
        $pass = $_ENV['MG_DB_PASS'] ?? getenv('MG_DB_PASS');
        $port = $_ENV['MG_DB_PORT'] ?? getenv('MG_DB_PORT') ?? 3306;

        if (empty($host) || empty($dbName) || empty($user) || empty($pass)) {
            throw new Exception(
                'Не настроены переменные окружения для подключения к БД. ' .
                'Требуются: MG_DB_HOST, MG_DB_NAME, MG_DB_USER, MG_DB_PASS'
            );
        }

        return [
            'host' => $host,
            'dbName' => $dbName,
            'user' => $user,
            'pass' => $pass,
            'port' => (int)$port
        ];
    }

    /**
     * Получить подключение к базе для записи
     * 
     * @return MySQL
     * @throws Exception
     */
    public function getWriteConnection(): MySQL
    {
        if ($this->writeConnection === null) {
            $config = $this->getDbConfig();
            
            // КРИТИЧЕСКАЯ ПРОВЕРКА: Разрешен только один хост для записи
            $this->validateWriteHost($config['host']);
            
            $this->writeConnection = new MySQL(
                $config['user'],
                $config['pass'],
                $config['dbName'],
                $config['host']
            );
            $this->writeConnection->doConnect();
        }

        return $this->writeConnection;
    }

    /**
     * Валидация хоста перед записью
     * 
     * @param string $host
     * @return bool
     * @throws Exception
     */
    public function validateWriteHost(string $host): bool
    {
        if ($host !== self::ALLOWED_WRITE_HOST) {
            throw new Exception(
                "КРИТИЧЕСКАЯ ОШИБКА: Запись разрешена только в базу: " . self::ALLOWED_WRITE_HOST . 
                ". Попытка записи в: " . $host . 
                ". Проверьте переменную MG_DB_HOST в .env файле."
            );
        }
        return true;
    }

    /**
     * Получить список миграций из migrations_mapping
     * 
     * @return array
     * @throws Exception
     */
    public function getMigrationsList(): array
    {
        $db = $this->getWriteConnection();
        return $db->getAllRows(
            'SELECT * FROM migrations_mapping ORDER BY created_at DESC'
        );
    }

    /**
     * Получить миграцию по ID
     * 
     * @param int $brzProjectId
     * @return array|null
     * @throws Exception
     */
    public function getMigrationById(int $brzProjectId): ?array
    {
        $db = $this->getWriteConnection();
        $result = $db->find(
            'SELECT * FROM migrations_mapping WHERE brz_project_id = ?',
            [$brzProjectId]
        );
        return $result ?: null;
    }

    /**
     * Получить миграцию по MB UUID
     * 
     * @param string $mbProjectUuid
     * @return array|null
     * @throws Exception
     */
    public function getMigrationByUuid(string $mbProjectUuid): ?array
    {
        $db = $this->getWriteConnection();
        $result = $db->find(
            'SELECT * FROM migrations_mapping WHERE mb_project_uuid = ?',
            [$mbProjectUuid]
        );
        return $result ?: null;
    }

    /**
     * Получить результаты миграций из migration_result_list
     * 
     * @param int|null $limit
     * @return array
     * @throws Exception
     */
    public function getMigrationResults(?int $limit = 100): array
    {
        $db = $this->getWriteConnection();
        $sql = 'SELECT * FROM migration_result_list ORDER BY migration_uuid DESC';
        if ($limit) {
            $sql .= ' LIMIT ' . (int)$limit;
        }
        return $db->getAllRows($sql);
    }

    /**
     * Получить результат миграции по MB UUID
     * 
     * @param string $mbProjectUuid
     * @return array|null
     * @throws Exception
     */
    public function getMigrationResultByUuid(string $mbProjectUuid): ?array
    {
        $db = $this->getWriteConnection();
        $result = $db->find(
            'SELECT * FROM migration_result_list WHERE mb_project_uuid = ? ORDER BY migration_uuid DESC LIMIT 1',
            [$mbProjectUuid]
        );
        return $result ?: null;
    }

    /**
     * Создать или обновить маппинг миграции
     * 
     * @param int $brzProjectId
     * @param string $mbProjectUuid
     * @param array $metaData
     * @return int ID записи
     * @throws Exception
     */
    public function upsertMigrationMapping(int $brzProjectId, string $mbProjectUuid, array $metaData = []): int
    {
        $db = $this->getWriteConnection();
        
        // Проверяем существование
        $existing = $db->find(
            'SELECT * FROM migrations_mapping WHERE brz_project_id = ? AND mb_project_uuid = ?',
            [$brzProjectId, $mbProjectUuid]
        );

        $changesJson = json_encode($metaData);

        if ($existing) {
            // Обновляем существующую запись через прямой SQL
            // Используем рефлексию для доступа к PDO
            $reflection = new \ReflectionClass($db);
            $pdoProperty = $reflection->getProperty('pdo');
            $pdoProperty->setAccessible(true);
            $pdo = $pdoProperty->getValue($db);
            
            $stmt = $pdo->prepare(
                'UPDATE migrations_mapping SET changes_json = ?, updated_at = NOW() WHERE brz_project_id = ? AND mb_project_uuid = ?'
            );
            $stmt->execute([$changesJson, $brzProjectId, $mbProjectUuid]);
            return (int)$existing['brz_project_id'];
        } else {
            // Создаем новую запись
            return $db->insert('migrations_mapping', [
                'brz_project_id' => $brzProjectId,
                'mb_project_uuid' => $mbProjectUuid,
                'changes_json' => $changesJson
            ]);
        }
    }

    /**
     * Удалить маппинг миграции
     * 
     * @param int $brzProjectId
     * @param string $mbProjectUuid
     * @return bool
     * @throws Exception
     */
    public function deleteMigrationMapping(int $brzProjectId, string $mbProjectUuid): bool
    {
        $db = $this->getWriteConnection();
        return $db->delete(
            'migrations_mapping',
            'brz_project_id = ? AND mb_project_uuid = ?',
            [$brzProjectId, $mbProjectUuid]
        );
    }

    /**
     * Сохранить результат миграции в migration_result_list
     * 
     * @param array $data
     * @return int ID записи
     * @throws Exception
     */
    public function saveMigrationResult(array $data): int
    {
        $db = $this->getWriteConnection();
        
        // Проверяем обязательные поля
        $required = ['migration_uuid', 'brz_project_id', 'mb_project_uuid', 'result_json'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Обязательное поле отсутствует: {$field}");
            }
        }

        return $db->insert('migration_result_list', [
            'migration_uuid' => $data['migration_uuid'],
            'brz_project_id' => (int)$data['brz_project_id'],
            'brizy_project_domain' => $data['brizy_project_domain'] ?? '',
            'mb_project_uuid' => $data['mb_project_uuid'],
            'result_json' => is_string($data['result_json']) ? $data['result_json'] : json_encode($data['result_json'])
        ]);
    }

    /**
     * Обновить запись в migration_result_list
     * 
     * @param string $migrationUuid UUID миграции
     * @param string $mbProjectUuid UUID проекта MB
     * @param array $data Данные для обновления
     * @return void
     * @throws Exception
     */
    public function updateMigrationResult(string $migrationUuid, string $mbProjectUuid, array $data): void
    {
        $db = $this->getWriteConnection();
        
        $setParts = [];
        $params = [];
        
        if (isset($data['brz_project_id'])) {
            $setParts[] = 'brz_project_id = ?';
            $params[] = (int)$data['brz_project_id'];
        }
        
        if (isset($data['brizy_project_domain'])) {
            $setParts[] = 'brizy_project_domain = ?';
            $params[] = $data['brizy_project_domain'];
        }
        
        if (isset($data['result_json'])) {
            $setParts[] = 'result_json = ?';
            $params[] = is_string($data['result_json']) ? $data['result_json'] : json_encode($data['result_json']);
        }
        
        if (empty($setParts)) {
            return; // Нет данных для обновления
        }
        
        $sql = 'UPDATE migration_result_list SET ' . implode(', ', $setParts) . 
               ' WHERE migration_uuid = ? AND mb_project_uuid = ?';
        $params[] = $migrationUuid;
        $params[] = $mbProjectUuid;
        
        // Используем рефлексию для доступа к PDO
        $reflection = new \ReflectionClass($db);
        $pdoProperty = $reflection->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        $pdo = $pdoProperty->getValue($db);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Получить настройки дашборда
     * 
     * @return array
     * @throws Exception
     */
    public function getSettings(): array
    {
        $db = $this->getWriteConnection();
        
        // Используем таблицу migrations_mapping с специальным ключом для настроек
        // Или создаем отдельную таблицу dashboard_settings
        // Для простоты используем файл конфигурации
        $settingsFile = dirname(__DIR__, 2) . '/var/config/dashboard_settings.json';
        
        if (file_exists($settingsFile)) {
            $content = file_get_contents($settingsFile);
            $settings = json_decode($content, true);
            if ($settings) {
                return $settings;
            }
        }
        
        return [
            'mb_site_id' => null,
            'mb_secret' => null,
        ];
    }

    /**
     * Сохранить настройки дашборда
     * 
     * @param array $settings
     * @return void
     * @throws Exception
     */
    public function saveSettings(array $settings): void
    {
        $settingsFile = dirname(__DIR__, 2) . '/var/config/dashboard_settings.json';
        $settingsDir = dirname($settingsFile);
        
        // Создаем директорию если не существует
        if (!is_dir($settingsDir)) {
            mkdir($settingsDir, 0755, true);
        }
        
        // Получаем текущие настройки
        $currentSettings = $this->getSettings();
        
        // Обновляем только переданные значения
        foreach ($settings as $key => $value) {
            if ($value === null || $value === '') {
                unset($currentSettings[$key]);
            } else {
                $currentSettings[$key] = $value;
            }
        }
        
        // Сохраняем в файл
        file_put_contents($settingsFile, json_encode($currentSettings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Создать новую волну миграций
     * 
     * @param string $waveId Уникальный ID волны
     * @param string $name Название волны
     * @param array $projectUuids Массив UUID проектов
     * @param int $workspaceId ID workspace
     * @param string $workspaceName Название workspace
     * @param int $batchSize Размер батча для параллельного выполнения
     * @param bool $mgrManual Флаг ручной миграции
     * @return int ID записи
     * @throws Exception
     */
    public function createWave(
        string $waveId,
        string $name,
        array $projectUuids,
        int $workspaceId,
        string $workspaceName,
        int $batchSize = 3,
        bool $mgrManual = false
    ): int {
        $db = $this->getWriteConnection();
        
        // Сохраняем список UUID проектов в отдельную таблицу wave_migrations
        // Сначала создаем запись в таблице waves
        $waveData = [
            'wave_id' => $waveId,
            'name' => $name,
            'workspace_id' => $workspaceId,
            'workspace_name' => $workspaceName,
            'status' => 'pending',
            'progress_total' => count($projectUuids),
            'progress_completed' => 0,
            'progress_failed' => 0,
            'batch_size' => $batchSize,
            'mgr_manual' => $mgrManual ? 1 : 0,
        ];
        
        try {
            $waveIdDb = $db->insert('waves', $waveData);
        } catch (Exception $e) {
            // Если таблица waves еще не создана, используем старый способ
            $waveUuid = "wave_{$waveId}";
            $changesJson = [
                'wave_id' => $waveId,
                'wave_name' => $name,
                'workspace_id' => $workspaceId,
                'workspace_name' => $workspaceName,
                'project_uuids' => $projectUuids,
                'batch_size' => $batchSize,
                'mgr_manual' => $mgrManual,
                'status' => 'pending',
                'progress' => [
                    'total' => count($projectUuids),
                    'completed' => 0,
                    'failed' => 0,
                ],
                'migrations' => [],
                'created_at' => date('Y-m-d H:i:s'),
            ];
            return $this->upsertMigrationMapping(0, $waveUuid, $changesJson);
        }
        
        // Сохраняем список UUID проектов в wave_migrations (если таблица существует)
        // Пока сохраняем в старую структуру для обратной совместимости
        $waveUuid = "wave_{$waveId}";
        $changesJson = [
            'project_uuids' => $projectUuids,
            'migrations' => [],
        ];
        $this->upsertMigrationMapping(0, $waveUuid, $changesJson);
        
        // Создаем записи в migration_result_list для всех UUID проектов
        // Это позволяет сразу видеть список миграций в волне
        foreach ($projectUuids as $mbUuid) {
            try {
                $this->saveMigrationResult([
                    'migration_uuid' => $waveId,
                    'brz_project_id' => 0, // Пока проект не создан
                    'brizy_project_domain' => '',
                    'mb_project_uuid' => $mbUuid,
                    'result_json' => json_encode([
                        'status' => 'pending',
                        'message' => 'Миграция ожидает выполнения'
                    ])
                ]);
            } catch (Exception $e) {
                // Логируем ошибку, но не прерываем создание волны
                error_log('Ошибка создания записи в migration_result_list для UUID ' . $mbUuid . ': ' . $e->getMessage());
            }
        }
        
        return $waveIdDb;
    }

    /**
     * Получить информацию о волне по ID
     * 
     * @param string $waveId ID волны
     * @return array|null
     * @throws Exception
     */
    public function getWave(string $waveId): ?array
    {
        $db = $this->getWriteConnection();
        
        // Пытаемся получить из новой таблицы waves
        try {
            $wave = $db->find(
                'SELECT * FROM waves WHERE wave_id = ?',
                [$waveId]
            );
            
            if (!$wave) {
                return null;
            }
            
            // Получаем migrations из старой структуры для обратной совместимости (если есть)
            $waveUuid = "wave_{$waveId}";
            $mapping = $db->find(
                'SELECT * FROM migrations_mapping WHERE mb_project_uuid = ? AND brz_project_id = 0',
                [$waveUuid]
            );
            $changesJson = $mapping ? json_decode($mapping['changes_json'] ?? '{}', true) : [];
            
            // Получаем список UUID проектов:
            // 1. Сначала из migrations_mapping (где они сохраняются при создании волны)
            // 2. Если их нет, то из migration_result_list (для уже выполненных миграций)
            $projectUuids = $changesJson['project_uuids'] ?? [];
            
            if (empty($projectUuids)) {
                // Если project_uuids нет в migrations_mapping, получаем из migration_result_list
                $migrationResults = $db->getAllRows(
                    'SELECT DISTINCT mb_project_uuid FROM migration_result_list WHERE migration_uuid = ?',
                    [$waveId]
                );
                $projectUuids = array_column($migrationResults, 'mb_project_uuid');
            }
            
            return [
                'id' => $wave['wave_id'],
                'name' => $wave['name'],
                'workspace_id' => $wave['workspace_id'],
                'workspace_name' => $wave['workspace_name'] ?? '',
                'project_uuids' => $projectUuids,
                'batch_size' => (int)($wave['batch_size'] ?? 3),
                'mgr_manual' => (bool)($wave['mgr_manual'] ?? false),
                'status' => $wave['status'] ?? 'pending',
                'progress' => [
                    'total' => (int)($wave['progress_total'] ?? 0),
                    'completed' => (int)($wave['progress_completed'] ?? 0),
                    'failed' => (int)($wave['progress_failed'] ?? 0),
                ],
                'migrations' => $changesJson['migrations'] ?? [],
                'created_at' => $wave['created_at'],
                'updated_at' => $wave['updated_at'],
                'completed_at' => $wave['completed_at'] ?? null,
            ];
        } catch (Exception $e) {
            // Если таблица waves не существует, используем старый способ
            $waveUuid = "wave_{$waveId}";
            
            $mapping = $db->find(
                'SELECT * FROM migrations_mapping WHERE mb_project_uuid = ? AND brz_project_id = 0',
                [$waveUuid]
            );

            if (!$mapping) {
                return null;
            }

            $changesJson = json_decode($mapping['changes_json'] ?? '{}', true);
            
            return [
                'id' => $waveId,
                'name' => $changesJson['wave_name'] ?? '',
                'workspace_id' => $changesJson['workspace_id'] ?? null,
                'workspace_name' => $changesJson['workspace_name'] ?? '',
                'project_uuids' => $changesJson['project_uuids'] ?? [],
                'batch_size' => $changesJson['batch_size'] ?? 3,
                'mgr_manual' => $changesJson['mgr_manual'] ?? false,
                'status' => $changesJson['status'] ?? 'pending',
                'progress' => [
                    'total' => $changesJson['progress']['total'] ?? 0,
                    'completed' => $changesJson['progress']['completed'] ?? 0,
                    'failed' => $changesJson['progress']['failed'] ?? 0,
                ],
                'migrations' => $changesJson['migrations'] ?? [],
                'created_at' => $mapping['created_at'],
                'updated_at' => $mapping['updated_at'],
                'completed_at' => $changesJson['completed_at'] ?? null,
            ];
        }
    }

    /**
     * Обновить прогресс волны
     * 
     * @param string $waveId ID волны
     * @param array $progress Прогресс: ['total' => int, 'completed' => int, 'failed' => int]
     * @param array $migrations Массив миграций
     * @param string|null $status Статус волны
     * @return void
     * @throws Exception
     */
    public function updateWaveProgress(
        string $waveId,
        array $progress,
        array $migrations = [],
        ?string $status = null
    ): void {
        $db = $this->getWriteConnection();
        
        // Пытаемся обновить в новой таблице waves
        try {
            $reflection = new \ReflectionClass($db);
            $pdoProperty = $reflection->getProperty('pdo');
            $pdoProperty->setAccessible(true);
            $pdo = $pdoProperty->getValue($db);
            
            $updateFields = [
                'progress_total' => $progress['total'] ?? 0,
                'progress_completed' => $progress['completed'] ?? 0,
                'progress_failed' => $progress['failed'] ?? 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            if ($status !== null) {
                $updateFields['status'] = $status;
                if ($status === 'completed' || $status === 'error') {
                    $updateFields['completed_at'] = date('Y-m-d H:i:s');
                }
            }
            
            $setClause = [];
            $values = [];
            foreach ($updateFields as $field => $value) {
                $setClause[] = "{$field} = ?";
                $values[] = $value;
            }
            $values[] = $waveId;
            
            $sql = 'UPDATE waves SET ' . implode(', ', $setClause) . ' WHERE wave_id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
        } catch (Exception $e) {
            // Если таблица waves не существует, используем старый способ
            $waveUuid = "wave_{$waveId}";
            
            $mapping = $db->find(
                'SELECT * FROM migrations_mapping WHERE mb_project_uuid = ? AND brz_project_id = 0',
                [$waveUuid]
            );

            if (!$mapping) {
                throw new Exception("Волна с ID {$waveId} не найдена");
            }

            $changesJson = json_decode($mapping['changes_json'] ?? '{}', true);
            
            // Обновляем прогресс
            $changesJson['progress'] = $progress;
            
            // Обновляем миграции
            if (!empty($migrations)) {
                $changesJson['migrations'] = $migrations;
            }
            
            // Обновляем статус если указан
            if ($status !== null) {
                $changesJson['status'] = $status;
                if ($status === 'completed' || $status === 'error') {
                    $changesJson['completed_at'] = date('Y-m-d H:i:s');
                }
            }

            // Обновляем запись
            $reflection = new \ReflectionClass($db);
            $pdoProperty = $reflection->getProperty('pdo');
            $pdoProperty->setAccessible(true);
            $pdo = $pdoProperty->getValue($db);
            
            $stmt = $pdo->prepare(
                'UPDATE migrations_mapping SET changes_json = ?, updated_at = NOW() WHERE mb_project_uuid = ? AND brz_project_id = 0'
            );
            $stmt->execute([json_encode($changesJson), $waveUuid]);
        }
        
        // Также обновляем миграции в старой структуре для обратной совместимости
        if (!empty($migrations)) {
            $waveUuid = "wave_{$waveId}";
            $mapping = $db->find(
                'SELECT * FROM migrations_mapping WHERE mb_project_uuid = ? AND brz_project_id = 0',
                [$waveUuid]
            );
            
            if ($mapping) {
                $changesJson = json_decode($mapping['changes_json'] ?? '{}', true);
                $changesJson['migrations'] = $migrations;
                
                $reflection = new \ReflectionClass($db);
                $pdoProperty = $reflection->getProperty('pdo');
                $pdoProperty->setAccessible(true);
                $pdo = $pdoProperty->getValue($db);
                
                $stmt = $pdo->prepare(
                    'UPDATE migrations_mapping SET changes_json = ?, updated_at = NOW() WHERE mb_project_uuid = ? AND brz_project_id = 0'
                );
                $stmt->execute([json_encode($changesJson), $waveUuid]);
            }
        }
    }

    /**
     * Получить список всех волн
     * 
     * @return array
     * @throws Exception
     */
    public function getWavesList(): array
    {
        $db = $this->getWriteConnection();
        
        // Пытаемся получить из новой таблицы waves
        try {
            $waves = $db->getAllRows(
                "SELECT * FROM waves ORDER BY created_at DESC"
            );
            
            $result = [];
            foreach ($waves as $wave) {
                $progressTotal = (int)($wave['progress_total'] ?? 0);
                $progressCompleted = (int)($wave['progress_completed'] ?? 0);
                $progressFailed = (int)($wave['progress_failed'] ?? 0);
                $dbStatus = $wave['status'] ?? 'pending';
                
                // Пересчитываем статус на основе прогресса, если он не соответствует
                $calculatedStatus = $dbStatus;
                if ($progressTotal > 0) {
                    $totalProcessed = $progressCompleted + $progressFailed;
                    if ($totalProcessed >= $progressTotal) {
                        // Все миграции завершены
                        $calculatedStatus = $progressFailed > 0 ? 'error' : 'completed';
                    } elseif ($totalProcessed > 0) {
                        // Есть прогресс, но не все завершено
                        if ($dbStatus === 'pending') {
                            $calculatedStatus = 'in_progress';
                        }
                    }
                }
                
                // Используем пересчитанный статус, если он отличается от БД и БД статус не завершен
                // Это позволяет исправить случаи, когда статус в БД не обновился
                $finalStatus = ($calculatedStatus !== $dbStatus && 
                               ($dbStatus === 'in_progress' || $dbStatus === 'pending')) 
                    ? $calculatedStatus 
                    : $dbStatus;
                
                $result[] = [
                    'id' => $wave['wave_id'],
                    'name' => $wave['name'],
                    'workspace_id' => $wave['workspace_id'],
                    'workspace_name' => $wave['workspace_name'] ?? '',
                    'status' => $finalStatus,
                    'progress' => [
                        'total' => $progressTotal,
                        'completed' => $progressCompleted,
                        'failed' => $progressFailed,
                    ],
                    'created_at' => $wave['created_at'],
                    'updated_at' => $wave['updated_at'],
                    'completed_at' => $wave['completed_at'] ?? null,
                ];
            }
            
            return $result;
        } catch (Exception $e) {
            // Если таблица waves не существует, используем старый способ
            $mappings = $db->getAllRows(
                "SELECT * FROM migrations_mapping WHERE mb_project_uuid LIKE 'wave_%' AND brz_project_id = 0 ORDER BY created_at DESC"
            );

            $waves = [];
            foreach ($mappings as $mapping) {
                $changesJson = json_decode($mapping['changes_json'] ?? '{}', true);
                
                // Извлекаем wave_id из mb_project_uuid (формат: wave_{waveId})
                $waveId = str_replace('wave_', '', $mapping['mb_project_uuid']);
                
                $progress = $changesJson['progress'] ?? ['total' => 0, 'completed' => 0, 'failed' => 0];
                $progressTotal = (int)($progress['total'] ?? 0);
                $progressCompleted = (int)($progress['completed'] ?? 0);
                $progressFailed = (int)($progress['failed'] ?? 0);
                $dbStatus = $changesJson['status'] ?? 'pending';
                
                // Пересчитываем статус на основе прогресса, если он не соответствует
                $calculatedStatus = $dbStatus;
                if ($progressTotal > 0) {
                    $totalProcessed = $progressCompleted + $progressFailed;
                    if ($totalProcessed >= $progressTotal) {
                        // Все миграции завершены
                        $calculatedStatus = $progressFailed > 0 ? 'error' : 'completed';
                    } elseif ($totalProcessed > 0) {
                        // Есть прогресс, но не все завершено
                        if ($dbStatus === 'pending') {
                            $calculatedStatus = 'in_progress';
                        }
                    }
                }
                
                // Используем пересчитанный статус, если он отличается от БД и БД статус не завершен
                $finalStatus = ($calculatedStatus !== $dbStatus && 
                               ($dbStatus === 'in_progress' || $dbStatus === 'pending')) 
                    ? $calculatedStatus 
                    : $dbStatus;
                
                $waves[] = [
                    'id' => $waveId,
                    'name' => $changesJson['wave_name'] ?? '',
                    'workspace_id' => $changesJson['workspace_id'] ?? null,
                    'workspace_name' => $changesJson['workspace_name'] ?? '',
                    'status' => $finalStatus,
                    'progress' => [
                        'total' => $progressTotal,
                        'completed' => $progressCompleted,
                        'failed' => $progressFailed,
                    ],
                    'created_at' => $mapping['created_at'],
                    'updated_at' => $mapping['updated_at'],
                    'completed_at' => $changesJson['completed_at'] ?? null,
                ];
            }

            return $waves;
        }
    }

    /**
     * Получить все миграции, связанные с волной
     * Получает миграции напрямую из migration_result_list по migration_uuid = wave_id
     * 
     * @param string $waveId ID волны (совпадает с migration_uuid в migration_result_list)
     * @return array Массив миграций с деталями
     * @throws Exception
     */
    public function getWaveMigrations(string $waveId): array
    {
        $wave = $this->getWave($waveId);
        
        if (!$wave) {
            return [];
        }

        $db = $this->getWriteConnection();
        $migrations = [];
        
        // Получаем все миграции из migration_result_list по migration_uuid = wave_id
        $migrationResults = $db->getAllRows(
            'SELECT * FROM migration_result_list WHERE migration_uuid = ? ORDER BY created_at ASC',
            [$waveId]
        );
        
        if (empty($migrationResults)) {
            return [];
        }
        
        // Оптимизация: получаем все migrations_mapping одним запросом (избегаем N+1)
        $brzProjectIds = array_filter(array_column($migrationResults, 'brz_project_id'));
        $migrationsMapping = [];
        if (!empty($brzProjectIds)) {
            $placeholders = implode(',', array_fill(0, count($brzProjectIds), '?'));
            $mappings = $db->getAllRows(
                "SELECT * FROM migrations_mapping WHERE brz_project_id IN ($placeholders)",
                $brzProjectIds
            );
            // Создаем индекс по brz_project_id для быстрого поиска
            foreach ($mappings as $mapping) {
                $migrationsMapping[$mapping['brz_project_id']] = $mapping;
            }
        }
        
        // Для каждой миграции собираем полную информацию
        foreach ($migrationResults as $migrationResult) {
            $mbUuid = $migrationResult['mb_project_uuid'];
            $brzProjectId = $migrationResult['brz_project_id'];
            
            // Получаем данные из migrations_mapping (из кеша)
            $migrationMapping = $migrationsMapping[$brzProjectId] ?? null;
            
            // Парсим result_json (с защитой от больших/поврежденных JSON)
            $resultJson = $migrationResult['result_json'] ?? '{}';
            $resultData = null;
            $resultValue = null;
            if (!empty($resultJson) && is_string($resultJson)) {
                // Проверяем, не обрезан ли JSON
                $trimmed = trim($resultJson);
                if (!empty($trimmed) && (substr($trimmed, -1) === '}' || substr($trimmed, -1) === ']')) {
                    try {
                        $resultData = json_decode($trimmed, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $resultValue = $resultData['value'] ?? $resultData ?? null;
                        }
                    } catch (Exception $e) {
                        // Игнорируем ошибки парсинга JSON
                    }
                }
            }
            if ($resultData === null) {
                $resultData = [];
            }
            
            // Объединяем данные из разных источников
            $migrationChanges = $migrationMapping 
                ? json_decode($migrationMapping['changes_json'] ?? '{}', true) 
                : [];

            // Определяем статус: приоритет у данных из result_json (status напрямую), затем из result value, затем из mapping
            $status = $resultData['status'] 
                ?? $resultValue['status'] 
                ?? $migrationChanges['status'] 
                ?? 'completed';

            // Определяем domain: приоритет у данных из result, затем из migration_result_list, затем из mapping
            $brizyProjectDomain = $migrationResult['brizy_project_domain'] 
                ?? $resultValue['brizy_project_domain'] 
                ?? $migrationChanges['brizy_project_domain'] 
                ?? null;

            // Определяем completed_at
            $completedAt = $migrationResult['created_at'] 
                ?? ($migrationMapping ? $migrationMapping['updated_at'] : null);

            // Определяем error
            $error = $resultValue['error'] 
                ?? $migrationChanges['error'] 
                ?? null;

            // Собираем полную информацию о миграции
            $migrationData = [
                'mb_project_uuid' => $mbUuid,
                'brz_project_id' => $brzProjectId,
                'status' => $status,
                'brizy_project_domain' => $brizyProjectDomain,
                'error' => $error,
                'completed_at' => $completedAt,
                'migration_uuid' => $migrationResult['migration_uuid'],
                'migration_id' => $migrationMapping['id'] ?? null,
            ];

            // Добавляем дополнительные данные из result_json если есть
            if ($resultValue) {
                $migrationData['result_data'] = [
                    'migration_id' => $resultValue['migration_id'] ?? null,
                    'date' => $resultValue['date'] ?? null,
                    'theme' => $resultValue['theme'] ?? null,
                    'mb_product_name' => $resultValue['mb_product_name'] ?? null,
                    'mb_site_id' => $resultValue['mb_site_id'] ?? null,
                    'progress' => $resultValue['progress'] ?? null,
                    'DEV_MODE' => $resultValue['DEV_MODE'] ?? null,
                    'mb_project_domain' => $resultValue['mb_project_domain'] ?? null,
                    'warnings' => $resultValue['message']['warning'] ?? ($resultValue['message'] ?? []),
                ];
                
                // Если brizy_project_domain не найден в других источниках, берем из result
                if (!$brizyProjectDomain && isset($resultValue['brizy_project_domain'])) {
                    $migrationData['brizy_project_domain'] = $resultValue['brizy_project_domain'];
                }
                
                // Если brz_project_id не найден, берем из result
                if (!$migrationData['brz_project_id'] && isset($resultValue['brizy_project_id'])) {
                    $migrationData['brz_project_id'] = (int)$resultValue['brizy_project_id'];
                }
            }

            $migrations[] = $migrationData;
        }

        return $migrations;
    }

    /**
     * Получить маппинг проектов для волны
     * Получает все записи из migrations_mapping для проектов этой волны
     * 
     * @param string $waveId ID волны (совпадает с migration_uuid в migration_result_list)
     * @return array Массив маппингов с деталями
     * @throws Exception
     */
    public function getWaveMapping(string $waveId): array
    {
        $db = $this->getWriteConnection();
        
        // Получаем все миграции из migration_result_list по migration_uuid = wave_id
        $migrationResults = $db->getAllRows(
            'SELECT * FROM migration_result_list WHERE migration_uuid = ? ORDER BY created_at ASC',
            [$waveId]
        );
        
        if (empty($migrationResults)) {
            return [];
        }
        
        // Получаем все brz_project_id из результатов
        $brzProjectIds = array_filter(array_column($migrationResults, 'brz_project_id'));
        
        if (empty($brzProjectIds)) {
            return [];
        }
        
        // Получаем все записи из migrations_mapping для этих проектов
        $placeholders = implode(',', array_fill(0, count($brzProjectIds), '?'));
        $mappings = $db->getAllRows(
            "SELECT * FROM migrations_mapping WHERE brz_project_id IN ($placeholders) ORDER BY created_at DESC",
            $brzProjectIds
        );
        
        // Создаем индекс по brz_project_id для связи с migration_result_list
        $mappingsByBrzId = [];
        foreach ($mappings as $mapping) {
            $mappingsByBrzId[$mapping['brz_project_id']] = $mapping;
        }
        
        // Объединяем данные из migration_result_list и migrations_mapping
        $result = [];
        foreach ($migrationResults as $migrationResult) {
            $brzProjectId = $migrationResult['brz_project_id'];
            $mapping = $mappingsByBrzId[$brzProjectId] ?? null;
            
            if (!$mapping) {
                // Если маппинга нет, создаем базовую запись из migration_result_list
                $result[] = [
                    'id' => null,
                    'brz_project_id' => $brzProjectId,
                    'mb_project_uuid' => $migrationResult['mb_project_uuid'],
                    'brizy_project_domain' => $migrationResult['brizy_project_domain'] ?? null,
                    'changes_json' => null,
                    'created_at' => $migrationResult['created_at'],
                    'updated_at' => $migrationResult['created_at'],
                ];
                continue;
            }
            
            // Парсим changes_json
            $changesJson = json_decode($mapping['changes_json'] ?? '{}', true);
            
            $result[] = [
                'id' => $mapping['id'] ?? null,
                'brz_project_id' => $mapping['brz_project_id'],
                'mb_project_uuid' => $mapping['mb_project_uuid'],
                'brizy_project_domain' => $migrationResult['brizy_project_domain'] 
                    ?? $changesJson['brizy_project_domain'] 
                    ?? null,
                'changes_json' => $changesJson,
                'created_at' => $mapping['created_at'],
                'updated_at' => $mapping['updated_at'],
            ];
        }
        
        return $result;
    }
}
