<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\DynamicElements\Forms;

use MBMigration\Builder\Layout\Theme\Voyage\Elements\DynamicElements\DynamicElement;

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