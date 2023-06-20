<?php

namespace MBMigration\Builder;

class ItemSetter
{
    private mixed $data;
    private mixed $item;

    public function __construct($json){
        $this->data = json_decode($json);
        $this->begin();
    }

    private function begin(): static
    {
        if (isset($this->data->value)) {
            $this->item = $this->data->value;
        }
        return $this;
    }

    public function item(int $id): static
    {
        if (isset($this->item->items[$id])) {
            $this->item = $this->item->items[$id];
        }
        return $this;
    }
    public function section(int $id): static
    {
        if (isset($this->item->items[$id])) {
            $this->item = $this->item->items[$id];
        }
        return $this;
    }

    public function setting(string $key, string $value, $externalValue = false): static
    {
        if($externalValue) {
            if (isset($this->item->$key)) {
                $this->item->$key = $value;
            } else {
                $this->addParameter($key, $value);
            }
            return $this;
        }
        if (isset($this->item->value->$key)) {
            $this->item->value->$key = $value;
        } else {
            $this->addParameter($key, $value);
        }
        return $this;
    }
    public function addItem($value): static
    {
        $this->item->items[] = $value;
        return $this;
    }

    public function get()
    {
        return $this->data;
    }

    private function addParameter(string $key, $value): static
    {
        $this->item->$key = $value;
        return $this;
    }
}