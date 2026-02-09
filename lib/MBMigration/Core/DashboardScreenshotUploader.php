<?php

namespace MBMigration\Core;

use Exception;

/**
 * DashboardScreenshotUploader
 * 
 * Класс для загрузки скриншотов на дашборд миграции через webhook API
 */
class DashboardScreenshotUploader
{
    /**
     * @var string|null URL сервера дашборда
     */
    private ?string $dashboardServerUrl;

    /**
     * @var bool Включена ли загрузка скриншотов
     */
    private bool $enabled;

    public function __construct(?string $dashboardServerUrl = null)
    {
        // Получаем URL из параметра или переменной окружения
        $this->dashboardServerUrl = $dashboardServerUrl ?? ($_ENV['DASHBOARD_SERVER_URL'] ?? null);
        
        // Загрузка включена только если указан URL дашборда
        $this->enabled = !empty($this->dashboardServerUrl);
        
        if ($this->enabled) {
            // Убеждаемся что URL заканчивается на / или не заканчивается на /
            $this->dashboardServerUrl = rtrim($this->dashboardServerUrl, '/');
        }
        
        Logger::instance()->debug("[Dashboard Screenshot Uploader] Initialized", [
            'enabled' => $this->enabled,
            'dashboard_server_url' => $this->dashboardServerUrl
        ]);
    }

    /**
     * Загрузить скриншот на дашборд
     * 
     * @param string $screenshotPath Путь к файлу скриншота
     * @param string $mbUuid UUID проекта MB
     * @param string $pageSlug Slug страницы
     * @param string $type Тип скриншота: 'source' или 'migrated'
     * @return array|null Результат загрузки или null если загрузка отключена
     * @throws Exception
     */
    public function uploadScreenshot(
        string $screenshotPath,
        string $mbUuid,
        string $pageSlug,
        string $type
    ): ?array {
        if (!$this->enabled) {
            Logger::instance()->debug("[Dashboard Screenshot Uploader] Upload disabled, skipping", [
                'screenshot_path' => $screenshotPath,
                'mb_uuid' => $mbUuid,
                'page_slug' => $pageSlug,
                'type' => $type
            ]);
            return null;
        }

        // Проверяем что файл существует
        if (!file_exists($screenshotPath)) {
            Logger::instance()->warning("[Dashboard Screenshot Uploader] Screenshot file not found", [
                'screenshot_path' => $screenshotPath
            ]);
            return null;
        }

        // Валидация типа
        if (!in_array($type, ['source', 'migrated'])) {
            Logger::instance()->error("[Dashboard Screenshot Uploader] Invalid screenshot type", [
                'type' => $type,
                'allowed_types' => ['source', 'migrated']
            ]);
            throw new Exception("Invalid screenshot type: {$type}. Must be 'source' or 'migrated'");
        }

        try {
            // Читаем содержимое файла
            $fileContent = file_get_contents($screenshotPath);
            if ($fileContent === false) {
                throw new Exception("Failed to read screenshot file: {$screenshotPath}");
            }

            // Кодируем в base64
            $base64Content = base64_encode($fileContent);
            
            // Определяем MIME тип
            $mimeType = mime_content_type($screenshotPath);
            if ($mimeType === false) {
                // По умолчанию PNG
                $mimeType = 'image/png';
            }

            // Формируем data URI
            $dataUri = 'data:' . $mimeType . ';base64,' . $base64Content;

            // Получаем имя файла
            $filename = basename($screenshotPath);

            // Формируем URL webhook
            $webhookUrl = $this->dashboardServerUrl . '/api/webhooks/screenshots';

            // Подготавливаем данные для отправки
            $data = [
                'mb_uuid' => $mbUuid,
                'page_slug' => $pageSlug,
                'type' => $type,
                'file_content' => $dataUri,
                'filename' => $filename
            ];

            Logger::instance()->info("[Dashboard Screenshot Uploader] Uploading screenshot", [
                'webhook_url' => $webhookUrl,
                'mb_uuid' => $mbUuid,
                'page_slug' => $pageSlug,
                'type' => $type,
                'filename' => $filename,
                'file_size' => filesize($screenshotPath),
                'mime_type' => $mimeType
            ]);

            // Отправляем запрос
            $ch = curl_init($webhookUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            $curlErrorCode = curl_errno($ch);
            $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);

            // Извлекаем хост и endpoint из URL
            $parsedUrl = parse_url($webhookUrl);
            $host = ($parsedUrl['scheme'] ?? 'http') . '://' . ($parsedUrl['host'] ?? 'unknown');
            if (isset($parsedUrl['port'])) {
                $host .= ':' . $parsedUrl['port'];
            }
            $endpoint = $parsedUrl['path'] ?? '/';

            if ($curlError) {
                Logger::instance()->error("[Dashboard Screenshot Uploader] cURL error occurred", [
                    'screenshot_path' => $screenshotPath,
                    'mb_uuid' => $mbUuid,
                    'page_slug' => $pageSlug,
                    'type' => $type,
                    'host' => $host,
                    'endpoint' => $endpoint,
                    'webhook_url' => $webhookUrl,
                    'effective_url' => $effectiveUrl,
                    'curl_error' => $curlError,
                    'curl_error_code' => $curlErrorCode,
                    'http_code' => $httpCode,
                    'response' => $response !== false ? substr($response, 0, 500) : null
                ]);
                throw new Exception("cURL error: {$curlError}");
            }

            if ($httpCode !== 200) {
                Logger::instance()->error("[Dashboard Screenshot Uploader] HTTP error occurred", [
                    'screenshot_path' => $screenshotPath,
                    'mb_uuid' => $mbUuid,
                    'page_slug' => $pageSlug,
                    'type' => $type,
                    'host' => $host,
                    'endpoint' => $endpoint,
                    'webhook_url' => $webhookUrl,
                    'effective_url' => $effectiveUrl,
                    'http_code' => $httpCode,
                    'response' => $response !== false ? substr($response, 0, 500) : null
                ]);
                $errorMessage = "HTTP error: {$httpCode}";
                if ($response) {
                    $errorMessage .= " - " . $response;
                }
                throw new Exception($errorMessage);
            }

            $responseData = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Logger::instance()->error("[Dashboard Screenshot Uploader] JSON decode error", [
                    'screenshot_path' => $screenshotPath,
                    'mb_uuid' => $mbUuid,
                    'page_slug' => $pageSlug,
                    'type' => $type,
                    'host' => $host,
                    'endpoint' => $endpoint,
                    'webhook_url' => $webhookUrl,
                    'http_code' => $httpCode,
                    'response' => $response !== false ? substr($response, 0, 500) : null,
                    'json_error' => json_last_error_msg()
                ]);
                throw new Exception("Failed to decode response: " . json_last_error_msg());
            }

            if (!isset($responseData['success']) || !$responseData['success']) {
                $errorMessage = $responseData['error'] ?? 'Unknown error';
                Logger::instance()->error("[Dashboard Screenshot Uploader] Upload failed (server response)", [
                    'screenshot_path' => $screenshotPath,
                    'mb_uuid' => $mbUuid,
                    'page_slug' => $pageSlug,
                    'type' => $type,
                    'host' => $host,
                    'endpoint' => $endpoint,
                    'webhook_url' => $webhookUrl,
                    'http_code' => $httpCode,
                    'response' => $responseData,
                    'error_message' => $errorMessage
                ]);
                throw new Exception("Upload failed: {$errorMessage}");
            }

            Logger::instance()->info("[Dashboard Screenshot Uploader] Screenshot uploaded successfully", [
                'mb_uuid' => $mbUuid,
                'page_slug' => $pageSlug,
                'type' => $type,
                'response' => $responseData
            ]);

            return $responseData;

        } catch (Exception $e) {
            // Извлекаем хост и endpoint из URL для логирования
            $webhookUrl = $this->dashboardServerUrl . '/api/webhooks/screenshots';
            $parsedUrl = parse_url($webhookUrl);
            $host = ($parsedUrl['scheme'] ?? 'http') . '://' . ($parsedUrl['host'] ?? 'unknown');
            if (isset($parsedUrl['port'])) {
                $host .= ':' . $parsedUrl['port'];
            }
            $endpoint = $parsedUrl['path'] ?? '/';

            Logger::instance()->error("[Dashboard Screenshot Uploader] Error uploading screenshot", [
                'screenshot_path' => $screenshotPath,
                'mb_uuid' => $mbUuid,
                'page_slug' => $pageSlug,
                'type' => $type,
                'host' => $host,
                'endpoint' => $endpoint,
                'webhook_url' => $webhookUrl,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode()
            ]);
            
            // Не прерываем процесс миграции из-за ошибки загрузки скриншота
            // Просто логируем ошибку
            return null;
        }
    }

    /**
     * Проверить доступность дашборда
     * 
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Получить URL сервера дашборда
     * 
     * @return string|null
     */
    public function getDashboardServerUrl(): ?string
    {
        return $this->dashboardServerUrl;
    }
}
