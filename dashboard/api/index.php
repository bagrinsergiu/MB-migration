<?php
/**
 * Dashboard API Entry Point
 * Доступен по адресу: http://localhost:8000/dashboard/api/*
 */

// Отключаем вывод ошибок в HTML формате
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');

// Устанавливаем обработчик ошибок для перехвата всех ошибок
// Важно: не выводим ошибки, только логируем
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    // Логируем ошибку, но не выводим
    error_log("PHP Error: $message in $file on line $line");
    // Возвращаем true, чтобы предотвратить стандартную обработку ошибки
    return true;
}, E_ALL | E_STRICT);

// Устанавливаем обработчик исключений
set_exception_handler(function($exception) {
    error_log("Uncaught exception: " . $exception->getMessage());
    error_log("Stack trace: " . $exception->getTraceAsString());
    // Не выводим исключение, только логируем
});

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
    'Dashboard\\Services\\TestMigrationService' => __DIR__ . '/services/TestMigrationService.php',
    'Dashboard\\Services\\AuthService' => __DIR__ . '/services/AuthService.php',
    'Dashboard\\Services\\WaveReviewService' => __DIR__ . '/services/WaveReviewService.php',
    'Dashboard\\Services\\UserService' => __DIR__ . '/services/UserService.php',
    'Dashboard\\Controllers\\MigrationController' => __DIR__ . '/controllers/MigrationController.php',
    'Dashboard\\Controllers\\LogController' => __DIR__ . '/controllers/LogController.php',
    'Dashboard\\Controllers\\SettingsController' => __DIR__ . '/controllers/SettingsController.php',
    'Dashboard\\Controllers\\WaveController' => __DIR__ . '/controllers/WaveController.php',
    'Dashboard\\Controllers\\QualityAnalysisController' => __DIR__ . '/controllers/QualityAnalysisController.php',
    'Dashboard\\Controllers\\TestMigrationController' => __DIR__ . '/controllers/TestMigrationController.php',
    'Dashboard\\Controllers\\AuthController' => __DIR__ . '/controllers/AuthController.php',
    'Dashboard\\Controllers\\UserController' => __DIR__ . '/controllers/UserController.php',
    'Dashboard\\Middleware\\AuthMiddleware' => __DIR__ . '/middleware/AuthMiddleware.php',
    'Dashboard\\Middleware\\PermissionMiddleware' => __DIR__ . '/middleware/PermissionMiddleware.php',
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
use Dashboard\Controllers\TestMigrationController;
use Dashboard\Controllers\AuthController;
use Dashboard\Controllers\UserController;
use Dashboard\Services\QualityAnalysisService;
use Dashboard\Services\MigrationService;
use Dashboard\Services\WaveReviewService;
use Dashboard\Middleware\AuthMiddleware;
use Dashboard\Middleware\PermissionMiddleware;
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
        // Публичный доступ к скриншотам по токену ревью (без авторизации)
        if (preg_match('#^/review/wave/([^/]+)/screenshots/(.+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $token = $matches[1];
                $filename = basename($matches[2]);
                
                // Проверяем токен (но не требуем полной авторизации)
                $reviewService = new WaveReviewService();
                $waveId = $reviewService->validateToken($token);
                
                if (!$waveId) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Недействительный или истекший токен доступа'
                    ], 403);
                }
                
                // Ищем файл в var/tmp/project_*/ директориях
                $currentFile = __FILE__;
                $projectRoot = dirname(dirname(dirname($currentFile)));
                $screenshotsDir = $projectRoot . '/var/tmp/';
                
                $found = false;
                $filePath = null;
                $dirs = [];
                
                if (is_dir($screenshotsDir)) {
                    $dirs = glob($screenshotsDir . 'project_*', GLOB_ONLYDIR);
                    
                    foreach ($dirs as $dir) {
                        $potentialPath = $dir . '/' . $filename;
                        if (file_exists($potentialPath)) {
                            $filePath = $potentialPath;
                            $found = true;
                            break;
                        }
                    }
                }
                
                // Также проверяем корневую директорию var/tmp/
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
                        'error' => 'Скриншот не найден: ' . $filename
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

        // Публичный доступ к ревью волны (без авторизации)
        if (preg_match('#^/review/wave/([^/]+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $token = $matches[1];
                $reviewService = new WaveReviewService();
                $waveId = $reviewService->validateToken($token);
                
                if (!$waveId) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Недействительный или истекший токен доступа'
                    ], 403);
                }
                
                // Получаем информацию о токене и настройках доступа
                $tokenInfo = $reviewService->getTokenInfo($token);
                if (!$tokenInfo) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Токен не найден'
                    ], 403);
                }
                
                // Получаем детали волны
                $waveController = new WaveController();
                $waveResponse = $waveController->getDetails($request, $waveId);
                
                // Если ответ успешный, добавляем информацию о настройках доступа
                if ($waveResponse->getStatusCode() === 200) {
                    $waveData = json_decode($waveResponse->getContent(), true);
                    if ($waveData && $waveData['success']) {
                        // Добавляем настройки доступа для каждого проекта
                        if (isset($waveData['data']['migrations']) && is_array($waveData['data']['migrations'])) {
                            foreach ($waveData['data']['migrations'] as &$migration) {
                                // Проверяем оба возможных ключа для UUID
                                $mbUuid = $migration['mb_uuid'] ?? $migration['mb_project_uuid'] ?? null;
                                if ($mbUuid) {
                                    $projectAccess = $reviewService->getProjectAccess($token, $mbUuid);
                                    // Если нет индивидуальных настроек, проект доступен по умолчанию
                                    // Устанавливаем review_access только если есть настройки, иначе null (что означает доступ по умолчанию)
                                    $migration['review_access'] = $projectAccess;
                                } else {
                                    // Если нет UUID, проект недоступен
                                    $migration['review_access'] = ['is_active' => false];
                                }
                            }
                            unset($migration);
                        }
                        
                        // Добавляем информацию о токене
                        $waveData['data']['token_info'] = [
                            'name' => $tokenInfo['name'],
                            'description' => $tokenInfo['description'],
                            'settings' => $tokenInfo['settings']
                        ];
                        
                        return new JsonResponse($waveData, 200);
                    }
                }
                
                return $waveResponse;
            }
        }

        // Публичный доступ к деталям миграции по токену (без авторизации)
        if (preg_match('#^/review/wave/([^/]+)/migration/([^/]+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $token = $matches[1];
                $mbUuid = $matches[2];
                
                $reviewService = new WaveReviewService();
                $waveId = $reviewService->validateToken($token);
                
                if (!$waveId) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Недействительный или истекший токен доступа'
                    ], 403);
                }
                
                // Проверяем настройки доступа для проекта
                // Если токен валиден, доступ разрешен по умолчанию
                // Блокируем только если явно установлено is_active = false
                $projectAccess = $reviewService->getProjectAccess($token, $mbUuid);
                
                // Если есть индивидуальные настройки и проект заблокирован
                if ($projectAccess && isset($projectAccess['is_active']) && $projectAccess['is_active'] === false) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Доступ к этому проекту ограничен'
                    ], 403);
                }
                
                // Получаем детали миграции по mb_uuid
                $migrationController = new MigrationController();
                $migrationResponse = $migrationController->getDetailsByUuid($request, $mbUuid);
                
                // Если ответ успешный, добавляем информацию о разрешенных вкладках
                if ($migrationResponse->getStatusCode() === 200) {
                    $migrationData = json_decode($migrationResponse->getContent(), true);
                    if ($migrationData && $migrationData['success']) {
                        // Если есть индивидуальные настройки, используем их, иначе все вкладки доступны
                        $allowedTabs = $projectAccess && isset($projectAccess['allowed_tabs']) 
                            ? $projectAccess['allowed_tabs'] 
                            : ['overview', 'details', 'logs', 'screenshots', 'quality', 'analysis']; // Все вкладки по умолчанию
                        $migrationData['data']['allowed_tabs'] = $allowedTabs;
                        return new JsonResponse($migrationData, 200);
                    }
                }
                
                return $migrationResponse;
            }
        }

        // Публичный доступ к статистике анализа качества миграции по токену
        if (preg_match('#^/review/wave/([^/]+)/migration/([^/]+)/analysis/statistics$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $token = $matches[1];
                    $mbUuid = $matches[2];
                    
                    $reviewService = new WaveReviewService();
                    $waveId = $reviewService->validateToken($token);
                    
                    if (!$waveId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Недействительный или истекший токен доступа'
                        ], 403, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Проверяем настройки доступа для проекта
                    $projectAccess = $reviewService->getProjectAccess($token, $mbUuid);
                    
                    // Если есть индивидуальные настройки и проект заблокирован
                    if ($projectAccess && isset($projectAccess['is_active']) && $projectAccess['is_active'] === false) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Доступ к этому проекту ограничен'
                        ], 403, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Получаем детали миграции для получения brz_project_id
                    $migrationService = new MigrationService();
                    $migrationDetails = $migrationService->getMigrationDetailsByUuid($mbUuid);
                    
                    if (!$migrationDetails) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Миграция не найдена'
                        ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Извлекаем brz_project_id
                    $brzProjectId = null;
                    if (isset($migrationDetails['mapping']['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['mapping']['brz_project_id'];
                    } elseif (isset($migrationDetails['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['brz_project_id'];
                    }
                    
                    if (!$brzProjectId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Не удалось определить ID проекта Brizy'
                        ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Получаем статистику анализа качества
                    $qualityService = new QualityAnalysisService();
                    $statistics = $qualityService->getMigrationStatistics($brzProjectId);
                    
                    return new JsonResponse([
                        'success' => true,
                        'data' => $statistics
                    ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
                } catch (\Throwable $e) {
                    error_log("Error in analysis statistics endpoint: " . $e->getMessage());
                    error_log("Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => true,
                        'data' => [
                            'total_pages' => 0,
                            'avg_quality_score' => null,
                            'by_severity' => [
                                'critical' => 0,
                                'high' => 0,
                                'medium' => 0,
                                'low' => 0,
                                'none' => 0
                            ],
                            'token_statistics' => [
                                'total_prompt_tokens' => 0,
                                'total_completion_tokens' => 0,
                                'total_tokens' => 0,
                                'avg_tokens_per_page' => 0,
                                'total_cost_usd' => 0,
                                'avg_cost_per_page_usd' => 0
                            ]
                        ]
                    ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
                }
            }
        }

        // Публичный доступ к отчетам анализа качества миграции по токену
        if (preg_match('#^/review/wave/([^/]+)/migration/([^/]+)/analysis/reports$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $token = $matches[1];
                    $mbUuid = $matches[2];
                    
                    $reviewService = new WaveReviewService();
                    $waveId = $reviewService->validateToken($token);
                    
                    if (!$waveId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Недействительный или истекший токен доступа'
                        ], 403, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Проверяем настройки доступа для проекта
                    $projectAccess = $reviewService->getProjectAccess($token, $mbUuid);
                    
                    // Если есть индивидуальные настройки и проект заблокирован
                    if ($projectAccess && isset($projectAccess['is_active']) && $projectAccess['is_active'] === false) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Доступ к этому проекту ограничен'
                        ], 403, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Получаем детали миграции для получения brz_project_id
                    $migrationService = new MigrationService();
                    $migrationDetails = $migrationService->getMigrationDetailsByUuid($mbUuid);
                    
                    if (!$migrationDetails) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Миграция не найдена'
                        ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Извлекаем brz_project_id
                    $brzProjectId = null;
                    if (isset($migrationDetails['mapping']['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['mapping']['brz_project_id'];
                    } elseif (isset($migrationDetails['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['brz_project_id'];
                    }
                    
                    if (!$brzProjectId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Не удалось определить ID проекта Brizy'
                        ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Получаем список отчетов анализа качества
                    $qualityService = new QualityAnalysisService();
                    $reports = $qualityService->getReportsByMigration($brzProjectId);
                    
                    // Убеждаемся, что $reports - это массив
                    if (!is_array($reports)) {
                        $reports = [];
                    }
                    
                    return new JsonResponse([
                        'success' => true,
                        'data' => $reports,
                        'count' => count($reports)
                    ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
                } catch (\Throwable $e) {
                    error_log("Error in analysis reports endpoint: " . $e->getMessage());
                    error_log("Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Ошибка при получении отчетов анализа: ' . $e->getMessage()
                    ], 500, ['Content-Type' => 'application/json; charset=utf-8']);
                }
            }
        }

        // Публичный доступ к деталям анализа конкретной страницы по токену
        if (preg_match('#^/review/wave/([^/]+)/migration/([^/]+)/analysis/([^/]+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $token = $matches[1];
                    $mbUuid = $matches[2];
                    $pageSlug = urldecode($matches[3]);
                    
                    $reviewService = new WaveReviewService();
                    $waveId = $reviewService->validateToken($token);
                    
                    if (!$waveId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Недействительный или истекший токен доступа'
                        ], 403, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Проверяем настройки доступа для проекта
                    $projectAccess = $reviewService->getProjectAccess($token, $mbUuid);
                    
                    // Если есть индивидуальные настройки и проект заблокирован
                    if ($projectAccess && isset($projectAccess['is_active']) && $projectAccess['is_active'] === false) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Доступ к этому проекту ограничен'
                        ], 403, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Получаем детали миграции для получения brz_project_id
                    $migrationService = new MigrationService();
                    $migrationDetails = $migrationService->getMigrationDetailsByUuid($mbUuid);
                    
                    if (!$migrationDetails) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Миграция не найдена'
                        ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Извлекаем brz_project_id
                    $brzProjectId = null;
                    if (isset($migrationDetails['mapping']['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['mapping']['brz_project_id'];
                    } elseif (isset($migrationDetails['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['brz_project_id'];
                    }
                    
                    if (!$brzProjectId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Не удалось определить ID проекта Brizy'
                        ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    // Получаем детали анализа страницы
                    $qualityService = new QualityAnalysisService();
                    $report = $qualityService->getReportBySlug($brzProjectId, $pageSlug, false);
                    
                    if (!$report) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Анализ страницы не найден'
                        ], 404, ['Content-Type' => 'application/json; charset=utf-8']);
                    }
                    
                    return new JsonResponse([
                        'success' => true,
                        'data' => $report
                    ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
                } catch (\Throwable $e) {
                    error_log("Error in page analysis endpoint: " . $e->getMessage());
                    error_log("Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Ошибка при получении деталей анализа: ' . $e->getMessage()
                    ], 500, ['Content-Type' => 'application/json; charset=utf-8']);
                }
            }
        }

        // Публичный доступ к анализу качества миграции по токену (список страниц)
        if (preg_match('#^/review/wave/([^/]+)/migration/([^/]+)/analysis$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                try {
                    $token = $matches[1];
                    $mbUuid = $matches[2];
                    
                    $reviewService = new WaveReviewService();
                    $waveId = $reviewService->validateToken($token);
                    
                    if (!$waveId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Недействительный или истекший токен доступа'
                        ], 403);
                    }
                    
                    // Проверяем настройки доступа для проекта
                    $projectAccess = $reviewService->getProjectAccess($token, $mbUuid);
                    
                    // Если есть индивидуальные настройки и проект заблокирован
                    if ($projectAccess && isset($projectAccess['is_active']) && $projectAccess['is_active'] === false) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Доступ к этому проекту ограничен'
                        ], 403);
                    }
                    
                    // Получаем детали миграции для получения brz_project_id
                    $migrationService = new MigrationService();
                    $migrationDetails = $migrationService->getMigrationDetailsByUuid($mbUuid);
                    
                    if (!$migrationDetails) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Миграция не найдена'
                        ], 404);
                    }
                    
                    // Извлекаем brz_project_id из mapping или из результата
                    $brzProjectId = null;
                    if (isset($migrationDetails['mapping']['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['mapping']['brz_project_id'];
                    } elseif (isset($migrationDetails['brz_project_id'])) {
                        $brzProjectId = (int)$migrationDetails['brz_project_id'];
                    }
                    
                    if (!$brzProjectId) {
                        return new JsonResponse([
                            'success' => false,
                            'error' => 'Не удалось определить ID проекта Brizy'
                        ], 404);
                    }
                    
                    // Получаем список страниц с анализом качества
                    $qualityService = new QualityAnalysisService();
                    $pages = $qualityService->getPagesList($brzProjectId);
                    
                    // Убеждаемся, что $pages - это массив
                    if (!is_array($pages)) {
                        $pages = [];
                    }
                    
                    return new JsonResponse([
                        'success' => true,
                        'data' => $pages,
                        'count' => count($pages)
                    ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
                } catch (\Throwable $e) {
                    error_log("Error in analysis endpoint: " . $e->getMessage());
                    error_log("Stack trace: " . $e->getTraceAsString());
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Ошибка при получении данных анализа: ' . $e->getMessage()
                    ], 500, ['Content-Type' => 'application/json; charset=utf-8']);
                }
            }
        }

        // Публичный доступ к логам миграции по токену
        if (preg_match('#^/review/wave/([^/]+)/migration/([^/]+)/logs$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $token = $matches[1];
                $mbUuid = $matches[2];
                
                $reviewService = new WaveReviewService();
                $waveId = $reviewService->validateToken($token);
                
                if (!$waveId) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Недействительный или истекший токен доступа'
                    ], 403);
                }
                
                // Проверяем настройки доступа для проекта
                // Если токен валиден, доступ разрешен по умолчанию
                // Блокируем только если явно установлено is_active = false
                $projectAccess = $reviewService->getProjectAccess($token, $mbUuid);
                
                // Если есть индивидуальные настройки и проект заблокирован
                if ($projectAccess && isset($projectAccess['is_active']) && $projectAccess['is_active'] === false) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Доступ к этому проекту ограничен'
                    ], 403);
                }
                
                // Проверяем, разрешена ли вкладка logs
                // Если нет индивидуальных настроек, все вкладки доступны по умолчанию
                $allowedTabs = $projectAccess && isset($projectAccess['allowed_tabs']) 
                    ? $projectAccess['allowed_tabs'] 
                    : ['overview', 'details', 'logs', 'screenshots', 'quality', 'analysis']; // Все вкладки по умолчанию
                
                if (!in_array('logs', $allowedTabs)) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => 'Доступ к логам не разрешен для этого проекта'
                    ], 403);
                }
                
                // Получаем логи миграции
                $waveController = new WaveController();
                return $waveController->getMigrationLogs($request, $waveId, $mbUuid);
            }
        }

        // Авторизация (публичные endpoints)
        if (preg_match('#^/auth/login$#', $apiPath)) {
            if ($request->getMethod() === 'POST') {
                $controller = new AuthController();
                return $controller->login($request);
            }
        }

        if (preg_match('#^/auth/logout$#', $apiPath)) {
            if ($request->getMethod() === 'POST') {
                $controller = new AuthController();
                return $controller->logout($request);
            }
        }

        if (preg_match('#^/auth/check$#', $apiPath)) {
            if ($request->getMethod() === 'GET') {
                $controller = new AuthController();
                return $controller->check($request);
            }
        }

        // Проверка авторизации для защищенных endpoints
        $authMiddleware = new AuthMiddleware();
        $authResponse = $authMiddleware->checkAuth($request);
        if ($authResponse !== null) {
            return $authResponse;
        }

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

        if (preg_match('#^/waves/([^/]+)/mapping/(\d+)/cloning$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'PUT') {
                $waveId = $matches[1];
                $brzProjectId = (int)$matches[2];
                $controller = new WaveController();
                return $controller->toggleCloning($request, $waveId, $brzProjectId);
            }
        }

        if (preg_match('#^/waves/([^/]+)/review-token$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $waveId = $matches[1];
                $controller = new WaveController();
                return $controller->createReviewToken($request, $waveId);
            }
        }

        if (preg_match('#^/waves/([^/]+)/review-tokens/(\d+)/projects/([^/]+)$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'PUT') {
                $waveId = $matches[1];
                $tokenId = (int)$matches[2];
                $mbUuid = $matches[3];
                $controller = new WaveController();
                return $controller->updateProjectAccess($request, $waveId, $tokenId, $mbUuid);
            }
        }

        if (preg_match('#^/waves/([^/]+)/review-tokens/(\d+)$#', $apiPath, $matches)) {
            $waveId = $matches[1];
            $tokenId = (int)$matches[2];
            $controller = new WaveController();
            
            if ($request->getMethod() === 'PUT') {
                return $controller->updateReviewToken($request, $waveId, $tokenId);
            }
            if ($request->getMethod() === 'DELETE') {
                return $controller->deleteReviewToken($request, $waveId, $tokenId);
            }
        }

        if (preg_match('#^/waves/([^/]+)/review-tokens$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $waveId = $matches[1];
                $controller = new WaveController();
                return $controller->getReviewTokens($request, $waveId);
            }
        }

        // Тестовые миграции
        if (preg_match('#^/test-migrations$#', $apiPath)) {
            $controller = new TestMigrationController();
            if ($request->getMethod() === 'GET') {
                return $controller->list($request);
            }
            if ($request->getMethod() === 'POST') {
                return $controller->create($request);
            }
        }

        if (preg_match('#^/test-migrations/(\d+)$#', $apiPath, $matches)) {
            $id = (int)$matches[1];
            $controller = new TestMigrationController();
            
            if ($request->getMethod() === 'GET') {
                return $controller->getDetails($request, $id);
            }
            if ($request->getMethod() === 'PUT') {
                return $controller->update($request, $id);
            }
            if ($request->getMethod() === 'DELETE') {
                return $controller->delete($request, $id);
            }
        }

        if (preg_match('#^/test-migrations/(\d+)/run$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $id = (int)$matches[1];
                $controller = new TestMigrationController();
                return $controller->run($request, $id);
            }
        }

        if (preg_match('#^/test-migrations/(\d+)/reset-status$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'POST') {
                $id = (int)$matches[1];
                $controller = new TestMigrationController();
                return $controller->resetStatus($request, $id);
            }
        }

        // Управление пользователями (требует разрешение users.manage)
        if (preg_match('#^/users$#', $apiPath)) {
            $permissionMiddleware = new PermissionMiddleware();
            $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'view');
            if ($permissionCheck !== null) {
                return $permissionCheck;
            }

            $controller = new UserController();
            if ($request->getMethod() === 'GET') {
                return $controller->list($request);
            }
            if ($request->getMethod() === 'POST') {
                $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'create');
                if ($permissionCheck !== null) {
                    return $permissionCheck;
                }
                return $controller->create($request);
            }
        }

        if (preg_match('#^/users/(\d+)$#', $apiPath, $matches)) {
            $permissionMiddleware = new PermissionMiddleware();
            $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'view');
            if ($permissionCheck !== null) {
                return $permissionCheck;
            }

            $id = (int)$matches[1];
            $controller = new UserController();
            
            if ($request->getMethod() === 'GET') {
                return $controller->getDetails($request, $id);
            }
            if ($request->getMethod() === 'PUT') {
                $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'edit');
                if ($permissionCheck !== null) {
                    return $permissionCheck;
                }
                return $controller->update($request, $id);
            }
            if ($request->getMethod() === 'DELETE') {
                $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'delete');
                if ($permissionCheck !== null) {
                    return $permissionCheck;
                }
                return $controller->delete($request, $id);
            }
        }

        if (preg_match('#^/users/roles$#', $apiPath)) {
            if ($request->getMethod() === 'GET') {
                $permissionMiddleware = new PermissionMiddleware();
                $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'view');
                if ($permissionCheck !== null) {
                    return $permissionCheck;
                }
                $controller = new UserController();
                return $controller->getRoles($request);
            }
        }

        if (preg_match('#^/users/permissions$#', $apiPath)) {
            if ($request->getMethod() === 'GET') {
                $permissionMiddleware = new PermissionMiddleware();
                $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'view');
                if ($permissionCheck !== null) {
                    return $permissionCheck;
                }
                $controller = new UserController();
                return $controller->getPermissions($request);
            }
        }

        if (preg_match('#^/users/(\d+)/permissions$#', $apiPath, $matches)) {
            if ($request->getMethod() === 'GET') {
                $permissionMiddleware = new PermissionMiddleware();
                $permissionCheck = $permissionMiddleware->checkPermission($request, 'users', 'view');
                if ($permissionCheck !== null) {
                    return $permissionCheck;
                }
                $id = (int)$matches[1];
                $controller = new UserController();
                return $controller->getUserPermissions($request, $id);
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
                'PUT /waves/:id/mapping/:brz_project_id/cloning',
                'POST /waves/:id/migrations/:mb_uuid/restart',
                'GET /waves/:id/migrations/:mb_uuid/logs',
                'GET /waves/:id/projects/:brz_project_id/logs',
                'GET /test-migrations',
                'POST /test-migrations',
                'GET /test-migrations/:id',
                'PUT /test-migrations/:id',
                'DELETE /test-migrations/:id',
                'POST /test-migrations/:id/run',
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
