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
        error_log("[WaveService::createWave] Начало создания волны: name={$name}, projects=" . count($projectUuids) . ", batchSize={$batchSize}, mgrManual=" . ($mgrManual ? 'true' : 'false'));
        
        // Валидация
        if (empty($name)) {
            error_log("[WaveService::createWave] ОШИБКА: Название волны пустое");
            throw new Exception('Название волны обязательно');
        }
        
        if (empty($projectUuids)) {
            error_log("[WaveService::createWave] ОШИБКА: Список UUID проектов пустой");
            throw new Exception('Список UUID проектов не может быть пустым');
        }

        // Генерируем уникальный ID волны
        $waveId = time() . '_' . random_int(1000, 9999);
        error_log("[WaveService::createWave] Сгенерирован waveId: {$waveId}");

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
            error_log("[WaveService::createWave] Logger инициализирован: {$logPath}");
        } else {
            error_log("[WaveService::createWave] Logger уже инициализирован");
        }

        // Инициализируем Config перед использованием BrizyAPI
        // Проверяем, инициализирован ли Config (через mainToken)
        if (empty(Config::$mainToken)) {
            error_log("[WaveService::createWave] Config не инициализирован, инициализируем...");
            $this->initializeConfig();
            error_log("[WaveService::createWave] Config инициализирован");
        } else {
            error_log("[WaveService::createWave] Config уже инициализирован");
        }

        // Создаем или находим workspace
        error_log("[WaveService::createWave] Поиск workspace с именем: {$name}");
        $brizyApi = new BrizyAPI();
        $workspaceId = $brizyApi->getWorkspaces($name);
        error_log("[WaveService::createWave] Результат поиска workspace: " . ($workspaceId ? "найден ID={$workspaceId}" : "не найден"));
        
        if (!$workspaceId) {
            // Создаем новый workspace
            error_log("[WaveService::createWave] Workspace не найден, создаем новый...");
            try {
                $workspaceResult = $brizyApi->createdWorkspaces($name);
                error_log("[WaveService::createWave] Результат создания workspace: " . json_encode($workspaceResult));
                
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
        error_log("[WaveService::createWave] Сохранение волны в БД: waveId={$waveId}, workspaceId={$workspaceId}");
        try {
            $this->dbService->createWave(
                $waveId,
                $name,
                $projectUuids,
                $workspaceId,
                $name, // workspace_name = name волны
                $batchSize,
                $mgrManual
            );
            error_log("[WaveService::createWave] Волна успешно сохранена в БД");
        } catch (Exception $e) {
            error_log("[WaveService::createWave] ОШИБКА сохранения волны в БД: " . $e->getMessage());
            throw $e;
        }

        // Запускаем выполнение волны в фоне
        error_log("[WaveService::createWave] Запуск выполнения волны в фоне: waveId={$waveId}");
        try {
            $this->runWaveInBackground($waveId);
            error_log("[WaveService::createWave] Волна успешно запущена в фоне");
        } catch (Exception $e) {
            error_log("[WaveService::createWave] ОШИБКА запуска волны в фоне: " . $e->getMessage());
            error_log("[WaveService::createWave] Stack trace: " . $e->getTraceAsString());
            throw $e;
        }

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
        error_log("[WaveService::runWaveInBackground] Начало запуска волны в фоне: waveId={$waveId}");
        
        $projectRoot = dirname(__DIR__, 3);
        $logFile = $projectRoot . '/var/log/wave_' . $waveId . '_' . time() . '.log';
        
        error_log("[WaveService::runWaveInBackground] Лог-файл: {$logFile}");
        
        // Создаем файл лога
        @file_put_contents($logFile, "=== Wave execution started at " . date('Y-m-d H:i:s') . " ===\n");
        @file_put_contents($logFile, "Wave ID: {$waveId}\n", FILE_APPEND);
        @file_put_contents($logFile, "Project root: {$projectRoot}\n", FILE_APPEND);
        
        // Создаем wrapper script для выполнения волны
        $wrapperScript = $projectRoot . '/var/tmp/wave_wrapper_' . $waveId . '_' . time() . '.php';
        error_log("[WaveService::runWaveInBackground] Wrapper script: {$wrapperScript}");
        
        $projectRootEscaped = addslashes($projectRoot);
        $waveIdEscaped = addslashes($waveId);
        
        // Получаем данные волны для использования в генерации скрипта
        error_log("[WaveService::runWaveInBackground] Получение данных волны из БД...");
        $dbService = new DatabaseService();
        $wave = $dbService->getWave($waveId);
        if (!$wave) {
            $errorMsg = "ERROR: Wave not found: {$waveId}";
            error_log("[WaveService::runWaveInBackground] {$errorMsg}");
            @file_put_contents($logFile, "{$errorMsg}\n", FILE_APPEND);
            throw new Exception($errorMsg);
        }
        error_log("[WaveService::runWaveInBackground] Данные волны получены: workspaceId=" . ($wave['workspace_id'] ?? 'null') . ", projects=" . count($wave['project_uuids'] ?? []));
        
        $mgrManualValue = ($wave['mgr_manual'] ?? false) ? 'true' : 'false';
        error_log("[WaveService::runWaveInBackground] mgrManual: {$mgrManualValue}");
        
        $wrapperContent = "<?php\n";
        $wrapperContent .= "chdir('{$projectRootEscaped}');\n";
        $wrapperContent .= "require_once '{$projectRootEscaped}/vendor/autoload_runtime.php';\n";
        $wrapperContent .= "use Dashboard\Services\WaveService;\n";
        $wrapperContent .= "use Dashboard\Services\DatabaseService;\n";
        $wrapperContent .= "use MBMigration\ApplicationBootstrapper;\n";
        $wrapperContent .= "use MBMigration\Core\Config;\n";
        $wrapperContent .= "use MBMigration\Layer\Brizy\BrizyAPI;\n";
        $wrapperContent .= "use Symfony\Component\HttpFoundation\Request;\n\n";
        
        $wrapperContent .= "error_log('[WaveWrapper] Script started at ' . date('Y-m-d H:i:s'));\n";
        $wrapperContent .= "error_log('[WaveWrapper] Wave ID: {$waveIdEscaped}');\n";
        $wrapperContent .= "error_log('[WaveWrapper] Project root: {$projectRootEscaped}');\n";
        $wrapperContent .= "error_log('[WaveWrapper] PHP version: ' . PHP_VERSION);\n";
        $wrapperContent .= "error_log('[WaveWrapper] Working directory: ' . getcwd());\n\n";
        
        $wrapperContent .= "try {\n";
        $wrapperContent .= "    error_log('[WaveWrapper] Initializing DatabaseService...');\n";
        $wrapperContent .= "    \$dbService = new DatabaseService();\n";
        $wrapperContent .= "    error_log('[WaveWrapper] Getting wave data from DB...');\n";
        $wrapperContent .= "    \$wave = \$dbService->getWave('{$waveIdEscaped}');\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    if (!\$wave) {\n";
        $wrapperContent .= "        error_log('[WaveWrapper] ERROR: Wave not found: {$waveIdEscaped}');\n";
        $wrapperContent .= "        exit(1);\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    error_log('[WaveWrapper] Wave data loaded: workspaceId=' . (\$wave['workspace_id'] ?? 'null') . ', projects=' . count(\$wave['project_uuids'] ?? []));\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Обновляем статус на in_progress\n";
        $wrapperContent .= "    error_log('[WaveWrapper] Updating wave status to in_progress...');\n";
        $wrapperContent .= "    \$dbService->updateWaveProgress('{$waveIdEscaped}', \$wave['progress'], \$wave['migrations'], 'in_progress');\n";
        $wrapperContent .= "    error_log('[WaveWrapper] Wave status updated to in_progress');\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    error_log('[WaveWrapper] Initializing BrizyAPI...');\n";
        $wrapperContent .= "    \$brizyApi = new BrizyAPI();\n";
        $wrapperContent .= "    \$workspaceId = \$wave['workspace_id'];\n";
        $wrapperContent .= "    \$projectUuids = \$wave['project_uuids'];\n";
        $wrapperContent .= "    \$batchSize = \$wave['batch_size'];\n";
        $wrapperContent .= "    error_log('[WaveWrapper] Workspace ID: ' . \$workspaceId . ', Projects count: ' . count(\$projectUuids) . ', Batch size: ' . \$batchSize);\n";
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
        $wrapperContent .= "    \$app->doInnitConfig();\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Выполняем миграции параллельно с учетом batch_size\n";
        $wrapperContent .= "    \$pending = array_values(\$projectUuids);\n";
        $wrapperContent .= "    \$activeProcesses = [];\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    while (!empty(\$pending) || !empty(\$activeProcesses)) {\n";
        $wrapperContent .= "        // Запускаем новые миграции до достижения batch_size\n";
        $wrapperContent .= "        while (count(\$activeProcesses) < \$batchSize && !empty(\$pending)) {\n";
        $wrapperContent .= "            \$mbUuid = array_shift(\$pending);\n";
        $wrapperContent .= "            \n";
        $wrapperContent .= "            try {\n";
        $wrapperContent .= "                // Запускаем миграцию в отдельном процессе (создание проекта будет внутри скрипта)\n";
        $wrapperContent .= "                \$migrationScript = sys_get_temp_dir() . '/wave_migration_' . '{$waveIdEscaped}' . '_' . md5(\$mbUuid) . '_' . time() . '_' . getmypid() . '.php';\n";
        $wrapperContent .= "                \$scriptContent = '<?php' . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"chdir('{$projectRootEscaped}');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"require_once '{$projectRootEscaped}/vendor/autoload_runtime.php';\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"use Dashboard\\\\Services\\\\DatabaseService;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"use MBMigration\\\\ApplicationBootstrapper;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"use MBMigration\\\\Layer\\\\Brizy\\\\BrizyAPI;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"use Symfony\\\\Component\\\\HttpFoundation\\\\Request;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"use Exception;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"error_log('[WaveMigration] Starting migration for MB UUID: ' . \\\$mbUuid);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"try {\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 1: Initializing DatabaseService...');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$dbService = new DatabaseService();\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 2: Loading wave data from DB...');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$wave = \$dbService->getWave('{$waveIdEscaped}');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    if (!\$wave) throw new Exception('Wave not found: {$waveIdEscaped}');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$workspaceId = \$wave['workspace_id'];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$migrations = \$wave['migrations'] ?? [];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$progress = \$wave['progress'];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \\\$mgrManual = {$mgrManualValue};\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \\\$mbUuid = \" . var_export(\$mbUuid, true) . \";\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 3: Initializing BrizyAPI...');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$brizyApi = new BrizyAPI();\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$projectName = 'Project_' . \\\$mbUuid;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 4: Creating project in workspace. Name: ' . \$projectName . ', Workspace ID: ' . \$workspaceId);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \\\$brzProjectId = \$brizyApi->createProject(\$projectName, \$workspaceId, 'id');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 5: Project creation result: ' . (\\\$brzProjectId ? 'SUCCESS, ID=' . \\\$brzProjectId : 'FAILED'));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    if (!\\\$brzProjectId) throw new Exception('Failed to create project in workspace. Project name: ' . \$projectName . ', Workspace ID: ' . \$workspaceId);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    // Обновляем статус миграции на in_progress\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$migrationIndex = array_search(\\\$mbUuid, array_column(\$migrations, 'mb_project_uuid'));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    if (\$migrationIndex === false) {\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[] = ['mb_project_uuid' => \\\$mbUuid, 'brz_project_id' => \\\$brzProjectId, 'status' => 'in_progress'];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    } else {\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[\$migrationIndex]['status'] = 'in_progress';\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[\$migrationIndex]['brz_project_id'] = \\\$brzProjectId;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    }\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 7: Initializing ApplicationBootstrapper...');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$context = [];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$request = Request::create('/', 'GET');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$app = new ApplicationBootstrapper(\$context, \$request);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$app->doInnitConfig();\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 8: Starting migrationFlow. MB UUID: ' . \\\$mbUuid . ', Brizy Project ID: ' . \\\$brzProjectId . ', Workspace ID: ' . \$workspaceId . ', MgrManual: ' . (\\\$mgrManual ? 'true' : 'false'));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \\\$result = \\\$app->migrationFlow(\\\$mbUuid, \\\$brzProjectId, \$workspaceId, '', false, \\\$mgrManual);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] Step 9: migrationFlow completed. Result type: ' . gettype(\\\$result) . ', Is array: ' . (is_array(\\\$result) ? 'yes' : 'no'));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$migrationData = is_array(\$result) ? \$result : [];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$migrationIndex = array_search(\\\$mbUuid, array_column(\$migrations, 'mb_project_uuid'));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    if (\$migrationIndex !== false) {\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[\$migrationIndex]['status'] = 'completed';\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[\$migrationIndex]['brizy_project_domain'] = \$migrationData['brizy_project_domain'] ?? null;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[\$migrationIndex]['completed_at'] = date('Y-m-d H:i:s');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    }\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$finalBrzProjectId = \$migrationData['brizy_project_id'] ?? \" . \$brzProjectId . \";\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$dbService->upsertMigrationMapping(\\\$finalBrzProjectId, \\\$mbUuid, ['status' => 'completed', 'brizy_project_domain' => \$migrationData['brizy_project_domain'] ?? null, 'brizy_project_id' => \\\$finalBrzProjectId, 'completed_at' => date('Y-m-d H:i:s')]);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$progress['completed']++;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    file_put_contents('\" . \$migrationScript . \".result', json_encode(['success' => true, 'mb_uuid' => \\\$mbUuid, 'result' => \$migrationData]));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"} catch (Exception \$e) {\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] ERROR: Exception caught: ' . \$e->getMessage());\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] ERROR: File: ' . \$e->getFile() . ', Line: ' . \$e->getLine());\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    error_log('[WaveMigration] ERROR: Stack trace: ' . \$e->getTraceAsString());\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$dbService = new DatabaseService();\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$wave = \$dbService->getWave('{$waveIdEscaped}');\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$migrations = \$wave['migrations'] ?? [];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$progress = \$wave['progress'];\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \\\$mbUuid = \" . var_export(\$mbUuid, true) . \";\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$migrationIndex = array_search(\\\$mbUuid, array_column(\$migrations, 'mb_project_uuid'));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    if (\$migrationIndex !== false) {\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[\$migrationIndex]['status'] = 'error';\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"        \$migrations[\$migrationIndex]['error'] = \$e->getMessage();\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    }\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$progress['failed']++;\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations);\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"    file_put_contents('\" . \$migrationScript . \".result', json_encode(['success' => false, 'mb_uuid' => \\\$mbUuid, 'error' => \$e->getMessage(), 'file' => \$e->getFile(), 'line' => \$e->getLine()]));\" . PHP_EOL;\n";
        $wrapperContent .= "                \$scriptContent .= \"}\";\n";
        $wrapperContent .= "                \n";
                $wrapperContent .= "                error_log('[WaveWrapper] Saving migration script: ' . \$migrationScript);\n";
                $wrapperContent .= "                file_put_contents(\$migrationScript, \$scriptContent);\n";
                $wrapperContent .= "                error_log('[WaveWrapper] Migration script saved: ' . \$migrationScript . ' (' . filesize(\$migrationScript) . ' bytes)');\n";
                $wrapperContent .= "                \n";
                $wrapperContent .= "                // Запускаем процесс в фоне\n";
                $wrapperContent .= "                \$command = sprintf('cd %s && nohup php -f %s > /dev/null 2>&1 & echo $!', escapeshellarg('{$projectRootEscaped}'), escapeshellarg(\$migrationScript));\n";
                $wrapperContent .= "                error_log('[WaveWrapper] Starting migration process for ' . \$mbUuid . ': ' . \$command);\n";
                $wrapperContent .= "                \$pid = trim(shell_exec(\$command));\n";
                $wrapperContent .= "                error_log('[WaveWrapper] Migration process started: mbUuid=' . \$mbUuid . ', pid=' . (\$pid ?: 'NOT SET'));\n";
                $wrapperContent .= "                \n";
                $wrapperContent .= "                if (!empty(\$pid) && is_numeric(\$pid)) {\n";
                $wrapperContent .= "                    \$activeProcesses[\$mbUuid] = ['pid' => (int)\$pid, 'script' => \$migrationScript];\n";
                $wrapperContent .= "                    error_log('[WaveWrapper] Active processes count: ' . count(\$activeProcesses));\n";
                $wrapperContent .= "                } else {\n";
                $wrapperContent .= "                    error_log('[WaveWrapper] ERROR: Failed to start migration process for ' . \$mbUuid . ', pid=' . (\$pid ?: 'empty'));\n";
                $wrapperContent .= "                    throw new Exception('Failed to start migration process');\n";
                $wrapperContent .= "                }\n";
        $wrapperContent .= "            } catch (Exception \$e) {\n";
        $wrapperContent .= "                error_log('Error starting migration for ' . \$mbUuid . ': ' . \$e->getMessage());\n";
        $wrapperContent .= "                \$migrationIndex = array_search(\$mbUuid, array_column(\$migrations, 'mb_project_uuid'));\n";
        $wrapperContent .= "                if (\$migrationIndex !== false) {\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['status'] = 'error';\n";
        $wrapperContent .= "                    \$migrations[\$migrationIndex]['error'] = \$e->getMessage();\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "                \$progress['failed']++;\n";
        $wrapperContent .= "                \$dbService->updateWaveProgress('{$waveIdEscaped}', \$progress, \$migrations);\n";
        $wrapperContent .= "            }\n";
        $wrapperContent .= "        }\n";
        $wrapperContent .= "        \n";
        $wrapperContent .= "        // Проверяем завершенные процессы\n";
        $wrapperContent .= "        error_log('[WaveWrapper] Checking completed processes. Active processes count: ' . count(\$activeProcesses));\n";
        $wrapperContent .= "        foreach (\$activeProcesses as \$mbUuid => \$processInfo) {\n";
        $wrapperContent .= "            \$pid = \$processInfo['pid'];\n";
        $wrapperContent .= "            \$script = \$processInfo['script'];\n";
        $wrapperContent .= "            \$resultFile = \$script . '.result';\n";
        $wrapperContent .= "            \n";
        $wrapperContent .= "            error_log('[WaveWrapper] Checking process: mbUuid=' . \$mbUuid . ', pid=' . \$pid . ', script=' . \$script);\n";
        $wrapperContent .= "            \n";
        $wrapperContent .= "            // Проверяем, завершился ли процесс\n";
        $wrapperContent .= "            \$processRunning = false;\n";
        $wrapperContent .= "            if (\$pid > 0) {\n";
        $wrapperContent .= "                \$checkCommand = sprintf('ps -p %d -o pid= 2>/dev/null', \$pid);\n";
        $wrapperContent .= "                \$psOutput = trim(shell_exec(\$checkCommand));\n";
        $wrapperContent .= "                \$processRunning = !empty(\$psOutput);\n";
        $wrapperContent .= "                error_log('[WaveWrapper] Process check result: mbUuid=' . \$mbUuid . ', pid=' . \$pid . ', running=' . (\$processRunning ? 'yes' : 'no'));\n";
        $wrapperContent .= "            }\n";
        $wrapperContent .= "            \n";
        $wrapperContent .= "            // Если процесс завершился и есть результат\n";
        $wrapperContent .= "            if (!\$processRunning && file_exists(\$resultFile)) {\n";
        $wrapperContent .= "                error_log('[WaveWrapper] Process completed: mbUuid=' . \$mbUuid . ', reading result file: ' . \$resultFile);\n";
        $wrapperContent .= "                \$resultData = json_decode(file_get_contents(\$resultFile), true);\n";
        $wrapperContent .= "                if (\$resultData && \$resultData['success']) {\n";
        $wrapperContent .= "                    error_log('[WaveWrapper] Migration SUCCESS: mbUuid=' . \$mbUuid);\n";
        $wrapperContent .= "                } else {\n";
        $wrapperContent .= "                    error_log('[WaveWrapper] Migration FAILED: mbUuid=' . \$mbUuid . ', error=' . (\$resultData['error'] ?? 'unknown'));\n";
        $wrapperContent .= "                }\n";
        $wrapperContent .= "                unset(\$activeProcesses[\$mbUuid]);\n";
        $wrapperContent .= "                @unlink(\$script);\n";
        $wrapperContent .= "                @unlink(\$resultFile);\n";
        $wrapperContent .= "                error_log('[WaveWrapper] Cleaned up script and result file for: mbUuid=' . \$mbUuid);\n";
        $wrapperContent .= "            } elseif (!\$processRunning && !file_exists(\$resultFile)) {\n";
        $wrapperContent .= "                error_log('[WaveWrapper] WARNING: Process completed but no result file found: mbUuid=' . \$mbUuid . ', pid=' . \$pid . ', script=' . \$script);\n";
        $wrapperContent .= "            }\n";
        $wrapperContent .= "        }\n";
        $wrapperContent .= "        \n";
        $wrapperContent .= "        // Небольшая задержка перед следующей итерацией\n";
        $wrapperContent .= "        if (!empty(\$activeProcesses)) {\n";
        $wrapperContent .= "            usleep(500000); // 0.5 секунды\n";
        $wrapperContent .= "        }\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Очищаем оставшиеся скрипты (на случай ошибок)\n";
        $wrapperContent .= "    foreach (glob(sys_get_temp_dir() . '/wave_migration_' . '{$waveIdEscaped}' . '_*.php') as \$script) {\n";
        $wrapperContent .= "        @unlink(\$script);\n";
        $wrapperContent .= "        @unlink(\$script . '.result');\n";
        $wrapperContent .= "    }\n";
        $wrapperContent .= "    \n";
        $wrapperContent .= "    // Выполняем миграции последовательно (старый код для совместимости - удалить после тестирования)\n";
        $wrapperContent .= "    /*\n";
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

            // Сохраняем данные о страницах в page_quality_analysis даже без анализа качества
            // Это нужно для отображения страниц во вкладке "Страницы"
            try {
                $pageList = $app->getPageList();
                if (!empty($pageList) && isset($result['brizy_project_id'])) {
                    $qualityReport = new \MBMigration\Analysis\QualityReport();
                    $mbProjectDomain = $result['mb_project_domain'] ?? null;
                    $brizyProjectDomain = $result['brizy_project_domain'] ?? null;
                    
                    foreach ($pageList as $page) {
                        $pageSlug = $page['slug'] ?? null;
                        if (empty($pageSlug)) {
                            continue;
                        }
                        
                        // Формируем URLs для страницы
                        $sourceUrl = null;
                        $migratedUrl = null;
                        
                        if ($mbProjectDomain) {
                            $sourceUrl = rtrim($mbProjectDomain, '/') . '/' . ltrim($pageSlug, '/');
                        }
                        
                        if ($brizyProjectDomain) {
                            $migratedUrl = rtrim($brizyProjectDomain, '/') . '/' . ltrim($pageSlug, '/');
                        }
                        
                        // Сохраняем базовую запись о странице без анализа качества
                        $qualityReport->saveReport([
                            'migration_id' => (int)$result['brizy_project_id'],
                            'mb_project_uuid' => $mbUuid,
                            'page_slug' => $pageSlug,
                            'source_url' => $sourceUrl,
                            'migrated_url' => $migratedUrl,
                            'analysis_status' => 'pending', // Статус "pending" означает, что анализ не был выполнен
                            'quality_score' => null,
                            'severity_level' => 'none',
                            'issues_summary' => [],
                            'detailed_report' => [],
                            'screenshots_path' => json_encode([])
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Логируем ошибку, но не прерываем выполнение миграции
                error_log("Ошибка сохранения данных о страницах: " . $e->getMessage());
            }

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
     * Массовый перезапуск миграций в волне
     * Очищает кэш, lock-файлы и БД записи, затем перезапускает миграции
     * 
     * @param string $waveId ID волны
     * @param array $mbUuids Массив UUID проектов для перезапуска (если пустой - все миграции)
     * @param array $params Дополнительные параметры (mb_site_id, mb_secret и т.д.)
     * @return array
     * @throws Exception
     */
    public function restartAllMigrationsInWave(string $waveId, array $mbUuids = [], array $params = []): array
    {
        error_log("[WaveService::restartAllMigrationsInWave] Начало массового перезапуска: waveId={$waveId}, mbUuids=" . count($mbUuids) . ", params=" . json_encode($params));
        
        $wave = $this->dbService->getWave($waveId);
        
        if (!$wave) {
            $errorMsg = "Волна не найдена: {$waveId}";
            error_log("[WaveService::restartAllMigrationsInWave] ОШИБКА: {$errorMsg}");
            throw new Exception($errorMsg);
        }

        error_log("[WaveService::restartAllMigrationsInWave] Волна найдена: name=" . ($wave['name'] ?? 'N/A') . ", workspace_id=" . ($wave['workspace_id'] ?? 'N/A'));

        // Получаем workspace_id из волны
        $workspaceId = $wave['workspace_id'] ?? null;
        if (!$workspaceId) {
            $errorMsg = "Workspace ID не найден в волне: {$waveId}";
            error_log("[WaveService::restartAllMigrationsInWave] ОШИБКА: {$errorMsg}");
            throw new Exception($errorMsg);
        }

        error_log("[WaveService::restartAllMigrationsInWave] Workspace ID: {$workspaceId}");

        // Получаем все миграции волны
        $migrations = $this->dbService->getWaveMigrations($waveId);
        error_log("[WaveService::restartAllMigrationsInWave] Найдено миграций в волне: " . count($migrations));
        
        // Фильтруем по переданным UUID, если указаны
        if (!empty($mbUuids)) {
            $migrations = array_filter($migrations, function($m) use ($mbUuids) {
                return in_array($m['mb_project_uuid'], $mbUuids);
            });
            error_log("[WaveService::restartAllMigrationsInWave] После фильтрации по UUID: " . count($migrations) . " миграций");
        }

        if (empty($migrations)) {
            $errorMsg = "Не найдено миграций для перезапуска";
            error_log("[WaveService::restartAllMigrationsInWave] ОШИБКА: {$errorMsg}");
            throw new Exception($errorMsg);
        }

        $migrationService = new \Dashboard\Services\MigrationService();
        $results = [
            'total' => count($migrations),
            'processed' => 0,
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        // Загружаем настройки по умолчанию
        $settings = $this->dbService->getSettings();
        $mbSiteId = $params['mb_site_id'] ?? $settings['mb_site_id'] ?? null;
        $mbSecret = $params['mb_secret'] ?? $settings['mb_secret'] ?? null;

        if (empty($mbSiteId) || empty($mbSecret)) {
            throw new Exception('mb_site_id и mb_secret должны быть указаны либо в запросе, либо в настройках');
        }

        // Обновляем статус волны на in_progress
        error_log("[WaveService::restartAllMigrationsInWave] Обновление статуса волны на in_progress...");
        try {
            $waveProgress = $wave['progress'] ?? ['total' => count($migrations), 'completed' => 0, 'failed' => 0];
            $waveMigrations = $wave['migrations'] ?? [];
            $this->dbService->updateWaveProgress($waveId, $waveProgress, $waveMigrations, 'in_progress');
            error_log("[WaveService::restartAllMigrationsInWave] Статус волны обновлен на in_progress");
        } catch (Exception $e) {
            error_log("[WaveService::restartAllMigrationsInWave] ОШИБКА при обновлении статуса волны: " . $e->getMessage());
        }

        // Очищаем кэш, lock-файлы и сбрасываем статус для каждой миграции (быстро, без запуска миграций)
        error_log("[WaveService::restartAllMigrationsInWave] Начало обработки " . count($migrations) . " миграций...");
        foreach ($migrations as $index => $migration) {
            $mbUuid = $migration['mb_project_uuid'];
            $brzProjectId = $migration['brz_project_id'] ?? 0;
            
            error_log("[WaveService::restartAllMigrationsInWave] Обработка миграции " . ($index + 1) . "/" . count($migrations) . ": mbUuid={$mbUuid}, brzProjectId={$brzProjectId}");
            
            $detail = [
                'mb_uuid' => $mbUuid,
                'brz_project_id' => $brzProjectId,
                'cache_cleared' => false,
                'lock_removed' => false,
                'status_reset' => false,
                'restarted' => false,
                'error' => null
            ];

            try {
                // Если проект уже создан, очищаем кэш и lock-файлы
                if ($brzProjectId > 0) {
                    // 1. Удаляем lock-файл
                    try {
                        $lockResult = $migrationService->removeMigrationLock($mbUuid, $brzProjectId);
                        if ($lockResult['success']) {
                            $detail['lock_removed'] = $lockResult['removed'] ?? false;
                        }
                    } catch (Exception $e) {
                        $detail['error'] = 'Ошибка удаления lock-файла: ' . $e->getMessage();
                    }

                    // 2. Удаляем кэш-файл
                    try {
                        $cacheResult = $migrationService->removeMigrationCache($mbUuid, $brzProjectId);
                        if ($cacheResult['success']) {
                            $detail['cache_cleared'] = $cacheResult['removed'] ?? false;
                        }
                    } catch (Exception $e) {
                        if ($detail['error']) {
                            $detail['error'] .= '; Ошибка удаления кэша: ' . $e->getMessage();
                        } else {
                            $detail['error'] = 'Ошибка удаления кэша: ' . $e->getMessage();
                        }
                    }

                    // 3. Сбрасываем статус в БД
                    try {
                        $statusResult = $migrationService->resetMigrationStatus($mbUuid, $brzProjectId);
                        if ($statusResult['success']) {
                            $detail['status_reset'] = true;
                        }
                    } catch (Exception $e) {
                        if ($detail['error']) {
                            $detail['error'] .= '; Ошибка сброса статуса: ' . $e->getMessage();
                        } else {
                            $detail['error'] = 'Ошибка сброса статуса: ' . $e->getMessage();
                        }
                    }
                }

                // 4. Сбрасываем статус в migration_result_list на pending
                try {
                    $this->dbService->updateMigrationResult($waveId, $mbUuid, [
                        'result_json' => [
                            'status' => 'pending',
                            'message' => 'Подготовка к перезапуску миграции'
                        ]
                    ]);
                } catch (Exception $e) {
                    error_log("Ошибка обновления migration_result_list для $mbUuid: " . $e->getMessage());
                }

                // 5. Запускаем миграцию в фоне через отдельный процесс
                error_log("[WaveService::restartAllMigrationsInWave] Запуск миграции в фоне: mbUuid={$mbUuid}, brzProjectId={$brzProjectId}");
                try {
                    $this->startMigrationInBackground($waveId, $mbUuid, $brzProjectId, $workspaceId, $mbSiteId, $mbSecret, $params);
                    error_log("[WaveService::restartAllMigrationsInWave] Миграция успешно запущена в фоне: mbUuid={$mbUuid}");
                    $detail['restarted'] = true;
                    $results['success']++;
                } catch (Exception $startError) {
                    error_log("[WaveService::restartAllMigrationsInWave] ОШИБКА при запуске миграции в фоне: mbUuid={$mbUuid}, error=" . $startError->getMessage());
                    $detail['error'] = ($detail['error'] ? $detail['error'] . '; ' : '') . 'Ошибка запуска миграции: ' . $startError->getMessage();
                    $results['failed']++;
                }

            } catch (Exception $e) {
                error_log("[WaveService::restartAllMigrationsInWave] ОШИБКА при обработке миграции mbUuid={$mbUuid}: " . $e->getMessage());
                error_log("[WaveService::restartAllMigrationsInWave] Stack trace: " . $e->getTraceAsString());
                $detail['error'] = $e->getMessage();
                $results['failed']++;
                // Обновляем статус на error при ошибке
                try {
                    $this->dbService->updateMigrationResult($waveId, $mbUuid, [
                        'result_json' => [
                            'status' => 'error',
                            'error' => $e->getMessage(),
                            'message' => 'Ошибка при подготовке перезапуска миграции'
                        ]
                    ]);
                } catch (Exception $updateError) {
                    error_log("Ошибка обновления статуса на error для $mbUuid: " . $updateError->getMessage());
                }
            }

            $results['processed']++;
            $results['details'][] = $detail;
        }

        error_log("[WaveService::restartAllMigrationsInWave] Массовый перезапуск завершен: total=" . $results['total'] . ", success=" . $results['success'] . ", failed=" . $results['failed'] . ", processed=" . $results['processed']);
        
        return [
            'success' => $results['failed'] === 0,
            'message' => sprintf(
                'Обработано: %d из %d. Успешно: %d, Ошибок: %d',
                $results['processed'],
                $results['total'],
                $results['success'],
                $results['failed']
            ),
            'results' => $results
        ];
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
     * Получить логи для волны миграций
     * 
     * @param string $waveId ID волны
     * @return string Содержимое лог-файла
     * @throws Exception
     */
    public function getWaveLogs(string $waveId): string
    {
        $projectRoot = dirname(__DIR__, 3);
        $logPath = $_ENV['LOG_PATH'] ?? getenv('LOG_PATH') ?: $projectRoot . '/var/log';
        
        // Ищем все лог-файлы для этой волны
        // Формат: wave_{waveId}_{timestamp}.log или wave_{waveId}.log
        $logFiles = [];
        
        // Сначала ищем файлы с timestamp
        $pattern = $logPath . '/wave_' . $waveId . '_*.log';
        $files = glob($pattern);
        if ($files) {
            $logFiles = array_merge($logFiles, $files);
        }
        
        // Также ищем файл без timestamp
        $simpleLogFile = $logPath . '/wave_' . $waveId . '.log';
        if (file_exists($simpleLogFile)) {
            $logFiles[] = $simpleLogFile;
        }
        
        // Сортируем по времени модификации (новые первыми)
        usort($logFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        if (empty($logFiles)) {
            return 'Лог-файлы для волны не найдены. Ожидаемые файлы: wave_' . $waveId . '_*.log или wave_' . $waveId . '.log';
        }
        
        // Объединяем содержимое всех найденных файлов (начиная с самого нового)
        $allLogs = [];
        foreach ($logFiles as $logFile) {
            if (file_exists($logFile) && is_readable($logFile)) {
                $content = file_get_contents($logFile);
                if ($content) {
                    $allLogs[] = "=== " . basename($logFile) . " ===\n" . $content;
                }
            }
        }
        
        if (empty($allLogs)) {
            return 'Лог-файлы найдены, но не удалось прочитать их содержимое';
        }
        
        return implode("\n\n", $allLogs);
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
     * Запустить миграцию в фоне через отдельный процесс
     * 
     * @param string $waveId ID волны
     * @param string $mbUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy (0 если нужно создать)
     * @param int $workspaceId ID workspace
     * @param string $mbSiteId Site ID
     * @param string $mbSecret Secret
     * @param array $params Дополнительные параметры
     * @return void
     * @throws Exception
     */
    private function startMigrationInBackground(string $waveId, string $mbUuid, int $brzProjectId, int $workspaceId, string $mbSiteId, string $mbSecret, array $params = []): void
    {
        error_log("[WaveService::startMigrationInBackground] Начало запуска миграции в фоне: waveId={$waveId}, mbUuid={$mbUuid}, brzProjectId={$brzProjectId}, workspaceId={$workspaceId}");
        
        $projectRoot = dirname(__DIR__, 3);
        $migrationScript = sys_get_temp_dir() . '/wave_restart_migration_' . $waveId . '_' . md5($mbUuid) . '_' . time() . '_' . getmypid() . '.php';
        
        error_log("[WaveService::startMigrationInBackground] Migration script path: {$migrationScript}");
        
        $projectRootEscaped = addslashes($projectRoot);
        $waveIdEscaped = addslashes($waveId);
        $mbUuidEscaped = addslashes($mbUuid);
        $mgrManual = $params['mgr_manual'] ?? false;
        $mgrManualValue = $mgrManual ? 'true' : 'false';
        
        error_log("[WaveService::startMigrationInBackground] Параметры: projectRoot={$projectRoot}, mgrManual={$mgrManualValue}");
        
        $scriptContent = "<?php\n";
        $scriptContent .= "error_log('[RestartMigration] Script started at ' . date('Y-m-d H:i:s'));\n";
        $scriptContent .= "error_log('[RestartMigration] Wave ID: {$waveIdEscaped}');\n";
        $scriptContent .= "error_log('[RestartMigration] MB UUID: {$mbUuidEscaped}');\n";
        $scriptContent .= "error_log('[RestartMigration] Project root: {$projectRootEscaped}');\n";
        $scriptContent .= "chdir('{$projectRootEscaped}');\n";
        $scriptContent .= "error_log('[RestartMigration] Changed directory to: ' . getcwd());\n";
        $scriptContent .= "require_once '{$projectRootEscaped}/vendor/autoload_runtime.php';\n";
        $scriptContent .= "error_log('[RestartMigration] Autoload loaded');\n";
        $scriptContent .= "use Dashboard\\Services\\DatabaseService;\n";
        $scriptContent .= "use Dashboard\\Services\\WaveService;\n";
        $scriptContent .= "use Exception;\n\n";
        $scriptContent .= "try {\n";
        $scriptContent .= "    error_log('[RestartMigration] Initializing services...');\n";
        $scriptContent .= "    \$dbService = new DatabaseService();\n";
        $scriptContent .= "    \$waveService = new WaveService();\n";
        $scriptContent .= "    \$waveId = '{$waveIdEscaped}';\n";
        $scriptContent .= "    \$mbUuid = '{$mbUuidEscaped}';\n\n";
        $scriptContent .= "    // Обновляем статус на in_progress\n";
        $scriptContent .= "    error_log('[RestartMigration] Updating status to in_progress...');\n";
        $scriptContent .= "    \$dbService->updateMigrationResult(\$waveId, \$mbUuid, [\n";
        $scriptContent .= "        'result_json' => [\n";
        $scriptContent .= "            'status' => 'in_progress',\n";
        $scriptContent .= "            'message' => 'Миграция запущена',\n";
        $scriptContent .= "            'started_at' => date('Y-m-d H:i:s')\n";
        $scriptContent .= "        ]\n";
        $scriptContent .= "    ]);\n\n";
        $scriptContent .= "    // Выполняем миграцию через restartMigrationInWave\n";
        $scriptContent .= "    error_log('[RestartMigration] Starting migration restart...');\n";
        $scriptContent .= "    \$restartParams = [\n";
        $scriptContent .= "        'mb_site_id' => '" . addslashes($mbSiteId) . "',\n";
        $scriptContent .= "        'mb_secret' => '" . addslashes($mbSecret) . "',\n";
        $scriptContent .= "        'mgr_manual' => {$mgrManualValue}\n";
        $scriptContent .= "    ];\n";
        // Определяем путь к файлу результата один раз
        $resultFileEscaped = addslashes($migrationScript . '.result');
        
        $scriptContent .= "    \$result = \$waveService->restartMigrationInWave(\$waveId, \$mbUuid, \$restartParams);\n";
        $scriptContent .= "    error_log('[RestartMigration] Migration restart completed: success=' . (isset(\$result['success']) && \$result['success'] ? 'true' : 'false'));\n\n";
        $scriptContent .= "    // Результат уже сохранен в restartMigrationInWave\n";
        $scriptContent .= "    \$resultFile = '{$resultFileEscaped}';\n";
        $scriptContent .= "    if (file_exists(\$resultFile)) {\n";
        $scriptContent .= "        @unlink(\$resultFile);\n";
        $scriptContent .= "    }\n";
        $scriptContent .= "    file_put_contents(\$resultFile, json_encode(['success' => true, 'mb_uuid' => \$mbUuid, 'result' => \$result]));\n";
        $scriptContent .= "} catch (Exception \$e) {\n";
        $scriptContent .= "    try {\n";
        $scriptContent .= "        \$dbService = new DatabaseService();\n";
        $scriptContent .= "        \$dbService->updateMigrationResult('{$waveIdEscaped}', '{$mbUuidEscaped}', [\n";
        $scriptContent .= "            'result_json' => [\n";
        $scriptContent .= "                'status' => 'error',\n";
        $scriptContent .= "                'error' => \$e->getMessage(),\n";
        $scriptContent .= "                'message' => 'Ошибка при выполнении миграции'\n";
        $scriptContent .= "            ]\n";
        $scriptContent .= "        ]);\n";
        $scriptContent .= "    } catch (Exception \$updateError) {\n";
        $scriptContent .= "        error_log('Ошибка обновления статуса: ' . \$updateError->getMessage());\n";
        $scriptContent .= "    }\n";
        $scriptContent .= "    \$resultFile = '{$resultFileEscaped}';\n";
        $scriptContent .= "    file_put_contents(\$resultFile, json_encode(['success' => false, 'mb_uuid' => '{$mbUuidEscaped}', 'error' => \$e->getMessage()]));\n";
        $scriptContent .= "}\n";
        
        error_log("[WaveService::startMigrationInBackground] Сохранение migration script...");
        $writeResult = @file_put_contents($migrationScript, $scriptContent);
        if ($writeResult === false) {
            $errorMsg = "Не удалось сохранить migration script: {$migrationScript}";
            error_log("[WaveService::startMigrationInBackground] ОШИБКА: {$errorMsg}");
            throw new Exception($errorMsg);
        }
        error_log("[WaveService::startMigrationInBackground] Migration script сохранен: {$migrationScript} (размер: " . filesize($migrationScript) . " байт)");
        
        // Запускаем процесс в фоне с перенаправлением в лог-файл волны
        $logFile = dirname(__DIR__, 3) . '/var/log/wave_' . $waveId . '_' . time() . '.log';
        @mkdir(dirname($logFile), 0755, true);
        
        $command = sprintf(
            'cd %s && nohup php -f %s >> %s 2>&1 & echo $!',
            escapeshellarg($projectRoot),
            escapeshellarg($migrationScript),
            escapeshellarg($logFile)
        );
        
        error_log("[WaveService::startMigrationInBackground] Команда запуска: {$command}");
        error_log("[WaveService::startMigrationInBackground] Лог-файл: {$logFile}");
        $pid = trim(shell_exec($command));
        error_log("[WaveService::startMigrationInBackground] Результат выполнения команды: PID=" . ($pid ?: 'NOT SET'));
        
        if (empty($pid) || !is_numeric($pid)) {
            $errorMsg = "Не удалось запустить процесс миграции в фоне. PID: " . ($pid ?: 'empty');
            error_log("[WaveService::startMigrationInBackground] ОШИБКА: {$errorMsg}");
            throw new Exception($errorMsg);
        }
        
        error_log("[WaveService::startMigrationInBackground] Процесс успешно запущен: PID={$pid}");
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
