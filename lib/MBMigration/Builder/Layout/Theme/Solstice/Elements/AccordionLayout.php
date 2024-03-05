<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class AccordionLayout extends AbstractElement
{
    protected function internalTransformToItem(ElementContextInterface $data): array
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}