<?php

namespace MBMigration\Builder\Utils;

class FamilyTreeMenu {
    public static function findChildrenByChildId($nestedArray, $childId): array
    {
        $result = [];
        self::searchChildrenByChildId($nestedArray, $childId, $result);
        return $result;
    }

    public static function findParentByChildSlug($nestedArray, $childSlug): array
    {
        $result = [];
        self::searchParentByChildSlug($nestedArray, $childSlug, $result);
        return $result;
    }

    private static function searchChildrenByChildId($array, $childId, &$result) {
        foreach ($array as $item) {

            if($item['collection'] == $childId){
                $result = $item['child'];
                return;
            }

            if (isset($item['child'])) {
                foreach ($item['child'] as $child) {
                    if ($child['collection'] == $childId) {
                        $result = $item['child'];
                        return;
                    }
                }
            }
        }
    }

    private static function searchParentByChildSlug($array, $childSlug, &$result) {
        foreach ($array as $item) {
            if (isset($item['child'])) {
                foreach ($item['child'] as $child) {
                    if ($child['slug'] == $childSlug) {
                        $result = $item;
                        return;
                    }

                    self::searchParentByChildSlug($item['child'], $childSlug, $result);
                }
            }
        }
    }
}