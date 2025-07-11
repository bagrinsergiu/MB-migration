<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\FooterElement;
use MBMigration\Builder\Utils\ColorConverter;

class Footer extends FooterElement
{

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $mbSection = $data->getMbSection();

        $sectionSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';

        $sectionStyle = $this->getDomElementStyles(
            $sectionSelector,
            [
                'border-top-color',
                'border-top-width'
            ],
            $this->browserPage
        );


        $sectionOptionStyle = [
            "borderStyle"=> "solid",
            "borderColorHex"=> ColorConverter::convertColorRgbToHex($sectionStyle["border-top-color"]),
            "borderColorOpacity"=> 1,
            "borderColorPalette"=> "",


            "borderWidthType"=> "ungrouped",
            "borderTopWidth"=> (int) $sectionStyle["border-top-width"],
            "borderRightWidth"=> 0,
            "borderBottomWidth"=> 0,
            "borderLeftWidth"=> 0,

        ];

        $section = $brizySection->getValue();

        foreach ($sectionOptionStyle as $key => $value) {
            $option= 'set_'. $key;

            $section->$option($value);
        }

        return $brizySection;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,
        ];
    }

}

