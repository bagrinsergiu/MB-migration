<?php

namespace MBMigration\Builder\BrizyComponent;

use JsonSerializable;
use Exception;
class BrizyComponentPage extends BrizyComponent implements JsonSerializable
{
    private $fields;

    public function __construct($data)
    {
        if (isset($data['items'])) {
            $this->set(
                'items',
                array_map(function ($component) {
                    return new BrizyComponent($component);
                }, $data['items'])
            );
        }

        foreach ($data as $field => $value) {
            if ($field == 'items') {
                continue;
            }
            $this->set($field, $value);
        }
    }

    public function set($field, $value)
    {
        $this->fields[$field] = $value;

        return $this;
    }

    public function add($field, $value)
    {
        $this->fields[$field] = array_merge($this->fields[$field], $value);

        return $this;
    }

    public function get($field, $value)
    {
        if(!isset($this->fields[$field])) {
            throw new Exception('Field ' . $field . ' not found');
        }

        return $this->fields[$field];
    }

    public function __call($methodName, $params)
    {
        $matches = [];
        preg_match("/^(?<action>set|get|add)_(?<field>\w+)$/", $methodName, $matches);
        $method = strtolower($matches['action']);
        $field = $matches['field'];

        return call_user_func_array([$this, $method], [$field, array_pop($params)]);

    }

    public function jsonSerialize()
    {
        return $this->fields;
    }
}
