<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;

class EventGalleryLayout extends AbstractElement
{
    protected function internalTransformToItem(ElementDataInterface $data): array
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}