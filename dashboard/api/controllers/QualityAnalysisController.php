<?php

namespace Dashboard\Controllers;

use Dashboard\Services\QualityAnalysisService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class QualityAnalysisController
{
    /**
     * @var QualityAnalysisService
     */
    private $qualityService;

    public function __construct()
    {
        $this->qualityService = new QualityAnalysisService();
    }

    /**
     * GET /api/migrations/:id/quality-analysis
     * Получить список анализов качества для миграции
     */
    public function getAnalysisList(Request $request, int $migrationId): JsonResponse
    {
        try {
            $reports = $this->qualityService->getReportsByMigration($migrationId);
            
            return new JsonResponse([
                'success' => true,
                'data' => $reports,
                'count' => count($reports)
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id/quality-analysis/statistics
     * Получить статистику по анализу качества миграции
     */
    public function getStatistics(Request $request, int $migrationId): JsonResponse
    {
        try {
            $statistics = $this->qualityService->getMigrationStatistics($migrationId);
            
            return new JsonResponse([
                'success' => true,
                'data' => $statistics
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id/quality-analysis/:pageSlug
     * Получить детали анализа конкретной страницы
     */
    public function getPageAnalysis(Request $request, int $migrationId, string $pageSlug): JsonResponse
    {
        try {
            // Проверяем параметр include_archived из query string
            $includeArchived = $request->query->getBoolean('include_archived', false);
            $report = $this->qualityService->getReportBySlug($migrationId, $pageSlug, $includeArchived);
            
            if (!$report) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Анализ для страницы не найден'
                ], 404);
            }
            
            return new JsonResponse([
                'success' => true,
                'data' => $report
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id/quality-analysis/archived
     * Получить список архивных анализов качества для миграции
     */
    public function getArchivedAnalysisList(Request $request, int $migrationId): JsonResponse
    {
        try {
            $reports = $this->qualityService->getArchivedReportsByMigration($migrationId);
            
            return new JsonResponse([
                'success' => true,
                'data' => $reports,
                'count' => count($reports)
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/migrations/:id/quality-analysis/:pageSlug/screenshots/:type
     * Получить скриншот страницы
     * type: 'source' или 'migrated'
     */
    public function getScreenshot(Request $request, int $migrationId, string $pageSlug, string $type): JsonResponse
    {
        try {
            $report = $this->qualityService->getReportBySlug($migrationId, $pageSlug);
            
            if (!$report || empty($report['screenshots_path'])) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Скриншот не найден'
                ], 404);
            }
            
            $screenshots = $report['screenshots_path'];
            $screenshotPath = null;
            
            if ($type === 'source' && isset($screenshots['source'])) {
                $screenshotPath = $screenshots['source'];
            } elseif ($type === 'migrated' && isset($screenshots['migrated'])) {
                $screenshotPath = $screenshots['migrated'];
            } else {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Неверный тип скриншота. Используйте: source или migrated'
                ], 400);
            }
            
            if (!file_exists($screenshotPath)) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Файл скриншота не найден'
                ], 404);
            }
            
            // Возвращаем путь к скриншоту (фронтенд будет загружать его напрямую)
            // Или можно вернуть base64, но это будет тяжело для больших файлов
            return new JsonResponse([
                'success' => true,
                'data' => [
                    'path' => $screenshotPath,
                    'url' => '/dashboard/api/screenshots/' . basename($screenshotPath),
                    'exists' => true,
                    'size' => filesize($screenshotPath)
                ]
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
