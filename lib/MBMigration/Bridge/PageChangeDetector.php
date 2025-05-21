<?php

namespace MBMigration\Bridge;

use DateTime;
use Exception;
use MBMigration\Bridge\Interfaces\PageChangeDetectorInterface;

/**
 * Detects changes in pages
 */
class PageChangeDetector implements PageChangeDetectorInterface
{
    private array $changedPages = [];

    /**
     * Check if pages have changed since a specific date
     *
     * @param array $pages The list of pages to check
     * @param string $snapshotDate The date to compare against
     * @return array The list of changed pages
     */
    public function detectChanges(array $pages, string $snapshotDate): array
    {
        $this->changedPages = [];
        $this->checkPagesRecursively($pages, $snapshotDate);
        return $this->changedPages;
    }

    /**
     * Recursively check pages and their children for changes
     *
     * @param array $pages The list of pages to check
     * @param string $snapshotDate The date to compare against
     */
    private function checkPagesRecursively(array $pages, string $snapshotDate): void
    {
        foreach ($pages as $page) {
            $isChanged = $this->isPageModifiedAfterDate($page['updated_at'], $snapshotDate);

            if ($isChanged) {
                $this->changedPages[$page['slug']] = $page['updated_at'];
            }

            // Recursively check child pages
            if (isset($page['child']) && !empty($page['child'])) {
                $this->checkPagesRecursively($page['child'], $snapshotDate);
            }
        }
    }

    /**
     * Filter pages that were modified after a specific date
     *
     * @param array $pages The list of pages to filter
     * @param string $migrationDate The date to compare against
     * @param array $modifiedPages Reference to the array to store modified pages
     */
    public function filterModifiedPages(array $pages, string $migrationDate, array &$modifiedPages): void
    {
        foreach ($pages as $page) {
            if (isset($page['updated_at'])) {
                $isModified = $this->isPageModifiedAfterDate($page['updated_at'], $migrationDate);

                if ($isModified) {
                    $modifiedPages[$page['slug']] = $page['updated_at'];
                }
            }

            // Recursively check child pages
            if (isset($page['child']) && !empty($page['child'])) {
                $this->filterModifiedPages($page['child'], $migrationDate, $modifiedPages);
            }
        }
    }

    /**
     * Check if a page was modified after a specific date
     *
     * @param string $pageDate The page update date
     * @param string $compareDate The date to compare against
     * @return bool True if the page was modified after the date, false otherwise
     */
    public function isPageModifiedAfterDate(string $pageDate, string $compareDate): bool
    {
        try {
            $pageDateTime = new DateTime($pageDate);
            $compareDateTime = new DateTime($compareDate);

            $pageDateOnly = $pageDateTime->format('Y-m-d');
            $compareDateOnly = $compareDateTime->format('Y-m-d');

            // If dates are the same, consider it modified
            if ($pageDateOnly === $compareDateOnly) {
                return true;
            }

            // Return true if page date is after compare date
            return $pageDateOnly > $compareDateOnly;
        } catch (Exception $e) {
            // If there's an error parsing dates, return false
            return false;
        }
    }

    /**
     * Format the list of changed pages for response
     *
     * @param array $changedPages The list of changed pages
     * @return array The formatted list of changed pages
     */
    public function formatChangedPagesForResponse(array $changedPages): array
    {
        $formattedPages = [];

        foreach ($changedPages as $slug => $dateUpdated) {
            $formattedPages[] = [
                'slug' => $slug,
                'date_updated' => $dateUpdated
            ];
        }

        return $formattedPages;
    }
}
