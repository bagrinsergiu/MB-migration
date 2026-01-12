<?php

use MBMigration\ApplicationBootstrapper;
use MBMigration\WaveProc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static function (array $context, Request $request): Response {
    $pathInfo = $request->getPathInfo();
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $uriPath = parse_url($requestUri, PHP_URL_PATH);
    
    // Handle dashboard static assets first
    if (preg_match('#^/dashboard/assets/#', $pathInfo) || preg_match('#^/dashboard/assets/#', $uriPath)) {
        $distPath = dirname(__DIR__) . '/dashboard/frontend/dist';
        $filePath = preg_replace('#^/dashboard/#', '', $pathInfo ?: $uriPath);
        $staticFile = $distPath . '/' . $filePath;
        
        if (file_exists($staticFile) && is_file($staticFile)) {
            $mimeTypes = [
                'js' => 'application/javascript',
                'mjs' => 'application/javascript',
                'css' => 'text/css',
                'json' => 'application/json',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'svg' => 'image/svg+xml',
                'ico' => 'image/x-icon',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf' => 'font/ttf',
                'eot' => 'application/vnd.ms-fontobject',
            ];
            $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
            $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';
            
            $response = new Response();
            $response->headers->set('Content-Type', $mimeType);
            $response->headers->set('Cache-Control', 'public, max-age=31536000');
            $response->setContent(file_get_contents($staticFile));
            return $response;
        }
    }
    
    // Handle all other dashboard routes
    if (strpos($pathInfo, '/dashboard') === 0 || strpos($uriPath, '/dashboard') === 0) {
        // Для API routes возвращаем результат из dashboard/api/index.php
        if (strpos($pathInfo, '/dashboard/api') === 0 || strpos($uriPath, '/dashboard/api') === 0) {
            $dashboardApi = require dirname(__DIR__) . '/dashboard/api/index.php';
            return $dashboardApi($context, $request);
        }
        // Для остальных dashboard routes используем require_once
        require_once dirname(__DIR__) . '/dashboard/index.php';
        exit;
    }
    
    switch ($pathInfo) {
        case '/health':
            return new JsonResponse(["status" => "success",], 200);
    }

    $app = new ApplicationBootstrapper($context, $request);

    try {
        $config = $app->doInnitConfig();
    } catch (Exception $e) {
        if ($e->getCode() < 100) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
        return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
    }

    $bridge = new MBMigration\Bridge\Bridge($app, $config, $request);

    switch ($request->getPathInfo()) {
        case '/mapping':
            $response = $bridge
                ->checkPreparedProject()
                ->getMessageResponse();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/mapping/list':
            $response = $bridge->mappingList();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/mapping/list/all':
            $response = $bridge->addAllMappingList();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/changes/checkAll':
            $response = $bridge->checkAllProjectchanges();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/migration_log':
            try {
                return new JsonResponse($app->getMigrationLogs());
            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
        case '/utils/clearWorkspace':
            try {
                return new JsonResponse(
                    $bridge->clearWorkspace()
                        ->getMessageResponse()
                );
            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
            case '/projects/makePro':
            try {
                return new JsonResponse(
                    $bridge->makeAllProjectsPRO()
                        ->getMessageResponse()
                );
            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
        case '/migration/wave':

            $projectUuids = ["f2c701b1-c16c-4bf0-b759-aa89d133c84c"];
            $migrationRunner = new WaveProc($projectUuids);
            $migrationRunner->runMigrations();

            $response = $bridge->migrationWave();

            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );

        case '/app':
            $response = $bridge->mApp();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/addTagManualMigration':
            $response = $bridge->addTagManualMigration();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/delTagManualMigration':
            $response = $bridge->delTagManualMigration();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/setCloningLink':
            $response = $bridge->setCloningLincMigration();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        case '/cloning':
            $response = $bridge->doCloningProjects();
            return new JsonResponse(
                $response->getMessage(),
                $response->getStatusCode()
            );
        default:
            try {
                $response = $bridge->runMigration()
                    ->getMessageResponse();

                return new JsonResponse(
                    $response->getMessage(),
                    $response->getStatusCode()
                );
            } catch (Exception $e) {
                if ($e->getCode() < 100) {
                    return new JsonResponse(['error' => $e->getMessage()], 404);
                }

                return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
    }
};
