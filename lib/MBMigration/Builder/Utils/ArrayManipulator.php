<?php

namespace MBMigration\Builder\Utils;

class ArrayManipulator
{
    private $array;
    private array $previousArray = [];

    public function init(array $array) {
        $this->array = $array;
    }

    public function groupBy($key) {
        $result = array();
        foreach($this->array as $item) {
            $result[$item[$key]][] = $item;
        }
        $this->array = $result;
        return $this;
    }

    public function orderBy($key, $direction = 'asc') {
        usort($this->array, function($a, $b) use ($key, $direction) {
            if($direction == 'asc') {
                return $a[$key] > $b[$key];
            } else {
                return $a[$key] < $b[$key];
            }
        });
        return $this;
    }

    public function filterBy($key, $value) {
        $this->array = array_filter($this->array, function($item) use ($key, $value) {
            return $item[$key] == $value;
        });
        return $this;
    }

    public function get() {
        return $this->array;
    }

    public function groupItemsListByParentId($list, $sectionCategory, $typeSection): array
    {
        $parents = [];
        $itemsList = [];
        $parentItem = [];

        foreach ($list as $item) {
            if ($item["parent_id"] == null && $item["content"] == null && $item["item_type"] == null) {
                $item["items"] = [];
                $parents[] = $item;
            } else if ($item["parent_id"] == null) {
                $itemsList[] = $item;
            }
        }

        foreach ($parents as &$parentItem) {
            foreach ($list as $item) {
                if($item["parent_id"] == $parentItem["id"]) {
                    $parentItem['items'][] = $item;
                }
            }
        }

        switch ($sectionCategory) {
            case 'gallery':
                return ['slide' => $itemsList, 'list' => $parents];
            case 'accordion':
            case 'list':
            case 'tabs':
                return ['head' => $itemsList, 'items' => $parents];
            default:
                return $this->itemSwitcher($itemsList, $parentItem, $typeSection);
        }
    }

    public function itemSwitcher($itemsList, $parentItem, $typeSection)
    {
        switch ($typeSection) {
            case 'left-gallery':
                return ['items' => $itemsList, 'gallery' => $parentItem];
            default:
                return $itemsList;
        }
    }


    public function groupItemsListByParentId__q($list, $sectionCategory): array
    {
        $result = [];
        $parents = [];
        $itemsList = [];

        if($sectionCategory !== 'gallery') {
            foreach ($list as $item) {
                if ($item["parent_id"] == null && $item["content"] == null && $item["category"] == 'list') {
                    $parents[$item["id"]] = $item;
                    $parents[$item["id"]]["item"] = [];
                } else if ($item["parent_id"] == null && $item["content"] == null && $item["category"] == 'accordion') {
                    $parents[$item["id"]] = $item;
                    $parents[$item["id"]]["item"] = [];
                } else if ($item["parent_id"] == null && $item["content"] == null && $item["category"] == 'tab') {
                    $parents[$item["id"]] = $item;
                    $parents[$item["id"]]["item"] = [];
                }
            }
        }

        if(!empty($parents)) {
            foreach ($parents as &$parent) {
                foreach ($list as $item) {
                    if($parent['id'] == $item['parent_id'])
                    {
                        $parent['item'][] = $item;
                    }
                }
            }

            usort($parents, function($a, $b) {
                return $a["order_by"] <=> $b["order_by"];
            });
        } else {
             $result = [];
             $parents = [];
             foreach ($list as $item) {
                 if ($item["parent_id"] == null && $item["category"] == "list") {
                     $parents[$item["id"]] = $item;
                     $parents[$item["id"]]["children"] = [];
                 } else if ($item["parent_id"] == null && $item["category"] == "accordion") {
                     $parents[$item["id"]] = $item;
                     $parents[$item["id"]]["children"] = [];
                 } else if ($item["parent_id"] == null && $item["category"] == "text") {
                     $result[] = $item;
                 } else if ($item["parent_id"] == null && $item["category"] == "photo") {
                     $result[] = $item;
                 } else if ($item["parent_id"] == null && $item["category"] == "media") {
                     $result[] = $item;
                 } else if ($item["parent_id"] == null && $item["category"] == "accordion") {
                     $result[] = $item;
                 } else if ($item["parent_id"] == null && $item["category"] == "tab") {
                     $result[] = $item;
                 } else {
                     $parents[$item["parent_id"]]["children"][] = $item;
                 }
             }

             foreach ($parents as $key => $parent) {
                 $children = $parent["children"];
                 usort($children, function($a, $b) {
                     return $a["order_by"] <=> $b["order_by"];
                 });

                 foreach ($result as &$item) {
                     if($key == $item['id']) {
                         $item['children'] = $children;
                     }
                 }
                 //$result[] = $parent;
             }

             if($sectionCategory === 'gallery') {
                 foreach ($parents as $item) {
                     $itemsList[] = $item;
                 }

                 return [
                     'slide' => $result,
                     'list' => $itemsList
                 ];
             }

             return $result;
        }

        return $parents;
    }

    public function _groupArrayByParentId($list)
    {
        $result = [];
        $parents = [];
        foreach ($list as $item) {
            if ($item["parent_id"] == null && $item["category"] == "list") {
                $parents[$item["id"]] = $item;
                $parents[$item["id"]]["children"] = [];
            } else if ($item["parent_id"] == null && $item["category"] == "text") {
                $result[] = $item;
            } else if ($item["parent_id"] == null && $item["category"] == "photo") {
                $result[] = $item;
            } else if ($item["parent_id"] == null && $item["category"] == "media") {
                $result[] = $item;
            } else {
                $parents[$item["parent_id"]]["children"][] = $item;
            }
        }
        foreach ($parents as $key => $parent) {
            $children = $parent["children"];
            usort($children, function($a, $b) {
                return $a["order_by"] <=> $b["order_by"];
            });
            foreach ($result as &$item){
                if($key == $item['id']){
                    $item['children'] = $children;
                }
            }
            //$result[] = $parent;
        }
        return $result;
    }

    public function groupArrayByParentId_($list)
    {
        $result = [];
        $parents = [];
        foreach ($list as $item) {
            if ($item["parent_id"] == null && $item["category"] == "list") {
                $parents[$item["id"]] = $item;
                $parents[$item["id"]]["children"] = [];
            } else if ($item["parent_id"] == null && $item["category"] == "text") {
                $result[] = $item;
            } else if ($item["parent_id"] == null && $item["category"] == "photo") {
                $result[] = $item;
            } else if ($item["parent_id"] == null && $item["category"] == "media") {
                $result[] = $item;
            } else {
                $parents[$item["parent_id"]]["children"][] = $item;
            }
        }
        foreach ($parents as $parent) {
            $children = $parent["children"];
            usort($children, function($a, $b) {
                return $a["order_by"] <=> $b["order_by"];
            });
            $parent["children"] = $children;
            $result[] = $parent;
        }
        return $result;
    }

    public static function sortArrayByPosition($array)
    {
        usort($array, function ($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $array;
    }

    public static function checkArrayPath($array, $path): bool
    {
        $keys = explode('/', $path);
        $current = $array;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                return false;
            }
            $current = $current[$key];
        }

        return true;
    }

    private function groupItemsGalery(array $items): array
    {

        return $sections;
    }

    public function getComparePreviousArray(): array
    {
        return $this->previousArray;
    }

    public function compareArrays(array $newArray): bool {
        if (empty($this->previousArray)) {
            $this->previousArray = $newArray;
            return true;
        }

        if ($this->hasDeletions($this->previousArray, $newArray) || !$this->hasSameUUIDs($this->previousArray, $newArray)) {
            return false;
        }

        $this->previousArray = $newArray;
        return true;
    }

    private function hasDeletions(array $old, array $new): bool {
        foreach ($old as $key => $value) {
            if (is_array($value)) {
                if (!isset($new[$key]) || !is_array($new[$key]) || $this->hasDeletions($value, $new[$key])) {
                    return true;
                }
            } elseif (!array_key_exists($key, $new)) {
                return true;
            }
        }
        return false;
    }

    private function hasSameUUIDs(array $old, array $new): bool {
        $oldFamilies = $this->extractFamilies($old);
        $newFamilies = $this->extractFamilies($new);

        foreach ($oldFamilies as $family => $uuid) {
            if (isset($newFamilies[$family]) && $newFamilies[$family] !== $uuid) {
                return false;
            }
        }

        return true;
    }

    private function extractFamilies(array $array): array {
        $families = [];
        foreach ($array as $value) {
            if (is_array($value)) {
                if (isset($value['family'], $value['uuid'])) {
                    $families[$value['family']] = $value['uuid'];
                } else {
                    $families = array_merge($families, $this->extractFamilies($value));
                }
            }
        }
        return $families;
    }

}
