<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Events;

use MBMigration\Builder\Utils\ColorConverter;

class EventDetailsPageLayout extends \MBMigration\Builder\Layout\Common\Template\DetailPages\EventDetailsPageLayout
{
    protected function filterDetailPageStyles($sectionProperties, $sectionPalette): array
    {
        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

        $customSectionProperties = [
            'detailButtonBorderStyle' => 'solid',
            'detailButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonBorderColorOpacity' => 1,
            'detailButtonBorderColorPalette' => '',

            "detailButtonBorderWidthType" => "grouped",
            "detailButtonBorderWidth" => 1,
            "detailButtonBorderTopWidth" => 1,
            "detailButtonBorderRightWidth" => 1,
            "detailButtonBorderBottomWidth" => 1,
            "detailButtonBorderLeftWidth" => 1,

            'subscribeEventButtonBorderStyle' => 'solid',
            'subscribeEventButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'subscribeEventButtonBorderColorOpacity' => 1,
            'subscribeEventButtonBorderColorPalette' => '',

            "subscribeEventButtonBorderWidthType" => "grouped",
            "subscribeEventButtonBorderWidth" => 1,
            "subscribeEventButtonBorderTopWidth" => 1,
            "subscribeEventButtonBorderRightWidth" => 1,
            "subscribeEventButtonBorderBottomWidth" => 1,
            "subscribeEventButtonBorderLeftWidth" => 1,

            "hoverSubscribeEventButtonBorderStyle" => "solid",
            "hoverSubscribeEventButtonBorderColorHex" => $basicButtonStyleHover['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            "hoverSubscribeEventButtonBorderColorOpacity" => 1,
            "hoverSubscribeEventButtonBorderColorPalette" => "",
            "hoverSubscribeEventButtonBorderWidthType" => "grouped",
            "hoverSubscribeEventButtonBorderWidth" => 1,
            "hoverSubscribeEventButtonBorderTopWidth" => 1,
            "hoverSubscribeEventButtonBorderRightWidth" => 1,
            "hoverSubscribeEventButtonBorderBottomWidth" => 1,
            "hoverSubscribeEventButtonBorderLeftWidth" => 1,
        ];
        return array_merge($sectionProperties, $customSectionProperties);
    }

    protected function filterDetailPageStyles2($sectionProperties, $sectionPalette): array
    {
        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

        $customSectionProperties = [
            'detailButtonBorderStyle' => 'solid',
            'detailButtonBorderColorHex' => $basicButtonStyleHover['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonBorderColorOpacity' => 1,
            'detailButtonBorderColorPalette' => '',

            "detailButtonBorderWidthType" => "grouped",
            "detailButtonBorderWidth" => 1,
            "detailButtonBorderTopWidth" => 1,
            "detailButtonBorderRightWidth" => 1,
            "detailButtonBorderBottomWidth" => 1,
            "detailButtonBorderLeftWidth" => 1,

            'subscribeEventButtonBorderStyle' => 'solid',
            'subscribeEventButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'subscribeEventButtonBorderColorOpacity' => 1,
            'subscribeEventButtonBorderColorPalette' => '',
            "subscribeEventButtonBorderWidthType" => "grouped",
            "subscribeEventButtonBorderWidth" => 1,
            "subscribeEventButtonBorderTopWidth" => 1,
            "subscribeEventButtonBorderRightWidth" => 1,
            "subscribeEventButtonBorderBottomWidth" => 1,
            "subscribeEventButtonBorderLeftWidth" => 1,
        ];
        return array_merge($sectionProperties, $customSectionProperties);
    }
}
