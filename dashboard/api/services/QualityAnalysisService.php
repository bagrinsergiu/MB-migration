<?php

namespace Dashboard\Services;

use MBMigration\Analysis\QualityReport;
use Exception;

/**
 * QualityAnalysisService
 * 
 * Сервис для работы с анализом качества миграций
 */
class QualityAnalysisService
{
    /**
     * @var QualityReport
     */
    private $qualityReport;

    public function __construct()
    {
        $this->qualityReport = new QualityReport();
    }

    /**
     * Получить список отчетов по миграции
     * 
     * @param int $migrationId ID миграции (brz_project_id)
     * @return array
     */
    public function getReportsByMigration(int $migrationId): array
    {
        return $this->qualityReport->getReportsByMigration($migrationId);
    }

    /**
     * Получить отчет по slug страницы
     * 
     * @param int $migrationId ID миграции
     * @param string $pageSlug Slug страницы
     * @param bool $includeArchived Включить архивные отчеты
     * @return array|null
     */
    public function getReportBySlug(int $migrationId, string $pageSlug, bool $includeArchived = false): ?array
    {
        return $this->qualityReport->getReportBySlug($migrationId, $pageSlug, $includeArchived);
    }

    /**
     * Получить статистику по миграции
     * 
     * @param int $migrationId ID миграции
     * @return array
     */
    public function getMigrationStatistics(int $migrationId): array
    {
        return $this->qualityReport->getMigrationStatistics($migrationId);
    }

    /**
     * Получить архивные отчеты по миграции
     * 
     * @param int $migrationId ID миграции
     * @return array
     */
    public function getArchivedReportsByMigration(int $migrationId): array
    {
        return $this->qualityReport->getArchivedReportsByMigration($migrationId);
    }
}
