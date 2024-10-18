<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements\Forms;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class LeftFormWithText extends AbstractElement
{
    protected function internalTransformToItem(ElementContextInterface $data) : BrizyComponent
    {
        $section = new ItemBuilder();
        $section->newItem($this->brizyKit['main']);

        return $section->get();
    }
}
