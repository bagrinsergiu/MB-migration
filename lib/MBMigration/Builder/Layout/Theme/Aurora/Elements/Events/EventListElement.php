<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class EventListElement extends EventListLayout
{
    use CssPropertyExtractorAware;
    /**
     * Override to add widget styling logic
     * Flow: collect data -> normalize styles -> determine parameters -> apply styles
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $selector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';
        
        // 1. Collect all data from source section
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);
        // getNodeSubPalette returns string or false, handle both cases
        if ($sectionSubPalette === false) {
            $mbSection = $data->getMbSection();
            $sectionSubPalette = $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1';
        }
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);
        
        // Get button styles from page
        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();
        
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);
        
        // Get fonts
        $fonts = FontsController::getFontsFamilyFromName('main_text');
        
        // 2. Normalize obtained styles
        $hasButtonStyles = !empty($basicButtonStyleNormal) && !empty($basicButtonStyleHover);
        
        // Helper functions for button colors with fallbacks
        $getButtonBg = function($hover = false) use ($hasButtonStyles, $sectionPalette, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            if ($hasButtonStyles && !empty($style['background-color'])) {
                return $style['background-color'];
            }
            return $sectionPalette['btn-bg'] ?? $sectionPalette['btn'] ?? $sectionPalette['bg'] ?? '#f8f8f8';
        };
        
        $getButtonText = function($hover = false) use ($hasButtonStyles, $sectionPalette, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            if ($hasButtonStyles && !empty($style['color'])) {
                return $style['color'];
            }
            return $sectionPalette['btn-text'] ?? $sectionPalette['text'] ?? '#333333';
        };
        
        $getButtonBgOpacity = function($hover = false) use ($hasButtonStyles, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            return $style['background-color-opacity'] ?? 1;
        };
        
        $getButtonTextOpacity = function($hover = false) use ($hasButtonStyles, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            return $style['color-opacity'] ?? 1;
        };
        
        // 3. Determine which parameters are needed (grouped by category)
        // Get widget component - use EventListElement widget (parent uses wrong widget)
        $brizyWidget = new BrizyComponent(json_decode($this->brizyKit['EventListElement']['widget'], true));
        
        // Get base section from parent (but we'll add widget ourselves)
        $brizySection = parent::internalTransformToItem($data);
        
        // Base properties that are always needed
        $sectionProperties = [
            // Typography properties
            'titleTypographyFontFamily' => $fonts,
            'titleTypographyFontStyle' => '',
            'titleTypographyFontFamilyType' => 'upload',
            'titleTypographyLineHeight' => 1.8,
            
            'dateTypographyFontFamily' => $fonts,
            'dateTypographyFontStyle' => '',
            'dateTypographyFontFamilyType' => 'upload',
            'dateTypographyLineHeight' => 1.8,
            
            'typographyFontFamily' => $fonts,
            'typographyFontStyle' => '',
            'typographyFontFamilyType' => 'upload',
            'eventsTypographyLineHeight' => 1.8,
            
            'listItemMetaTypographyLineHeight' => 1.8,
            'previewTypographyFontStyle' => 'paragraph',
            
            'paginationTypographyFontStyle' => 'heading6',
            'detailButtonTypographyFontStyle' => 'button',
            'registerButtonTypographyFontStyle' => 'button',
            
            // Color properties - using section palette and button styles
            'titleColorHex' => $sectionPalette['link'] ?? '#0066cc',
            'titleColorOpacity' => 1,
            'titleColorPalette' => '',
            
            'hoverTitleColorHex' => $sectionPalette['link'] ?? '#0052a3',
            'hoverTitleColorOpacity' => 1,
            'hoverTitleColorPalette' => '',
            
            'dateColorHex' => $sectionPalette['text'],
            'dateColorOpacity' => 1,
            'dateColorPalette' => '',
            
            'colorHex' => $sectionPalette['text'],
            'colorOpacity' => 1,
            'colorPalette' => '',
            
            'listTitleColorHex' => $sectionPalette['text'],
            'listTitleColorOpacity' => 1,
            'listTitleColorPalette' => '',
            
            'listItemTitleColorHex' => $sectionPalette['link'] ?? '#0066cc',
            'listItemTitleColorOpacity' => 1,
            'listItemTitleColorPalette' => '',
            
            'hoverListItemTitleColorHex' => $sectionPalette['link'] ?? '#0052a3',
            'hoverListItemTitleColorOpacity' => 1,
            'hoverListItemTitleColorPalette' => '',
            
            'listItemMetaColorHex' => $sectionPalette['text'],
            'listItemMetaColorOpacity' => 1,
            'listItemMetaColorPalette' => '',
            
            'listItemDateColorHex' => $getButtonText(true),
            'listItemDateColorOpacity' => $getButtonTextOpacity(true),
            'listItemDateColorPalette' => '',
            
            'listItemDateBgColorHex' => $getButtonBg(true),
            'listItemDateBgColorOpacity' => $getButtonBgOpacity(true),
            'listItemDateBgColorType' => 'solid',
            'listItemDateBgColorPalette' => '',
            
            'groupingDateColorHex' => $sectionPalette['text'],
            'groupingDateColorOpacity' => 1,
            'groupingDateColorPalette' => '',
            
            'eventsColorHex' => $sectionPalette['link'] ?? '#0066cc',
            'eventsColorOpacity' => 1,
            'eventsColorPalette' => '',
            
            'hoverEventsColorHex' => $sectionPalette['link'] ?? '#0052a3',
            'hoverEventsColorOpacity' => 1,
            'hoverEventsColorPalette' => '',
            
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
            
            // Pagination colors
            'listPaginationColorHex' => $sectionPalette['text'],
            'listPaginationColorOpacity' => 1,
            'listPaginationColorPalette' => '',
            
            'hoverListPaginationColorHex' => $sectionPalette['text'],
            'hoverListPaginationColorOpacity' => 0.75,
            'hoverListPaginationColorPalette' => '',
            
            'activePaginationColorHex' => $sectionPalette['text'],
            'activePaginationColorOpacity' => 1,
            'activePaginationColorPalette' => '',
            
            'listPaginationArrowsColorHex' => $sectionPalette['text'],
            'listPaginationArrowsColorOpacity' => 1,
            'listPaginationArrowsColorPalette' => '',
            
            'hoverListPaginationArrowsColorHex' => $sectionPalette['text'],
            'hoverListPaginationArrowsColorOpacity' => 0.75,
            'hoverListPaginationArrowsColorPalette' => '',
            
            // Meta links colors
            'metaLinksColorHex' => $sectionPalette['link'] ?? '#0066cc',
            'metaLinksColorOpacity' => 1,
            'metaLinksColorPalette' => '',
            
            'hoverMetaLinksColorHex' => $sectionPalette['link'] ?? '#0052a3',
            'hoverMetaLinksColorOpacity' => 1,
            'hoverMetaLinksColorPalette' => '',
            
            // Button colors
            'detailButtonBgColorHex' => $getButtonBg(),
            'detailButtonBgColorOpacity' => $getButtonBgOpacity(),
            'detailButtonBgColorPalette' => '',
            'detailButtonBgColorType' => 'solid',
            
            'hoverDetailButtonBgColorHex' => $getButtonBg(true),
            'hoverDetailButtonBgColorOpacity' => $getButtonBgOpacity(true),
            'hoverDetailButtonBgColorPalette' => '',
            
            'detailButtonColorHex' => $getButtonText(),
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',
            
            'hoverDetailButtonColorHex' => $getButtonText(true),
            'hoverDetailButtonColorOpacity' => $getButtonTextOpacity(true),
            'hoverDetailButtonColorPalette' => '',
            
            'detailButtonGradientColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonGradientColorOpacity' => 1,
            'detailButtonGradientColorPalette' => '',
            
            // Register button colors
            'registerButtonBgColorHex' => $getButtonBg(),
            'registerButtonBgColorOpacity' => $getButtonBgOpacity(),
            'registerButtonBgColorPalette' => '',
            
            'hoverRegisterButtonBgColorHex' => $getButtonBg(true),
            'hoverRegisterButtonBgColorOpacity' => $getButtonBgOpacity(true),
            'hoverRegisterButtonBgColorPalette' => '',
            
            'registerButtonColorHex' => $getButtonText(),
            'registerButtonColorOpacity' => 1,
            'registerButtonColorPalette' => '',
            
            'hoverRegisterButtonColorHex' => $getButtonText(true),
            'hoverRegisterButtonColorOpacity' => $getButtonTextOpacity(true),
            'hoverRegisterButtonColorPalette' => '',
            
            // View colors
            'viewColorHex' => $sectionPalette['text'],
            'viewColorOpacity' => 0.7,
            'viewColorPalette' => '',
            
            'hoverViewColorHex' => $sectionPalette['text'],
            'hoverViewColorOpacity' => 1,
            'hoverViewColorPalette' => '',
            
            'activeViewColorHex' => $sectionPalette['text'],
            'activeViewColorOpacity' => 1,
            'activeViewColorPalette' => '',
            
            'layoutViewTypographyFontFamily' => $fonts,
            'layoutViewTypographyFontStyle' => '',
            'layoutViewTypographyFontFamilyType' => 'upload',
            
            // Calendar colors
            'calendarDaysBgColorHex' => $sectionPalette['bg'] ?? $getButtonBg(),
            'calendarDaysBgColorOpacity' => $getButtonBgOpacity(),
            'calendarDaysBgColorPalette' => '',
            
            'calendarHeadingColorHex' => $sectionPalette['text'] ?? $getButtonText(),
            'calendarHeadingColorOpacity' => $getButtonTextOpacity(),
            'calendarHeadingColorPalette' => '',
            
            'calendarDaysColorHex' => $sectionPalette['text'] ?? $getButtonText(),
            'calendarDaysColorOpacity' => $getButtonTextOpacity(),
            'calendarDaysColorPalette' => '',
            
            'calendarBorderStyle' => 'solid',
            'calendarBorderColorHex' => $sectionPalette['text'] ?? $getButtonText() ?? '#e0e0e0',
            'calendarBorderColorOpacity' => 0.3,
            'calendarBorderColorPalette' => '',
            'calendarBorderWidth' => 1,
            'calendarBorderWidthType' => 'grouped',
            
            // Filter background
            'filterBgColorHex' => $sectionPalette['bg'] ?? $getButtonBg() ?? '#f8f8f8',
            'filterBgColorOpacity' => 1,
            'filterBgColorPalette' => '',
            
            // Detail page button text
            'detailPageButtonText' => 'Learn More',
        ];
        
        // 4. Filter and apply only needed parameters (can be overridden in child classes)
        $sectionProperties = $this->filterEventListElementStyles($sectionProperties, $data);
        
        // Apply styles to widget
        $widgetItem = $this->getWidgetItem($brizyWidget);
        foreach ($sectionProperties as $key => $value) {
            if ($value !== null) {
                $method = 'set_' . $key;
                if (method_exists($widgetItem->getValue(), $method)) {
                    $widgetItem->getValue()->$method($value);
                }
            }
        }
        
        // Add widget to section (parent doesn't add it, so we do it here)
        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $existingItems = $sectionItemComponent->getValue()->get_items();
        $sectionItemComponent->getValue()->set_items(array_merge($existingItems, [$brizyWidget]));
        
        return $brizySection;
    }
    
    /**
     * Get widget item from brizy widget component
     */
    protected function getWidgetItem(BrizyComponent $brizyWidget): BrizyComponent
    {
        return $brizyWidget->getItemWithDepth(0);
    }
    
    /**
     * Filter event list element styles - can be overridden to customize
     */
    protected function filterEventListElementStyles(array $sectionProperties, ElementContextInterface $data): array
    {
        return $sectionProperties;
    }
    
    /**
     * Get properties for main section padding/margins
     */
    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType" => "ungrouped",
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
            
            "mobilePaddingType" => "ungrouped",
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
    
    /**
     * Get top padding of the first element
     */
    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }
    
    /**
     * Get mobile top padding of the first element
     */
    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }
}
