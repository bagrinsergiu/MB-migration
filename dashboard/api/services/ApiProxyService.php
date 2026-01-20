<?php

namespace Dashboard\Services;

use Exception;

/**
 * ApiProxyService
 * 
 * Проксирование запросов к существующему API миграций
 */
class ApiProxyService
{
    /**
     * @var string
     */
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost:8080';
    }

    /**
     * Запустить миграцию через существующий API
     * 
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function runMigration(array $params): array
    {
        // Обязательные параметры
        $required = ['mb_project_uuid', 'brz_project_id', 'mb_site_id', 'mb_secret'];
        foreach ($required as $key) {
            if (empty($params[$key])) {
                throw new Exception("Обязательный параметр отсутствует: {$key}");
            }
        }

        // Формируем URL, исключая null значения
        $queryParams = [];
        if (!empty($params['mb_project_uuid'])) {
            $queryParams['mb_project_uuid'] = $params['mb_project_uuid'];
        }
        if (!empty($params['brz_project_id'])) {
            $queryParams['brz_project_id'] = $params['brz_project_id'];
        }
        if (!empty($params['mb_site_id'])) {
            $queryParams['mb_site_id'] = $params['mb_site_id'];
        }
        if (!empty($params['mb_secret'])) {
            $queryParams['mb_secret'] = $params['mb_secret'];
        }
        if (!empty($params['brz_workspaces_id'])) {
            $queryParams['brz_workspaces_id'] = $params['brz_workspaces_id'];
        }
        if (!empty($params['mb_page_slug'])) {
            $queryParams['mb_page_slug'] = $params['mb_page_slug'];
        }
        $queryParams['mgr_manual'] = $params['mgr_manual'] ?? 0;
        
        // Добавляем параметр quality_analysis если он передан
        if (isset($params['quality_analysis'])) {
            $queryParams['quality_analysis'] = $params['quality_analysis'] ? 'true' : 'false';
        }
        
        $url = $this->baseUrl . '/?' . http_build_query($queryParams);

        // Запускаем миграцию асинхронно в фоне через PHP напрямую
        // Используем прямой вызов PHP скрипта вместо HTTP запроса
        // dirname(__DIR__, 3) из dashboard/api/services дает /project
        $logFile = dirname(__DIR__, 3) . '/var/log/migration_background_' . $params['brz_project_id'] . '_' . time() . '.log';
        
        // Создаем файл лога заранее
        @file_put_contents($logFile, "=== Migration started at " . date('Y-m-d H:i:s') . " ===\n");
        @file_put_contents($logFile, "URL: " . $url . "\n", FILE_APPEND);
        
        // Формируем команду для запуска миграции напрямую через PHP CLI
        // Используем прямой вызов ApplicationBootstrapper
        $projectRoot = dirname(__DIR__, 3);
        $wrapperScript = $projectRoot . '/var/tmp/migration_wrapper_' . $params['brz_project_id'] . '_' . time() . '.php';
        
        // Создаем временный скрипт-обертку для запуска миграции
        // Используем autoload_runtime.php как в index.php для правильной загрузки контекста
        $projectRootEscaped = addslashes($projectRoot);
        $wrapperContent = "<?php\n";
        $wrapperContent .= "chdir('{$projectRootEscaped}');\n";
        $wrapperContent .= "\$_GET = " . var_export($queryParams, true) . ";\n";
        $wrapperContent .= "\$_SERVER['REQUEST_METHOD'] = 'GET';\n";
        $wrapperContent .= "\$_SERVER['REQUEST_URI'] = '/?' . http_build_query(\$_GET);\n";
        $wrapperContent .= "\$_SERVER['SCRIPT_NAME'] = '/index.php';\n";
        $wrapperContent .= "\$_SERVER['PHP_SELF'] = '/index.php';\n";
        $wrapperContent .= "\$_SERVER['PATH_INFO'] = '/';\n";
        $wrapperContent .= "require_once '{$projectRootEscaped}/vendor/autoload_runtime.php';\n";
        $wrapperContent .= "use Symfony\Component\HttpFoundation\Request;\n";
        $wrapperContent .= "use Symfony\Component\HttpFoundation\JsonResponse;\n\n";
        $brzProjectId = (int)$params['brz_project_id'];
        $mbProjectUuid = $params['mb_project_uuid'];
        
        $wrapperContent .= "return static function (array \$context, Request \$request): JsonResponse {\n";
        $wrapperContent .= "    \$app = new MBMigration\ApplicationBootstrapper(\$context, \$request);\n";
        $wrapperContent .= "    try {\n";
        $wrapperContent .= "        \$config = \$app->doInnitConfig();\n";
        $wrapperContent .= "        \$bridge = new MBMigration\Bridge\Bridge(\$app, \$config, \$request);\n";
        $wrapperContent .= "        \$response = \$bridge->runMigration()->getMessageResponse();\n";
        $wrapperContent .= "        \$responseData = \$response->getMessage();\n";
        $wrapperContent .= "        error_log('Migration completed: ' . json_encode(\$responseData));\n";
        $wrapperContent .= "        \n";
        $wrapperContent .= "        // Обновляем статус в БД после успешного завершения\n";
        $wrapperContent .= "        try {\n";
        $wrapperContent .= "            require_once '{$projectRootEscaped}/dashboard/api/services/DatabaseService.php';\n";
        $wrapperContent .= "            \$dbService = new Dashboard\Services\DatabaseService();\n";
        $wrapperContent .= "            \$status = 'completed';\n";
        $wrapperContent .= "            if (isset(\$responseData['value']['status']) && \$responseData['value']['status'] === 'success') {\n";
        $wrapperContent .= "                \$status = 'completed';\n";
        $wrapperContent .= "            } elseif (isset(\$responseData['error'])) {\n";
        $wrapperContent .= "                \$status = 'error';\n";
        $wrapperContent .= "            }\n";
        $wrapperContent .= "            \$metaData = [\n";
        $wrapperContent .= "                'status' => \$status,\n";
        $wrapperContent .= "                'completed_at' => date('Y-m-d H:i:s'),\n";
        $wrapperContent .= "                'brizy_project_id' => \$responseData['value']['brizy_project_id'] ?? null,\n";
        $wrapperContent .= "                'brizy_project_domain' => \$responseData['value']['brizy_project_domain'] ?? null,\n";
        $wrapperContent .= "                'migration_id' => \$responseData['value']['migration_id'] ?? null,\n";
        $wrapperContent .= "                'date' => \$responseData['value']['date'] ?? null,\n";
        $wrapperContent .= "                'theme' => \$responseData['value']['theme'] ?? null,\n";
        $wrapperContent .= "                'mb_product_name' => \$responseData['value']['mb_product_name'] ?? null,\n";
        $wrapperContent .= "                'mb_site_id' => \$responseData['value']['mb_site_id'] ?? null,\n";
        $wrapperContent .= "                'mb_project_domain' => \$responseData['value']['mb_project_domain'] ?? null,\n";
        $wrapperContent .= "                'progress' => isset(\$responseData['value']['progress']) ? json_encode(\$responseData['value']['progress']) : null,\n";
        $wrapperContent .= "            ];\n";
        $wrapperContent .= "            if (isset(\$responseData['error'])) {\n";
        $wrapperContent .= "                \$metaData['error'] = is_string(\$responseData['error']) ? \$responseData['error'] : json_encode(\$responseData['error']);\n";
        $wrapperContent .= "            }\n";
        $wrapperContent .= "            \$dbService->upsertMigrationMapping({$brzProjectId}, '{$mbProjectUuid}', \$metaData);\n";
        $wrapperContent .= "            \n";
        $wrapperContent .= "            // Сохраняем результат в migration_result_list\n";
        $wrapperContent .= "            if (isset(\$responseData['value']['brizy_project_id']) && isset(\$responseData['value']['mb_uuid'])) {\n";
        $wrapperContent .= "                try {\n";
        $wrapperContent .= "                    \$migrationUuid = time() . random_int(100, 999);\n";
        $wrapperContent .= "                    \$dbService->saveMigrationResult([\n";
        $wrapperContent .= "                        'migration_uuid' => \$migrationUuid,\n";
        $wrapperContent .= "                        'brz_project_id' => (int)\$responseData['value']['brizy_project_id'],\n";
        $wrapperContent .= "                        'brizy_project_domain' => \$responseData['value']['brizy_project_domain'] ?? '',\n";
        $wrapperContent .= "                        'mb_project_uuid' => \$responseData['value']['mb_uuid'],\n";
        $wrapperContent .= "                        'result_json' => json_encode(\$responseData)\n";
        $wrapperContent .= "                    ]);\n";
        $wrapperContent .= "                } catch (Exception \$saveEx) {\n";
        $wrapperContent .= "                    error_log('Save result error: ' . \$saveEx->getMessage());\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "            }\n";
        $wrapperContent .= "        } catch (Exception \$dbEx) {\n";
        $wrapperContent .= "            error_log('DB update error: ' . \$dbEx->getMessage());\n";
        $wrapperContent .= "        }\n";
        $wrapperContent .= "        \n";
        $wrapperContent .= "        return new JsonResponse(\$responseData, \$response->getStatusCode());\n";
        $wrapperContent .= "    } catch (Exception \$e) {\n";
        $wrapperContent .= "        error_log('Migration error: ' . \$e->getMessage());\n";
        $wrapperContent .= "        error_log('Stack trace: ' . \$e->getTraceAsString());\n";
        $wrapperContent .= "        \n";
        $wrapperContent .= "        // Обновляем статус в БД при ошибке\n";
        $wrapperContent .= "        try {\n";
        $wrapperContent .= "            require_once '{$projectRootEscaped}/dashboard/api/services/DatabaseService.php';\n";
        $wrapperContent .= "            \$dbService = new Dashboard\Services\DatabaseService();\n";
        $wrapperContent .= "            \$dbService->upsertMigrationMapping({$brzProjectId}, '{$mbProjectUuid}', [\n";
        $wrapperContent .= "                'status' => 'error',\n";
        $wrapperContent .= "                'error' => \$e->getMessage(),\n";
        $wrapperContent .= "                'updated_at' => date('Y-m-d H:i:s'),\n";
        $wrapperContent .= "            ]);\n";
        $wrapperContent .= "        } catch (Exception \$dbEx) {\n";
        $wrapperContent .= "            error_log('DB update error: ' . \$dbEx->getMessage());\n";
        $wrapperContent .= "        }\n";
        $wrapperContent .= "        \n";
        $wrapperContent .= "        return new JsonResponse(['error' => \$e->getMessage()], 500);\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "};\n";
        @file_put_contents($wrapperScript, $wrapperContent);
        
        // Запускаем PHP скрипт-обертку в фоне через nohup
        $command = sprintf(
            'cd %s && nohup php -f %s >> %s 2>&1 &',
            escapeshellarg($projectRoot),
            escapeshellarg($wrapperScript),
            escapeshellarg($logFile)
        );
        
        $pid = null;
        @exec($command, $output, $returnVar);
        
        // Если exec не сработал, пробуем через shell_exec
        if ($returnVar !== 0) {
            $result = @shell_exec($command);
            $pid = $result ? trim($result) : 'background';
        } else {
            $pid = 'background';
        }
        
        // Логируем PID
        @file_put_contents($logFile, "Command: " . $command . "\n", FILE_APPEND);
        @file_put_contents($logFile, "PID: " . ($pid ?: 'unknown') . "\n", FILE_APPEND);
        
        // Возвращаем немедленный ответ о том, что миграция запущена
        return [
            'http_code' => 202, // Accepted
            'data' => [
                'status' => 'in_progress',
                'message' => 'Миграция запущена и выполняется в фоне. Это может занять несколько минут.',
                'mb_project_uuid' => $params['mb_project_uuid'],
                'brz_project_id' => $params['brz_project_id'],
                'note' => 'Проверьте статус миграции через несколько минут'
            ],
            'raw_data' => ['status' => 'in_progress'],
            'success' => true // Считаем успешным запуск
        ];
    }

    /**
     * Получить логи миграции
     * 
     * @param int $brzProjectId
     * @return array
     * @throws Exception
     */
    public function getMigrationLogs(int $brzProjectId): array
    {
        $url = $this->baseUrl . '/migration_log?brz_project_id=' . $brzProjectId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Ошибка при получении логов: {$error}");
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Ошибка декодирования JSON: " . json_last_error_msg());
        }

        return [
            'http_code' => $httpCode,
            'data' => $data
        ];
    }
}
