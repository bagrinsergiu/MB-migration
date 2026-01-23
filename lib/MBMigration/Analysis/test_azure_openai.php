<?php
/**
 * Тестовый скрипт для проверки подключения к Azure OpenAI
 * 
 * Использование:
 * php lib/MBMigration/Analysis/test_azure_openai.php
 */

// Определяем корень проекта (3 уровня вверх от lib/MBMigration/Analysis/)
$projectRoot = dirname(__DIR__, 3);
require_once $projectRoot . '/vendor/autoload_runtime.php';

// Загрузка переменных окружения
if (file_exists($projectRoot . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createMutable($projectRoot);
    $dotenv->safeLoad();
}

use MBMigration\Analysis\AIComparisonService;
use MBMigration\Core\Logger;

echo "🧪 Тест подключения к Azure OpenAI\n";
echo "=====================================\n\n";

// Проверяем наличие параметров
$azureEndpoint = $_ENV['AZURE_API_ENDPOINT'] ?? '';
$azureKey = $_ENV['AZURE_API_KEY'] ?? '';
$azureVersion = $_ENV['AZURE_API_VERSION'] ?? '';
$azureModel = $_ENV['AZURE_API_MODEL_NAME'] ?? '';

echo "📋 Проверка параметров:\n";
echo "  AZURE_API_ENDPOINT: " . ($azureEndpoint ? "✅ " . $azureEndpoint : "❌ не установлен") . "\n";
echo "  AZURE_API_KEY: " . ($azureKey ? "✅ установлен (" . substr($azureKey, 0, 10) . "...)" : "❌ не установлен") . "\n";
echo "  AZURE_API_VERSION: " . ($azureVersion ? "✅ " . $azureVersion : "❌ не установлен") . "\n";
echo "  AZURE_API_MODEL_NAME: " . ($azureModel ? "✅ " . $azureModel : "❌ не установлен") . "\n\n";

if (empty($azureEndpoint) || empty($azureKey) || empty($azureModel)) {
    echo "❌ Ошибка: Не все параметры Azure OpenAI настроены!\n";
    echo "   Добавьте параметры в .env файл:\n";
    echo "   AZURE_API_ENDPOINT=https://a-evd-res.openai.azure.com/\n";
    echo "   AZURE_API_KEY=your-key\n";
    echo "   AZURE_API_VERSION=2024-12-01-preview\n";
    echo "   AZURE_API_MODEL_NAME=gpt-4.1\n";
    exit(1);
}

// Проверяем поддержку Vision API
echo "🔍 Проверка поддержки Vision API:\n";
echo "  ⚠️  ВАЖНО: Модель gpt-4.1 может не поддерживать Vision API!\n";
echo "  Для работы с изображениями обычно нужны модели:\n";
echo "    - gpt-4-vision-preview\n";
echo "    - gpt-4o\n";
echo "    - gpt-4-turbo\n";
echo "  Проверьте документацию Azure OpenAI для вашей модели.\n\n";

// Создаем тестовый сервис
try {
    echo "🔧 Создание AIComparisonService...\n";
    $service = new AIComparisonService();
    echo "  ✅ Сервис создан успешно\n\n";
} catch (Exception $e) {
    echo "  ❌ Ошибка создания сервиса: " . $e->getMessage() . "\n";
    exit(1);
}

// Тест 1: Проверка подключения через реальный анализ
echo "📤 Тест 1: Проверка подключения к Azure OpenAI...\n";
echo "  (Этот тест проверит подключение при реальном анализе)\n\n";

// Тест 2: Проверка с реальным изображением (если есть)
echo "📤 Тест 2: Проверка с изображением...\n";
$testScreenshotPath = $projectRoot . '/var/tmp/project_23356258/source_da75cd652254fce37e953d7f261f132d.png';
if (file_exists($testScreenshotPath)) {
    echo "  Найден тестовый скриншот: " . basename($testScreenshotPath) . "\n";
    try {
        $sourceData = [
            'url' => 'http://test.com/source',
            'screenshot_path' => $testScreenshotPath,
            'html' => '<html><body><h1>Test</h1></body></html>'
        ];
        $migratedData = [
            'url' => 'http://test.com/migrated',
            'screenshot_path' => $testScreenshotPath,
            'html' => '<html><body><h1>Test</h1></body></html>'
        ];
        
        echo "  Отправка запроса с изображением...\n";
        $result = $service->comparePages($sourceData, $migratedData);
        
        echo "  ✅ Анализ завершен!\n";
        echo "  Качество: " . ($result['quality_score'] ?? 'N/A') . "\n";
        echo "  Уровень критичности: " . ($result['severity_level'] ?? 'N/A') . "\n";
        echo "  Проблем найдено: " . count($result['issues'] ?? []) . "\n";
    } catch (Exception $e) {
        echo "  ❌ Ошибка анализа: " . $e->getMessage() . "\n";
        if (strpos($e->getMessage(), 'vision') !== false || strpos($e->getMessage(), 'image') !== false) {
            echo "  ⚠️  Похоже, что модель gpt-4.1 не поддерживает Vision API!\n";
            echo "  Рекомендуется использовать модель с поддержкой Vision:\n";
            echo "    - gpt-4-vision-preview\n";
            echo "    - gpt-4o\n";
            echo "    - gpt-4-turbo\n";
        }
    }
} else {
    echo "  ⚠️  Тестовый скриншот не найден, пропускаем тест с изображением\n";
    echo "  Путь: " . $testScreenshotPath . "\n";
}

echo "\n✅ Тестирование завершено!\n";
echo "\n💡 Рекомендации:\n";
echo "1. Если тест 1 прошел успешно, но тест 2 с изображением не работает,\n";
echo "   значит модель gpt-4.1 не поддерживает Vision API.\n";
echo "2. Для анализа качества миграции нужна модель с поддержкой Vision API.\n";
echo "3. Проверьте доступные модели в Azure OpenAI Portal.\n";
