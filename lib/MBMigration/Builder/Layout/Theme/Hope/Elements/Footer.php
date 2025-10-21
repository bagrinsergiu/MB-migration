<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\FooterElement;
use MBMigration\Builder\Utils\ColorConverter;

class Footer extends FooterElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();
        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);

        $sortItems = $this->sortItems($mbSection['items']);
        foreach ($sortItems as $i => $item) {
            $column = $this->getFooterColumnElement($brizySectionItemComponent, 0);

            $elementContext = $data->instanceWithBrizyComponentAndMBSection($item, $column);
            $this->handleItemMbSection($item, $elementContext);
        }

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $elementContext = $data->instanceWithBrizyComponentAndMBSection($mbSection, $brizySectionItemComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

//        $bgSectionStyles = $this->browserPage->evaluateScript(
//            'brizy.getStyles',
//            [
//                'selector' => 'body',
//                'styleProperties' => ['background-color', 'opacity'],
//                'families' => [],
//                'defaultFamily' => '',
//            ]
//        );
//
//        $convertColorRgbToHex = ColorConverter::convertColorRgbToHex($bgSectionStyles['data']['background-color']);
//        $rgba2opacity = ColorConverter::rgba2opacity($bgSectionStyles['data']['opacity']);
//        $brizySection->getItemWithDepth(0)
//            ->getValue()
//            ->set_bgColorHex($convertColorRgbToHex)
//            ->set_bgColorOpacity($rgba2opacity);

        return $brizySection;
    }


    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,

            "mobilePaddingType" => "grouped",
            "mobilePadding" => 20,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 20,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 20,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

}
