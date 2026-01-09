<?php

namespace Dashboard\Services;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\Layer\Brizy\BrizyAPI;

/**
 * WaveService
 * 
 * Сервис для работы с волнами миграций
 */
class WaveService
{
    /** @var DatabaseService */
    private $dbService;

    public function __construct()
    {
        $this->dbService = new DatabaseService();
    }

    /**
     * Создать новую волну миграций
     * 
     * @param string $name Название волны
     * @param array $projectUuids Массив UUID проектов
     * @param int $batchSize Размер батча
     * @param bool $mgrManual Флаг ручной миграции
     * @return array
     * @throws Exception
     */
    public function createWave(
        string $name,
        array $projectUuids,
        int $batchSize = 3,
        bool $mgrManual = false
    ): array {
        // Валидация
        if (empty($name)) {
            throw new Exception('Название волны обязательно');
        }
        
        if (empty($projectUuids)) {
            throw new Exception('Список UUID проектов не может быть пустым');
        }

        // Генерируем уникальный ID волны
        $waveId = time() . '_' . random_int(1000, 9999);

        // Инициализируем Logger перед использованием BrizyAPI
        // Проверяем, инициализирован ли Logger, и если нет - инициализируем
        if (!Logger::isInitialized()) {
            $logPath = dirname(__DIR__, 3) . '/var/log/wave_' . $waveId . '.log';
            @mkdir(dirname($logPath), 0755, true);
            Logger::initialize(
                'WaveService',
                \Monolog\Logger::DEBUG,
                $logPath
            );
        }

        // Инициализируем Config перед использованием BrizyAPI
        // Проверяем, инициализирован ли Config (через mainToken)
        if (empty(Config::$mainToken)) {
            $this->initializeConfig();
        }

        // Создаем или находим workspace
        $brizyApi = new BrizyAPI();
        $workspaceId = $brizyApi->getWorkspaces($name);
        
        if (!$workspaceId) {
            // Создаем новый workspace
            try {
                $workspaceResult = $brizyApi->createdWorkspaces($name);
                
                if (empty($workspaceResult)) {
                    throw new Exception('Пустой ответ от API при создании workspace');
                }
                
                // Проверяем статус ответа (может быть false при ошибке)
                if (isset($workspaceResult['status']) && ($workspaceResult['status'] === false || $workspaceResult['status'] >= 400)) {
                    $errorMsg = 'Ошибка создания workspace: ';
                    if (isset($workspaceResult['body'])) {
                        $errorBody = json_decode($workspaceResult['body'], true);
                        if (is_array($errorBody)) {
                            $errorMsg .= $errorBody['message'] ?? $errorBody['error'] ?? json_encode($errorBody);
                        } else {
                            $errorMsg .= $workspaceResult['body'];
                        }
                    } else {
                        $errorMsg .= 'HTTP ' . ($workspaceResult['status'] === false ? 'Connection failed' : $workspaceResult['status']);
                    }
                    throw new Exception($errorMsg);
                }
                
                // Парсим ответ
                $workspaceData = null;
                if (isset($workspaceResult['body'])) {
                    $workspaceData = json_decode($workspaceResult['body'], true);
                    // Проверяем на ошибки JSON
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Если не JSON, возможно это уже ID
                        if (is_numeric($workspaceResult['body'])) {
                            $workspaceId = (int)$workspaceResult['body'];
                        } else {
                            throw new Exception('Неверный формат ответа от API: ' . substr($workspaceResult['body'], 0, 200));
                        }
                    }
                } elseif (is_array($workspaceResult) && isset($workspaceResult['id'])) {
                    $workspaceData = $workspaceResult;
                }
                
                if ($workspaceData && !$workspaceId) {
                    if (isset($workspaceData['id'])) {
                        $workspaceId = $workspaceData['id'];
                    } elseif (isset($workspaceData[0]['id'])) {
                        $workspaceId = $workspaceData[0]['id'];
                    }
                }
                
                // Если не получили ID из ответа, пытаемся найти созданный workspace
                if (!$workspaceId) {
                    // Небольшая задержка для синхронизации
                    sleep(1);
                    $workspaceId = $brizyApi->getWorkspaces($name);
                    if (!$workspaceId) {
                        throw new Exception('Workspace создан, но не найден. Попробуйте создать волну еще раз.');
                    }
                }
            } catch (Exception $e) {
                // Если это уже наше исключение, пробрасываем дальше
                if (strpos($e->getMessage(), 'Ошибка создания workspace') !== false || 
                    strpos($e->getMessage(), 'Workspace создан') !== false ||
                    strpos($e->getMessage(), 'Пустой ответ') !== false ||
                    strpos($e->getMessage(), 'Неверный формат') !== false) {
                    throw $e;
                }
                // Иначе оборачиваем в более понятное сообщение
                throw new Exception('Ошибка при создании workspace: ' . $e->getMessage());
            }
        }

        // Сохраняем волну в БД
        $this->dbService->createWave(
            $waveId,
            $name,
            $projectUuids,
            $workspaceId,
            $name, // workspace_name = name волны
            $batchSize,
            $mgrManual
        );

        // Запускаем выполнение волны в фоне
        $this->runWaveInBackground($waveId);

        return [
            'wave_id' => $waveId,
            'workspace_id' => $workspaceId,
            'workspace_name' => $name,
            'status' => 'in_progress',
        ];
    }

    /**
     * Запустить выполнение волны в фоне
     * 
     * @param string $waveId ID волны
     * @return void
     * @throws Exception
     */
    private function runWaveInBackground(string $waveId): void
    {
        $projectRoot = dirname(__DIR__, 3);
        $logFile = $projectRoot . '/var/log/wave_' . $waveId . '_' . time() . '.log';
        
        // Создаем файл лога
        @file_put_contents($logFile, "=== Wave execution started at " . date('Y-m-d H:i:s') . " ===\n");
        @file_put_contents($logFile, "Wave ID: {$waveId}\n", FILE_APPEND);
        
        // Создаем wrapper script для выполнения волны
        $wrapperScript = $projectRoot . '/var/tmp/wave_wrapper_' . $waveId . '_' . time() . '.php';
        
        $projectRootEscaped = addslashes($projectRoot);
        $waveIdEscaped = addslashes($waveId);
        
        $wrapperContent = "<?php\n";
        $wrapperContent .= "chdir('{$projectRootEscaped}');\n";
        $wrapperContent .= "require_once '{$projectRootEscaped}/vendor/autoload_runtime.php';\n";
        $wrapperContent .= "use Dashboard\Services\WaveService;\n";
        $wrapperContent .= "use Dashboard\Services\DatabaseService;\n";
        $wrapperContent .= "use MBMigration\ApplicationBootstrapper;\n";
        $wrapperContent .= "use MBMigration\Core\Config;\n";
        $wrapperContent .= "use MBMigration\Layer\Brizy\BrizyAPI;\n";
        $wrapperContent .= "use Symfony\Component\HttpFoundation\Request;\n\n";
        
        $wrapperContent .= "try {\n";
        $wrapperContent .= "    \$dbService = new DatabaseService();\n";
        $wrapperContent .= "    \$wave = \$dbService->getWave('{$waveIdEscaped}');\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    if (!\$wave) {\n";
        $wrapperContent .= "        error_log('Wave not found: {$waveIdEscaped}');\n";
        $wrapperContent .= "        exit(1);\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Обновляем статус на in_progress\n";
        $wrapperContent .= "    \$dbService->updateWaveProgress('{$waveIdEscaped}', \$wave['progress'], \$wave['migrations'], 'in_progress');\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    \$brizyApi = new BrizyAPI();\n";
        $wrapperContent .= "    \$workspaceId = \$wave['workspace_id'];\n";
        $wrapperContent .= "    \$projectUuids = \$wave['project_uuids'];\n";
        $wrapperContent .= "    \$batchSize = \$wave['batch_size'];\n";
        $wrapperContent .= "    \$mgrManual = \$wave['mgr_manual'];\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    \$migrations = \$wave['migrations'] ?? [];\n";
        $wrapperContent .= "    \$progress = \$wave['progress'];\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Загружаем настройки по умолчанию\n";
        $wrapperContent .= "    \$settings = \$dbService->getSettings();\n";
        $wrapperContent .= "    \$mbSiteId = \$settings['mb_site_id'] ?? null;\n";
        $wrapperContent .= "    \$mbSecret = \$settings['mb_secret'] ?? null;\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    if (empty(\$mbSiteId) || empty(\$mbSecret)) {\n";
        $wrapperContent .= "        error_log('MB Site ID or Secret not configured');\n";
        $wrapperContent .= "        \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations, 'error');\n";
        $wrapperContent .= "        exit(1);\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Инициализируем ApplicationBootstrapper\n";
        $wrapperContent .= "    \$context = [];\n";
        $wrapperContent .= "    \$request = Request::create('/', 'GET');\n";
        $wrapperContent .= "    \$app = new ApplicationBootstrapper(\$context, \$request);\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Выполняем миграции последовательно\n";
        $wrapperContent .= "    foreach (\$projectUuids as \$mbUuid) {\n";
        $wrapperContent .= "            \n";
        $wrapperContent .= "            try {\n";
        $wrapperContent .= "                // Создаем проект в workspace\n";
        $wrapperContent .= "                \$projectName = 'Project_' . \$mbUuid;\n";
        $wrapperContent .= "                \$brzProjectId = \$brizyApi->createProject(\$projectName, \$workspaceId, 'id');\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                if (!\$brzProjectId) {\n";
        $wrapperContent .= "                    throw new Exception('Failed to create project in workspace');\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // Обновляем статус миграции на in_progress\n";
        $wrapperContent .= "                \$migrationIndex = array_search(\$mbUuid, array_column(\$migrations, 'mb_project_uuid'));\n";
        $wrapperContent .= "                if (\$migrationIndex === false) {\n";
        $wrapperContent .= "                    \$migrations[] = [\n";
        $wrapperContent .= "                        'mb_project_uuid' => \$mbUuid,\n";
        $wrapperContent .= "                        'brz_project_id' => \$brzProjectId,\n";
        $wrapperContent .= "                        'status' => 'in_progress',\n";
        $wrapperContent .= "                    ];\n";
        $wrapperContent .= "                } else {\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['status'] = 'in_progress';\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['brz_project_id'] = \$brzProjectId;\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "                \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations);\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // Выполняем миграцию\n";
        $wrapperContent .= "                \$result = \$app->migrationFlow(\n";
        $wrapperContent .= "                    \$mbUuid,\n";
        $wrapperContent .= "                    \$brzProjectId,\n";
        $wrapperContent .= "                    \$workspaceId,\n";
        $wrapperContent .= "                    '',\n";
        $wrapperContent .= "                    false,\n";
        $wrapperContent .= "                    \$mgrManual\n";
        $wrapperContent .= "                );\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // migrationFlow возвращает массив напрямую\n";
        $wrapperContent .= "                // Формируем структуру ответа для сохранения в migration_result_list\n";
        $wrapperContent .= "                \$migrationData = is_array(\$result) ? \$result : [];\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // Формируем полный ответ в формате для migration_result_list\n";
        $wrapperContent .= "                \$responseData = ['value' => \$migrationData];\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // Обновляем результат миграции\n";
        $wrapperContent .= "                \$migrationIndex = array_search(\$mbUuid, array_column(\$migrations, 'mb_project_uuid'));\n";
        $wrapperContent .= "                if (\$migrationIndex !== false) {\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['status'] = 'completed';\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['brizy_project_domain'] = \$migrationData['brizy_project_domain'] ?? null;\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['completed_at'] = date('Y-m-d H:i:s');\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // Сохраняем результат в migrations_mapping\n";
        $wrapperContent .= "                \$finalBrzProjectId = \$migrationData['brizy_project_id'] ?? \$brzProjectId;\n";
        $wrapperContent .= "                \$dbService->upsertMigrationMapping(\$finalBrzProjectId, \$mbUuid, [\n";
        $wrapperContent .= "                    'status' => 'completed',\n";
        $wrapperContent .= "                    'brizy_project_domain' => \$migrationData['brizy_project_domain'] ?? null,\n";
        $wrapperContent .= "                    'brizy_project_id' => \$finalBrzProjectId,\n";
        $wrapperContent .= "                    'migration_id' => \$migrationData['migration_id'] ?? null,\n";
        $wrapperContent .= "                    'date' => \$migrationData['date'] ?? null,\n";
        $wrapperContent .= "                    'theme' => \$migrationData['theme'] ?? null,\n";
        $wrapperContent .= "                    'mb_product_name' => \$migrationData['mb_product_name'] ?? null,\n";
        $wrapperContent .= "                    'mb_site_id' => \$migrationData['mb_site_id'] ?? null,\n";
        $wrapperContent .= "                    'progress' => \$migrationData['progress'] ?? null,\n";
        $wrapperContent .= "                    'DEV_MODE' => \$migrationData['DEV_MODE'] ?? null,\n";
        $wrapperContent .= "                    'message' => \$migrationData['message'] ?? null,\n";
        $wrapperContent .= "                    'completed_at' => date('Y-m-d H:i:s'),\n";
        $wrapperContent .= "                ]);\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // Сохраняем результат в migration_result_list\n";
        $wrapperContent .= "                // Используем mb_uuid из результата или из параметров\n";
        $wrapperContent .= "                \$resultMbUuid = \$migrationData['mb_uuid'] ?? \$mbUuid;\n";
        $wrapperContent .= "                \$resultBrzProjectId = \$migrationData['brizy_project_id'] ?? \$brzProjectId;\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                if (\$resultBrzProjectId && \$resultMbUuid) {\n";
        $wrapperContent .= "                    try {\n";
        $wrapperContent .= "                        \$migrationUuid = time() . random_int(100, 999);\n";
        $wrapperContent .= "                        \$dbService->saveMigrationResult([\n";
        $wrapperContent .= "                            'migration_uuid' => \$migrationUuid,\n";
        $wrapperContent .= "                            'brz_project_id' => (int)\$resultBrzProjectId,\n";
        $wrapperContent .= "                            'brizy_project_domain' => \$migrationData['brizy_project_domain'] ?? '',\n";
        $wrapperContent .= "                            'mb_project_uuid' => \$resultMbUuid,\n";
        $wrapperContent .= "                            'result_json' => json_encode(\$responseData)\n";
        $wrapperContent .= "                        ]);\n";
        $wrapperContent .= "                    } catch (Exception \$saveEx) {\n";
        $wrapperContent .= "                        error_log('Save result error: ' . \$saveEx->getMessage());\n";
        $wrapperContent .= "                    }\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                \$progress['completed']++;\n";
        $wrapperContent .= "                \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations);\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "            } catch (Exception \$e) {\n";
        $wrapperContent .= "                error_log('Migration error for ' . \$mbUuid . ': ' . \$e->getMessage());\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                // Обновляем статус на error\n";
        $wrapperContent .= "                \$migrationIndex = array_search(\$mbUuid, array_column(\$migrations, 'mb_project_uuid'));\n";
        $wrapperContent .= "                if (\$migrationIndex !== false) {\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['status'] = 'error';\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['error'] = \$e->getMessage();\n";
        $wrapperContent .= "                } else {\n";
        $wrapperContent .= "                    \$migrations[] = [\n";
        $wrapperContent .= "                        'mb_project_uuid' => \$mbUuid,\n";
        $wrapperContent .= "                        'status' => 'error',\n";
        $wrapperContent .= "                        'error' => \$e->getMessage(),\n";
        $wrapperContent .= "                    ];\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "                \n";
        $wrapperContent .= "                \$progress['failed']++;\n";
        $wrapperContent .= "                \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations);\n";
        $wrapperContent .= "            }\n";
        $wrapperContent .= "        }\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Обновляем финальный статус\n";
        $wrapperContent .= "    \$finalStatus = (\$progress['failed'] > 0) ? 'error' : 'completed';\n";
        $wrapperContent .= "    \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations, \$finalStatus);\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    error_log('Wave execution completed: {$waveIdEscaped}');\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "} catch (Exception \$e) {\n";
        $wrapperContent .= "    error_log('Wave execution error: ' . \$e->getMessage());\n";
        $wrapperContent .= "    error_log('Stack trace: ' . \$e->getTraceAsString());\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    try {\n";
        $wrapperContent .= "        \$dbService = new DatabaseService();\n";
        $wrapperContent .= "        \$wave = \$dbService->getWave('{$waveIdEscaped}');\n";
        $wrapperContent .= "        if (\$wave) {\n";
        $wrapperContent .= "            \$dbService->updateWaveProgress('{$waveIdEscaped}', \$wave['progress'], \$wave['migrations'], 'error');\n";
        $wrapperContent .= "        }\n";
        $wrapperContent .= "    } catch (Exception \$dbEx) {\n";
        $wrapperContent .= "        error_log('DB update error: ' . \$dbEx->getMessage());\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    exit(1);\n";
        $wrapperContent .= "}\n";
        
        @file_put_contents($wrapperScript, $wrapperContent);
        
        // Запускаем скрипт в фоне
        $command = sprintf(
            'cd %s && nohup php -f %s >> %s 2>&1 &',
            escapeshellarg($projectRoot),
            escapeshellarg($wrapperScript),
            escapeshellarg($logFile)
        );
        
        @file_put_contents($logFile, "Command: " . $command . "\n", FILE_APPEND);
        
        $pid = null;
        @exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            $command = sprintf(
                'cd %s && nohup php -f %s >> %s 2>&1 & echo $!',
                escapeshellarg($projectRoot),
                escapeshellarg($wrapperScript),
                escapeshellarg($logFile)
            );
            $result = @shell_exec($command);
            $pid = $result ? trim($result) : 'background';
        } else {
            $pid = 'background';
        }
        
        @file_put_contents($logFile, "PID: " . ($pid ?: 'unknown') . "\n", FILE_APPEND);
    }

    /**
     * Получить список всех волн
     * 
     * @return array
     * @throws Exception
     */
    public function getWavesList(): array
    {
        return $this->dbService->getWavesList();
    }

    /**
     * Получить детали волны
     * 
     * @param string $waveId ID волны
     * @return array|null
     * @throws Exception
     */
    public function getWaveDetails(string $waveId): ?array
    {
        $wave = $this->dbService->getWave($waveId);
        
        if (!$wave) {
            return null;
        }

        $migrations = $this->dbService->getWaveMigrations($waveId);
        
        return [
            'wave' => $wave,
            'migrations' => $migrations,
        ];
    }

    /**
     * Перезапустить миграцию в волне
     * 
     * @param string $waveId ID волны
     * @param string $mbUuid UUID проекта MB
     * @param array $params Дополнительные параметры
     * @return array
     * @throws Exception
     */
    public function restartMigrationInWave(string $waveId, string $mbUuid, array $params = []): array
    {
        $wave = $this->dbService->getWave($waveId);
        
        if (!$wave) {
            throw new Exception('Волна не найдена');
        }

        $workspaceId = $wave['workspace_id'];
        
        // Получаем миграцию из migration_result_list
        $migrations = $this->dbService->getWaveMigrations($waveId);
        $migration = null;
        foreach ($migrations as $m) {
            if ($m['mb_project_uuid'] === $mbUuid) {
                $migration = $m;
                break;
            }
        }

        if (!$migration) {
            throw new Exception('Миграция не найдена в волне');
        }

        // Если brz_project_id = 0, нужно создать проект в workspace
        $brzProjectId = $migration['brz_project_id'] ?? 0;
        
        if ($brzProjectId == 0) {
            // Инициализируем Config и Logger если нужно
            if (empty(\MBMigration\Core\Config::$mainToken)) {
                $this->initializeConfig();
            }
            if (!\MBMigration\Core\Logger::isInitialized()) {
                $logPath = dirname(__DIR__, 3) . '/var/log/wave_' . $waveId . '.log';
                @mkdir(dirname($logPath), 0755, true);
                \MBMigration\Core\Logger::initialize(
                    'WaveService',
                    \Monolog\Logger::DEBUG,
                    $logPath
                );
            }
            
            // Создаем проект в workspace
            $brizyApi = new \MBMigration\Layer\Brizy\BrizyAPI();
            $projectName = 'Project_' . $mbUuid;
            $brzProjectId = $brizyApi->createProject($projectName, $workspaceId, 'id');
            
            if (!$brzProjectId) {
                throw new Exception('Не удалось создать проект в workspace');
            }
            
            // Обновляем запись в migration_result_list с новым brz_project_id (но еще не in_progress)
            $this->dbService->updateMigrationResult($waveId, $mbUuid, [
                'brz_project_id' => $brzProjectId,
                'result_json' => [
                    'status' => 'pending',
                    'message' => 'Проект создан, подготовка к миграции'
                ]
            ]);
        }

        // Загружаем настройки по умолчанию
        $settings = $this->dbService->getSettings();
        $mbSiteId = $params['mb_site_id'] ?? $settings['mb_site_id'] ?? null;
        $mbSecret = $params['mb_secret'] ?? $settings['mb_secret'] ?? null;

        if (empty($mbSiteId) || empty($mbSecret)) {
            throw new Exception('mb_site_id и mb_secret должны быть указаны либо в запросе, либо в настройках');
        }

        // Формируем контекст для ApplicationBootstrapper
        $context = $this->buildApplicationContext();
        
        // Выполняем миграцию синхронно (для перезапуска)
        $request = \Symfony\Component\HttpFoundation\Request::create('/', 'GET', [
            'mb_site_id' => $mbSiteId,
            'mb_secret' => $mbSecret
        ]);
        $app = new \MBMigration\ApplicationBootstrapper($context, $request);

        try {
            // Инициализируем Config перед выполнением миграции
            $app->doInnitConfig();
            
            // Обновляем статус на in_progress только когда миграция реально начинается
            $this->dbService->updateMigrationResult($waveId, $mbUuid, [
                'result_json' => [
                    'status' => 'in_progress',
                    'message' => 'Миграция запущена',
                    'started_at' => date('Y-m-d H:i:s')
                ]
            ]);
            
            $result = $app->migrationFlow(
                $mbUuid,
                $brzProjectId,
                $workspaceId,
                '',
                false,
                $wave['mgr_manual']
            );

            // Обновляем статус миграции в волне
            $migrations = $wave['migrations'];
            $migrationIndex = array_search($mbUuid, array_column($migrations, 'mb_project_uuid'));
            
            if ($migrationIndex !== false) {
                $migrations[$migrationIndex]['status'] = 'completed';
                $migrations[$migrationIndex]['brizy_project_domain'] = $result['brizy_project_domain'] ?? null;
                $migrations[$migrationIndex]['completed_at'] = date('Y-m-d H:i:s');
                unset($migrations[$migrationIndex]['error']);
            }

            $progress = $wave['progress'];
            if ($migration['status'] === 'error') {
                $progress['failed'] = max(0, $progress['failed'] - 1);
            }
            if ($migration['status'] !== 'completed') {
                $progress['completed']++;
            }

            $this->dbService->updateWaveProgress($waveId, $progress, $migrations);

            // Сохраняем результат в migrations_mapping
            $finalBrzProjectId = $result['brizy_project_id'] ?? $brzProjectId;
            $this->dbService->upsertMigrationMapping($finalBrzProjectId, $mbUuid, [
                'status' => 'completed',
                'brizy_project_domain' => $result['brizy_project_domain'] ?? null,
                'brizy_project_id' => $finalBrzProjectId,
                'completed_at' => date('Y-m-d H:i:s'),
            ]);

            // Обновляем запись в migration_result_list с результатами миграции
            $this->dbService->updateMigrationResult($waveId, $mbUuid, [
                'brz_project_id' => $finalBrzProjectId,
                'brizy_project_domain' => $result['brizy_project_domain'] ?? '',
                'result_json' => [
                    'value' => $result,
                    'status' => 'completed'
                ]
            ]);

            return [
                'success' => true,
                'data' => $result,
            ];
        } catch (Exception $e) {
            // Обновляем статус на error в migration_result_list
            $this->dbService->updateMigrationResult($waveId, $mbUuid, [
                'result_json' => [
                    'status' => 'error',
                    'error' => $e->getMessage(),
                    'message' => 'Ошибка при выполнении миграции'
                ]
            ]);

            // Обновляем статус миграции в волне
            $migrations = $wave['migrations'];
            $migrationIndex = array_search($mbUuid, array_column($migrations, 'mb_project_uuid'));
            
            if ($migrationIndex !== false) {
                $migrations[$migrationIndex]['status'] = 'error';
                $migrations[$migrationIndex]['error'] = $e->getMessage();
            }

            $progress = $wave['progress'];
            if ($migration['status'] === 'completed') {
                $progress['completed'] = max(0, $progress['completed'] - 1);
            }
            $progress['failed']++;

            $this->dbService->updateWaveProgress($waveId, $progress, $migrations);

            throw $e;
        }
    }

    /**
     * Получить маппинг проектов для волны
     * 
     * @param string $waveId ID волны
     * @return array
     * @throws Exception
     */
    public function getWaveMapping(string $waveId): array
    {
        $wave = $this->dbService->getWave($waveId);
        
        if (!$wave) {
            throw new Exception('Волна не найдена');
        }

        return $this->dbService->getWaveMapping($waveId);
    }

    /**
     * Инициализировать Config для работы с BrizyAPI
     * Загружает настройки из переменных окружения
     * 
     * @return void
     * @throws Exception
     */
    private function initializeConfig(): void
    {
        $projectRoot = dirname(__DIR__, 3);
        
        // Загружаем переменные окружения
        if (file_exists($projectRoot . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createMutable($projectRoot);
            $dotenv->safeLoad();
        }
        
        $prodEnv = $projectRoot . '/.env.prod.local';
        if (file_exists($prodEnv)) {
            $dotenv = \Dotenv\Dotenv::createMutable($projectRoot, ['.env.prod.local']);
            $dotenv->safeLoad();
        }
        
        // Получаем настройки из переменных окружения
        $brizyCloudHost = $_ENV['BRIZY_CLOUD_HOST'] ?? getenv('BRIZY_CLOUD_HOST') ?: 'https://cloud.brizy.io';
        $brizyCloudToken = $_ENV['BRIZY_CLOUD_TOKEN'] ?? getenv('BRIZY_CLOUD_TOKEN');
        $logPath = $_ENV['LOG_PATH'] ?? getenv('LOG_PATH') ?: $projectRoot . '/var/log';
        $cachePath = $_ENV['CACHE_PATH'] ?? getenv('CACHE_PATH') ?: $projectRoot . '/var/cache';
        
        if (empty($brizyCloudToken)) {
            throw new Exception('BRIZY_CLOUD_TOKEN не установлен в переменных окружения');
        }
        
        // Получаем настройки БД из переменных окружения
        $mbDbHost = $_ENV['MB_DB_HOST'] ?? getenv('MB_DB_HOST') ?: 'localhost';
        $mbDbPort = $_ENV['MB_DB_PORT'] ?? getenv('MB_DB_PORT') ?: '3306';
        $mbDbName = $_ENV['MB_DB_NAME'] ?? getenv('MB_DB_NAME') ?: '';
        $mbDbUser = $_ENV['MB_DB_USER'] ?? getenv('MB_DB_USER') ?: '';
        $mbDbPass = $_ENV['MB_DB_PASSWORD'] ?? getenv('MB_DB_PASSWORD') ?: '';
        
        $mgDbHost = $_ENV['MG_DB_HOST'] ?? getenv('MG_DB_HOST') ?: $mbDbHost;
        $mgDbPort = $_ENV['MG_DB_PORT'] ?? getenv('MG_DB_PORT') ?: $mbDbPort;
        $mgDbName = $_ENV['MG_DB_NAME'] ?? getenv('MG_DB_NAME') ?: '';
        $mgDbUser = $_ENV['MG_DB_USER'] ?? getenv('MG_DB_USER') ?: '';
        $mgDbPass = $_ENV['MG_DB_PASS'] ?? getenv('MG_DB_PASS') ?: '';
        
        $mbMediaHost = $_ENV['MB_MEDIA_HOST'] ?? getenv('MB_MEDIA_HOST') ?: '';
        $mbPreviewHost = $_ENV['MB_PREVIEW_HOST'] ?? getenv('MB_PREVIEW_HOST') ?: 'staging.cloversites.com';
        
        // Создаем настройки для Config
        $settings = [
            'devMode' => (bool)($_ENV['DEV_MODE'] ?? getenv('DEV_MODE') ?? false),
            'mgrMode' => (bool)($_ENV['MGR_MODE'] ?? getenv('MGR_MODE') ?? false),
            'db' => [
                'dbHost' => $mbDbHost,
                'dbPort' => $mbDbPort,
                'dbName' => $mbDbName,
                'dbUser' => $mbDbUser,
                'dbPass' => $mbDbPass,
            ],
            'db_mg' => [
                'dbHost' => $mgDbHost,
                'dbPort' => $mgDbPort,
                'dbName' => $mgDbName,
                'dbUser' => $mgDbUser,
                'dbPass' => $mgDbPass,
            ],
            'assets' => [
                'MBMediaStaging' => $mbMediaHost,
            ],
            'previewBaseHost' => $mbPreviewHost,
        ];
        
        // Инициализируем Config
        @mkdir($logPath, 0755, true);
        @mkdir($cachePath, 0755, true);
        
        new Config(
            $brizyCloudHost,
            $logPath,
            $cachePath,
            $brizyCloudToken,
            $settings
        );
    }

    /**
     * Получить логи миграции из файла
     * 
     * @param string $waveId ID волны
     * @param string $mbUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @return array
     * @throws Exception
     */
    public function getMigrationLogs(string $waveId, string $mbUuid, int $brzProjectId): array
    {
        $projectRoot = dirname(__DIR__, 3);
        $logPath = $_ENV['LOG_PATH'] ?? getenv('LOG_PATH') ?: $projectRoot . '/var/log';
        
        // Формируем путь к лог-файлу (как в ApplicationBootstrapper::migrationFlow)
        // LOG_FILE_PATH формируется в buildApplicationContext как $logPath . '/migration_' . time()
        // Но реальный файл создается как LOG_FILE_PATH . '_' . $brz_project_id . '.log'
        // Поэтому ищем файлы по паттерну
        
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
        
        // Вариант 3: Ищем в логах волны
        $waveLogFile = $logPath . '/wave_' . $waveId . '.log';
        if (file_exists($waveLogFile)) {
            $logFiles[] = $waveLogFile;
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
                    // Разбиваем логи по паттерну Monolog: ][ (конец одной записи и начало другой)
                    // Заменяем ][ на ]\n[ чтобы каждая запись была на отдельной строке
                    $content = str_replace('][', "]\n[", $content);
                    
                    // Фильтруем логи по brz_project_id если это общий файл
                    if (strpos($logFile, '_' . $brzProjectId . '.log') !== false || 
                        strpos($logFile, 'wave_') !== false) {
                        $lines = explode("\n", $content);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (empty($line)) {
                                continue;
                            }
                            
                            // Фильтруем только строки, связанные с этой миграцией
                            if (strpos($line, "brizy-$brzProjectId") !== false || 
                                strpos($line, $mbUuid) !== false ||
                                strpos($logFile, '_' . $brzProjectId . '.log') !== false ||
                                preg_match('/\[202\d-\d{2}-\d{2}/', $line)) { // Если это запись с датой
                                $allLogs[] = $line;
                            }
                        }
                    } else {
                        // Для специфичных файлов просто разбиваем на строки
                        $lines = explode("\n", $content);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (!empty($line)) {
                                $allLogs[] = $line;
                            }
                        }
                    }
                }
            }
        }
        
        // Если не нашли логи в файлах, пробуем через grep (как в ApplicationBootstrapper)
        if (empty($allLogs)) {
            $logFilePath = $logPath . '/migration_*';
            $command = sprintf(
                'grep -h "brizy-%d\|%s" %s/*.log 2>/dev/null | tail -1000',
                $brzProjectId,
                escapeshellarg($mbUuid),
                escapeshellarg($logPath)
            );
            $output = @shell_exec($command);
            if ($output) {
                $lines = explode("\n", trim($output));
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        // Разбиваем склеенные записи
                        $line = str_replace('][', "]\n[", $line);
                        $subLines = explode("\n", $line);
                        foreach ($subLines as $subLine) {
                            $subLine = trim($subLine);
                            if (!empty($subLine)) {
                                $allLogs[] = $subLine;
                            }
                        }
                    }
                }
            }
        }
        
        // Убираем дубликаты и сортируем (если есть временные метки)
        $allLogs = array_unique($allLogs);
        $allLogs = array_values($allLogs); // Переиндексируем массив
        
        return [
            'logs' => $allLogs,
            'log_files' => $logFiles,
            'brz_project_id' => $brzProjectId,
            'mb_uuid' => $mbUuid
        ];
    }

    /**
     * Удалить lock-файл миграции
     * 
     * @param string $mbUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @return array
     * @throws Exception
     */
    public function removeMigrationLock(string $mbUuid, int $brzProjectId): array
    {
        $projectRoot = dirname(__DIR__, 3);
        $cachePath = $_ENV['CACHE_PATH'] ?? getenv('CACHE_PATH') ?: $projectRoot . '/var/cache';
        
        // Формируем путь к lock-файлу (как в ApplicationBootstrapper)
        $lockFile = $cachePath . '/' . $mbUuid . '-' . $brzProjectId . '.lock';
        
        if (!file_exists($lockFile)) {
            return [
                'success' => true,
                'message' => 'Lock-файл не найден (возможно, уже удален)',
                'lock_file' => $lockFile,
                'removed' => false
            ];
        }
        
        if (!is_writable($lockFile) && !is_writable($cachePath)) {
            throw new Exception('Нет прав на удаление lock-файла: ' . $lockFile);
        }
        
        $removed = @unlink($lockFile);
        
        if (!$removed) {
            throw new Exception('Не удалось удалить lock-файл: ' . $lockFile);
        }
        
        return [
            'success' => true,
            'message' => 'Lock-файл успешно удален',
            'lock_file' => $lockFile,
            'removed' => true
        ];
    }

    /**
     * Построить контекст для ApplicationBootstrapper из переменных окружения
     * 
     * @return array
     * @throws Exception
     */
    private function buildApplicationContext(): array
    {
        $projectRoot = dirname(__DIR__, 3);
        
        // Загружаем переменные окружения
        if (file_exists($projectRoot . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createMutable($projectRoot);
            $dotenv->safeLoad();
        }
        
        $prodEnv = $projectRoot . '/.env.prod.local';
        if (file_exists($prodEnv)) {
            $dotenv = \Dotenv\Dotenv::createMutable($projectRoot, ['.env.prod.local']);
            $dotenv->safeLoad();
        }
        
        $logPath = $_ENV['LOG_PATH'] ?? getenv('LOG_PATH') ?: $projectRoot . '/var/log';
        $cachePath = $_ENV['CACHE_PATH'] ?? getenv('CACHE_PATH') ?: $projectRoot . '/var/cache';
        
        // Создаем директории если их нет
        @mkdir($logPath, 0755, true);
        @mkdir($cachePath, 0755, true);
        
        // Формируем путь к лог-файлу для этой миграции
        $logFilePath = $logPath . '/migration_' . time();
        
        return [
            'LOG_FILE_PATH' => $logFilePath,
            'LOG_LEVEL' => (int)($_ENV['LOG_LEVEL'] ?? getenv('LOG_LEVEL') ?: \Monolog\Logger::DEBUG),
            'LOG_PATH' => $logPath,
            'CACHE_PATH' => $cachePath,
            'DEV_MODE' => (bool)($_ENV['DEV_MODE'] ?? getenv('DEV_MODE') ?? false),
            'MGR_MODE' => (bool)($_ENV['MGR_MODE'] ?? getenv('MGR_MODE') ?? false),
            'MB_DB_HOST' => $_ENV['MB_DB_HOST'] ?? getenv('MB_DB_HOST') ?: 'localhost',
            'MB_DB_PORT' => $_ENV['MB_DB_PORT'] ?? getenv('MB_DB_PORT') ?: '3306',
            'MB_DB_NAME' => $_ENV['MB_DB_NAME'] ?? getenv('MB_DB_NAME') ?: '',
            'MB_DB_USER' => $_ENV['MB_DB_USER'] ?? getenv('MB_DB_USER') ?: '',
            'MB_DB_PASSWORD' => $_ENV['MB_DB_PASSWORD'] ?? getenv('MB_DB_PASSWORD') ?: '',
            'MG_DB_HOST' => $_ENV['MG_DB_HOST'] ?? getenv('MG_DB_HOST') ?: ($_ENV['MB_DB_HOST'] ?? getenv('MB_DB_HOST') ?: 'localhost'),
            'MG_DB_PORT' => $_ENV['MG_DB_PORT'] ?? getenv('MG_DB_PORT') ?: ($_ENV['MB_DB_PORT'] ?? getenv('MB_DB_PORT') ?: '3306'),
            'MG_DB_NAME' => $_ENV['MG_DB_NAME'] ?? getenv('MG_DB_NAME') ?: '',
            'MG_DB_USER' => $_ENV['MG_DB_USER'] ?? getenv('MG_DB_USER') ?: '',
            'MG_DB_PASS' => $_ENV['MG_DB_PASS'] ?? getenv('MG_DB_PASS') ?: '',
            'MB_MEDIA_HOST' => $_ENV['MB_MEDIA_HOST'] ?? getenv('MB_MEDIA_HOST') ?: '',
            'MB_PREVIEW_HOST' => $_ENV['MB_PREVIEW_HOST'] ?? getenv('MB_PREVIEW_HOST') ?: 'staging.cloversites.com',
            'BRIZY_CLOUD_HOST' => $_ENV['BRIZY_CLOUD_HOST'] ?? getenv('BRIZY_CLOUD_HOST') ?: 'https://cloud.brizy.io',
            'BRIZY_CLOUD_TOKEN' => $_ENV['BRIZY_CLOUD_TOKEN'] ?? getenv('BRIZY_CLOUD_TOKEN') ?: '',
            'APP_AUTHORIZATION_TOKEN' => $_ENV['APP_AUTHORIZATION_TOKEN'] ?? getenv('APP_AUTHORIZATION_TOKEN') ?: '',
            'MB_MONKCMS_API' => $_ENV['MB_MONKCMS_API'] ?? getenv('MB_MONKCMS_API') ?: '',
            'AWS_BUCKET_ACTIVE' => (bool)($_ENV['AWS_BUCKET_ACTIVE'] ?? getenv('AWS_BUCKET_ACTIVE') ?? false),
            'AWS_KEY' => $_ENV['AWS_KEY'] ?? getenv('AWS_KEY') ?: '',
            'AWS_SECRET' => $_ENV['AWS_SECRET'] ?? getenv('AWS_SECRET') ?: '',
            'AWS_REGION' => $_ENV['AWS_REGION'] ?? getenv('AWS_REGION') ?: '',
            'AWS_BUCKET' => $_ENV['AWS_BUCKET'] ?? getenv('AWS_BUCKET') ?: '',
        ];
    }
}
