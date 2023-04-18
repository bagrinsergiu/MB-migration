<?php

namespace Builder;

class ArrayManipulator
{

    private $array;

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

    public function groupArrayByParentId($list)
    {
        $result = [];
        $parents = [];
        foreach ($list as $item) {
            if ($item["parent_id"] == null && $item["category"] == "list") {
                $parents[$item["id"]] = $item;
                $parents[$item["id"]]["children"] = [];
            } else if ($item["parent_id"] == null && $item["category"] == "text") {
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

}