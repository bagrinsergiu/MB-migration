<?php

namespace MBMigration\Core;

use Exception;
use MBMigration\Layer\DataSource\driver\MySQL;

/**
 * MigrationStatusService
 * 
 * Класс для управления статусом миграций
 * Синхронизирует данные между БД и lock-файлами
 */
class MigrationStatusService
{
    /**
     * @var MySQL|null Подключение к БД
     */
    private $db = null;

    /**
     * @var string Путь к директории с lock-файлами
     */
    private $cachePath;

    /**
     * Безопасный вызов Logger (не падает если Logger не инициализирован)
     */
    private function log(string $level, string $message, array $context = []): void
    {
        try {
            $logger = Logger::instance();
            switch ($level) {
                case 'debug':
                    $logger->debug($message, $context);
                    break;
                case 'info':
                    $logger->info($message, $context);
                    break;
                case 'warning':
                    $logger->warning($message, $context);
                    break;
                case 'error':
                    $logger->error($message, $context);
                    break;
            }
        } catch (Exception $e) {
            // Logger не инициализирован, игнорируем
        }
    }

    public function __construct(?MySQL $db = null, ?string $cachePath = null)
    {
        // Используем переданный путь или пытаемся получить из Config, или используем системный temp
        if ($cachePath !== null) {
            $this->cachePath = $cachePath;
        } elseif (property_exists(Config::class, 'cachePath') && !empty(Config::$cachePath)) {
            $this->cachePath = Config::$cachePath;
        } else {
            $this->cachePath = sys_get_temp_dir() . '/migration_cache';
        }
        
        if ($db === null) {
            // Создаем подключение к БД используя конфигурацию из Config
            // Проверяем, что Config инициализирован
            if (property_exists(Config::class, 'mgConfigMySQL') && !empty(Config::$mgConfigMySQL)) {
                try {
                    $this->db = new MySQL(
                        Config::$mgConfigMySQL['dbUser'],
                        Config::$mgConfigMySQL['dbPass'],
                        Config::$mgConfigMySQL['dbName'],
                        Config::$mgConfigMySQL['dbHost']
                    );
                    $this->db->doConnect();
                } catch (Exception $e) {
                    $this->log('warning', "[Migration Status Service] Failed to connect to database", [
                        'error' => $e->getMessage()
                    ]);
                    // Продолжаем работу без БД, используя только lock-файлы
                }
            } else {
                // Config не инициализирован, работаем только с lock-файлами
                $this->log('debug', "[Migration Status Service] Config not initialized, using lock files only");
            }
        } else {
            $this->db = $db;
        }
    }

    /**
     * Сохранить параметры веб-хука и начать миграцию
     * 
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @param string|null $webhookUrl URL веб-хука
     * @param string|null $webhookMbProjectUuid UUID проекта для веб-хука
     * @param int|null $webhookBrzProjectId ID проекта Brizy для веб-хука
     * @return bool
     */
    public function saveWebhookParams(
        string $mbProjectUuid,
        int $brzProjectId,
        ?string $webhookUrl = null,
        ?string $webhookMbProjectUuid = null,
        ?int $webhookBrzProjectId = null
    ): bool {
        try {
            $now = date('Y-m-d H:i:s');
            
            // Сохраняем в БД
            if ($this->db !== null) {
                try {
                    $existing = $this->db->find(
                        'SELECT * FROM migration_status WHERE mb_project_uuid = ? AND brz_project_id = ?',
                        [$mbProjectUuid, $brzProjectId]
                    );

                    $data = [
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => $brzProjectId,
                        'webhook_url' => $webhookUrl,
                        'webhook_mb_project_uuid' => $webhookMbProjectUuid,
                        'webhook_brz_project_id' => $webhookBrzProjectId,
                        'status' => 'in_progress',
                        'started_at' => $now,
                        'updated_at' => $now
                    ];

                    if ($existing) {
                        // Обновляем существующую запись
                        $this->db->update('migration_status', $data, [
                            'mb_project_uuid' => $mbProjectUuid,
                            'brz_project_id' => $brzProjectId
                        ]);
                    } else {
                        // Создаем новую запись
                        $this->db->insert('migration_status', $data);
                    }

                    $this->log('info', "[Migration Status Service] Webhook params saved to database", [
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => $brzProjectId
                    ]);
                } catch (Exception $e) {
                    $this->log('error', "[Migration Status Service] Failed to save to database", [
                        'error' => $e->getMessage(),
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => $brzProjectId
                    ]);
                }
            }

            // Сохраняем в lock-файл
            $this->updateLockFile($mbProjectUuid, $brzProjectId, [
                'webhook_url' => $webhookUrl,
                'webhook_mb_project_uuid' => $webhookMbProjectUuid,
                'webhook_brz_project_id' => $webhookBrzProjectId,
                'status' => 'in_progress',
                'started_at' => $now
            ]);

            return true;
        } catch (Exception $e) {
            $this->log('error', "[Migration Status Service] Failed to save webhook params", [
                'error' => $e->getMessage(),
                'mb_project_uuid' => $mbProjectUuid,
                'brz_project_id' => $brzProjectId
            ]);
            return false;
        }
    }

    /**
     * Обновить статус миграции
     * 
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @param string $status Статус: 'pending', 'in_progress', 'completed', 'error'
     * @param array $additionalData Дополнительные данные (результат миграции, ошибка, прогресс)
     * @return bool
     */
    public function updateStatus(
        string $mbProjectUuid,
        int $brzProjectId,
        string $status,
        array $additionalData = []
    ): bool {
        try {
            $now = date('Y-m-d H:i:s');
            $updateData = [
                'status' => $status,
                'updated_at' => $now
            ];

            // Добавляем дополнительные данные
            if (isset($additionalData['migration_uuid'])) {
                $updateData['migration_uuid'] = $additionalData['migration_uuid'];
            }
            if (isset($additionalData['migration_id'])) {
                $updateData['migration_id'] = $additionalData['migration_id'];
            }
            if (isset($additionalData['brizy_project_id'])) {
                $updateData['brizy_project_id'] = $additionalData['brizy_project_id'];
            }
            if (isset($additionalData['brizy_project_domain'])) {
                $updateData['brizy_project_domain'] = $additionalData['brizy_project_domain'];
            }
            if (isset($additionalData['error'])) {
                $updateData['error'] = $additionalData['error'];
            }
            if (isset($additionalData['progress'])) {
                $updateData['progress'] = is_string($additionalData['progress']) 
                    ? $additionalData['progress'] 
                    : json_encode($additionalData['progress'], JSON_UNESCAPED_UNICODE);
            }

            if ($status === 'completed') {
                $updateData['completed_at'] = $now;
            } elseif ($status === 'error') {
                $updateData['failed_at'] = $now;
            }

            // Обновляем в БД
            if ($this->db !== null) {
                try {
                    $this->db->update('migration_status', $updateData, [
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => $brzProjectId
                    ]);

                    $this->log('info', "[Migration Status Service] Status updated in database", [
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => $brzProjectId,
                        'status' => $status
                    ]);
                } catch (Exception $e) {
                    $this->log('error', "[Migration Status Service] Failed to update status in database", [
                        'error' => $e->getMessage(),
                        'mb_project_uuid' => $mbProjectUuid,
                        'brz_project_id' => $brzProjectId
                    ]);
                }
            }

            // Обновляем lock-файл
            $lockData = array_merge(['status' => $status], $additionalData);
            if ($status === 'completed') {
                $lockData['completed_at'] = $now;
            } elseif ($status === 'error') {
                $lockData['failed_at'] = $now;
            }
            $this->updateLockFile($mbProjectUuid, $brzProjectId, $lockData);

            return true;
        } catch (Exception $e) {
            $this->log('error', "[Migration Status Service] Failed to update status", [
                'error' => $e->getMessage(),
                'mb_project_uuid' => $mbProjectUuid,
                'brz_project_id' => $brzProjectId,
                'status' => $status
            ]);
            return false;
        }
    }

    /**
     * Получить статус миграции
     * 
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @return array|null Данные о миграции или null если не найдена
     */
    public function getStatus(string $mbProjectUuid, int $brzProjectId): ?array
    {
        try {
            // Сначала пытаемся получить из lock-файла (более актуальные данные)
            $lockFile = $this->getLockFilePath($mbProjectUuid, $brzProjectId);
            if (file_exists($lockFile)) {
                $lockContent = @file_get_contents($lockFile);
                if ($lockContent) {
                    $lockData = json_decode($lockContent, true);
                    if ($lockData) {
                        // Пытаемся дополнить данными из БД
                        $dbData = null;
                        if ($this->db !== null) {
                            try {
                                $dbData = $this->db->find(
                                    'SELECT * FROM migration_status WHERE mb_project_uuid = ? AND brz_project_id = ?',
                                    [$mbProjectUuid, $brzProjectId]
                                );
                            } catch (Exception $e) {
                                $this->log('warning', "[Migration Status Service] Failed to read from database", [
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }

                        // Объединяем данные (lock-файл имеет приоритет для текущего статуса)
                        $result = $this->mergeStatusData($lockData, $dbData);
                        return $result;
                    }
                }
            }

            // Если lock-файл не найден, пытаемся получить из БД
            if ($this->db !== null) {
                try {
                    $dbData = $this->db->find(
                        'SELECT * FROM migration_status WHERE mb_project_uuid = ? AND brz_project_id = ?',
                        [$mbProjectUuid, $brzProjectId]
                    );
                    if ($dbData) {
                        return $this->formatStatusData($dbData);
                    }
                } catch (Exception $e) {
                    $this->log('warning', "[Migration Status Service] Failed to read from database", [
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return null;
        } catch (Exception $e) {
            $this->log('error', "[Migration Status Service] Failed to get status", [
                'error' => $e->getMessage(),
                'mb_project_uuid' => $mbProjectUuid,
                'brz_project_id' => $brzProjectId
            ]);
            return null;
        }
    }

    /**
     * Обновить lock-файл
     * 
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @param array $data Данные для обновления
     * @return void
     */
    private function updateLockFile(string $mbProjectUuid, int $brzProjectId, array $data): void
    {
        try {
            $lockFile = $this->getLockFilePath($mbProjectUuid, $brzProjectId);
            
            // Читаем существующие данные
            $existingData = [];
            if (file_exists($lockFile)) {
                $lockContent = @file_get_contents($lockFile);
                if ($lockContent) {
                    $decoded = json_decode($lockContent, true);
                    if ($decoded) {
                        $existingData = $decoded;
                    }
                }
            }

            // Объединяем данные
            $mergedData = array_merge($existingData, $data);
            
            // Сохраняем обновленные данные
            file_put_contents($lockFile, json_encode($mergedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (Exception $e) {
            $this->log('warning', "[Migration Status Service] Failed to update lock file", [
                'error' => $e->getMessage(),
                'mb_project_uuid' => $mbProjectUuid,
                'brz_project_id' => $brzProjectId
            ]);
        }
    }

    /**
     * Получить путь к lock-файлу
     * 
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @return string
     */
    private function getLockFilePath(string $mbProjectUuid, int $brzProjectId): string
    {
        return $this->cachePath . "/" . $mbProjectUuid . "-" . $brzProjectId . ".lock";
    }

    /**
     * Объединить данные из lock-файла и БД
     * 
     * @param array $lockData Данные из lock-файла
     * @param array|null $dbData Данные из БД
     * @return array
     */
    private function mergeStatusData(array $lockData, ?array $dbData): array
    {
        $result = $lockData;

        // Дополняем данными из БД, если их нет в lock-файле
        if ($dbData) {
            if (!isset($result['webhook_url']) && isset($dbData['webhook_url'])) {
                $result['webhook_url'] = $dbData['webhook_url'];
            }
            if (!isset($result['webhook_mb_project_uuid']) && isset($dbData['webhook_mb_project_uuid'])) {
                $result['webhook_mb_project_uuid'] = $dbData['webhook_mb_project_uuid'];
            }
            if (!isset($result['webhook_brz_project_id']) && isset($dbData['webhook_brz_project_id'])) {
                $result['webhook_brz_project_id'] = $dbData['webhook_brz_project_id'];
            }
        }

        return $this->formatStatusData($result);
    }

    /**
     * Форматировать данные статуса для ответа API
     * 
     * @param array $data Сырые данные
     * @return array Отформатированные данные
     */
    private function formatStatusData(array $data): array
    {
        $result = [
            'status' => $data['status'] ?? 'pending',
            'mb_project_uuid' => $data['mb_project_uuid'] ?? null,
            'brz_project_id' => $data['brz_project_id'] ?? null
        ];

        // Сохраняем параметры вебхука (нужны для callWebhookOnCompletion)
        if (isset($data['webhook_url'])) {
            $result['webhook_url'] = $data['webhook_url'];
        }
        if (isset($data['webhook_mb_project_uuid'])) {
            $result['webhook_mb_project_uuid'] = $data['webhook_mb_project_uuid'];
        }
        if (isset($data['webhook_brz_project_id'])) {
            $result['webhook_brz_project_id'] = $data['webhook_brz_project_id'];
        }

        // Добавляем прогресс если есть
        if (isset($data['progress'])) {
            $progress = is_string($data['progress']) ? json_decode($data['progress'], true) : $data['progress'];
            if ($progress) {
                $result['progress'] = [
                    'total_pages' => $progress['Total'] ?? $progress['total_pages'] ?? 0,
                    'processed_pages' => $progress['Success'] ?? $progress['processed_pages'] ?? 0,
                    'progress_percent' => $this->calculateProgressPercent(
                        $progress['Total'] ?? $progress['total_pages'] ?? 0,
                        $progress['Success'] ?? $progress['processed_pages'] ?? 0
                    ),
                    'current_stage' => $data['current_stage'] ?? null
                ];
            }
        } elseif (isset($data['total_pages']) || isset($data['processed_pages'])) {
            // Если прогресс хранится отдельными полями в lock-файле
            $result['progress'] = [
                'total_pages' => $data['total_pages'] ?? 0,
                'processed_pages' => $data['processed_pages'] ?? 0,
                'progress_percent' => $this->calculateProgressPercent(
                    $data['total_pages'] ?? 0,
                    $data['processed_pages'] ?? 0
                ),
                'current_stage' => $data['current_stage'] ?? null
            ];
        }

        // Добавляем временные метки
        if (isset($data['started_at'])) {
            $result['started_at'] = $this->formatDateTime($data['started_at']);
        }
        if (isset($data['updated_at'])) {
            $result['updated_at'] = $this->formatDateTime($data['updated_at']);
        }
        if (isset($data['completed_at'])) {
            $result['completed_at'] = $this->formatDateTime($data['completed_at']);
        }
        if (isset($data['failed_at'])) {
            $result['failed_at'] = $this->formatDateTime($data['failed_at']);
        }

        // Добавляем данные результата для завершенных миграций
        if ($result['status'] === 'completed') {
            if (isset($data['brizy_project_id'])) {
                $result['brizy_project_id'] = $data['brizy_project_id'];
            }
            if (isset($data['brizy_project_domain'])) {
                $result['brizy_project_domain'] = $data['brizy_project_domain'];
            }
            if (isset($data['migration_id'])) {
                $result['migration_id'] = $data['migration_id'];
            }
        }

        // Добавляем ошибку для статуса error
        if ($result['status'] === 'error' && isset($data['error'])) {
            $result['error'] = $data['error'];
        }

        return $result;
    }

    /**
     * Вычислить процент выполнения
     * 
     * @param int $totalPages Общее количество страниц
     * @param int $processedPages Обработанное количество страниц
     * @return int Процент выполнения (0-100)
     */
    private function calculateProgressPercent(int $totalPages, int $processedPages): int
    {
        if ($totalPages <= 0) {
            return 0;
        }

        $percent = (int)round(($processedPages / $totalPages) * 100);
        return min(max($percent, 0), 100);
    }

    /**
     * Форматировать дату/время в ISO 8601
     * 
     * @param string|int $dateTime Дата/время (строка или timestamp)
     * @return string Отформатированная дата в ISO 8601
     */
    private function formatDateTime($dateTime): string
    {
        if (is_numeric($dateTime)) {
            return date('c', (int)$dateTime);
        } else {
            $timestamp = strtotime($dateTime);
            return $timestamp !== false ? date('c', $timestamp) : $dateTime;
        }
    }
}
