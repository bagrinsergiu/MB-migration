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

        $imageStyle = $this->getDomElementStyles(
            $sectionSelector . ' div.photo-container > img',
            [
                'height',
                'width'
            ],
            $this->browserPage
        );

        foreach ($mbSection['items'] as $item)
        {
            if($item['category'] === 'photo')
            {
                $additionalParams = [
                    'sizeType' => 'custom',
                    'imageWidth' => $item['settings']['image']['width'] ?? 720,
                    'imageHeight' => $item['settings']['image']['height'] ?? 160,
                    'width' => (int) $imageStyle['width'] ?? 360,
                    'height' => (int) $imageStyle['height'] ?? 40,
                    'widthSuffix' => 'px',
                    'heightSuffix' => 'px',
                ];

                $brizySection->addImage(
                    $item,
                    $additionalParams,
                    $item['order_by']
                );
            }
        }

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

