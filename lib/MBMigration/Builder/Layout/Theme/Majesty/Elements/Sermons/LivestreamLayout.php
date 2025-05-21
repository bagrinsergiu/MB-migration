<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements\Sermons;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\ElementDataInterface;

class LivestreamLayout extends AbstractElement
{
    protected function internalTransformToItem(ElementDataInterface $data): array
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}
