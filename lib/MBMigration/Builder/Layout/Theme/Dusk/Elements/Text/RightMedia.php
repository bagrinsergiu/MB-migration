<?php

namespace MBMigration\Builder\Layout\Theme\Dusk\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class RightMedia extends PhotoTextElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1, 0, 0)
            ->mobileSizeTypeOriginal()
            ->addMobileContentAlign();
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $sectionRow = $brizySection->getItemWithDepth(0);

        $imageColumn = $sectionRow->getItemWithDepth(1);
        $textColumn  = $sectionRow->getItemWithDepth(0);

        if ($imageColumn && $imageColumn->getType() === 'Column') {
            $imageColumn->getValue()
                ->set_paddingType('ungrouped')
                ->set_paddingTop(0)
                ->set_paddingTopSuffix('px')
                ->set_paddingBottom(0)
                ->set_verticalAlign('center')
                ->set_paddingBottomSuffix('px');
        }

        if ($textColumn && $textColumn->getType() === 'Column') {
            $textColumn->getValue()
                ->set_paddingType('ungrouped')
                ->set_paddingTop(100)
                ->set_paddingTopSuffix('px')
                ->set_paddingBottom(100)
                ->set_paddingBottomSuffix('px');
        }

        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getDonationsButtonOptions(): array
    {
        return [
            'mobilePaddingTop' => 10,
            'mobilePaddingRight' => 0,
            'mobilePaddingBottom' => 10,
            'mobilePaddingLeft' => 0,
        ];
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
            "mobilePaddingBottom" => 20,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
