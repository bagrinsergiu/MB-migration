<?php

namespace MBMigration;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\Core\S3Uploader;
use Symfony\Component\HttpFoundation\Request;

class ApplicationBootstrapper
{
    private array $context;
    private Request $request;
    private Config $config;
    private array $projectPagesList;
    private string $projectUUID;

    public function __construct(array $context, Request $request)
    {
        $this->context = $context;
        $this->request = $request;
        $this->projectPagesList = [];

        $logFilePath = $this->context['LOG_FILE_PATH'] . '_ApplicationBootstrapper.log';

        $logger = Logger::initialize(
            "ApplicationBootstrapper",
            $this->context['LOG_LEVEL'],
            $logFilePath
        );
    }

    /**
     * @throws Exception
     */
    function doInnitConfig(): Config
    {
        $settings = [
            'devMode' => (bool)$this->context['DEV_MODE'] ?? false,
            'mgrMode' => (bool)$this->context['MGR_MODE'] ?? false,
            'db' => [
                'dbHost' => $this->context['MB_DB_HOST'],
                'dbPort' => $this->context['MB_DB_PORT'],
                'dbName' => $this->context['MB_DB_NAME'],
                'dbUser' => $this->context['MB_DB_USER'],
                'dbPass' => $this->context['MB_DB_PASSWORD'],
            ],
            'db_mg' => [
                'dbHost' => $this->context['MG_DB_HOST'],
                'dbPort' => $this->context['MG_DB_PORT'],
                'dbName' => $this->context['MG_DB_NAME'],
                'dbUser' => $this->context['MG_DB_USER'],
                'dbPass' => $this->context['MG_DB_PASS'],
            ],
            'assets' => [
//                'CloudUrlJsonKit' => 'https://bitblox-develop.s3.amazonaws.com/',
                'MBMediaStaging' => $this->context['MB_MEDIA_HOST'],
//                'MBMediaStaging'  => 'https://s3.amazonaws.com/media.dev.cloversites.com'
            ],
//            'previewBaseHost' => 'staging.cloversites.com',
            'previewBaseHost' => $this->context['MB_PREVIEW_HOST'],
        ];
        $mb_site_id = $this->request->get('mb_site_id') ?? '';
        $mb_secret = $this->request->get('mb_secret') ?? '';
        $authorization_token = $this->request->get('token') ?? '';
        $brizyCloudToken = $this->request->get('brizy_cloud_token') ?? null;

        if (isset($this->context['APP_AUTHORIZATION_TOKEN']) && !empty($this->context['APP_AUTHORIZATION_TOKEN'])) {
            if ($authorization_token !== $this->context['APP_AUTHORIZATION_TOKEN']) {
                throw new Exception('Unauthorized', 401);
            }
        }

        if (!empty($mb_site_id) && !empty($mb_secret)) {
            $settings['metaData']['mb_site_id'] = $mb_site_id;
            $settings['metaData']['mb_secret'] = $mb_secret;
        }

        if (!empty($this->context['MB_MONKCMS_API'])) {
            $settings['monkcms_api'] = $this->context['MB_MONKCMS_API'];
        }

        try {
            $this->config = new Config(
                $this->context['BRIZY_CLOUD_HOST'],
                $this->context['LOG_PATH'],
                $this->context['CACHE_PATH'],
                $brizyCloudToken ?? $this->context['BRIZY_CLOUD_TOKEN'],
                $settings
            );
            
            // Загружаем конфигурацию дашборда из переменных окружения
            Config::loadDashboardConfig();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }

        return $this->config;
    }

    /**
     * @throws Exception
     */
    function getMigrationLogs(): array
    {
        $migrationId = $this->request->get('brz_project_id');
        if (!$migrationId) {
            throw new Exception('Missing brz_project_id parameter', 400);
        }

        $logFile = $this->context['LOG_FILE_PATH'];
        if (!file_exists($logFile)) {
            throw new Exception('Log file not found', 404);
        }

        $command = sprintf('grep %s %s 2>/dev/null', escapeshellarg("brizy-$migrationId"), escapeshellarg($logFile));
        $output = shell_exec($command);

        $logs = $output ? explode("\n", trim($output)) : [];

        return ['migration_id' => $migrationId, 'logs' => $logs];
    }

    /**
     * @throws Exception
     */
    public function migrationFlow(
        $mb_project_uuid,
        $brz_project_id,
        $brz_workspaces_id,
        $mb_page_slug,
        $mMgrIgnore = false,
        $mrgManual = false,
        $qualityAnalysis = false,
        $mb_element_name = '',
        $skip_media_upload = false,
        $skip_cache = false
    ): array
    {
        $s3Uploader = new S3Uploader(
            (bool)$this->context['AWS_BUCKET_ACTIVE'] ?? false,
            $this->context['AWS_KEY'] ?? '',
            $this->context['AWS_SECRET'] ?? '',
            $this->context['AWS_REGION'] ?? '',
            $this->context['AWS_BUCKET'] ?? ''
        );

        // Проверяем, запущена ли миграция под управлением волны
        $waveId = $this->request->get('wave_id');
        Logger::instance()->info('Checking wave_id in request', [
            'wave_id' => $waveId,
            'wave_id_empty' => empty($waveId),
            'mb_project_uuid' => $mb_project_uuid ?? null,
            'brz_project_id' => $brz_project_id ?? null,
            'all_request_params' => $this->request->query->all()
        ]);
        if (!empty($waveId)) {
            // Создаем отдельный лог-файл для проекта в волне
            $logPath = $this->context['LOG_PATH'];
            $waveLogDir = $logPath . '/wave_' . $waveId;
            @mkdir($waveLogDir, 0755, true);
            $logFilePath = $waveLogDir . '/project_' . $brz_project_id . '.log';
            
            Logger::instance()->info('Migration started under wave management', [
                'wave_id' => $waveId,
                'brz_project_id' => $brz_project_id,
                'mb_project_uuid' => $mb_project_uuid,
                'log_file' => $logFilePath
            ]);
        } else {
            // Обычный лог-файл для миграции без волны
            $logFilePath = $this->context['LOG_FILE_PATH'] . '_' . $brz_project_id . '.log';
        }

        $logger = Logger::initialize(
            "brizy-$brz_project_id",
            $this->context['LOG_LEVEL'],
            $logFilePath
        );

        $lockFile = $this->context['CACHE_PATH'] . "/" . $mb_project_uuid . "-" . $brz_project_id . ".lock";

        // Проверяем lock-файл и реальное состояние процесса
        if (file_exists($lockFile)) {
            $lockContent = @file_get_contents($lockFile);
            $processRunning = false;
            $pid = null;
            
            if ($lockContent) {
                // Пытаемся распарсить как JSON (новый формат с PID)
                $lockData = json_decode($lockContent, true);
                if ($lockData && isset($lockData['pid'])) {
                    $pid = (int)$lockData['pid'];
                    
                    // Проверяем, запущен ли процесс по PID
                    if ($pid > 0) {
                        // Используем ps для проверки существования процесса (более надежно)
                        $command = sprintf('ps -p %d -o pid= 2>/dev/null', $pid);
                        $psOutput = @shell_exec($command);
                        $psOutputTrimmed = trim($psOutput ?? '');
                        $processRunning = !empty($psOutputTrimmed);
                        
                        // Дополнительная проверка через exec для надежности
                        if (!$processRunning) {
                            exec($command, $execOutput, $execReturnCode);
                            $processRunning = ($execReturnCode === 0 && !empty($execOutput));
                        }
                        
                        Logger::instance()->info('Checking process by PID', [
                            'pid' => $pid,
                            'ps_output' => $psOutputTrimmed,
                            'ps_output_raw' => $psOutput,
                            'process_running' => $processRunning,
                            'command' => $command
                        ]);
                        
                        // Если процесс не найден через ps, считаем его неактивным
                        // независимо от возраста lock-файла (ps более надежен)
                        if (!$processRunning) {
                            Logger::instance()->info('Process not found by PID, will remove stale lock file', [
                                'pid' => $pid,
                                'lock_file' => $lockFile,
                                'ps_output' => $psOutputTrimmed
                            ]);
                        }
                    } else {
                        Logger::instance()->warning('Invalid PID in lock file', [
                            'pid' => $pid,
                            'lock_file' => $lockFile
                        ]);
                    }
                } else {
                    // Старый формат lock-файла - проверяем время модификации
                    // Если файл недавно обновлялся (менее 5 минут), считаем процесс активным
                    $lockFileMtime = filemtime($lockFile);
                    $lockFileAge = time() - $lockFileMtime;
                    $processRunning = $lockFileAge < 300; // 5 минут
                    
                    Logger::instance()->info('Old format lock file detected', [
                        'lock_file' => $lockFile,
                        'age_seconds' => $lockFileAge,
                        'process_running' => $processRunning
                    ]);
                }
            } else {
                // Не удалось прочитать файл - проверяем время модификации
                $lockFileMtime = filemtime($lockFile);
                $lockFileAge = time() - $lockFileMtime;
                $processRunning = $lockFileAge < 300; // 5 минут
            }
            
            if ($processRunning) {
                Logger::instance()->warning('The process migration is already running.', [
                    'lock_file' => $lockFile,
                    'pid' => $pid ?? 'unknown'
                ]);
                throw new Exception('The process migration is already running.', 400);
            } else {
                // Процесс не запущен, но lock-файл существует - удаляем его
                Logger::instance()->info('Lock file exists but process is not running, removing stale lock file', [
                    'lock_file' => $lockFile,
                    'pid' => $pid ?? 'unknown'
                ]);
                
                // Удаляем lock-файл
                $unlinkResult = @unlink($lockFile);
                if ($unlinkResult) {
                    Logger::instance()->info('Stale lock file removed successfully', [
                        'lock_file' => $lockFile,
                        'pid' => $pid ?? 'unknown'
                    ]);
                } else {
                    $lastError = error_get_last();
                    Logger::instance()->warning('Failed to remove stale lock file, trying chmod + unlink', [
                        'lock_file' => $lockFile,
                        'pid' => $pid ?? 'unknown',
                        'error' => $lastError,
                        'file_exists' => file_exists($lockFile),
                        'is_writable' => is_writable($lockFile)
                    ]);
                    // Пытаемся удалить через chmod + unlink
                    @chmod($lockFile, 0666);
                    $unlinkResult2 = @unlink($lockFile);
                    if ($unlinkResult2) {
                        Logger::instance()->info('Stale lock file removed after chmod', [$lockFile]);
                    } else {
                        Logger::instance()->error('Still failed to remove lock file after chmod', [
                            'lock_file' => $lockFile,
                            'error' => error_get_last()
                        ]);
                    }
                    // Все равно продолжаем, так как процесс не запущен
                }
            }
        }

        try {
            // Сохраняем параметры веб-хука если они переданы (включая автоматически сформированные)
            $webhookUrl = $this->request->get('webhook_url');
            $webhookMbProjectUuid = $this->request->get('webhook_mb_project_uuid');
            $webhookBrzProjectId = $this->request->get('webhook_brz_project_id');
            
            if (!empty($webhookUrl) && !empty($webhookMbProjectUuid) && !empty($webhookBrzProjectId)) {
                Logger::instance()->info('Saving webhook parameters with wave_id', [
                    'mb_project_uuid' => $mb_project_uuid,
                    'brz_project_id' => $brz_project_id,
                    'webhook_url' => $webhookUrl,
                    'wave_id' => $waveId,
                    'wave_id_empty' => empty($waveId)
                ]);
                try {
                    $statusService = new \MBMigration\Core\MigrationStatusService();
                    $result = $statusService->saveWebhookParams(
                        $mb_project_uuid,
                        (int)$brz_project_id,
                        $webhookUrl,
                        $webhookMbProjectUuid,
                        (int)$webhookBrzProjectId,
                        $waveId
                    );
                    
                    if ($result) {
                        // Определяем источник параметров веб-хука
                        $source = $this->request->query->has('webhook_url') ? 'explicit' : 'auto-generated';
                        
                        Logger::instance()->info('Webhook parameters saved successfully', [
                            'mb_project_uuid' => $mb_project_uuid,
                            'brz_project_id' => $brz_project_id,
                            'webhook_url' => $webhookUrl,
                            'source' => $source,
                            'wave_id' => $this->request->get('wave_id')
                        ]);
                    } else {
                        Logger::instance()->warning('Failed to save webhook parameters', [
                            'mb_project_uuid' => $mb_project_uuid,
                            'brz_project_id' => $brz_project_id
                        ]);
                    }
                } catch (Exception $e) {
                    Logger::instance()->error('Exception while saving webhook parameters', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'mb_project_uuid' => $mb_project_uuid,
                        'brz_project_id' => $brz_project_id
                    ]);
                }
            }

            // Сохраняем PID процесса в lock-файл в формате JSON
            $pid = getmypid();
            
            // Читаем существующий lock-файл, чтобы сохранить wave_id если он был сохранен ранее
            $existingLockData = [];
            if (file_exists($lockFile)) {
                $existingContent = @file_get_contents($lockFile);
                if ($existingContent) {
                    $existingLockData = json_decode($existingContent, true) ?: [];
                    Logger::instance()->debug('Reading existing lock file before update', [
                        'lock_file' => $lockFile,
                        'existing_keys' => array_keys($existingLockData),
                        'wave_id_in_existing' => $existingLockData['wave_id'] ?? null
                    ]);
                }
            }
            
            $lockData = [
                'mb_project_uuid' => $mb_project_uuid,
                'brz_project_id' => $brz_project_id,
                'pid' => $pid,
                'started_at' => date('Y-m-d H:i:s'),
                'started_timestamp' => time(),
                'current_stage' => 'Инициализация миграции',
                'stage_updated_at' => time()
            ];
            
            // Добавляем параметры веб-хука в lock-файл если они есть
            if (!empty($webhookUrl) && !empty($webhookMbProjectUuid) && !empty($webhookBrzProjectId)) {
                $lockData['webhook_url'] = $webhookUrl;
                $lockData['webhook_mb_project_uuid'] = $webhookMbProjectUuid;
                $lockData['webhook_brz_project_id'] = (int)$webhookBrzProjectId;
            }
            
            // Сохраняем wave_id если миграция запущена от имени wave
            // Используем wave_id из запроса, или из существующего lock-файла (если был сохранен ранее)
            if (!empty($waveId)) {
                $lockData['wave_id'] = $waveId;
                Logger::instance()->info('Adding wave_id to lock file from request', [
                    'lock_file' => $lockFile,
                    'wave_id' => $waveId,
                    'mb_project_uuid' => $mb_project_uuid,
                    'brz_project_id' => $brz_project_id
                ]);
            } elseif (!empty($existingLockData['wave_id'])) {
                // Сохраняем wave_id из существующего lock-файла, если он был сохранен ранее
                $lockData['wave_id'] = $existingLockData['wave_id'];
                Logger::instance()->info('Preserving wave_id from existing lock file', [
                    'lock_file' => $lockFile,
                    'wave_id' => $existingLockData['wave_id'],
                    'mb_project_uuid' => $mb_project_uuid,
                    'brz_project_id' => $brz_project_id
                ]);
            } else {
                Logger::instance()->debug('No wave_id provided, migration not from wave', [
                    'lock_file' => $lockFile,
                    'mb_project_uuid' => $mb_project_uuid,
                    'brz_project_id' => $brz_project_id,
                    'wave_id_in_request' => $waveId,
                    'wave_id_in_existing' => $existingLockData['wave_id'] ?? null
                ]);
            }
            
            // Объединяем с существующими данными, чтобы не потерять другие поля
            $lockData = array_merge($existingLockData, $lockData);
            
            file_put_contents($lockFile, json_encode($lockData, JSON_PRETTY_PRINT));
            Logger::instance()->info('Creating/updating lock file with PID', [
                'lock_file' => $lockFile,
                'pid' => $pid,
                'lock_data_keys' => array_keys($lockData),
                'wave_id_in_lock' => $lockData['wave_id'] ?? null
            ]);

            $migrationPlatform = new MigrationPlatform(
                $this->config, 
                $logger, 
                $mb_page_slug, 
                $brz_workspaces_id, 
                $mMgrIgnore, 
                $mrgManual, 
                $qualityAnalysis,
                $mb_element_name,
                $skip_media_upload,
                $skip_cache
            );
            $migrationPlatform->start($mb_project_uuid, $brz_project_id);

            $this->projectPagesList = $migrationPlatform->getProjectPagesList();
            $this->projectUUID = $migrationPlatform->getProjectUUID();


        } catch (Exception $e) {

            throw new Exception($e->getMessage(), 400);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {

            throw new Exception($e->getMessage(), 400);
        }

        Logger::instance()->info('Releasing lock file', [$lockFile]);
        if (file_exists($lockFile)) {
            if (!unlink($lockFile)) {
                Logger::instance()->warning('Failed to release lock file.', [$lockFile]);
            }
        } else {
            Logger::instance()->warning('Lock file does not exist, nothing to release.', [$lockFile]);
        }

        try {
            $fullLogUrl = $s3Uploader->uploadLogFile($brz_project_id, $logFilePath);
        } catch (\Exception $e) {
            Logger::instance()->warning('Failed to upload log file to S3.', [$e->getMessage()]);
        }

        $migrationStatus = $migrationPlatform->getLogs() ?? [];
        $migrationStatus['mMigration'] = $migrationPlatform->getStatusManualMigration();
        $migrationStatus['fullLogUrl'] = $fullLogUrl ?? '';

        unset($migrationPlatform);

        return $migrationStatus;
    }

    public function getPageList(): array
    {
        return $this->projectPagesList ?? [];
    }

    public function getProjectUUDI(): string
    {
        return $this->projectUUID ?? '';
    }

}
