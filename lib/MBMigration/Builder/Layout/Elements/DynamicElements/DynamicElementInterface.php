<?php

namespace MBMigration\Builder\Layout\Elements\DynamicElements;

interface DynamicElementInterface
{
    public function getElement(array $elementData);
}