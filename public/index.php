<?php

use MBMigration\Core\Config;
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

    $settings = [
        'devMode' => $context['APP_ENV'] == 'dev',
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

    try {
        $config = new Config(
            $context['BRIZY_CLOUD_HOST'],
            $context['LOG_PATH'],
            $context['CACHE_PATH'],
            $context['BRIZY_CLOUD_TOKEN'],
            $settings
        );
    } catch (Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
    }

    if (!$mb_project_uuid = $request->get('mb_project_uuid')) {
        return new JsonResponse(['error' => 'Invalid mb_project_uuid'], 400);
    }
    if (!$brz_project_id = $request->get('brz_project_id')) {
        return new JsonResponse(['error' => 'Invalid brz_project_id'], 400);
    }

    $logger = \MBMigration\Core\Logger::initialize(
        "brizy-$brz_project_id",
        $context['LOG_LEVEL'],
        $context['LOG_FILE_PATH']
    );

    $mb_page_slug = $request->get('mb_page_slug') ?? '';
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

        $migrationPlatform = new \MBMigration\MigrationPlatform($config, $logger, $mb_page_slug);
        $result = $migrationPlatform->start($mb_project_uuid, $brz_project_id);
    } catch (Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
    } finally {
        \MBMigration\Core\Logger::instance()->info('Releasing lock file', [$lockFile]);
        unlink($lockFile);
    }

    return new JsonResponse($migrationPlatform->getLogs());
};
