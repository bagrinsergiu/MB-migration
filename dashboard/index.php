<?php
/**
 * Dashboard Entry Point
 * Доступен по адресу: http://localhost:8080/dashboard
 */

// Проверяем, запрашивается ли API
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$pathInfo = parse_url($requestUri, PHP_URL_PATH);

// Если запрос к API, перенаправляем в api/index.php
if (strpos($pathInfo, '/dashboard/api') === 0) {
    require_once __DIR__ . '/api/index.php';
    exit;
}

// Если запрос к статическим файлам фронтенда (CSS, JS, assets)
$distPath = __DIR__ . '/frontend/dist';
if (file_exists($distPath) && is_dir($distPath)) {
    // Проверяем, запрашивается ли статический файл (assets, css, js)
    if (preg_match('#^/dashboard/assets/#', $pathInfo) || preg_match('#^/dashboard/assets/#', $requestUri)) {
        // Убираем /dashboard из начала пути
        $filePath = preg_replace('#^/dashboard/#', '', $pathInfo ?: parse_url($requestUri, PHP_URL_PATH));
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
            header('Content-Type: ' . $mimeType);
            header('Cache-Control: public, max-age=31536000');
            readfile($staticFile);
            exit;
        }
    }
}

// Иначе отдаем HTML страницу React приложения
$indexHtmlPath = __DIR__ . '/frontend/dist/index.html';
if (file_exists($indexHtmlPath)) {
    // Просто отдаем HTML как есть, пути уже правильные благодаря base: '/dashboard/' в vite.config.ts
    readfile($indexHtmlPath);
    exit;
}

// Fallback: если фронтенд не собран, показываем заглушку
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MB Migration Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dashboard-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 800px;
            width: 90%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .status {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .status h2 {
            color: #1e40af;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .status p {
            color: #64748b;
            line-height: 1.6;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .warning h2 {
            color: #92400e;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .warning code {
            background: #fef3c7;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>🚀 MB Migration Dashboard</h1>
        <p class="subtitle">Веб-панель управления миграцией проектов</p>
        
        <div class="warning">
            <h2>⚠️ Фронтенд не собран</h2>
            <p>
                Для работы дашборда необходимо собрать фронтенд:<br>
                <code>cd dashboard/frontend && npm install && npm run build</code>
            </p>
        </div>

        <div class="status">
            <h2>✅ API работает</h2>
            <p>
                API endpoints доступны по адресу <strong>http://localhost:8080/dashboard/api</strong><br>
                <a href="/dashboard/api/health" style="color: #3b82f6;">Проверить API</a>
            </p>
        </div>
    </div>
</body>
</html>
