<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;

class SmallGroupsList extends AbstractElement
{
    public function transformToItem(ElementDataInterface $data): array
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}