<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\Elements\Text\TwoHorizontalTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class TwoHorizontalText extends TwoHorizontalTextElement
{

    use ShadowAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mainBrizySection = parent::internalTransformToItem($data);

        $mbSection = $data->getMbSection();

        $sectionSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"] .group';
        $backgroundColorStyles = ColorConverter::convertColorRgbToHex(
            $this->getDomElementStyles($sectionSelector , ['border-right-color'], $this->browserPage));

        $group0 = [
            "borderStyle" => "solid",
            "borderColorHex" => $backgroundColorStyles['border-right-color'],
            "borderColorOpacity" => 1,
            "borderColorPalette" => "",

            "borderWidthType" => 'ungrouped',
            "borderTopWidth" => 0,
            "borderWidth" => 0,
            "borderRightWidth" => 2,
            "borderBottomWidth" => 0,
            "borderLeftWidth" => 0,

            "paddingType" => "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 5,
            "paddingTopSuffix" => "px",
            "paddingRight" => 30,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 5,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 5,
            "paddingLeftSuffix" => "px",

        ];

        $group1 = [
            "paddingType" => "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 5,
            "paddingTopSuffix" => "px",
            "paddingRight" => 5,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 5,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 30,
            "paddingLeftSuffix" => "px",
        ];

        foreach ($group0 as $key => $value) {
            $method = "set_".$key;
            $mainBrizySection->getItemWithDepth(0, 0, 0)->getValue()
                ->$method($value);
        }

        foreach ($group1 as $key => $value) {
            $method = "set_".$key;
            $mainBrizySection->getItemWithDepth(0, 0, 1)->getValue()
                ->$method($value);
        }

        $this->handleShadow($mainBrizySection);

        return $mainBrizySection;
    }

    protected function getLeftColumnComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getRightColumnComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 100;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 100;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType" => "ungrouped",
            "paddingTop" => 90,
            "paddingBottom" => 90,

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 70,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 70,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
