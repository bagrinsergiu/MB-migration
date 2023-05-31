<?php

namespace Builder;

class sectionsBuilder implements QueryBuilder
{
    private mixed $data;
    private mixed $item;

    public function __construct($json){
        $this->data = json_decode($json);
    }

    public function section(): QueryBuilder
    {
        if (isset($this->data->value)) {
            $this->item = $this->data->value;
        }
        return $this;
    }

    public function item(int $id): QueryBuilder
    {
        if (isset($this->item->items[$id]->value)) {
            $this->item = $this->item->items[$id]->value;
        }
        return $this;
    }

    public function settings(string $key, string $value): QueryBuilder
    {
        if (isset($this->item->$key)) {
            $this->item->$key = $value;
        }
        else{
            $this->addParameter($key, $value);
        }
        return $this;
    }

    public function get(): object
    {
        return $this->item;
    }

    private function addParameter(string $key, $value): QueryBuilder
    {
        $this->item->$key = $value;
        return $this;
    }
}