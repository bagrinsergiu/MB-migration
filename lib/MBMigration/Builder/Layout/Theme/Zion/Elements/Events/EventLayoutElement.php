<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Events;

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
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function getStyleOptions($sectionPalette, $basicButtonStyleNormal, $basicButtonStyleHover, $fonts): array
    {
        return [
            'eventDetailPageButtonText' => 'Learn More',

            'titleTypographyLineHeight' => 1.8,

            'listItemMetaTypographyLineHeight' => 1.8,

            'dateTypographyLineHeight' => 1.8,
            'eventsTypographyLineHeight' => 1.8,

            'dateTypographyFontStyle' => '',
            'dateTypographyFontFamily' => 1.8,

            'previewColorHex' => '#f8f8f8',
            'previewColorOpacity' => 1,
            'previewColorPalette' => '',

            'hoverPreviewColorHex' => '#f8f8f8',
            'hoverPreviewColorOpacity' => 0.8,
            'hoverPreviewColorPalette' => '',

            'resultsHeadingColorHex' => $sectionPalette['text'],
            'resultsHeadingColorOpacity' => 1,
            'resultsHeadingColorPalette' => '',

            'resultsHeadingTypographyFontFamilyType' => $fonts,
            'resultsHeadingTypographyFontStyle' => '',

            'listPaginationArrowsColorHex' => $sectionPalette['text'],
            'listPaginationArrowsColorOpacity' => 1,
            'listPaginationArrowsColorPalette' => '',

            'hoverListPaginationArrowsColorHex' => $sectionPalette['text'],
            'hoverListPaginationArrowsColorOpacity' => 0.75,
            'hoverListPaginationArrowsColorPalette' => '',

            'listPaginationColorHex' => $sectionPalette['text'],
            'listPaginationColorOpacity' => 1,
            'listPaginationColorPalette' => '',

            'calendarDaysBgColorHex' => $sectionPalette['bg'] ?? $basicButtonStyleNormal['background-color'],
            'calendarDaysBgColorOpacity' => $basicButtonStyleNormal['background-color-opacity'] ?? 1,
            'calendarDaysBgColorPalette' => '',

            'calendarHeadingColorHex' => $sectionPalette['text'] ?? $basicButtonStyleNormal['color'],
            'calendarHeadingColorOpacity' => $basicButtonStyleNormal['color-opacity'] ?? 1,
            'calendarHeadingColorPalette' => '',

            'calendarDaysColorHex' => $sectionPalette['text'] ?? $basicButtonStyleNormal['color'],
            'calendarDaysColorOpacity' => $basicButtonStyleNormal['color-opacity'] ?? 1,
            'calendarDaysColorPalette' => '',

            'eventsColorHex' => $sectionPalette['link'],
            'eventsColorOpacity' => 1,
            'eventsColorPalette' => '',

            'hoverEventsColorHex' => $sectionPalette['link'],
            'hoverEventsColorOpacity' => 0.75,
            'hoverEventsColorPalette' => '',

            'listItemTitleColorHex' => $sectionPalette['link'],
            'listItemTitleColorOpacity' => 1,
            'listItemTitleColorPalette' => '',

            'hoverListItemTitleColorHex' => $sectionPalette['link'],
            'hoverListItemTitleColorOpacity' => 0.75,
            'hoverListItemTitleColorPalette' => '',

            'listItemMetaColorHex' => $sectionPalette['text'],
            'listItemMetaColorOpacity' => 1,
            'listItemMetaColorPalette' => '',

            'listItemDateColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleHover['color'] ?? $sectionPalette['text'],
            'listItemDateColorOpacity' => 0.75 ?? $basicButtonStyleHover['color-opacity'] ?? 1,
            'listItemDateColorPalette' => '',

            'listTitleColorHex' => $sectionPalette['text'],
            'listTitleColorOpacity' => 1,
            'listTitleColorPalette' => '',

            'groupingDateColorHex' => $sectionPalette['text'],
            'groupingDateColorOpacity' => 1,
            'groupingDateColorPalette' => '',

            'hoverTitleColorHex' => $sectionPalette['link'],
            'hoverTitleColorOpacity' => 0.75,
            'hoverTitleColorPalette' => '',

            'titleColorHex' => $sectionPalette['link'],
            'titleColorOpacity' => 1,
            'titleColorPalette' => '',

            'dateColorHex' => $sectionPalette['text'],
            'dateColorOpacity' => 1,
            'dateColorPalette' => '',

            'listItemDateBgColorHex' => $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'listItemDateBgColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ?? 1,
            'listItemDateBgColorType' => 'solid',
            'listItemDateBgColorPalette' => '',

            'detailButtonBgColorHex' => $basicButtonStyleNormal['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'detailButtonBgColorOpacity' => $basicButtonStyleNormal['background-color-opacity'] ?? 1,
            'detailButtonBgColorPalette' => '',

            "hoverDetailButtonBgColorType" => "solid",
            'hoverDetailButtonBgColorHex' => $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'hoverDetailButtonBgColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ?? 1,
            'hoverDetailButtonBgColorPalette' => '',

//            'detailButtonBorderStyle' => 'solid',
//            'detailButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
//            'detailButtonBorderColorOpacity' => $basicButtonStyleNormal['border-top-color-opacity'] ?? 1,
//            'detailButtonBorderColorPalette' => '',
//
//            "detailButtonBorderWidthType" => "grouped",
//            "detailButtonBorderWidth" => 1,
//            "detailButtonBorderTopWidth" => 1,
//            "detailButtonBorderRightWidth" => 1,
//            "detailButtonBorderBottomWidth" => 1,
//            "detailButtonBorderLeftWidth" => 1,

            'detailButtonColorHex' => $basicButtonStyleNormal['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $basicButtonStyleHover['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75 ?? $basicButtonStyleHover['color-opacity'] ?? 1,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonGradientColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonGradientColorOpacity' => 1,
            'detailButtonGradientColorPalette' => '',

            'hoverViewColorHex' => $sectionPalette['text'],
            'hoverViewColorOpacity' => 1,
            'hoverViewColorPalette' => '',

            'viewColorHex' => $sectionPalette['text'],
            'viewColorOpacity' => 0.7,
            'viewColorPalette' => '',

            'activeViewColorHex' => $sectionPalette['text'],
            'activeViewColorOpacity' => 1,
            'activeViewColorPalette' => '',

            'layoutViewTypographyFontFamily' => $fonts,
            'layoutViewTypographyFontStyle' => '',
            'layoutViewTypographyFontFamilyType' => 'upload',

            "detailButtonBorderStyle" => $basicButtonStyleNormal['border-style'] ?? "solid",
            "detailButtonBorderColorHex" =>  $basicButtonStyleNormal['border-top-color'],
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
