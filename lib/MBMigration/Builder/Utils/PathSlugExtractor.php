<?php

namespace MBMigration\Builder\Utils;

use MBMigration\Builder\VariableCache;

class PathSlugExtractor
{
    public static function getFullUrl($slug, bool $getPath = false): string
    {
        $cache = VariableCache::getInstance();
        $treePages = $cache->get('ParentPages');

        $domain = $cache->get('settings')['domain'];

        $urlBuilder = new UrlBuilder($domain);

        $pathPages = self::getOrderedPathString($treePages, $slug, 'slug');

        if ($getPath){
            return $pathPages;
        }

        return $urlBuilder->setPath($pathPages)->build();
    }

    public static function getFullUrlById($slug, bool $getPath = false): string
    {
        $cache = VariableCache::getInstance();
        $treePages = $cache->get('ParentPages');

        $domain = $cache->get('settings')['domain'];

        $urlBuilder = new UrlBuilder($domain);

        $pathPages = self::getOrderedPathString($treePages, $slug, 'id');

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
