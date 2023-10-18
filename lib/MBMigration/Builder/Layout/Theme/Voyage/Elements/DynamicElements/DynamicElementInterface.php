<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\DynamicElements;

interface DynamicElementInterface
{
    public function getElement(array $elementData);
}