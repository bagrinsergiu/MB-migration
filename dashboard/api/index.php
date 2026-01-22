<?php
/**
 * Dashboard API Entry Point
 * Доступен по адресу: http://localhost:8000/dashboard/api/*
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
    'Dashboard\\Services\\WaveLogger' => __DIR__ . '/services/WaveLogger.php',
    'Dashboard\\Services\\MigrationExecutionService' => __DIR__ . '/services/MigrationExecutionService.php',
    'Dashboard\\Services\\QualityAnalysisService' => __DIR__ . '/services/QualityAnalysisService.php',
    'Dashboard\\Controllers\\MigrationController' => __DIR__ . '/controllers/MigrationController.php',
    'Dashboard\\Controllers\\LogController' => __DIR__ . '/controllers/LogController.php',
    'Dashboard\\Controllers\\SettingsController' => __DIR__ . '/controllers/SettingsController.php',
    'Dashboard\\Controllers\\WaveController' => __DIR__ . '/controllers/WaveController.php',
    'Dashboard\\Controllers\\QualityAnalysisController' => __DIR__ . '/controllers/QualityAnalysisController.php',
];

foreach ($classesToLoad as $class => $file) {
    if (!class_exists($class) && file_exists($file)) {
        require_once $file;
    }
}

use Dashboard\Controllers\MigrationController;
use Dashboard\Controllers\LogController;
use Dashboard\Controllers\WaveController;
use Dashboard\Controllers\QualityAnalysisController;
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
                '/api/migrations/:id/lock' => 'DELETE - Удалить lock-файл миграции',
                '/api/migrations/:id/kill' => 'POST - Убить процесс миграции',
                '/api/migrations/:id/process' => 'GET - Информация о процессе миграции (мониторинг)',
                '/api/migrations/:id/cache' => 'DELETE - Удалить кэш-файл миграции',
                '/api/migrations/:id/reset-status' => 'POST - Сбросить статус миграции на pending',
                '/api/migrations/:id/hard-reset' => 'POST - Hard reset: удалить lock, cache, убить процесс и сбросить статус',
                '/api/migrations/:id/logs' => 'GET - Логи миграции',
                '/api/logs/:brz_project_id' => 'GET - Логи миграции (старый endpoint)',
                '/api/logs/recent' => 'GET - Последние логи',
                '/api/waves' => 'GET/POST - Список волн / Создать волну',
                '/api/waves/:id' => 'GET - Детали волны',
                '/api/waves/:id/status' => 'GET - Статус волны',
                '/api/waves/:id/restart-all' => 'POST - Массовый перезапуск всех миграций в волне',
                '/api/waves/:id/migrations/:mb_uuid/restart' => 'POST - Перезапустить миграцию в волне',
                '/api/waves/:id/logs' => 'GET - Логи волны',
                '/api/waves/:id/migrations/:mb_uuid/logs' => 'GET - Логи миграции в волне',
                '/api/waves/:id/projects/:brz_project_id/logs' => 'GET - Логи проекта в волне по brz_project_id',
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

        if (preg_match('#^/migrations/(\d+)/lock$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'DELETE') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->removeLock($request, $id);
            }
        }

        if (preg_match('#^/migrations/(\d+)/kill$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->killProcess($request, $id);
            }
        }

        if (preg_match('#^/migrations/(\d+)/process$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->getProcessInfo($request, $id);
            }
        }

        if (preg_match('#^/migrations/(\d+)/cache$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'DELETE') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->removeCache($request, $id);
            }
        }

        if (preg_match('#^/migrations/(\d+)/reset-status$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->resetStatus($request, $id);
            }
        }

        if (preg_match('#^/migrations/(\d+)/hard-reset$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->hardReset($request, $id);
            }
        }

        // Анализ качества миграций
        if (preg_match('#^/migrations/(\d+)/quality-analysis$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $migrationId = (int)$matches[1];
                $controller = new QualityAnalysisController();
                return $controller->getAnalysisList($request, $migrationId);
            }
        }

        if (preg_match('#^/migrations/(\d+)/quality-analysis/statistics$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $migrationId = (int)$matches[1];
                $controller = new QualityAnalysisController();
                return $controller->getStatistics($request, $migrationId);
            }
        }

        if (preg_match('#^/migrations/(\d+)/pages$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $migrationId = (int)$matches[1];
                $controller = new QualityAnalysisController();
                return $controller->getPagesList($request, $migrationId);
            }
        }

        if (preg_match('#^/migrations/(\d+)/quality-analysis/archived$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $migrationId = (int)$matches[1];
                $controller = new QualityAnalysisController();
                return $controller->getArchivedAnalysisList($request, $migrationId);
            }
        }

        if (preg_match('#^/migrations/(\d+)/quality-analysis/([^/]+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $migrationId = (int)$matches[1];
                $pageSlug = urldecode($matches[2]);
                $controller = new QualityAnalysisController();
                return $controller->getPageAnalysis($request, $migrationId, $pageSlug);
            }
        }

        if (preg_match('#^/migrations/(\d+)/quality-analysis/([^/]+)/screenshots/(source|migrated)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $migrationId = (int)$matches[1];
                $pageSlug = urldecode($matches[2]);
                $type = $matches[3];
                $controller = new QualityAnalysisController();
                return $controller->getScreenshot($request, $migrationId, $pageSlug, $type);
            }
        }

        if (preg_match('#^/migrations/(\d+)/quality-analysis/([^/]+)/reanalyze$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                error_log("[API] Reanalyze route matched: apiPath={$apiPath}");
                try {
                    $migrationId = (int)$matches[1];
                    $pageSlug = urldecode($matches[2]);
                    error_log("[API] Reanalyze request: migrationId={$migrationId}, pageSlug={$pageSlug}");
                    
                    if (!class_exists('Dashboard\\Controllers\\QualityAnalysisController')) {
                        error_log("[API] QualityAnalysisController class not found!");
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'QualityAnalysisController class not found'
                        ], 500);
                    }
                    
                    error_log("[API] Creating QualityAnalysisController instance...");
                    $controller = new QualityAnalysisController();
                    error_log("[API] Controller created, calling reanalyzePage...");
                    $result = $controller->reanalyzePage($request, $migrationId, $pageSlug);
                    error_log("[API] reanalyzePage returned successfully");
                    return $result;
                } catch (\Throwable $e) {
                    error_log("[API] Fatal error in reanalyze route: " . $e->getMessage());
                    error_log("[API] File: " . $e->getFile() . ", Line: " . $e->getLine());
                    error_log("[API] Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine(),
                        'type' => get_class($e)
                    ], 500);
                } catch (\Exception $e) {
                    error_log("[API] Exception in reanalyze route: " . $e->getMessage());
                    error_log("[API] File: " . $e->getFile() . ", Line: " . $e->getLine());
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine()
                    ], 500);
                }
            }
        }

        if (preg_match('#^/migrations/(\d+)/logs$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $id = (int)$matches[1];
                    error_log("[API] Get migration logs request: migrationId={$id}");
                    $controller = new MigrationController();
                    return $controller->getMigrationLogs($request, $id);
                } catch (\Throwable $e) {
                    error_log("[API] Fatal error in get migration logs route: " . $e->getMessage());
                    error_log("[API] Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine(),
                        'type' => get_class($e)
                    ], 500);
                }
            }
        }

        if (preg_match('#^/migrations/(\d+)/rebuild-page$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $id = (int)$matches[1];
                $controller = new MigrationController();
                return $controller->rebuildPage($request, $id);
            }
        }

        if (preg_match('#^/migrations/(\d+)/rebuild-page-no-analysis$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                try {
                    $id = (int)$matches[1];
                    error_log("[API] Rebuild page (no analysis) request: migrationId={$id}");
                    $controller = new MigrationController();
                    return $controller->rebuildPageNoAnalysis($request, $id);
                } catch (\Throwable $e) {
                    error_log("[API] Fatal error in rebuild-page-no-analysis route: " . $e->getMessage());
                    error_log("[API] Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine()
                    ], 500);
                }
            }
        }

        // Прямой доступ к скриншотам по имени файла
        if (preg_match('#^/screenshots/(.+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $filename = basename($matches[1]);
                // Ищем файл в var/tmp/project_*/ директориях
                // Используем реальный путь к проекту из текущего файла
                $currentFile = __FILE__; // /home/sg/projects/MB-migration/dashboard/api/index.php
                $projectRoot = dirname(dirname(dirname($currentFile))); // Поднимаемся на 3 уровня
                $screenshotsDir = $projectRoot . '/var/tmp/';
                
                // Логируем для отладки
                error_log("Screenshot request: filename=$filename, projectRoot=$projectRoot, screenshotsDir=$screenshotsDir");
                
                // Ищем файл во всех поддиректориях project_*
                $found = false;
                $filePath = null;
                $dirs = [];
                
                if (is_dir($screenshotsDir)) {
                    $dirs = glob($screenshotsDir . 'project_*', GLOB_ONLYDIR);
                    error_log("Found project dirs: " . json_encode($dirs));
                    
                    foreach ($dirs as $dir) {
                        $potentialPath = $dir . '/' . $filename;
                        error_log("Checking: $potentialPath, exists: " . (file_exists($potentialPath) ? 'YES' : 'NO'));
                        if (file_exists($potentialPath)) {
                            $filePath = $potentialPath;
                            $found = true;
                            break;
                        }
                    }
                } else {
                    error_log("Screenshots directory does not exist: $screenshotsDir");
                }
                
                // Если не нашли, пробуем поискать по полному пути (для старых записей)
                if (!$found) {
                    // Проверяем, может быть filename содержит путь
                    if (strpos($filename, '/') !== false) {
                        // Извлекаем только имя файла
                        $actualFilename = basename($filename);
                        foreach ($dirs as $dir) {
                            $potentialPath = $dir . '/' . $actualFilename;
                            if (file_exists($potentialPath)) {
                                $filePath = $potentialPath;
                                $found = true;
                                break;
                            }
                        }
                    }
                }
                
                // Также проверяем корневую директорию var/tmp/ (для старых файлов)
                if (!$found) {
                    $rootPath = $screenshotsDir . $filename;
                    if (file_exists($rootPath)) {
                        $filePath = $rootPath;
                        $found = true;
                    }
                }
                
                if (!$found || !$filePath) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Скриншот не найден: ' . $filename,
                        'debug' => [
                            'filename' => $filename,
                            'screenshots_dir' => $screenshotsDir,
                            'dirs_found' => $dirs ?? [],
                            'project_root' => $projectRoot,
                            'current_file' => __FILE__
                        ]
                    ], 404);
                }
                
                // Определяем MIME тип
                $mimeType = mime_content_type($filePath);
                if (!$mimeType) {
                    $mimeType = 'image/png';
                }
                
                // Возвращаем файл
                $response = new Response(file_get_contents($filePath), 200);
                $response->headers->set('Content-Type', $mimeType);
                $response->headers->set('Content-Disposition', 'inline; filename="' . $filename . '"');
                $response->headers->set('Cache-Control', 'public, max-age=3600');
                return $response;
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

        if (preg_match('#^/waves/([^/]+)/restart-all$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $waveId = $matches[1];
                $controller = new WaveController();
                return $controller->restartAllMigrations($request, $waveId);
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

        if (preg_match('#^/waves/([^/]+)/logs$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $waveId = $matches[1];
                    error_log("[API] Get wave logs request: waveId={$waveId}");
                    $controller = new WaveController();
                    return $controller->getWaveLogs($request, $waveId);
                } catch (\Throwable $e) {
                    error_log("[API] Fatal error in get wave logs route: " . $e->getMessage());
                    error_log("[API] Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine(),
                        'type' => get_class($e)
                    ], 500);
                }
            }
        }

        if (preg_match('#^/waves/([^/]+)/migrations/([^/]+)/logs$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $waveId = $matches[1];
                    $mbUuid = $matches[2];
                    error_log("[API] Get wave migration logs request: waveId={$waveId}, mbUuid={$mbUuid}");
                    $controller = new WaveController();
                    return $controller->getMigrationLogs($request, $waveId, $mbUuid);
                } catch (\Throwable $e) {
                    error_log("[API] Fatal error in get wave migration logs route: " . $e->getMessage());
                    error_log("[API] Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine(),
                        'type' => get_class($e)
                    ], 500);
                }
            }
        }

        // Новый endpoint для получения логов проекта в волне по brz_project_id
        if (preg_match('#^/waves/([^/]+)/projects/(\d+)/logs$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $waveId = $matches[1];
                    $brzProjectId = (int)$matches[2];
                    error_log("[API] Get wave project logs request: waveId={$waveId}, brzProjectId={$brzProjectId}");
                    $controller = new WaveController();
                    return $controller->getProjectLogs($request, $waveId, $brzProjectId);
                } catch (\Throwable $e) {
                    error_log("[API] Fatal error in get wave project logs route: " . $e->getMessage());
                    error_log("[API] Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine(),
                        'type' => get_class($e)
                    ], 500);
                }
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
                'GET /waves/:id/projects/:brz_project_id/logs',
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
