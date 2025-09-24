<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements\Sermons;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;

class LivestreamLayout extends AbstractElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}
