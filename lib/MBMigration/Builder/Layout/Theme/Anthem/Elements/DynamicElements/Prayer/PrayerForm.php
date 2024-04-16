<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Prayer;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;
use MBMigration\Core\Logger;

class PrayerForm extends DynamicElement
{
    public function getElement(array $elementData = [])
    {
        $this->sectionData = $elementData;
        return $this->PrayerForm($elementData);
    }

    private function PrayerForm(array $elementData)
    {
        Logger::instance()->info('Create bloc');

        $objBlock = new ItemBuilder();

        $decoded = $this->jsonDecode['dynamic']['PrayerForm'];

        $objBlock->newItem($decoded['main']);

        $block = $this->replaceIdWithRandom($objBlock->get());

        return json_encode($block);
    }
}