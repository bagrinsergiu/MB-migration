<?php

namespace MBMigration\Builder\Utils;

class FamilyTreeMenu {
    public static function findChildrenByChildId($nestedArray, $childId): array
    {
        $result = [];
        self::searchChildrenByChildId($nestedArray, $childId, $result);
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
}