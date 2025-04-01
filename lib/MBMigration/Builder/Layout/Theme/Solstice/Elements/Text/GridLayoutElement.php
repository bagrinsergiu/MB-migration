<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GridLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\GridLayoutElement
{
    protected function getItemsPerRow(): int
    {
        return 3;
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent;
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
