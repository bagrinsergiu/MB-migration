<?php

namespace MBMigration\Builder\Layout\Elements\DynamicElements\Forms;

use MBMigration\Builder\Layout\Elements\DynamicElements\DynamicElement;

class Form extends DynamicElement
{

    public function getElement(array $elementData)
    {
        return $this->form();
    }

    private function form()
    {
        return true;
    }
}