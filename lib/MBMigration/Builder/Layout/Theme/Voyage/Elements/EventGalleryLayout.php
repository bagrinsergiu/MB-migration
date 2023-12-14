<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class EventGalleryLayout extends AbstractElement
{
    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}