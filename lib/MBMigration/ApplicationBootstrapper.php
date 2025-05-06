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
    private MigrationPlatform $migrationPlatform;

    public function __construct(array $context, Request $request)
    {
        $this->context = $context;
        $this->request = $request;
    }

    /**
     * @throws Exception
     */
    function doInnitConfig(): Config
    {
        $settings = [
            'devMode' => (bool) $this->context['DEV_MODE'] ?? false,
            'mgrMode' => (bool) $this->context['MGR_MODE'] ?? false,
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

        if(isset($this->context['APP_AUTHORIZATION_TOKEN']) && !empty($this->context['APP_AUTHORIZATION_TOKEN'])) {
            if($authorization_token !== $this->context['APP_AUTHORIZATION_TOKEN']) {
                throw new Exception('Unauthorized', 401);
            }
        }

        if(!empty($mb_site_id) && !empty($mb_secret)) {
            $settings['metaData']['mb_site_id'] = $mb_site_id;
            $settings['metaData']['mb_secret'] = $mb_secret;
        }

        if( !empty($this->context['MB_MONKCMS_API'])){
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
    public function migrationNormalFlow($mMgrIgnore = false, $mrgManual = false): array
    {
        $mb_project_uuid = $this->request->get('mb_project_uuid');
        if (!isset($mb_project_uuid)) {

            throw new Exception('Invalid mb_project_uuid', 400);
        }

        $brz_project_id = $this->request->get('brz_project_id');
        if (!isset($brz_project_id)) {

            throw new Exception('Invalid brz_project_id', 400);
        }

        $brz_workspaces_id = (int) $this->request->get('brz_workspaces_id') ?? 0;

        $s3Uploader = new S3Uploader(
            (bool) $this->context['AWS_BUCKET_ACTIVE'] ?? false,
            $this->context['AWS_KEY'] ?? '',
            $this->context['AWS_SECRET'] ?? '',
            $this->context['AWS_REGION'] ?? '',
            $this->context['AWS_BUCKET'] ?? ''
        );

        $logFilePath = $this->context['LOG_FILE_PATH'].'_'.$brz_project_id.'.log';

        $logger = Logger::initialize(
            "brizy-$brz_project_id",
            $this->context['LOG_LEVEL'],
            $logFilePath
        );

        $mb_page_slug = $this->request->get('mb_page_slug') ?? '';
        $mgr_manual = $this->request->get('mgr_manual') ?? 0;

        $lockFile = $this->context['CACHE_PATH']."/".$mb_project_uuid."-".$brz_project_id.".lock";

        if (file_exists($lockFile)) {
            Logger::instance()->warning('The process migration is already running.', [$lockFile]);

            throw new Exception('The process migration is already running.', 400);
        }

        try {
            file_put_contents($lockFile, $mb_project_uuid."-".$brz_project_id);
            Logger::instance()->info('Creating lock file', [$lockFile]);

            $this->migrationPlatform = new MigrationPlatform($this->config, $logger, $mb_page_slug, $brz_workspaces_id, $mMgrIgnore, $mrgManual);
            $this->migrationPlatform->start($mb_project_uuid, $brz_project_id);
        } catch (Exception $e) {

            throw new Exception($e->getMessage(), 400);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {

            throw new Exception($e->getMessage(), 400);
        } finally {
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
            }catch (\Exception $e) {
                Logger::instance()->warning('Failed to upload log file to S3.', [$e->getMessage()]);
            }
        }

        $migrationStatus = $this->migrationPlatform->getLogs() ?? [];
        $migrationStatus['mMigration'] = $this->migrationPlatform->getStatusManualMigration();
        $migrationStatus['fullLogUrl'] = $fullLogUrl;

        return $migrationStatus;
    }

    public function getPageList(): array
    {
        return $this->migrationPlatform->getProjectPagesList();
    }

    public function getProjectUUDI(): string
    {
        return $this->migrationPlatform->getProjectUUID();
    }

}
