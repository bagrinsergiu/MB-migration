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
        
        // Проверяем, нужно ли запускать синхронно (для отладки)
        $syncExecution = isset($params['sync_execution']) && $params['sync_execution'] === true;
        $debugMode = isset($params['debug_mode']) && $params['debug_mode'] === true;
        
        // Если включен режим отладки или синхронного выполнения, запускаем в текущем процессе
        if ($syncExecution || $debugMode) {
            return $this->runMigrationSync($params);
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
        if (!empty($params['mb_element_name'])) {
            $queryParams['mb_element_name'] = $params['mb_element_name'];
        }
        if (isset($params['skip_media_upload'])) {
            $queryParams['skip_media_upload'] = $params['skip_media_upload'] ? 'true' : 'false';
        }
        if (isset($params['skip_cache'])) {
            $queryParams['skip_cache'] = $params['skip_cache'] ? 'true' : 'false';
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
        $wrapperContent .= "    // Обновляем PID в lock-файле при запуске процесса\n";
        $wrapperContent .= "    \$pid = getmypid();\n";
        $wrapperContent .= "    \$cachePath = \$context['CACHE_PATH'] ?? '{$projectRootEscaped}/var/cache';\n";
        $wrapperContent .= "    \$lockFile = \$cachePath . '/' . '{$mbProjectUuid}' . '-' . {$brzProjectId} . '.lock';\n";
        $wrapperContent .= "    if (file_exists(\$lockFile)) {\n";
        $wrapperContent .= "        \$lockContent = @file_get_contents(\$lockFile);\n";
        $wrapperContent .= "        \$lockData = \$lockContent ? json_decode(\$lockContent, true) : [];\n";
        $wrapperContent .= "        if (!is_array(\$lockData)) \$lockData = [];\n";
        $wrapperContent .= "        \$lockData['pid'] = \$pid;\n";
        $wrapperContent .= "        \$lockData['started_at'] = date('Y-m-d H:i:s');\n";
        $wrapperContent .= "        \$lockData['started_timestamp'] = time();\n";
        $wrapperContent .= "        \$lockData['mb_project_uuid'] = '{$mbProjectUuid}';\n";
        $wrapperContent .= "        \$lockData['brz_project_id'] = {$brzProjectId};\n";
        $wrapperContent .= "        @file_put_contents(\$lockFile, json_encode(\$lockData, JSON_PRETTY_PRINT));\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    \n";
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
        
        // Создаем lock-файл заранее с PID (будет обновлен процессом)
        $cachePath = $_ENV['CACHE_PATH'] ?? getenv('CACHE_PATH') ?: $projectRoot . '/var/cache';
        $lockFile = $cachePath . '/' . $params['mb_project_uuid'] . '-' . $params['brz_project_id'] . '.lock';
        
        // Запускаем PHP скрипт-обертку в фоне через nohup и получаем PID
        $command = sprintf(
            'cd %s && nohup php -f %s >> %s 2>&1 & echo $!',
            escapeshellarg($projectRoot),
            escapeshellarg($wrapperScript),
            escapeshellarg($logFile)
        );
        
        $pid = null;
        $output = [];
        @exec($command, $output, $returnVar);
        
        // Извлекаем PID из вывода
        if (!empty($output)) {
            $pid = (int)trim(end($output));
        }
        
        // Если не получили PID через exec, пробуем другой способ
        if (!$pid || $pid <= 0) {
            $result = @shell_exec($command);
            if ($result) {
                $lines = explode("\n", trim($result));
                $pid = (int)trim(end($lines));
            }
        }
        
        // Если все еще нет PID, пробуем через ps после небольшой задержки
        if (!$pid || $pid <= 0) {
            usleep(500000); // 0.5 секунды
            $psCommand = sprintf(
                'ps aux | grep -E "php.*%s" | grep -v grep | awk \'{print $2}\' | head -1',
                escapeshellarg(basename($wrapperScript))
            );
            $psOutput = @shell_exec($psCommand);
            if ($psOutput) {
                $pid = (int)trim($psOutput);
            }
        }
        
        // Сохраняем PID в lock-файл, если получили
        if ($pid && $pid > 0) {
            $lockData = [
                'mb_project_uuid' => $params['mb_project_uuid'],
                'brz_project_id' => $params['brz_project_id'],
                'pid' => $pid,
                'started_at' => date('Y-m-d H:i:s'),
                'started_timestamp' => time(),
                'wrapper_script' => $wrapperScript,
                'log_file' => $logFile
            ];
            @file_put_contents($lockFile, json_encode($lockData, JSON_PRETTY_PRINT));
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
     * Запустить миграцию синхронно в текущем процессе (для отладки)
     * 
     * @param array $params
     * @return array
     * @throws Exception
     */
    private function runMigrationSync(array $params): array
    {
        // Формируем параметры запроса
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
        if (!empty($params['mb_element_name'])) {
            $queryParams['mb_element_name'] = $params['mb_element_name'];
        }
        if (isset($params['skip_media_upload'])) {
            $queryParams['skip_media_upload'] = $params['skip_media_upload'] ? 'true' : 'false';
        }
        if (isset($params['skip_cache'])) {
            $queryParams['skip_cache'] = $params['skip_cache'] ? 'true' : 'false';
        }
        $queryParams['mgr_manual'] = $params['mgr_manual'] ?? 0;
        
        if (isset($params['quality_analysis'])) {
            $queryParams['quality_analysis'] = $params['quality_analysis'] ? 'true' : 'false';
        }

        // Создаем Request объект с параметрами
        $projectRoot = dirname(__DIR__, 3);
        $originalDir = getcwd();
        chdir($projectRoot);
        
        // Устанавливаем $_GET для совместимости со старым кодом
        $originalGet = $_GET;
        $_GET = $queryParams;
        $originalServer = $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/?' . http_build_query($queryParams);
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['PATH_INFO'] = '/';
        
        // Загружаем переменные окружения для context
        if (file_exists($projectRoot . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createMutable($projectRoot);
            $dotenv->safeLoad();
        }
        if (file_exists($projectRoot . '/.env.prod.local')) {
            $dotenv = \Dotenv\Dotenv::createMutable($projectRoot, ['.env.prod.local']);
            $dotenv->safeLoad();
        }
        
        // Создаем Request объект
        $request = \Symfony\Component\HttpFoundation\Request::create('/', 'GET', $queryParams);
        
        // Формируем context из переменных окружения
        $context = [
            'APP_AUTHORIZATION_TOKEN' => $_ENV['APP_AUTHORIZATION_TOKEN'] ?? getenv('APP_AUTHORIZATION_TOKEN') ?? '',
            'LOG_PATH' => $_ENV['LOG_PATH'] ?? getenv('LOG_PATH') ?: $projectRoot . '/var/log',
            'CACHE_PATH' => $_ENV['CACHE_PATH'] ?? getenv('CACHE_PATH') ?: $projectRoot . '/var/cache',
            'LOG_FILE_PATH' => ($_ENV['LOG_PATH'] ?? getenv('LOG_PATH') ?: $projectRoot . '/var/log') . '/migration',
            'LOG_LEVEL' => $_ENV['LOG_LEVEL'] ?? getenv('LOG_LEVEL') ?? 'INFO',
            'DEV_MODE' => $_ENV['DEV_MODE'] ?? getenv('DEV_MODE') ?? false,
            'MGR_MODE' => $_ENV['MGR_MODE'] ?? getenv('MGR_MODE') ?? false,
            'MB_DB_HOST' => $_ENV['MB_DB_HOST'] ?? getenv('MB_DB_HOST') ?? '',
            'MB_DB_PORT' => $_ENV['MB_DB_PORT'] ?? getenv('MB_DB_PORT') ?? '3306',
            'MB_DB_NAME' => $_ENV['MB_DB_NAME'] ?? getenv('MB_DB_NAME') ?? '',
            'MB_DB_USER' => $_ENV['MB_DB_USER'] ?? getenv('MB_DB_USER') ?? '',
            'MB_DB_PASSWORD' => $_ENV['MB_DB_PASSWORD'] ?? getenv('MB_DB_PASSWORD') ?? '',
            'MG_DB_HOST' => $_ENV['MG_DB_HOST'] ?? getenv('MG_DB_HOST') ?? '',
            'MG_DB_PORT' => $_ENV['MG_DB_PORT'] ?? getenv('MG_DB_PORT') ?? '3306',
            'MG_DB_NAME' => $_ENV['MG_DB_NAME'] ?? getenv('MG_DB_NAME') ?? '',
            'MG_DB_USER' => $_ENV['MG_DB_USER'] ?? getenv('MG_DB_USER') ?? '',
            'MG_DB_PASS' => $_ENV['MG_DB_PASS'] ?? getenv('MG_DB_PASS') ?? '',
            'MB_MEDIA_HOST' => $_ENV['MB_MEDIA_HOST'] ?? getenv('MB_MEDIA_HOST') ?? '',
            'MB_PREVIEW_HOST' => $_ENV['MB_PREVIEW_HOST'] ?? getenv('MB_PREVIEW_HOST') ?? 'staging.cloversites.com',
            'BRIZY_CLOUD_HOST' => $_ENV['BRIZY_CLOUD_HOST'] ?? getenv('BRIZY_CLOUD_HOST') ?? '',
            'BRIZY_CLOUD_TOKEN' => $_ENV['BRIZY_CLOUD_TOKEN'] ?? getenv('BRIZY_CLOUD_TOKEN') ?? '',
            'AWS_BUCKET_ACTIVE' => $_ENV['AWS_BUCKET_ACTIVE'] ?? getenv('AWS_BUCKET_ACTIVE') ?? false,
            'AWS_KEY' => $_ENV['AWS_KEY'] ?? getenv('AWS_KEY') ?? '',
            'AWS_SECRET' => $_ENV['AWS_SECRET'] ?? getenv('AWS_SECRET') ?? '',
            'AWS_REGION' => $_ENV['AWS_REGION'] ?? getenv('AWS_REGION') ?? '',
            'AWS_BUCKET' => $_ENV['AWS_BUCKET'] ?? getenv('AWS_BUCKET') ?? '',
            'MB_MONKCMS_API' => $_ENV['MB_MONKCMS_API'] ?? getenv('MB_MONKCMS_API') ?? null,
        ];
        
        // Запускаем миграцию синхронно в текущем процессе
        try {
            $app = new \MBMigration\ApplicationBootstrapper($context, $request);
            $config = $app->doInnitConfig();
            $bridge = new \MBMigration\Bridge\Bridge($app, $config, $request);
            $response = $bridge->runMigration()->getMessageResponse();
            $responseData = $response->getMessage();
            
            // Обновляем статус в БД после завершения миграции
            try {
                require_once $projectRoot . '/dashboard/api/services/DatabaseService.php';
                $dbService = new \Dashboard\Services\DatabaseService();
                
                $brzProjectId = (int)($params['brz_project_id'] ?? 0);
                $mbProjectUuid = $params['mb_project_uuid'] ?? '';
                
                if ($brzProjectId > 0 && !empty($mbProjectUuid)) {
                    $status = 'completed';
                    if (isset($responseData['value']['status']) && $responseData['value']['status'] === 'success') {
                        $status = 'completed';
                    } elseif (isset($responseData['error'])) {
                        $status = 'error';
                    } elseif ($response->getStatusCode() >= 400) {
                        $status = 'error';
                    }
                    
                    $metaData = [
                        'status' => $status,
                        'completed_at' => date('Y-m-d H:i:s'),
                        'brizy_project_id' => $responseData['value']['brizy_project_id'] ?? $brzProjectId,
                        'brizy_project_domain' => $responseData['value']['brizy_project_domain'] ?? null,
                        'migration_id' => $responseData['value']['migration_id'] ?? null,
                        'date' => $responseData['value']['date'] ?? null,
                        'theme' => $responseData['value']['theme'] ?? null,
                        'mb_product_name' => $responseData['value']['mb_product_name'] ?? null,
                        'mb_site_id' => $responseData['value']['mb_site_id'] ?? null,
                        'mb_project_domain' => $responseData['value']['mb_project_domain'] ?? null,
                        'progress' => isset($responseData['value']['progress']) ? json_encode($responseData['value']['progress']) : null,
                    ];
                    
                    if (isset($responseData['error'])) {
                        $metaData['error'] = is_string($responseData['error']) ? $responseData['error'] : json_encode($responseData['error']);
                    }
                    
                    $dbService->upsertMigrationMapping($brzProjectId, $mbProjectUuid, $metaData);
                    
                    // Также сохраняем результат в migration_result_list
                    if (isset($responseData['value']['brizy_project_id']) && isset($responseData['value']['mb_uuid'])) {
                        try {
                            $migrationUuid = time() . random_int(100, 999);
                            $dbService->saveMigrationResult([
                                'migration_uuid' => $migrationUuid,
                                'brz_project_id' => (int)$responseData['value']['brizy_project_id'],
                                'brizy_project_domain' => $responseData['value']['brizy_project_domain'] ?? '',
                                'mb_project_uuid' => $responseData['value']['mb_uuid'],
                                'result_json' => json_encode($responseData)
                            ]);
                        } catch (\Exception $saveEx) {
                            error_log('Save result error: ' . $saveEx->getMessage());
                        }
                    }
                    
                    error_log('Migration status updated in DB: ' . $status . ' for brz_project_id: ' . $brzProjectId);
                    
                    // Если это тестовая миграция с элементом, сохраняем результат секции
                    if (!empty($params['mb_element_name'])) {
                        try {
                            // Получаем результат из кэша
                            $cache = \MBMigration\Builder\VariableCache::getInstance();
                            $cacheKey = 'test_migration_element_result_' . $params['mb_element_name'];
                            $elementResult = $cache->get($cacheKey);
                            
                            if ($elementResult && isset($elementResult['section_json'])) {
                                // Находим тестовую миграцию по параметрам
                                $dbWrite = $dbService->getWriteConnection();
                                $testMigration = $dbWrite->find(
                                    'SELECT id FROM test_migrations WHERE mb_project_uuid = ? AND brz_project_id = ? AND mb_element_name = ? ORDER BY id DESC LIMIT 1',
                                    [$mbProjectUuid, $brzProjectId, $params['mb_element_name']]
                                );
                                
                                if ($testMigration && isset($testMigration['id'])) {
                                    $dbWrite->getAllRows(
                                        'UPDATE test_migrations SET element_result_json = ? WHERE id = ?',
                                        [$elementResult['section_json'], $testMigration['id']]
                                    );
                                    error_log('Element result saved to test_migration id: ' . $testMigration['id']);
                                }
                            }
                        } catch (\Exception $elementEx) {
                            error_log('Failed to save element result: ' . $elementEx->getMessage());
                        }
                    }
                }
            } catch (\Exception $dbEx) {
                error_log('DB update error: ' . $dbEx->getMessage());
                // Не прерываем выполнение, только логируем ошибку
            }
            
            // Удаляем lock-файл после успешного завершения
            try {
                $brzProjectId = (int)($params['brz_project_id'] ?? 0);
                $mbProjectUuid = $params['mb_project_uuid'] ?? '';
                
                if ($brzProjectId > 0 && !empty($mbProjectUuid)) {
                    $cachePath = $context['CACHE_PATH'] ?? $projectRoot . '/var/cache';
                    $lockFile = $cachePath . '/' . $mbProjectUuid . '-' . $brzProjectId . '.lock';
                    
                    if (file_exists($lockFile)) {
                        @unlink($lockFile);
                        error_log('Lock file removed after sync migration completion: ' . $lockFile);
                    }
                }
            } catch (\Exception $lockEx) {
                error_log('Lock file removal error: ' . $lockEx->getMessage());
                // Не прерываем выполнение
            }
            
            // Восстанавливаем оригинальные значения
            $_GET = $originalGet;
            $_SERVER = $originalServer;
            chdir($originalDir);
            
            return [
                'http_code' => $response->getStatusCode(),
                'data' => $responseData,
                'raw_data' => $responseData,
                'success' => $response->getStatusCode() < 400
            ];
        } catch (\Exception $e) {
            // Обновляем статус на error при исключении
            try {
                require_once $projectRoot . '/dashboard/api/services/DatabaseService.php';
                $dbService = new \Dashboard\Services\DatabaseService();
                
                $brzProjectId = (int)($params['brz_project_id'] ?? 0);
                $mbProjectUuid = $params['mb_project_uuid'] ?? '';
                
                if ($brzProjectId > 0 && !empty($mbProjectUuid)) {
                    $dbService->upsertMigrationMapping($brzProjectId, $mbProjectUuid, [
                        'status' => 'error',
                        'error' => $e->getMessage(),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    error_log('Migration status updated to error in DB for brz_project_id: ' . $brzProjectId);
                    
                    // Удаляем lock-файл при ошибке
                    try {
                        $cachePath = $context['CACHE_PATH'] ?? $projectRoot . '/var/cache';
                        $lockFile = $cachePath . '/' . $mbProjectUuid . '-' . $brzProjectId . '.lock';
                        
                        if (file_exists($lockFile)) {
                            @unlink($lockFile);
                            error_log('Lock file removed after sync migration error: ' . $lockFile);
                        }
                    } catch (\Exception $lockEx) {
                        error_log('Lock file removal error on exception: ' . $lockEx->getMessage());
                    }
                }
            } catch (\Exception $dbEx) {
                error_log('DB update error on exception: ' . $dbEx->getMessage());
            }
            
            // Восстанавливаем оригинальные значения даже при ошибке
            $_GET = $originalGet;
            $_SERVER = $originalServer;
            chdir($originalDir);
            
            error_log('Sync migration error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return [
                'http_code' => 500,
                'data' => ['error' => $e->getMessage()],
                'raw_data' => ['error' => $e->getMessage()],
                'success' => false
            ];
        }
    }

    /**
     * Получить логи миграции
     * Сначала пытается получить через HTTP API, если не получается - читает из файлов
     * 
     * @param int $brzProjectId
     * @return array
     * @throws Exception
     */
    public function getMigrationLogs(int $brzProjectId): array
    {
        // Сначала пытаемся получить через HTTP API
        $url = $this->baseUrl . '/migration_log?brz_project_id=' . $brzProjectId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Короткий таймаут
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Если HTTP запрос успешен, возвращаем результат
        if (!$error && $httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return [
                    'http_code' => $httpCode,
                    'data' => $data
                ];
            }
        }

        // Если HTTP запрос не удался, читаем логи из файлов напрямую
        $projectRoot = dirname(__DIR__, 3);
        $logPath = $_ENV['LOG_PATH'] ?? getenv('LOG_PATH') ?: $projectRoot . '/var/log';
        
        // Ищем лог-файлы по паттерну
        $logFiles = [];
        
        // Вариант 1: Ищем файл по паттерну migration_*_$brzProjectId.log
        $pattern = $logPath . '/migration_*_' . $brzProjectId . '.log';
        $files = glob($pattern);
        if ($files) {
            $logFiles = array_merge($logFiles, $files);
        }
        
        // Вариант 2: Ищем файл по паттерну *_$brzProjectId.log (более общий)
        $pattern2 = $logPath . '/*_' . $brzProjectId . '.log';
        $files2 = glob($pattern2);
        if ($files2) {
            $logFiles = array_merge($logFiles, $files2);
        }
        
        // Вариант 3: Ищем в директориях волн
        $waveDirs = glob($logPath . '/wave_*', GLOB_ONLYDIR);
        foreach ($waveDirs as $waveDir) {
            $projectLogFile = $waveDir . '/project_' . $brzProjectId . '.log';
            if (file_exists($projectLogFile)) {
                $logFiles[] = $projectLogFile;
            }
        }
        
        // Сортируем по времени изменения (новые первыми)
        usort($logFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $allLogs = [];
        foreach ($logFiles as $logFile) {
            if (file_exists($logFile) && is_readable($logFile)) {
                $content = file_get_contents($logFile);
                if ($content) {
                    // Разбиваем логи по строкам
                    $content = str_replace('][', "]\n[", $content);
                    $lines = explode("\n", $content);
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            // Фильтруем только строки, связанные с этой миграцией
                            if (strpos($line, "brizy-$brzProjectId") !== false || 
                                strpos($line, (string)$brzProjectId) !== false ||
                                strpos($logFile, '_' . $brzProjectId . '.log') !== false ||
                                preg_match('/\[202\d-\d{2}-\d{2}/', $line)) {
                                $allLogs[] = $line;
                            }
                        }
                    }
                }
            }
        }
        
        // Если нашли логи в файлах, возвращаем их
        if (!empty($allLogs)) {
            return [
                'http_code' => 200,
                'data' => [
                    'migration_id' => $brzProjectId,
                    'logs' => array_values(array_unique($allLogs)),
                    'log_files' => $logFiles,
                    'source' => 'file'
                ]
            ];
        }
        
        // Если ничего не нашли, возвращаем ошибку
        throw new Exception("Лог-файлы для миграции не найдены. brz_project_id: {$brzProjectId}");
    }
}
