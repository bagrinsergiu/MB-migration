<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Events;

use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Template\DetailPages\EventDetailsPageLayout;

class EventLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Events\EventLayoutElement
{
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

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 200;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 200;
    }

    protected function getDetailsPageLayoutInstance(ElementContextInterface $data)
    {
        $mbSection = $data->getMbSection();

        return new EventDetailsPageLayout(
            $this->brizyKit['EventLayoutElement']['detail'],
            $this->getTopPaddingOfTheFirstElement() - 40,
            $this->getMobileTopPaddingOfTheFirstElement(),
            $this->pageTDO,
            $data,
            $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1'
        );
    }

    protected function filterEventLayoutElementStyles($sectionProperties, ElementContextInterface $data): array
    {
        $mbSection = $data->getMbSection();
        $selector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);
        if ($sectionSubPalette === false) {
            $sectionSubPalette = $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1';
        }
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $sectionProperties['filterBgColorHex'] = '#e5e5e5';
        $sectionProperties['filterBgColorOpacity'] = 1;
        $sectionProperties['filterBgColorPalette'] = '';

        $sectionProperties['hoverTitleColorHex'] = $sectionPalette['link'] ?? '#0052a3';
        $sectionProperties['hoverTitleColorOpacity'] = 1;
        $sectionProperties['hoverTitleColorPalette'] = '';

        $sectionProperties['hoverListItemTitleColorHex'] = $sectionPalette['link'] ?? '#0052a3';
        $sectionProperties['hoverListItemTitleColorOpacity'] = 1;
        $sectionProperties['hoverListItemTitleColorPalette'] = '';

        $sectionProperties['detailButtonBorderStyle'] = 'solid';
        $sectionProperties['detailButtonBorderColorHex'] = $sectionPalette['link'] ?? '#0066cc';
        $sectionProperties['detailButtonBorderColorOpacity'] = 1;
        $sectionProperties['detailButtonBorderColorPalette'] = '';
        $sectionProperties['detailButtonBorderWidthType'] = 'grouped';
        $sectionProperties['detailButtonBorderWidth'] = 2;
        $sectionProperties['detailButtonBorderTopWidth'] = 2;
        $sectionProperties['detailButtonBorderRightWidth'] = 2;
        $sectionProperties['detailButtonBorderBottomWidth'] = 2;
        $sectionProperties['detailButtonBorderLeftWidth'] = 2;

        return $sectionProperties;
    }
}
