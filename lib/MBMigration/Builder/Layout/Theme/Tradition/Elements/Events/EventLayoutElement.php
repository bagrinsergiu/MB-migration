<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
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

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getDetailsPageLayoutInstance(ElementContextInterface $data)
    {
        $mbSection = $data->getMbSection();
        return new EventDetailsPageLayout(
            $this->brizyKit['EventLayoutElement']['detail'],
            $this->getTopPaddingOfTheFirstElement()-40,
            $this->getMobileTopPaddingOfTheFirstElement(),
            $this->pageTDO,
            $data,
            $sectionSubPalette ?? $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1'
        );
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set('fullHeight', 'auto');
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
            'detailButtonBorderStyle' => 'solid',
            'detailButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonBorderColorOpacity' => $basicButtonStyleNormal['border-top-color-opacity'] ?? 1,
            'detailButtonBorderColorPalette' => '',

            "detailButtonBorderWidthType" => "grouped",
            "detailButtonBorderWidth" => 1,
            "detailButtonBorderTopWidth" => 1,
            "detailButtonBorderRightWidth" => 1,
            "detailButtonBorderBottomWidth" => 1,
            "detailButtonBorderLeftWidth" => 1,
        ];
        return array_merge($sectionProperties, $customSectionProperties);
    }
}
