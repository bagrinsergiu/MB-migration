<?php

use MBMigration\ApplicationBootstrapper;
use MBMigration\WaveProc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response {

    $projectUuids = [
        "f2c701b1-c16c-4bf0-b759-aa89d133c84c",
        "e1b8f452-0881-4322-ad40-60e1389332f9",
        "f1ddb8bc-3c69-4487-9149-0915e2dfbda0",
        "cc61b469-bb84-4c53-9731-7e2d3502e004"
    ];
    $migrationRunner = new WaveProc($projectUuids);
    $migrationRunner->runMigrations();

    return new JsonResponse(
        'success',
        200
    );

};
