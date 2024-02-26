<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Element;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class TabsLayout extends AbstractElement
{
    protected function internalTransformToItem(ElementDataInterface $data): array
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}