<?php
/**
 * Скрипт для просмотра скриншотов из базы данных
 * 
 * Использование:
 * php lib/MBMigration/Analysis/view_screenshots.php [migration_id] [page_slug]
 * 
 * Примеры:
 * php lib/MBMigration/Analysis/view_screenshots.php 12345
 * php lib/MBMigration/Analysis/view_screenshots.php 12345 home-page
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use MBMigration\Analysis\QualityReport;

// Загружаем переменные окружения
$dotenv = Dotenv::createMutable(__DIR__ . '/../../');
$dotenv->safeLoad();

$prodEnv = '.env.prod.local';
if (file_exists(__DIR__ . '/../../' . $prodEnv)) {
    Dotenv::createMutable(__DIR__ . '/../../', [$prodEnv])->safeLoad();
}

$migrationId = $argv[1] ?? null;
$pageSlug = $argv[2] ?? null;

if (!$migrationId) {
    echo "Использование: php view_screenshots.php [migration_id] [page_slug]\n";
    echo "Пример: php view_screenshots.php 12345 home-page\n";
    exit(1);
}

try {
    $reportService = new QualityReport();
    
    if ($pageSlug) {
        // Получаем конкретный отчет по slug
        $report = $reportService->getReportBySlug((int)$migrationId, $pageSlug);
        
        if (!$report) {
            echo "Отчет не найден для migration_id={$migrationId}, page_slug={$pageSlug}\n";
            exit(1);
        }
        
        echo "=== Отчет для страницы: {$pageSlug} ===\n";
        echo "Report ID: {$report['id']}\n";
        echo "Migration ID: {$report['migration_id']}\n";
        echo "Quality Score: " . ($report['quality_score'] ?? 'N/A') . "\n";
        echo "Severity Level: " . ($report['severity_level'] ?? 'N/A') . "\n";
        echo "Created At: " . ($report['created_at'] ?? 'N/A') . "\n\n";
        
        if (!empty($report['screenshots_path'])) {
            echo "=== Скриншоты ===\n";
            if (isset($report['screenshots_path']['source'])) {
                $sourcePath = $report['screenshots_path']['source'];
                $sourceExists = file_exists($sourcePath);
                echo "Source Screenshot:\n";
                echo "  Path: {$sourcePath}\n";
                echo "  Exists: " . ($sourceExists ? 'YES' : 'NO') . "\n";
                if ($sourceExists) {
                    echo "  Size: " . filesize($sourcePath) . " bytes\n";
                }
                echo "\n";
            }
            
            if (isset($report['screenshots_path']['migrated'])) {
                $migratedPath = $report['screenshots_path']['migrated'];
                $migratedExists = file_exists($migratedPath);
                echo "Migrated Screenshot:\n";
                echo "  Path: {$migratedPath}\n";
                echo "  Exists: " . ($migratedExists ? 'YES' : 'NO') . "\n";
                if ($migratedExists) {
                    echo "  Size: " . filesize($migratedPath) . " bytes\n";
                }
                echo "\n";
            }
        } else {
            echo "Скриншоты не найдены в отчете\n";
        }
    } else {
        // Получаем все отчеты по миграции
        $reports = $reportService->getReportsByMigration((int)$migrationId);
        
        if (empty($reports)) {
            echo "Отчеты не найдены для migration_id={$migrationId}\n";
            exit(1);
        }
        
        echo "=== Все отчеты для Migration ID: {$migrationId} ===\n";
        echo "Всего отчетов: " . count($reports) . "\n\n";
        
        foreach ($reports as $report) {
            echo "--- Report ID: {$report['id']} ---\n";
            echo "Page Slug: {$report['page_slug']}\n";
            echo "Quality Score: " . ($report['quality_score'] ?? 'N/A') . "\n";
            echo "Severity Level: " . ($report['severity_level'] ?? 'N/A') . "\n";
            echo "Created At: " . ($report['created_at'] ?? 'N/A') . "\n";
            
            if (!empty($report['screenshots_path'])) {
                if (isset($report['screenshots_path']['source'])) {
                    $sourcePath = $report['screenshots_path']['source'];
                    $sourceExists = file_exists($sourcePath);
                    echo "Source Screenshot: {$sourcePath} " . ($sourceExists ? '[EXISTS]' : '[NOT FOUND]') . "\n";
                }
                if (isset($report['screenshots_path']['migrated'])) {
                    $migratedPath = $report['screenshots_path']['migrated'];
                    $migratedExists = file_exists($migratedPath);
                    echo "Migrated Screenshot: {$migratedPath} " . ($migratedExists ? '[EXISTS]' : '[NOT FOUND]') . "\n";
                }
            } else {
                echo "Screenshots: Not found\n";
            }
            echo "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
