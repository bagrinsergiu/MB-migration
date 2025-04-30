<?php

use MBMigration\Core\Config;
use MBMigration\Core\S3Uploader;
use MBMigration\Layer\MB\MonkcmsAPI;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response {

    if ($request->get('health')) {
        return new JsonResponse([
            "status" => "success",
        ]);
    }

    if ($request->getPathInfo() === '/migration_log') {
        $migrationId = $request->get('brz_project_id');
        if (!$migrationId) {
            return new JsonResponse(['error' => 'Missing brz_project_id parameter'], 400);
        }

        $logFile = $context['LOG_FILE_PATH'];
        if (!file_exists($logFile)) {
            return new JsonResponse(['error' => 'Log file not found'], 404);
        }

        $command = sprintf('grep %s %s 2>/dev/null', escapeshellarg("brizy-$migrationId"), escapeshellarg($logFile));
        $output = shell_exec($command);

        $logs = $output ? explode("\n", trim($output)) : [];

        return new JsonResponse(['migration_id' => $migrationId, 'logs' => $logs]);
    }

    $settings = [
        'devMode' => (bool) $context['DEV_MODE'] ?? false,
        'db' => [
            'dbHost' => $context['MB_DB_HOST'],
            'dbPort' => $context['MB_DB_PORT'],
            'dbName' => $context['MB_DB_NAME'],
            'dbUser' => $context['MB_DB_USER'],
            'dbPass' => $context['MB_DB_PASSWORD'],
        ],
        'assets' => [
            //        'CloudUrlJsonKit' => 'https://bitblox-develop.s3.amazonaws.com/',
            'MBMediaStaging' => $context['MB_MEDIA_HOST'],
            //        'MBMediaStaging'  => 'https://s3.amazonaws.com/media.dev.cloversites.com'
        ],
        //'previewBaseHost' => 'staging.cloversites.com',
        'previewBaseHost' => $context['MB_PREVIEW_HOST'],
    ];
    $mb_site_id = $request->get('mb_site_id') ?? '';
    $mb_secret = $request->get('mb_secret') ?? '';
    $authorization_token = $request->get('token') ?? '';
    $brizyCloudToken = $request->get('brizy_cloud_token') ?? null;

    if(isset($context['APP_AUTHORIZATION_TOKEN']) && !empty($context['APP_AUTHORIZATION_TOKEN'])) {
        if($authorization_token !== $context['APP_AUTHORIZATION_TOKEN']) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }
    }

    if(!empty($mb_site_id) && !empty($mb_secret)) {
        $settings['metaData']['mb_site_id'] = $mb_site_id;
        $settings['metaData']['mb_secret'] = $mb_secret;
    }

    if( !empty($context['MB_MONKCMS_API'])){
        $settings['monkcms_api'] = $context['MB_MONKCMS_API'];
    }

    try {
        $config = new Config(
            $context['BRIZY_CLOUD_HOST'],
            $context['LOG_PATH'],
            $context['CACHE_PATH'],
            $brizyCloudToken ?? $context['BRIZY_CLOUD_TOKEN'],
            $settings
        );
    } catch (Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
    }

    $mb_project_uuid = $request->get('mb_project_uuid');
    if (!isset($mb_project_uuid)) {
        return new JsonResponse(['error' => 'Invalid mb_project_uuid'], 400);
    }

    $brz_project_id = $request->get('brz_project_id');
    if (!isset($brz_project_id)) {
        return new JsonResponse(['error' => 'Invalid brz_project_id'], 400);
    }

    $brz_workspaces_id = (int) $request->get('brz_workspaces_id') ?? 0;

    $s3Uploader = new S3Uploader(
        (bool) $context['AWS_BUCKET_ACTIVE'] ?? false,
        $context['AWS_KEY'] ?? '',
        $context['AWS_SECRET'] ?? '',
        $context['AWS_REGION'] ?? '',
        $context['AWS_BUCKET'] ?? ''
    );

    $logFilePath = $context['LOG_FILE_PATH'].'_'.$brz_project_id.'.log';

    $logger = \MBMigration\Core\Logger::initialize(
        "brizy-$brz_project_id",
        $context['LOG_LEVEL'],
        $logFilePath
    );

    $mb_page_slug = $request->get('mb_page_slug') ?? '';

    if ($request->getPathInfo() === '/mapping') {
        $sourceProjectID = $request->get('source_project_id');

        if (!$sourceProjectID) {
            return new JsonResponse(['error' => 'Missing brz_project_id parameter'], 400);
        }

        try{
            $bridge = new MBMigration\Bridge\Bridge($config);
            $bridge->setSourceProject($sourceProjectID);

           $preparedProjectID = $bridge->checkPreparedProject();

           if ($preparedProjectID) {
               return new JsonResponse(['data' => $preparedProjectID], 404);
           } else {
               return new JsonResponse(['error' => 'Project not found'], 404);
           }
        } catch (Exception $e) {
            return new JsonResponse(['error' => 'Project not found'], 404);
        }

    }


    $lockFile = $context['CACHE_PATH']."/".$mb_project_uuid."-".$brz_project_id.".lock";

    if (file_exists($lockFile)) {
        \MBMigration\Core\Logger::instance()->warning('The process migration is already running.', [$lockFile]);

        return new JsonResponse(['error' => 'The process migration is already running.'], 400);
    }

    # start the DB tunnel
    try {
        // create lock file
        file_put_contents($lockFile, $mb_project_uuid."-".$brz_project_id);
        \MBMigration\Core\Logger::instance()->info('Creating lock file', [$lockFile]);

        $migrationPlatform = new \MBMigration\MigrationPlatform($config, $logger, $mb_page_slug, $brz_workspaces_id);
        $result = $migrationPlatform->start($mb_project_uuid, $brz_project_id);
    } catch (Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
    } finally {
        \MBMigration\Core\Logger::instance()->info('Releasing lock file', [$lockFile]);
        if (file_exists($lockFile)) {
            if (!unlink($lockFile)) {
                \MBMigration\Core\Logger::instance()->warning('Failed to release lock file.', [$lockFile]);
            }
        } else {
            \MBMigration\Core\Logger::instance()->warning('Lock file does not exist, nothing to release.', [$lockFile]);
        }

        $fullLogUrl = $s3Uploader->uploadLogFile($brz_project_id, $logFilePath);

    }
    $migrationStatus = $migrationPlatform->getLogs() ?? [];
    $migrationStatus['fullLogUrl'] = $fullLogUrl;

    return new JsonResponse($migrationStatus);
};
