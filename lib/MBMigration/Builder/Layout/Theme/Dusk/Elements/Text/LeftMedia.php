<?php

namespace MBMigration\Builder\Layout\Theme\Dusk\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class LeftMedia extends PhotoTextElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 0,0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
