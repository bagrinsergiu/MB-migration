<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements;

interface DynamicElementInterface
{
    public function getElement(array $elementData);
}