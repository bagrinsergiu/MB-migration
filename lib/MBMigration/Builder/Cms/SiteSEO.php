<?php

namespace MBMigration\Builder\Cms;

use Exception;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Logger;
use MBMigration\Layer\Graph\QueryBuilder;

class SiteSEO
{
    /**
     * Sets the site title for the project in Brizy Cloud, updating only if different or missing.
     *
     * @param  string  $projectId  The Brizy project ID.
     * @param  QueryBuilder  $queryBuilder  The GraphQL query builder instance.
     * @param  VariableCache  $cache  The cache instance.
     *
     * @throws Exception If the update fails.
     */
    public static function setSiteTitle(
        string $projectId,
        QueryBuilder $queryBuilder,
        VariableCache $cache
    ): void {
        $metaField = $queryBuilder->getMetafieldByName('site_title');
        $settings  = $cache->get('settings');
        $siteTitle = $settings['title'] ?? ($metaField['value'] ?? 'My Default Site Title');

        Logger::instance()->debug('Setting site title to: '.$siteTitle);

        if (empty($siteTitle)) {
            Logger::instance()->warning('Site title is empty, skipping update.');
            return;
        }
        if (strlen($siteTitle) > 70) {
            Logger::instance()->warning('Site title exceeds 70 characters, trimming to fit SEO recommendation.');
            $siteTitle = substr($siteTitle, 0, 70);
        }

        try {
            if ($metaField && isset($metaField['value']) && $metaField['value'] === $siteTitle) {
                Logger::instance()->debug('Site title is already up to date: '.$siteTitle);
                return;
            }

            if (!$metaField) {
                Logger::instance()->warning('Site title metafield not found, creating or skipping.');
                return;
            }

            $result = $queryBuilder->updateMetafield($metaField['id'], $siteTitle, $projectId);

            if (!is_array($result) || $result['metafield']['value'] !== $siteTitle) {
                Logger::instance()->critical('Failed to update site title: '.json_encode($result, JSON_THROW_ON_ERROR));
            }

            Logger::instance()->info('Successfully updated site title to: '.$siteTitle);
        } catch (Exception $e) {
            Logger::instance()->critical('Error updating site title: '.$e->getMessage());
            throw $e;
        }
    }
}
