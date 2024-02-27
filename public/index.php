<?php

use MBMigration\Core\Config;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response {

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
        'metaData' => [
            'secret' => 'CSIWESNiYhpHAyyeOuIrfzHwwEMeFi68',
            'MBAccountID' => '9a1438f1-0fe5-47dd-9324-da224d34a63f',
            'MBVisitorID' => '79bce594-4bc0-40ba-83c4-1d35e6a0dbcd',
        ],
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
    $sss = $request->get('health');

    if (!$request->get('health')) {
        if (!$mb_project_uuid = $request->get('mb_project_uuid')) {
            return new JsonResponse(['error' => 'Invalid mb_project_uuid'], 400);
        }
        if (!$brz_project_id = $request->get('brz_project_id')) {
            return new JsonResponse(['error' => 'Invalid brz_project_id'], 400);
        }
    } else {
        return new JsonResponse([
            "status" => "success",
            "UMID" => "cbcd98f2edcdd92fa7e0282feb8fa9c2",
            "progress" => [
                "Total" => 1,
                "Success" => 1
            ],
            "processTime" => 128
        ]);
    }


    $mb_page_slug = $request->get('mb_page_slug') ?? '';

    # start the DB tunnel
    try {
        $migrationPlatform = new \MBMigration\MigrationPlatform($config, $mb_page_slug);
        $result = $migrationPlatform->start($mb_project_uuid, $brz_project_id);
    } catch (Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
    }

    return new JsonResponse($migrationPlatform->getLogs());
};
