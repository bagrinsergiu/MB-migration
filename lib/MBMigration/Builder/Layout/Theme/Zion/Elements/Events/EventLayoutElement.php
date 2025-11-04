<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Events;

use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class EventLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Events\EventLayoutElement
{
    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType" => "ungrouped",
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

    protected function filterEventLayoutElementStyles($sectionProperties, ElementContextInterface $data): array
    {
        $mbSection = $data->getMbSection();
        $selector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

        $customSectionProperties = [
           "detailButtonBorderStyle" => $basicButtonStyleNormal['border-style'] ?? "solid",
            "detailButtonBorderColorHex" => $basicButtonStyleNormal['border-top-color'],
            "detailButtonBorderColorOpacity" => $basicButtonStyleNormal['border-top-color-opacity'] ?? 1,
            "detailButtonBorderColorPalette" => "",

            "detailButtonBorderWidthType" => "grouped",
            "detailButtonBorderWidth" => $basicButtonStyleNormal['border-width'],
            "detailButtonBorderTopWidth" => $basicButtonStyleNormal['border-width'],
            "detailButtonBorderRightWidth" => $basicButtonStyleNormal['border-width'],
            "detailButtonBorderBottomWidth" => $basicButtonStyleNormal['border-width'],
            "detailButtonBorderLeftWidth" => $basicButtonStyleNormal['border-width'],

            "hoverDetailButtonBorderStyle" => $basicButtonStyleHover['border-style'] ?? "solid",
            "hoverDetailButtonBorderColorHex" => $basicButtonStyleHover['background-color'],
            "hoverDetailButtonBorderColorOpacity" => 1,
            "hoverDetailButtonBorderColorPalette" => "",

            "hoverDetailButtonBorderWidthType" => "grouped",
            "hoverDetailButtonBorderWidth" => $basicButtonStyleNormal['border-width'],
            "hoverDetailButtonBorderTopWidth" => $basicButtonStyleNormal['border-width'],
            "hoverDetailButtonBorderRightWidth" => $basicButtonStyleNormal['border-width'],
            "hoverDetailButtonBorderBottomWidth" => $basicButtonStyleNormal['border-width'],
            "hoverDetailButtonBorderLeftWidth" => $basicButtonStyleNormal['border-width'],
        ];
        return array_merge($sectionProperties, $customSectionProperties);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 75;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
