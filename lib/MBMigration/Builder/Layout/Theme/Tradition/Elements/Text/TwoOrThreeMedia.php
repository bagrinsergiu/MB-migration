<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;

class TwoOrThreeMedia extends PhotoTextElement
{
    private $imageCount = 0;

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, $this->imageCount++, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getItemImageParentComponent(BrizyComponent $brizySection, $photoPosition = null)
    {
        return $brizySection->getItemWithDepth(0, 1, $this->imageCount - 1, 0, 0);
    }
}
