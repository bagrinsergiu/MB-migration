<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Forms;

use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;

class Form extends DynamicElement
{

    public function getElement(array $elementData): bool
    {
        return $this->form();
    }

    private function form(): bool
    {
        return true;
    }
}