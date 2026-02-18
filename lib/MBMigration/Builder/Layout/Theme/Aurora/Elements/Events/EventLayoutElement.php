<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class EventLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Events\EventLayoutElement
{
    /** @var array<string, array{subpalette: string, palette: array}> */
    private array $paletteCache = [];

    protected function getDetailsPageLayoutInstance(ElementContextInterface $data): EventDetailsPageLayout
    {
        return new EventDetailsPageLayout(
            $this->brizyKit['EventLayoutElement']['detail'],
            $this->getTopPaddingOfTheFirstElement(),
            $this->getMobileTopPaddingOfTheFirstElement(),
            $this->pageTDO,
            $data,
            $this->getSectionPaletteForContext($data)['subpalette']
        );
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $insideItemComponent = $this->getInsideItemComponent($brizySection);
        $textContainerComponent = $this->getTextContainerComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $insideElementContext = $data->instanceWithBrizyComponent($insideItemComponent);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->transformItem($insideElementContext, $textContainerComponent, $styleList);

        ['palette' => $sectionPalette] = $this->getSectionPaletteForContext($data);
        $this->handleItemBackground($brizySection, ['background-color' => $sectionPalette['bg'] ?? '#ffffff']);

        return $brizySection;
    }

    protected function getSectionPaletteForContext(ElementContextInterface $data): array
    {
        $mbSection = $data->getMbSection();
        $selector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';

        if (isset($this->paletteCache[$selector])) {
            return $this->paletteCache[$selector];
        }

        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);
        if ($sectionSubPalette === false) {
            $sectionSubPalette = $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1';
        }
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $result = ['subpalette' => $sectionSubPalette, 'palette' => $sectionPalette];
        $this->paletteCache[$selector] = $result;

        return $result;
    }

    protected function getDeepWidgetItem(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getInsideItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $this->getInsideItemComponent($brizySection);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function filterEventLayoutElementStyles($sectionProperties, ElementContextInterface $data): array
    {
        ['palette' => $sectionPalette] = $this->getSectionPaletteForContext($data);

        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover  = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

        $hasButtonStyles = !empty($basicButtonStyleNormal) && !empty($basicButtonStyleHover);

        $getButtonBg = function ($hover = false) use ($hasButtonStyles, $sectionPalette, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            if ($hasButtonStyles && !empty($style['background-color'])) {
                return $style['background-color'];
            }
            return $sectionPalette['btn-bg'] ?? $sectionPalette['btn'] ?? $sectionPalette['bg'] ?? '#f8f8f8';
        };

        $getButtonText = function ($hover = false) use ($hasButtonStyles, $sectionPalette, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            if ($hasButtonStyles && !empty($style['color'])) {
                return $style['color'];
            }
            return $sectionPalette['btn-text'] ?? $sectionPalette['text'] ?? '#333333';
        };

        $getButtonBgOpacity = function ($hover = false) use ($basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            return $style['background-color-opacity'] ?? 1;
        };

        $getButtonTextOpacity = function ($hover = false) use ($basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            return $style['color-opacity'] ?? 1;
        };

        $sectionProperties['calendarDaysBgColorHex']     = $sectionPalette['bg'] ?? $getButtonBg();
        $sectionProperties['calendarDaysBgColorOpacity']  = 1;
        $sectionProperties['calendarDaysBgColorPalette']  = '';

        $sectionProperties['calendarHeadingColorHex']     = $sectionPalette['text'] ?? $getButtonText();
        $sectionProperties['calendarHeadingColorOpacity']  = $getButtonTextOpacity();
        $sectionProperties['calendarHeadingColorPalette']  = '';

        $sectionProperties['calendarDaysColorHex']     = $sectionPalette['text'] ?? $getButtonText();
        $sectionProperties['calendarDaysColorOpacity']  = $getButtonTextOpacity();
        $sectionProperties['calendarDaysColorPalette']  = '';

        $sectionProperties['calendarBorderColorHex']     = $sectionPalette['text'] ?? $getButtonText() ?? '#e0e0e0';
        $sectionProperties['calendarBorderColorOpacity']  = 0.3;
        $sectionProperties['calendarBorderColorPalette']  = '';

        // Featured view colors
        $sectionProperties['titleColorHex']              = $sectionPalette['link'] ?? $getButtonText();
        $sectionProperties['titleColorOpacity']           = 1;
        $sectionProperties['titleColorPalette']           = '';

        $sectionProperties['hoverTitleColorHex']         = $sectionPalette['link'] ?? $getButtonText(true);
        $sectionProperties['hoverTitleColorOpacity']      = 1;
        $sectionProperties['hoverTitleColorPalette']      = '';

        $sectionProperties['detailButtonBgColorHex']     = $getButtonBg();
        $sectionProperties['detailButtonBgColorOpacity']  = $getButtonBgOpacity();
        $sectionProperties['detailButtonBgColorPalette']  = '';

        $sectionProperties['hoverDetailButtonBgColorHex']     = $getButtonBg(true);
        $sectionProperties['hoverDetailButtonBgColorOpacity']  = $getButtonBgOpacity(true);
        $sectionProperties['hoverDetailButtonBgColorPalette']  = '';

        $sectionProperties['detailButtonColorHex']       = $getButtonText();
        $sectionProperties['detailButtonColorOpacity']    = 1;
        $sectionProperties['detailButtonColorPalette']    = '';

        $sectionProperties['hoverDetailButtonColorHex']  = $getButtonText(true);
        $sectionProperties['hoverDetailButtonColorOpacity'] = 0.75;
        $sectionProperties['hoverDetailButtonColorPalette'] = '';

        $sectionProperties['previewColorHex']            = '#f8f8f8';
        $sectionProperties['previewColorOpacity']         = 1;
        $sectionProperties['previewColorPalette']         = '';

        $sectionProperties['hoverPreviewColorHex']       = '#f8f8f8';
        $sectionProperties['hoverPreviewColorOpacity']    = 0.8;
        $sectionProperties['hoverPreviewColorPalette']    = '';

        // List view colors
        $sectionProperties['listTitleColorHex']              = $sectionPalette['text'] ?? $getButtonText();
        $sectionProperties['listTitleColorOpacity']           = 1;
        $sectionProperties['listTitleColorPalette']           = '';

        $sectionProperties['listItemTitleColorHex']          = $sectionPalette['link'] ?? $getButtonText();
        $sectionProperties['listItemTitleColorOpacity']       = 1;
        $sectionProperties['listItemTitleColorPalette']       = '';

        $sectionProperties['hoverListItemTitleColorHex']     = $sectionPalette['link'] ?? $getButtonText(true);
        $sectionProperties['hoverListItemTitleColorOpacity']  = 1;
        $sectionProperties['hoverListItemTitleColorPalette']  = '';

        $sectionProperties['listItemMetaColorHex']           = $sectionPalette['text'] ?? $getButtonText();
        $sectionProperties['listItemMetaColorOpacity']        = 1;
        $sectionProperties['listItemMetaColorPalette']        = '';

        $sectionProperties['listItemDateColorHex']           = $sectionPalette['btn-text'] ?? $getButtonText(true);
        $sectionProperties['listItemDateColorOpacity']        = 0.75;
        $sectionProperties['listItemDateColorPalette']        = '';

        $sectionProperties['listItemDateBgColorHex']         = $getButtonBg();
        $sectionProperties['listItemDateBgColorOpacity']      = $getButtonBgOpacity();
        $sectionProperties['listItemDateBgColorType']         = 'solid';
        $sectionProperties['listItemDateBgColorPalette']      = '';

        $sectionProperties['groupingDateColorHex']           = $sectionPalette['text'] ?? $getButtonText();
        $sectionProperties['groupingDateColorOpacity']        = 1;
        $sectionProperties['groupingDateColorPalette']        = '';

        return $sectionProperties;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType"=> "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
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
