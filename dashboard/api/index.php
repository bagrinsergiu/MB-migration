<?php
/**
 * Dashboard API Entry Point
 * Доступен по адресу: http://localhost:8080/dashboard/api/*
 */

require_once dirname(__DIR__, 2) . '/vendor/autoload_runtime.php';

// Загрузка переменных окружения из .env
if (file_exists(dirname(__DIR__, 2) . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createMutable(dirname(__DIR__, 2));
    $dotenv->safeLoad();
}

// Загрузка .env.prod.local если существует
$prodEnv = dirname(__DIR__, 2) . '/.env.prod.local';
if (file_exists($prodEnv)) {
    $dotenv = \Dotenv\Dotenv::createMutable(dirname(__DIR__, 2), ['.env.prod.local']);
    $dotenv->safeLoad();
}

// Автозагрузка Dashboard классов
spl_autoload_register(function ($class) {
    $prefix = 'Dashboard\\';
    $baseDir = __DIR__ . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});

// Предзагрузка всех необходимых классов
$classesToLoad = [
    'Dashboard\\Services\\DatabaseService' => __DIR__ . '/services/DatabaseService.php',
    'Dashboard\\Services\\ApiProxyService' => __DIR__ . '/services/ApiProxyService.php',
    'Dashboard\\Services\\MigrationService' => __DIR__ . '/services/MigrationService.php',
    'Dashboard\\Services\\WaveService' => __DIR__ . '/services/WaveService.php',
    'Dashboard\\Controllers\\MigrationController' => __DIR__ . '/controllers/MigrationController.php',
    'Dashboard\\Controllers\\LogController' => __DIR__ . '/controllers/LogController.php',
    'Dashboard\\Controllers\\SettingsController' => __DIR__ . '/controllers/SettingsController.php',
    'Dashboard\\Controllers\\WaveController' => __DIR__ . '/controllers/WaveController.php',
];

foreach ($classesToLoad as $class => $file) {
    if (!class_exists($class) && file_exists($file)) {
        require_once $file;
    }
}

use Dashboard\Controllers\MigrationController;
use Dashboard\Controllers\LogController;
use Dashboard\Controllers\WaveController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

return static function (array $context, Request $request): Response {
    // CORS headers для React приложения
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    // Handle preflight requests
    if ($request->getMethod() === 'OPTIONS') {
        return new Response('', 200);
    }

    $pathInfo = $request->getPathInfo();
    
    // Убираем /dashboard/api из пути для маршрутизации
    $apiPath = str_replace('/dashboard/api', '', $pathInfo);
    $apiPath = $apiPath ?: '/';

    // Health check endpoint
    if ($apiPath === '/health' || $apiPath === '/') {
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Dashboard API is running',
            'version' => '1.0.0',
            'endpoints' => [
                '/api/migrations' => 'GET - Список миграций',
                '/api/migrations/:id' => 'GET - Детали миграции',
                '/api/migrations/run' => 'POST - Запуск миграции',
                '/api/migrations/:id/restart' => 'POST - Перезапуск миграции',
                '/api/migrations/:id/status' => 'GET - Статус миграции',
                '/api/logs/:brz_project_id' => 'GET - Логи миграции',
                '/api/logs/recent' => 'GET - Последние логи',
                '/api/waves' => 'GET/POST - Список волн / Создать волну',
                '/api/waves/:id' => 'GET - Детали волны',
                '/api/waves/:id/status' => 'GET - Статус волны',
                '/api/waves/:id/migrations/:mb_uuid/restart' => 'POST - Перезапустить миграцию в волне',
                '/api/waves/:id/migrations/:mb_uuid/logs' => 'GET - Логи миграции в волне',
                '/api/waves/:id/migrations/:mb_uuid/lock' => 'DELETE - Удалить lock-файл миграции',
            ]
        ], 200);
    }

    // Маршрутизация API endpoints
    try {
        // Миграции
        if (preg_match('#^/migrations$#', $apiPath)) {
            $controller = new MigrationController();
            return $controller->list($request);
        }

        if (preg_match('#^/migrations/(\d+)$#', $apiPath, $matches)) {
            $id = (int)$matches[1];
            $controller = new MigrationController();
            
            if ($request->getMethod() === 'GET') {
                return $controller->getDetails($request, $id);
            }
        }

        if (preg_match('#^/migrations/run$#', $apiPath)) {
            if ($request->getMethod() === 'POST') {
                $controller = new MigrationController();
                return $controller->run($request);
            }
        }

        if (preg_match('#^/migrations/(\d+)/restart$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->restart($request, $id);
            }
        }

        if (preg_match('#^/migrations/(\d+)/status$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->getStatus($id);
            }
        }

        // Логи
        if (preg_match('#^/logs/(\d+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $brzProjectId = (int)$matches[1];
                $controller = new LogController();
                return $controller->getLogs($request, $brzProjectId);
            }
        }

        if (preg_match('#^/logs/recent$#', $apiPath)) {
            if ($request->getMethod() === 'GET') {
                $controller = new LogController();
                return $controller->getRecent($request);
            }
        }

        // Настройки
        if (preg_match('#^/settings$#', $apiPath)) {
            $controller = new \Dashboard\Controllers\SettingsController();
            if ($request->getMethod() === 'GET') {
                return $controller->get($request);
            }
            if ($request->getMethod() === 'POST') {
                return $controller->save($request);
            }
        }

        // Волны миграций
        if (preg_match('#^/waves$#', $apiPath)) {
            $controller = new WaveController();
            if ($request->getMethod() === 'GET') {
                return $controller->list($request);
            }
            if ($request->getMethod() === 'POST') {
                return $controller->create($request);
            }
        }

        if (preg_match('#^/waves/([^/]+)$#', $apiPath, $matches)) {
            $waveId = $matches[1];
            $controller = new WaveController();
            
            if ($request->getMethod() === 'GET') {
                return $controller->getDetails($request, $waveId);
            }
        }

        if (preg_match('#^/waves/([^/]+)/status$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $waveId = $matches[1];
                $controller = new WaveController();
                return $controller->getStatus($waveId);
            }
        }

        if (preg_match('#^/waves/([^/]+)/migrations/([^/]+)/restart$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $waveId = $matches[1];
                $mbUuid = $matches[2];
                $controller = new WaveController();
                return $controller->restartMigration($request, $waveId, $mbUuid);
            }
        }

        if (preg_match('#^/waves/([^/]+)/migrations/([^/]+)/logs$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $waveId = $matches[1];
                $mbUuid = $matches[2];
                $controller = new WaveController();
                return $controller->getMigrationLogs($request, $waveId, $mbUuid);
            }
        }

        if (preg_match('#^/waves/([^/]+)/migrations/([^/]+)/lock$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'DELETE') {
                $waveId = $matches[1];
                $mbUuid = $matches[2];
                $controller = new WaveController();
                return $controller->removeMigrationLock($request, $waveId, $mbUuid);
            }
        }

        if (preg_match('#^/waves/([^/]+)/mapping$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $waveId = $matches[1];
                $controller = new WaveController();
                return $controller->getMapping($waveId);
            }
        }

        // Если не найден маршрут
        return new JsonResponse([
            'error' => 'Endpoint not found',
            'path' => $apiPath,
            'method' => $request->getMethod(),
            'available_endpoints' => [
                'GET /health',
                'GET /migrations',
                'GET /migrations/:id',
                'POST /migrations/run',
                'POST /migrations/:id/restart',
                'GET /migrations/:id/status',
                'GET /logs/:brz_project_id',
                'GET /logs/recent',
                'GET/POST /settings',
                'GET/POST /waves',
                'GET /waves/:id',
                'GET /waves/:id/status',
                'GET /waves/:id/mapping',
                'POST /waves/:id/migrations/:mb_uuid/restart',
                'GET /waves/:id/migrations/:mb_uuid/logs',
            ]
        ], 404);

    } catch (\Exception $e) {
        return new JsonResponse([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
};
