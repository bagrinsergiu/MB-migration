<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GridLayout extends \MBMigration\Builder\Layout\Common\Element\GridLayout
{
    protected function getItemsPerRow(): int
    {
        return 4;
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0);
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
