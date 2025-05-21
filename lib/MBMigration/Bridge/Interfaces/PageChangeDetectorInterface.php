<?php

namespace MBMigration\Bridge\Interfaces;

/**
 * Interface for page change detector
 */
interface PageChangeDetectorInterface
{
    /**
     * Check if pages have changed since a specific date
     *
     * @param array $pages The list of pages to check
     * @param string $snapshotDate The date to compare against
     * @return array The list of changed pages
     */
    public function detectChanges(array $pages, string $snapshotDate): array;

    /**
     * Filter pages that were modified after a specific date
     *
     * @param array $pages The list of pages to filter
     * @param string $migrationDate The date to compare against
     * @param array $modifiedPages Reference to the array to store modified pages
     */
    public function filterModifiedPages(array $pages, string $migrationDate, array &$modifiedPages): void;

    /**
     * Check if a page was modified after a specific date
     *
     * @param string $pageDate The page update date
     * @param string $compareDate The date to compare against
     * @return bool True if the page was modified after the date, false otherwise
     */
    public function isPageModifiedAfterDate(string $pageDate, string $compareDate): bool;

    /**
     * Format the list of changed pages for response
     *
     * @param array $changedPages The list of changed pages
     * @return array The formatted list of changed pages
     */
    public function formatChangedPagesForResponse(array $changedPages): array;
}
