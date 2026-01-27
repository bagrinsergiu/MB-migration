<?php

namespace MBMigration\Builder\Utils;

use MBMigration\Builder\VariableCache;
use MBMigration\Builder\Factory\VariableCacheFactory;
use MBMigration\Core\Logger;
use MBMigration\Layer\MB\MBProjectDataCollector;

class PathSlugExtractor
{
    /**
     * Get and verify domain, find alternative if current is not accessible
     * 
     * @return string Working domain
     */
    private static function getWorkingDomain(): string
    {
        $cache = VariableCacheFactory::create();
        
        // Check cache for already verified working domain
        $cachedWorkingDomain = $cache->get('workingDomain');
        if (!empty($cachedWorkingDomain)) {
            return $cachedWorkingDomain;
        }

        $settings = $cache->get('settings');
        $domain = $settings['domain'] ?? null;

        if (empty($domain)) {
            Logger::instance()->warning("Domain not found in settings");
            return '';
        }

        // Normalize domain if needed
        $normalizedDomain = MBProjectDataCollector::normalizeDomain($domain);
        
        if (empty($normalizedDomain)) {
            Logger::instance()->warning("Failed to normalize domain: $domain");
            return $domain;
        }

        // Check if domain is accessible
        if (MBProjectDataCollector::isDomainAccessible($normalizedDomain)) {
            // Cache the working domain
            $cache->set('workingDomain', $normalizedDomain);
            return $normalizedDomain;
        }

        // Domain is not accessible, try to find alternative
        Logger::instance()->info("Domain $normalizedDomain is not accessible, searching for alternative...");
        
        $projectID_MB = $cache->get('projectId_MB');
        if (!empty($projectID_MB)) {
            $availableDomain = MBProjectDataCollector::findAvailableDomain($projectID_MB);
            if (!empty($availableDomain)) {
                Logger::instance()->info("Found alternative domain: $availableDomain");
                // Update settings with working domain
                $settings['domain'] = $availableDomain;
                $cache->set('settings', $settings);
                // Cache the working domain
                $cache->set('workingDomain', $availableDomain);
                return $availableDomain;
            }
        }

        // Fallback to normalized domain even if not accessible
        Logger::instance()->warning("No alternative domain found, using normalized domain: $normalizedDomain");
        // Cache it anyway to avoid repeated checks
        $cache->set('workingDomain', $normalizedDomain);
        return $normalizedDomain;
    }

    public static function getFullUrl($slug, bool $getPath = false): string
    {
        $cache = VariableCacheFactory::create();
        $treePages = $cache->get('ParentPages');

        $domain = self::getWorkingDomain();

        $urlBuilder = new UrlBuilder($domain);

        $pathPages = self::getOrderedPathString($treePages, $slug, 'slug');

        if ($getPath){
            return $pathPages;
        }

        return $urlBuilder->setPath($pathPages)->build();
    }

    public static function getFullUrlById($id, bool $getPath = false): string
    {
        $cache = VariableCacheFactory::create();
        $treePages = $cache->get('ParentPages');

        $domain = self::getWorkingDomain();

        $urlBuilder = new UrlBuilder($domain);

        $pathPages = self::getOrderedPathString($treePages, $id, 'id');

        if ($getPath){
            return $pathPages;
        }

        return $urlBuilder->setPath($pathPages)->build();
    }

    public static function findDeepestSlug($array, $parentHidden = false): array
    {
        $deepestSlug = null;
        $maxDepth = -1;

        foreach ($array as $item) {
            $currentHidden = isset($item['hidden']) ? $item['hidden'] : false;
            if ($parentHidden || $currentHidden) {
                continue;
            }

            if (isset($item['child']) && count($item['child']) >= 2) {
                $validChildren = array_filter($item['child'], function ($child) {
                    return isset($child['hidden']) && !$child['hidden'];
                });

                if (count($validChildren) >= 2) {
                    return ['slug' => reset($validChildren)['slug'], 'depth' => 1];
                }
            }

            if (!empty($item['child'])) {
                $result = self::findDeepestSlug($item['child'], $currentHidden);
                $depth = $result['depth'] + 1;

                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                    $deepestSlug = $result['slug'];
                }
            } else {
                $slug = $item['slug'];
                $depth = 0;

                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                    $deepestSlug = $slug;
                }
            }
        }

        if ($deepestSlug === null && isset($array[0]['slug'])) {
            return ['slug' => $array[0]['slug'], 'depth' => 0];
        }

        return ['slug' => $deepestSlug, 'depth' => $maxDepth];
    }

    public static function findElementBySlugAndOrder($data, $slug, $findBy) {
        foreach ($data as $item) {
            if ($item[$findBy] === $slug) {
                if (!empty($item['child'])) {
                    $result = self::findElementBySlugAndOrder($item['child'], $slug, $findBy);
                    if ($result !== null) {
                        array_unshift($result, $item[$findBy]);
                        return $result;
                    }
                }
                return [$item[$findBy]];
            }

            if (!empty($item['child'])) {
                $result = self::findElementBySlugAndOrder($item['child'], $slug, $findBy);
                if ($result !== null) {
                    array_unshift($result, $item[$findBy]);
                    return $result;
                }
            }
        }

        return null;
    }

    private static function getOrderedPath($data, $slug, $findBy) {
        $path = self::findElementBySlugAndOrder($data, $slug, $findBy);

        if ($path) {
            $orderedPath = [];
            foreach ($path as $slug) {
                foreach ($data as $item) {
                    if ($item[$findBy] === $slug) {
                        $orderedPath[] = $item;
                        $data = $item['child'];
                        break;
                    }
                }
            }
            return $orderedPath;
        }

        return null;
    }

    public static function getSiteMap(array $parentListPages, string $projectDomain): array {
        $list = [];
        foreach ($parentListPages as $parentPage) {
            $list[] = $projectDomain . '/' . $parentPage['slug'];
            if(!empty($parentPage['child'])){
                foreach ($parentPage['child'] as $childPage) {
                    $list[] = $projectDomain . '/' . $parentPage['slug'] . '/' . $childPage['slug'];
                }
            }
        }

        return $list;
    }

    public static function getProjectDomain(): string
    {
        $domain = self::getWorkingDomain();

        $UrlDomain = new UrlBuilder($domain);

        return $UrlDomain->getDomain();
    }

    public static function getOrderedPathString($data, $slug, $findBy): ?string
    {
        $orderedPath = self::getOrderedPath($data, $slug, $findBy);

        if ($orderedPath) {
            $pathArray = array_map(function ($item) {
                return $item['slug'];
            }, $orderedPath);

            return implode('/', $pathArray);
        }

        return null;
    }

}
