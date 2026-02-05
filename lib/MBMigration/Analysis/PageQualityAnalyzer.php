<?php

namespace MBMigration\Analysis;

use Exception;
use MBMigration\Core\Logger;
use MBMigration\Core\DashboardScreenshotUploader;

/**
 * PageQualityAnalyzer
 * 
 * Главный класс для анализа качества миграции страниц
 * Оркестрирует процесс захвата данных, AI анализа и сохранения результатов
 */
class PageQualityAnalyzer
{
    /**
     * @var CapturePageData
     */
    private $captureService;
    /**
     * @var AIComparisonService
     */
    private $aiService;
    /**
     * @var QualityReport
     */
    private $reportService;
    /**
     * @var bool
     */
    private $enabled;

    public function __construct(?bool $enabled = null)
    {
        $this->enabled = $enabled ?? ($_ENV['QUALITY_ANALYSIS_ENABLED'] ?? true);
        
        if ($this->enabled) {
            // CapturePageData будет создан с projectId при первом вызове analyzePage
            // Пока создаем без projectId, он будет установлен позже
            $this->captureService = new CapturePageData();
            $this->aiService = new AIComparisonService();
            $this->reportService = new QualityReport();
        }
    }

    /**
     * Проанализировать качество миграции страницы
     * 
     * @param string $sourceUrl URL исходной страницы (MB)
     * @param string $migratedUrl URL мигрированной страницы (Brizy)
     * @param string $pageSlug Slug страницы
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brizyProjectId ID проекта Brizy
     * @param string $themeName Название темы (designName) для использования тематического промпта
     * @return int|null ID созданного отчета или null если анализ отключен
     * @throws Exception
     */
    public function analyzePage(
        string $sourceUrl,
        string $migratedUrl,
        string $pageSlug,
        string $mbProjectUuid,
        int $brizyProjectId,
        string $themeName = 'default'
    ): ?int {
        if (!$this->enabled) {
            Logger::instance()->info("[Quality Analysis] Analysis is disabled, skipping page", [
                'page_slug' => $pageSlug,
                'mb_project_uuid' => $mbProjectUuid,
                'brizy_project_id' => $brizyProjectId
            ]);
            return null;
        }

        Logger::instance()->info("[Quality Analysis] ===== Starting quality analysis =====", [
            'page_slug' => $pageSlug,
            'source_url' => $sourceUrl,
            'migrated_url' => $migratedUrl,
            'mb_project_uuid' => $mbProjectUuid,
            'brizy_project_id' => $brizyProjectId,
            'theme_name' => $themeName
        ]);

        try {
            // Создаем CapturePageData с projectId для организации скриншотов по проектам
            // Используем brizyProjectId как идентификатор проекта
            $captureService = new CapturePageData(null, $brizyProjectId);
            
            // BREAKPOINT 5: Начало захвата исходной страницы
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 5: Starting source page capture =====", [
                'step' => 1,
                'total_steps' => 4,
                'url' => $sourceUrl,
                'page_slug' => $pageSlug,
                'project_id' => $brizyProjectId,
                'screenshots_path' => $captureService->getScreenshotsPath(),
                'url_valid' => filter_var($sourceUrl, FILTER_VALIDATE_URL) !== false
            ]);
            
            $sourceData = $captureService->captureSourcePage($sourceUrl, $pageSlug);
            
            // BREAKPOINT 6: Данные исходной страницы захвачены
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 6: Source page data captured =====", [
                'screenshot_path' => $sourceData['screenshot_path'] ?? null,
                'screenshot_exists' => isset($sourceData['screenshot_path']) && file_exists($sourceData['screenshot_path']),
                'screenshot_size' => isset($sourceData['screenshot_path']) && file_exists($sourceData['screenshot_path']) ? filesize($sourceData['screenshot_path']) : 0,
                'html_length' => strlen($sourceData['html'] ?? ''),
                'html_preview' => substr($sourceData['html'] ?? '', 0, 200) . '...',
                'url' => $sourceData['url'] ?? null,
                'has_screenshot' => isset($sourceData['screenshot_path']),
                'has_html' => !empty($sourceData['html'] ?? '')
            ]);

            // BREAKPOINT 7: Начало захвата мигрированной страницы
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 7: Starting migrated page capture =====", [
                'step' => 2,
                'total_steps' => 4,
                'url' => $migratedUrl,
                'page_slug' => $pageSlug,
                'project_id' => $brizyProjectId,
                'screenshots_path' => $captureService->getScreenshotsPath(),
                'url_valid' => filter_var($migratedUrl, FILTER_VALIDATE_URL) !== false
            ]);
            
            $migratedData = $captureService->captureMigratedPage($migratedUrl, $pageSlug);
            
            // BREAKPOINT 8: Данные мигрированной страницы захвачены
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 8: Migrated page data captured =====", [
                'screenshot_path' => $migratedData['screenshot_path'] ?? null,
                'screenshot_exists' => isset($migratedData['screenshot_path']) && file_exists($migratedData['screenshot_path']),
                'screenshot_size' => isset($migratedData['screenshot_path']) && file_exists($migratedData['screenshot_path']) ? filesize($migratedData['screenshot_path']) : 0,
                'html_length' => strlen($migratedData['html'] ?? ''),
                'html_preview' => substr($migratedData['html'] ?? '', 0, 200) . '...',
                'url' => $migratedData['url'] ?? null,
                'has_screenshot' => isset($migratedData['screenshot_path']),
                'has_html' => !empty($migratedData['html'] ?? '')
            ]);

            // Загружаем скриншоты на дашборд
            $this->uploadScreenshotsToDashboard($sourceData, $migratedData, $mbProjectUuid, $pageSlug);

            // BREAKPOINT 9: Подготовка к AI анализу (ПРОВЕРКА ДАННЫХ ПЕРЕД ОТПРАВКОЙ В AI)
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 9: Preparing for AI analysis - DATA VALIDATION =====", [
                'step' => 3,
                'total_steps' => 4,
                'page_slug' => $pageSlug,
                'source_data' => [
                    'url' => $sourceData['url'] ?? null,
                    'has_screenshot' => isset($sourceData['screenshot_path']),
                    'screenshot_path' => $sourceData['screenshot_path'] ?? null,
                    'screenshot_exists' => isset($sourceData['screenshot_path']) && file_exists($sourceData['screenshot_path']),
                    'screenshot_size' => isset($sourceData['screenshot_path']) && file_exists($sourceData['screenshot_path']) ? filesize($sourceData['screenshot_path']) : 0,
                    'has_html' => !empty($sourceData['html'] ?? ''),
                    'html_length' => strlen($sourceData['html'] ?? '')
                ],
                'migrated_data' => [
                    'url' => $migratedData['url'] ?? null,
                    'has_screenshot' => isset($migratedData['screenshot_path']),
                    'screenshot_path' => $migratedData['screenshot_path'] ?? null,
                    'screenshot_exists' => isset($migratedData['screenshot_path']) && file_exists($migratedData['screenshot_path']),
                    'screenshot_size' => isset($migratedData['screenshot_path']) && file_exists($migratedData['screenshot_path']) ? filesize($migratedData['screenshot_path']) : 0,
                    'has_html' => !empty($migratedData['html'] ?? ''),
                    'html_length' => strlen($migratedData['html'] ?? '')
                ],
                'data_ready_for_ai' => (
                    isset($sourceData['screenshot_path']) && file_exists($sourceData['screenshot_path']) &&
                    isset($migratedData['screenshot_path']) && file_exists($migratedData['screenshot_path']) &&
                    !empty($sourceData['html'] ?? '') &&
                    !empty($migratedData['html'] ?? '')
                )
            ]);
            
            // ВАЛИДАЦИЯ: Проверяем что все данные на месте перед отправкой в AI
            if (!isset($sourceData['screenshot_path']) || !file_exists($sourceData['screenshot_path'])) {
                throw new Exception("Source screenshot file not found: " . ($sourceData['screenshot_path'] ?? 'not_set'));
            }
            if (!isset($migratedData['screenshot_path']) || !file_exists($migratedData['screenshot_path'])) {
                throw new Exception("Migrated screenshot file not found: " . ($migratedData['screenshot_path'] ?? 'not_set'));
            }
            if (empty($sourceData['html'] ?? '')) {
                throw new Exception("Source HTML is empty");
            }
            if (empty($migratedData['html'] ?? '')) {
                throw new Exception("Migrated HTML is empty");
            }
            
            Logger::instance()->info("[Quality Analysis] Data validation passed, proceeding to AI analysis", [
                'theme' => $themeName
            ]);
            
            $analysisResult = $this->aiService->comparePages($sourceData, $migratedData, $themeName);
            
            // BREAKPOINT 10: Результат AI анализа
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 10: AI analysis completed =====", [
                'quality_score' => $analysisResult['quality_score'] ?? null,
                'severity_level' => $analysisResult['severity_level'] ?? 'unknown',
                'issues_count' => count($analysisResult['issues'] ?? []),
                'missing_elements_count' => count($analysisResult['missing_elements'] ?? []),
                'changed_elements_count' => count($analysisResult['changed_elements'] ?? []),
                'has_summary' => !empty($analysisResult['summary'] ?? ''),
                'summary_preview' => substr($analysisResult['summary'] ?? '', 0, 200),
                'full_result_keys' => array_keys($analysisResult)
            ]);

            // BREAKPOINT 11: Подготовка данных для сохранения в БД
            Logger::instance()->info("[Quality Analysis] ===== BREAKPOINT 11: Preparing report data for database =====", [
                'step' => 4,
                'total_steps' => 4,
                'page_slug' => $pageSlug,
                'migration_id' => $brizyProjectId,
                'mb_project_uuid' => $mbProjectUuid,
                'report_data_preview' => [
                    'quality_score' => $analysisResult['quality_score'] ?? null,
                    'severity_level' => $analysisResult['severity_level'] ?? 'none',
                    'has_issues_summary' => !empty($analysisResult['issues_summary'] ?? []),
                    'has_detailed_report' => !empty($analysisResult)
                ]
            ]);
            
            $reportId = $this->reportService->saveReport([
                'migration_id' => $brizyProjectId,
                'mb_project_uuid' => $mbProjectUuid,
                'page_slug' => $pageSlug,
                'source_url' => $sourceUrl,
                'migrated_url' => $migratedUrl,
                'analysis_status' => 'completed',
                'quality_score' => $analysisResult['quality_score'] ?? null,
                'severity_level' => $analysisResult['severity_level'] ?? 'none',
                'issues_summary' => [
                    'summary' => $analysisResult['summary'] ?? '',
                    'missing_elements' => $analysisResult['missing_elements'] ?? [],
                    'changed_elements' => $analysisResult['changed_elements'] ?? [],
                    'recommendations' => $analysisResult['recommendations'] ?? []
                ],
                'detailed_report' => $analysisResult,
                'screenshots_path' => json_encode([
                    'source' => basename($sourceData['screenshot_path']),
                    'migrated' => basename($migratedData['screenshot_path'])
                ])
            ]);

            // Подготавливаем информацию о скриншотах для логирования
            $screenshotsInfo = [
                'source' => $sourceData['screenshot_path'] ?? null,
                'migrated' => $migratedData['screenshot_path'] ?? null
            ];
            
            Logger::instance()->info("[Quality Analysis] ===== Quality analysis completed successfully =====", [
                'report_id' => $reportId,
                'page_slug' => $pageSlug,
                'mb_project_uuid' => $mbProjectUuid,
                'brizy_project_id' => $brizyProjectId,
                'quality_score' => $analysisResult['quality_score'] ?? null,
                'severity_level' => $analysisResult['severity_level'] ?? 'none',
                'summary' => $analysisResult['summary'] ?? 'No summary available',
                'screenshots' => [
                    'source_path' => $screenshotsInfo['source'],
                    'source_exists' => isset($screenshotsInfo['source']) && file_exists($screenshotsInfo['source']),
                    'source_size_bytes' => isset($screenshotsInfo['source']) && file_exists($screenshotsInfo['source']) ? filesize($screenshotsInfo['source']) : 0,
                    'migrated_path' => $screenshotsInfo['migrated'],
                    'migrated_exists' => isset($screenshotsInfo['migrated']) && file_exists($screenshotsInfo['migrated']),
                    'migrated_size_bytes' => isset($screenshotsInfo['migrated']) && file_exists($screenshotsInfo['migrated']) ? filesize($screenshotsInfo['migrated']) : 0
                ],
                'screenshots' => [
                    'source_path' => $screenshotsInfo['source'] ?? null,
                    'source_exists' => isset($screenshotsInfo['source']) && file_exists($screenshotsInfo['source']),
                    'source_size' => isset($screenshotsInfo['source']) && file_exists($screenshotsInfo['source']) ? filesize($screenshotsInfo['source']) : 0,
                    'migrated_path' => $screenshotsInfo['migrated'] ?? null,
                    'migrated_exists' => isset($screenshotsInfo['migrated']) && file_exists($screenshotsInfo['migrated']),
                    'migrated_size' => isset($screenshotsInfo['migrated']) && file_exists($screenshotsInfo['migrated']) ? filesize($screenshotsInfo['migrated']) : 0
                ]
            ]);

            return $reportId;

        } catch (Exception $e) {
            Logger::instance()->error("[Quality Analysis] ===== Error during quality analysis =====", [
                'page_slug' => $pageSlug,
                'mb_project_uuid' => $mbProjectUuid,
                'brizy_project_id' => $brizyProjectId,
                'source_url' => $sourceUrl,
                'migrated_url' => $migratedUrl,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Сохраняем отчет об ошибке
            try {
                $this->reportService->saveReport([
                    'migration_id' => $brizyProjectId,
                    'mb_project_uuid' => $mbProjectUuid,
                    'page_slug' => $pageSlug,
                    'source_url' => $sourceUrl,
                    'migrated_url' => $migratedUrl,
                    'analysis_status' => 'error',
                    'quality_score' => null,
                    'severity_level' => 'none',
                    'issues_summary' => [
                        'error' => $e->getMessage()
                    ],
                    'detailed_report' => [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]
                ]);
            } catch (Exception $saveEx) {
                Logger::instance()->error("Failed to save error report", [
                    'error' => $saveEx->getMessage()
                ]);
            }

            // Не прерываем процесс миграции из-за ошибки анализа
            return null;
        }
    }

    /**
     * Получить отчеты по миграции
     */
    public function getReports(int $brizyProjectId): array
    {
        return $this->reportService->getReportsByMigration($brizyProjectId);
    }

    /**
     * Получить статистику по миграции
     */
    public function getStatistics(int $brizyProjectId): array
    {
        return $this->reportService->getMigrationStatistics($brizyProjectId);
    }

    /**
     * Загрузить скриншоты на дашборд миграции
     * 
     * @param array $sourceData Данные исходной страницы
     * @param array $migratedData Данные мигрированной страницы
     * @param string $mbProjectUuid UUID проекта MB
     * @param string $pageSlug Slug страницы
     * @return void
     */
    private function uploadScreenshotsToDashboard(
        array $sourceData,
        array $migratedData,
        string $mbProjectUuid,
        string $pageSlug
    ): void {
        try {
            $uploader = new DashboardScreenshotUploader();
            
            if (!$uploader->isEnabled()) {
                Logger::instance()->debug("[Quality Analysis] Dashboard screenshot upload is disabled", [
                    'mb_uuid' => $mbProjectUuid,
                    'page_slug' => $pageSlug
                ]);
                return;
            }

            Logger::instance()->info("[Quality Analysis] Uploading screenshots to dashboard", [
                'mb_uuid' => $mbProjectUuid,
                'page_slug' => $pageSlug,
                'dashboard_url' => $uploader->getDashboardServerUrl()
            ]);

            // Загружаем скриншот исходной страницы
            if (isset($sourceData['screenshot_path']) && file_exists($sourceData['screenshot_path'])) {
                $uploader->uploadScreenshot(
                    $sourceData['screenshot_path'],
                    $mbProjectUuid,
                    $pageSlug,
                    'source'
                );
            }

            // Загружаем скриншот мигрированной страницы
            if (isset($migratedData['screenshot_path']) && file_exists($migratedData['screenshot_path'])) {
                $uploader->uploadScreenshot(
                    $migratedData['screenshot_path'],
                    $mbProjectUuid,
                    $pageSlug,
                    'migrated'
                );
            }

            Logger::instance()->info("[Quality Analysis] Screenshots upload to dashboard completed", [
                'mb_uuid' => $mbProjectUuid,
                'page_slug' => $pageSlug
            ]);

        } catch (Exception $e) {
            // Не прерываем процесс анализа из-за ошибки загрузки скриншотов
            Logger::instance()->warning("[Quality Analysis] Error uploading screenshots to dashboard", [
                'mb_uuid' => $mbProjectUuid,
                'page_slug' => $pageSlug,
                'error' => $e->getMessage()
            ]);
        }
    }
}
