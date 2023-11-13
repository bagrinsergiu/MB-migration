<?php

namespace MBMigration\Builder\BrizyComponent;

class BrizyPage implements \JsonSerializable
{
    private $items;

    public function __construct($data = [])
    {
        if (!is_array($data)) {
            throw new \Exception('Wrong data format provided for BrizyPage');
        }

        $this->items = $data;
    }

    public function jsonSerialize()
    {
        $getObjectVars = get_object_vars($this);

        return $getObjectVars;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): BrizyPage
    {
        $this->items = $items;

        return $this;
    }

    public function addItem(BrizyComponent $component): BrizyPage
    {
        $this->items[] = $component;

        return $this;
    }
}