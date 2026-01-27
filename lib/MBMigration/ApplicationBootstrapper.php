<?php

namespace MBMigration;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\Core\Factory\LoggerFactory;
use MBMigration\Core\S3Uploader;
use Psr\Log\LoggerInterface;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\MB\MBProjectDataCollector;
use Symfony\Component\HttpFoundation\Request;

class ApplicationBootstrapper
{
    private array $context;
    private Request $request;
    private Config $config;
    private array $projectPagesList;
    private string $projectUUID;
    /**
     * @var LoggerInterface Логгер для записи событий ApplicationBootstrapper
     */
    private LoggerInterface $logger;

    public function __construct(array $context, Request $request)
    {
        $this->context = $context;
        $this->request = $request;
        $this->projectPagesList = [];

        $logFilePath = $this->context['LOG_FILE_PATH'] . '_ApplicationBootstrapper.log';

        $this->logger = LoggerFactory::create(
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
        if (!empty($waveId)) {
            // Создаем отдельный лог-файл для проекта в волне
            $logPath = $this->context['LOG_PATH'];
            $waveLogDir = $logPath . '/wave_' . $waveId;
            @mkdir($waveLogDir, 0755, true);
            $logFilePath = $waveLogDir . '/project_' . $brz_project_id . '.log';
            
            $this->logger->info('Migration started under wave management', [
                'wave_id' => $waveId,
                'brz_project_id' => $brz_project_id,
                'mb_project_uuid' => $mb_project_uuid,
                'log_file' => $logFilePath
            ]);
        } else {
            // Обычный лог-файл для миграции без волны
            $logFilePath = $this->context['LOG_FILE_PATH'] . '_' . $brz_project_id . '.log';
        }

        $logger = LoggerFactory::create(
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
                        
                        $logger->info('Checking process by PID', [
                            'pid' => $pid,
                            'ps_output' => $psOutputTrimmed,
                            'ps_output_raw' => $psOutput,
                            'process_running' => $processRunning,
                            'command' => $command
                        ]);
                        
                        // Если процесс не найден через ps, считаем его неактивным
                        // независимо от возраста lock-файла (ps более надежен)
                        if (!$processRunning) {
                            $logger->info('Process not found by PID, will remove stale lock file', [
                                'pid' => $pid,
                                'lock_file' => $lockFile,
                                'ps_output' => $psOutputTrimmed
                            ]);
                        }
                    } else {
                        $logger->warning('Invalid PID in lock file', [
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
                    
                    $logger->info('Old format lock file detected', [
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
                $logger->warning('The process migration is already running.', [
                    'lock_file' => $lockFile,
                    'pid' => $pid ?? 'unknown'
                ]);
                throw new Exception('The process migration is already running.', 400);
            } else {
                // Процесс не запущен, но lock-файл существует - удаляем его
                $logger->info('Lock file exists but process is not running, removing stale lock file', [
                    'lock_file' => $lockFile,
                    'pid' => $pid ?? 'unknown'
                ]);
                
                // Удаляем lock-файл
                $unlinkResult = @unlink($lockFile);
                if ($unlinkResult) {
                    $logger->info('Stale lock file removed successfully', [
                        'lock_file' => $lockFile,
                        'pid' => $pid ?? 'unknown'
                    ]);
                } else {
                    $lastError = error_get_last();
                    $logger->warning('Failed to remove stale lock file, trying chmod + unlink', [
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
                        $logger->info('Stale lock file removed after chmod', [$lockFile]);
                    } else {
                        $logger->error('Still failed to remove lock file after chmod', [
                            'lock_file' => $lockFile,
                            'error' => error_get_last()
                        ]);
                    }
                    // Все равно продолжаем, так как процесс не запущен
                }
            }
        }

        try {
            // Сохраняем PID процесса в lock-файл в формате JSON
            $pid = getmypid();
            $lockData = [
                'mb_project_uuid' => $mb_project_uuid,
                'brz_project_id' => $brz_project_id,
                'pid' => $pid,
                'started_at' => date('Y-m-d H:i:s'),
                'started_timestamp' => time(),
                'current_stage' => 'Инициализация миграции',
                'stage_updated_at' => time()
            ];
            file_put_contents($lockFile, json_encode($lockData, JSON_PRETTY_PRINT));
            $logger->info('Creating lock file with PID', ['lock_file' => $lockFile, 'pid' => $pid]);

            // Создаем зависимости для MigrationPlatform (рефакторинг для тестируемости)
            // Эти зависимости теперь инжектируются через конструктор вместо создания внутри класса
            $brizyApi = new BrizyAPI($logger);
            $mbCollector = new MBProjectDataCollector();

            $migrationPlatform = new MigrationPlatform(
                $this->config, 
                $logger,
                $brizyApi,              // BrizyAPIInterface - инжектируется для тестируемости
                $mbCollector,           // MBProjectDataCollectorInterface - инжектируется для тестируемости
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

        $logger->info('Releasing lock file', [$lockFile]);
        if (file_exists($lockFile)) {
            if (!unlink($lockFile)) {
                $logger->warning('Failed to release lock file.', [$lockFile]);
            }
        } else {
            $logger->warning('Lock file does not exist, nothing to release.', [$lockFile]);
        }

        try {
            $fullLogUrl = $s3Uploader->uploadLogFile($brz_project_id, $logFilePath);
        } catch (\Exception $e) {
            $logger->warning('Failed to upload log file to S3.', [$e->getMessage()]);
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
