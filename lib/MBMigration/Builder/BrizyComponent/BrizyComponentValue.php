<?php

namespace MBMigration\Builder\BrizyComponent;

use JsonSerializable;

class BrizyComponentValue implements JsonSerializable, \IteratorAggregate
{
    private $fields;

    public function __construct($data, $parent = null)
    {
        if (isset($data['items'])) {
            $this->set(
                'items',
                array_map(function ($component) use ($parent) {
                    if ($component instanceof BrizyComponent) {
                        return $component;
                    }
                    if (is_array($component)) {
                        return BrizyComponent::fromArray($component, $parent);
                    }
                    // Unexpected item type; keep as-is to avoid fatal, but better to log upstream
                    return $component;
                }, $data['items'])
            );
        }

        foreach ($data as $field => $value) {
            if ($field == 'items') {
                continue;
            }
            $this->set($field, $value);
        }

        $this->set('_id', 'a' . bin2hex(random_bytes(16)));
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->fields);
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

    /**
     * Add a single item to the 'items' field and return the added item.
     * This is useful when you need to continue working with the added item immediately.
     *
     * @param mixed $item The item to add (typically BrizyComponent)
     * @param int|null $position Optional position to insert at (null = append at end)
     * @return mixed The added item (returns the same reference, changes will reflect in parent)
     */
    public function addItemAndGet($item, $position = null)
    {
        // Use existing add() method to handle all the insertion logic
        $this->add('items', $item, $position);

        // Return the added item based on position
        if ($position === null) {
            // Item was added at the end, return last element
            return $this->fields['items'][count($this->fields['items']) - 1];
        } else {
            // Calculate actual position where item was inserted
            $len = count($this->fields['items']);
            if (!is_int($position)) {
                $position = (int)$position;
            }
            if ($position < 0) {
                $actualPos = max(0, $len + $position + 1) - 1;
            } else {
                $actualPos = min($position, $len - 1);
            }
            return $this->fields['items'][$actualPos];
        }
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

        switch ($method) {
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

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->fields;
    }

}
