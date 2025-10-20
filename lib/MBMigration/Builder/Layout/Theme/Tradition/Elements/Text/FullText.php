<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;

class FullText extends FullTextElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0,0,0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }
}
