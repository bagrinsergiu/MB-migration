<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;

class FullWidthForm extends AbstractElement
{
    protected function internalTransformToItem(ElementDataInterface $data): array
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}