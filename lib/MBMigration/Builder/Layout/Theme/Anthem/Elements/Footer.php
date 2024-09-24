<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Element\FooterElement;

class Footer extends FooterElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "margin-left" => 0,
            "margin-right" => 0,
        ];
    }

}
