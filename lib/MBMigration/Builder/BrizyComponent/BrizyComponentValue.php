<?php

namespace MBMigration\Builder\BrizyComponent;

use Socket\Raw\Exception;

class BrizyComponentValue implements \JsonSerializable
{
    private $fields;
    public function __construct($data, $parent = null)
    {

        if (isset($data['items'])) {
            $this->set(
                'items',
                array_map(function ($component) use ($parent){
                    return new BrizyComponent($component,$parent);
                }, $data['items'])
            );
        }

        foreach ($data as $field => $value) {
            if ($field == 'items') {
                continue;
            }
            $this->set($field, $value);
        }

        $this->set('_id', bin2hex(random_bytes(16)));
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
        if (!isset($this->fields[$field])) {
            throw new \Exception();
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