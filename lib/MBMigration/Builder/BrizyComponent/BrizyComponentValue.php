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

        $this->set('_id', 'a'.bin2hex(random_bytes(16)));
    }

    public function set($field, $value)
    {
        $this->fields[$field] = $value;

        return $this;
    }

    public function add($field, $value, $position=null)
    {
        if($position === null) {
            $this->fields[$field] = array_merge($this->fields[$field], $value);
        } else {
            if($position === 0){
                array_unshift($this->fields[$field], ...$value);
            } else{
                array_splice($this->fields[$field], $position,0, $value);
            }
        }

        return $this;
    }

    public function get($field, $value)
    {
        if (!isset($this->fields[$field])) {
            throw new Exception();
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
