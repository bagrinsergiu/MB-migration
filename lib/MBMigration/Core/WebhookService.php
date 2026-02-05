<?php

namespace MBMigration\Core;

use Exception;

/**
 * WebhookService
 * 
 * Класс для вызова веб-хуков с retry логикой
 * Реализует механизм повторных попыток с экспоненциальной задержкой
 */
class WebhookService
{
    /**
     * @var int Максимальное количество попыток
     */
    private $maxRetries;

    /**
     * @var int Базовая задержка в секундах
     */
    private $baseDelay;

    /**
     * @var int Таймаут запроса в секундах
     */
    private $timeout;

    public function __construct(int $maxRetries = 5, int $baseDelay = 5, int $timeout = 10)
    {
        $this->maxRetries = $maxRetries;
        $this->baseDelay = $baseDelay;
        $this->timeout = $timeout;
    }

    /**
     * Вызвать веб-хук с retry логикой
     * 
     * @param string $webhookUrl URL веб-хука
     * @param array $data Данные для отправки
     * @return bool true если успешно, false если все попытки неудачны
     * @throws Exception
     */
    public function callWebhook(string $webhookUrl, array $data): bool
    {
        if (empty($webhookUrl)) {
            Logger::instance()->warning("[Webhook Service] Webhook URL is empty, skipping webhook call");
            return false;
        }

        Logger::instance()->info("[Webhook Service] Starting webhook call", [
            'webhook_url' => $webhookUrl,
            'data_keys' => array_keys($data),
            'max_retries' => $this->maxRetries
        ]);

        $lastError = null;
        $lastHttpCode = null;

        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                Logger::instance()->info("[Webhook Service] Attempt {$attempt}/{$this->maxRetries}", [
                    'webhook_url' => $webhookUrl,
                    'attempt' => $attempt
                ]);

                $result = $this->executeWebhookCall($webhookUrl, $data);
                
                if ($result['success']) {
                    Logger::instance()->info("[Webhook Service] Webhook called successfully", [
                        'webhook_url' => $webhookUrl,
                        'attempt' => $attempt,
                        'http_code' => $result['http_code'],
                        'response' => substr($result['response'] ?? '', 0, 500)
                    ]);
                    return true;
                }

                $lastError = $result['error'];
                $lastHttpCode = $result['http_code'];

                Logger::instance()->warning("[Webhook Service] Webhook call failed", [
                    'webhook_url' => $webhookUrl,
                    'attempt' => $attempt,
                    'http_code' => $lastHttpCode,
                    'error' => $lastError
                ]);

                // Если не последняя попытка, ждем перед повтором
                if ($attempt < $this->maxRetries) {
                    $delay = $this->calculateDelay($attempt);
                    Logger::instance()->info("[Webhook Service] Waiting {$delay} seconds before retry", [
                        'webhook_url' => $webhookUrl,
                        'next_attempt' => $attempt + 1,
                        'delay_seconds' => $delay
                    ]);
                    sleep($delay);
                }

            } catch (Exception $e) {
                $lastError = $e->getMessage();
                Logger::instance()->error("[Webhook Service] Exception during webhook call", [
                    'webhook_url' => $webhookUrl,
                    'attempt' => $attempt,
                    'error' => $lastError,
                    'exception' => $e
                ]);

                // Если не последняя попытка, ждем перед повтором
                if ($attempt < $this->maxRetries) {
                    $delay = $this->calculateDelay($attempt);
                    sleep($delay);
                }
            }
        }

        // Все попытки неудачны - логируем ошибку
        Logger::instance()->error("[Webhook Service] Failed to call webhook after {$this->maxRetries} attempts", [
            'webhook_url' => $webhookUrl,
            'last_error' => $lastError,
            'last_http_code' => $lastHttpCode,
            'max_retries' => $this->maxRetries
        ]);

        return false;
    }

    /**
     * Выполнить один вызов веб-хука
     * 
     * @param string $webhookUrl URL веб-хука
     * @param array $data Данные для отправки
     * @return array Результат вызова ['success' => bool, 'http_code' => int, 'error' => string|null, 'response' => string|null]
     */
    private function executeWebhookCall(string $webhookUrl, array $data): array
    {
        $ch = curl_init($webhookUrl);
        
        if ($ch === false) {
            return [
                'success' => false,
                'http_code' => 0,
                'error' => 'Failed to initialize cURL',
                'response' => null
            ];
        }

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        if ($jsonData === false) {
            curl_close($ch);
            return [
                'success' => false,
                'http_code' => 0,
                'error' => 'Failed to encode data to JSON: ' . json_last_error_msg(),
                'response' => null
            ];
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $success = ($httpCode >= 200 && $httpCode < 300) && empty($error);

        return [
            'success' => $success,
            'http_code' => $httpCode,
            'error' => $error ?: null,
            'response' => $response
        ];
    }

    /**
     * Вычислить задержку для попытки (экспоненциальная задержка)
     * 
     * @param int $attempt Номер попытки (начинается с 1)
     * @return int Задержка в секундах
     */
    private function calculateDelay(int $attempt): int
    {
        // Экспоненциальная задержка: baseDelay * attempt
        // Для attempt=1: 5 сек, attempt=2: 10 сек, attempt=3: 15 сек, attempt=4: 20 сек
        // Но согласно документации: 5 сек, 15 сек, 30 сек
        // Используем формулу: baseDelay * (attempt * 1.5) с округлением
        $delay = (int)round($this->baseDelay * $attempt * 1.5);
        
        // Ограничиваем максимальную задержку 60 секундами
        return min($delay, 60);
    }

    /**
     * Формировать данные для веб-хука из результата миграции
     * 
     * @param array $migrationResult Результат миграции из MigrationPlatform
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @param string $status Статус миграции: 'completed', 'error', 'in_progress'
     * @param string|null $error Сообщение об ошибке (только для status='error')
     * @return array Данные для веб-хука
     */
    public function formatWebhookData(
        array $migrationResult,
        string $mbProjectUuid,
        int $brzProjectId,
        string $status,
        ?string $error = null
    ): array {
        $data = [
            'mb_project_uuid' => $mbProjectUuid,
            'brz_project_id' => $brzProjectId,
            'status' => $status
        ];

        // Добавляем опциональные поля из результата миграции
        if (isset($migrationResult['migration_id'])) {
            $data['migration_uuid'] = $migrationResult['migration_id'];
            $data['migration_id'] = $migrationResult['migration_id'];
        }

        if (isset($migrationResult['brizy_project_id'])) {
            $data['brizy_project_id'] = $migrationResult['brizy_project_id'];
        }

        if (isset($migrationResult['brizy_project_domain'])) {
            $data['brizy_project_domain'] = $migrationResult['brizy_project_domain'];
        }

        if (isset($migrationResult['date'])) {
            $data['date'] = $migrationResult['date'];
        }

        if (isset($migrationResult['theme'])) {
            $data['theme'] = $migrationResult['theme'];
        }

        if (isset($migrationResult['mb_product_name'])) {
            $data['mb_product_name'] = $migrationResult['mb_product_name'];
        }

        if (isset($migrationResult['mb_site_id'])) {
            $data['mb_site_id'] = $migrationResult['mb_site_id'];
        }

        if (isset($migrationResult['mb_project_domain'])) {
            $data['mb_project_domain'] = $migrationResult['mb_project_domain'];
        }

        // Добавляем прогресс если есть
        if (isset($migrationResult['progress'])) {
            $progress = $migrationResult['progress'];
            $data['progress'] = [
                'total_pages' => $progress['Total'] ?? $progress['total_pages'] ?? 0,
                'processed_pages' => $progress['Success'] ?? $progress['processed_pages'] ?? 0,
                'progress_percent' => $this->calculateProgressPercent(
                    $progress['Total'] ?? $progress['total_pages'] ?? 0,
                    $progress['Success'] ?? $progress['processed_pages'] ?? 0
                )
            ];
        }

        // Добавляем ошибку если статус = 'error'
        if ($status === 'error' && $error !== null) {
            $data['error'] = $error;
        }

        return $data;
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
}
