<?php

namespace MBMigration\Builder\BrizyComponent;

use JsonSerializable;
use Exception;
class BrizyComponentValue implements JsonSerializable
{
    private $fields;
    public function __construct($data, $parent = null)
    {

        if (isset($data['items'])) {
            $this->set(
                'items',
                array_map(function ($component) use ($parent){
                    return BrizyComponent::fromArray($component, $parent);
                }, $data['items'])
            );
        }

        foreach ($data as $field => $value) {
            if ($field == 'items') {
                continue;
            }
            $this->set($field, $value);
        }

        $this->set('_id', 'a'.bin2hex(random_bytes(16)));
    }

    public function set($field, $value)
    {
        $this->fields[$field] = $value;

        return $this;
    }

    public function add($field, $value, $position = null)
    {
        if (!isset($this->fields[$field])) {
            $this->fields[$field] = [];
        } elseif (!is_array($this->fields[$field])) {
            $this->fields[$field] = [$this->fields[$field]];
        }

        if (!is_array($value)) {
            $itemsToInsert = [$value];
        } else {
            $isList = array_keys($value) === range(0, count($value) - 1);
            $itemsToInsert = $isList ? $value : [$value];
        }

        if ($position === null) {
            array_push($this->fields[$field], ...$itemsToInsert);
        } else {
            $len = count($this->fields[$field]);
            if (!is_int($position)) {
                $position = (int)$position;
            }
            if ($position < 0) {
                $position = max(0, $len + $position);
            } else {
                $position = min($position, $len);
            }

            if ($position === 0) {
                array_unshift($this->fields[$field], ...$itemsToInsert);
            } elseif ($position >= $len) {
                array_push($this->fields[$field], ...$itemsToInsert);
            } else {
                array_splice($this->fields[$field], $position, 0, $itemsToInsert);
            }
        }

        return $this;
    }


    public function get($field)
    {
        if (!isset($this->fields[$field])) {
            return null;
        }

        return $this->fields[$field];
    }

    public function __call($methodName, $params)
    {
        $methodName = preg_replace("/[^a-zA-Z0-9_]/", "", $methodName);

        $matches = [];
        preg_match("/^(?<action>set|get|add)_(?<field>\w+)$/", $methodName, $matches);
        $method = strtolower($matches['action']);
        $field = $matches['field'];

        switch($method) {
            case 'add':
                $arg = [
                    $matches['field'],
                    $params[0],
                    $params[1] ?? null
                    ];
                break;
            default:
                $arg = [
                    $field,
                    array_pop($params)
                ];
        }

        return call_user_func_array([$this, $method], $arg);

    }

    public function jsonSerialize()
    {
        return $this->fields;
    }

}
