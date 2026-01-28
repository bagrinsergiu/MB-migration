<?php

namespace MBMigration\Builder\Layout\Common\Elements\Events;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\Component\Button;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Template\DetailPages\EventDetailsPageLayout;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class EventLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use BrizyQueryBuilderAware;
    use CssPropertyExtractorAware;
    use Button;

    /**
     * @param $brizyKit
     * @param BrowserPageInterface $browserPage
     * @param QueryBuilder $queryBuilder
     */
    public function __construct($brizyKit, BrowserPageInterface $browserPage, QueryBuilder $queryBuilder)
    {
        parent::__construct($brizyKit, $browserPage);
        $this->setQueryBuilder($queryBuilder);
    }

    protected function getDetailsPageLayoutInstance(ElementContextInterface $data)
    {
        $mbSection = $data->getMbSection();
        return new EventDetailsPageLayout(
            $this->brizyKit['EventLayoutElement']['detail'],
            $this->getTopPaddingOfTheFirstElement(),
            $this->getMobileTopPaddingOfTheFirstElement(),
            $this->pageTDO,
            $data,
            $sectionSubPalette ?? $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1'
        );
    }

    /**
     * @throws BadJsonProvided
     * @throws BrowserScriptException
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['EventLayoutElement']['main'], true));
        $brizyWidget = new BrizyComponent(json_decode($this->brizyKit['EventLayoutElement']['widget'], true));

        $mbSection = $data->getMbSection();
        $selector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';

        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $DetailsPageLayout = $this->getDetailsPageLayoutInstance($data);

        $fonts = FontsController::getFontsFamilyFromName('main_text');

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent, [], $this->getAdditionalTopPaddingOfTheFirstElement());

        $elementContext = $data->instanceWithBrizyComponent($this->getTextContainerComponent($brizySection));
        if (!empty($mbSection['head'])) {
            $this->handleRichTextHead($elementContext, $this->browserPage);
        } else {
            $this->handleRichTextItems($elementContext, $this->browserPage);
        }

        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();

        $detailsSection = $DetailsPageLayout->setStyleDetailPage($sectionPalette);

        $detailCollectionItem = $this->createDetailsCollectionItem(
            $data->getThemeContext()->getBrizyCollectionTypeURI(),
            $detailsSection
        );

        $entityTypeId = $detailCollectionItem['type']['id'] ?? $detailCollectionItem['type']['slug'] ?? '';
        $entityId = $detailCollectionItem['id'] ?? '';
        
        if (empty($entityTypeId) || empty($entityId)) {
            Logger::instance()->warning('EventLayoutElement: Missing required collection item data', [
                'entityTypeId' => $entityTypeId,
                'entityId' => $entityId,
                'collectionItem' => $detailCollectionItem
            ]);
            return $brizyWidget;
        }
        
        $placeholder = base64_encode('{{ brizy_dc_url_post entityType="' . $entityTypeId . '" entityId="' . $entityId . '" }}');
        $this->getDetailsLinksComponent($brizyWidget)
            ->getValue()
            ->set_eventDetailPageSource($collectionTypeUri)
            ->set_eventDetailPage("{{placeholder content='$placeholder'}}");

        switch ($mbSection['typeSection']) {
            case 'event-tile-layout':
                $eventTabs = [
                    'featuredViewOrder' => 1,
                    "listViewOrder" => 2,
                    'calendarViewOrder' => 3,
                ];
                break;
            default:
                $eventTabs = [
                    'featuredViewOrder' => 3,
                    "listViewOrder" => 2,
                    'calendarViewOrder' => 1,
                ];
                break;
        }

        $featured = [
            "howManyFeatured" => 6
        ];

        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

        // Check if button styles are empty (no buttons found in section)
        // If empty, prioritize subpalette colors over button styles
        $hasButtonStyles = !empty($basicButtonStyleNormal) && !empty($basicButtonStyleHover);
        
        // Helper function to get button background color with proper fallback
        $getButtonBg = function($hover = false) use ($hasButtonStyles, $sectionPalette, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            if ($hasButtonStyles && !empty($style['background-color'])) {
                return $style['background-color'];
            }
            return $sectionPalette['btn-bg'] ?? $sectionPalette['btn'] ?? $sectionPalette['bg'] ?? '#f8f8f8';
        };
        
        // Helper function to get button text color with proper fallback
        $getButtonText = function($hover = false) use ($hasButtonStyles, $sectionPalette, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            if ($hasButtonStyles && !empty($style['color'])) {
                return $style['color'];
            }
            return $sectionPalette['btn-text'] ?? $sectionPalette['text'] ?? '#333333';
        };
        
        // Helper function to get button background opacity
        $getButtonBgOpacity = function($hover = false) use ($hasButtonStyles, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            return $style['background-color-opacity'] ?? 1;
        };
        
        // Helper function to get button text opacity
        $getButtonTextOpacity = function($hover = false) use ($hasButtonStyles, $basicButtonStyleNormal, $basicButtonStyleHover) {
            $style = $hover ? $basicButtonStyleHover : $basicButtonStyleNormal;
            return $style['color-opacity'] ?? 1;
        };

        $sectionProperties = [
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

            'filterBgColorHex' => $sectionPalette['bg'] ?? $getButtonBg() ?? '#f8f8f8',
            'filterBgColorOpacity' => 1,
            'filterBgColorPalette' => '',

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

            'eventsColorHex' => $sectionPalette['link'] ?? '#0066cc',
            'eventsColorOpacity' => 1,
            'eventsColorPalette' => '',

            'hoverEventsColorHex' => $sectionPalette['link'] ?? '#0052a3',
            'hoverEventsColorOpacity' => 1,
            'hoverEventsColorPalette' => '',

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

            'listTitleColorHex' => $sectionPalette['text'],
            'listTitleColorOpacity' => 1,
            'listTitleColorPalette' => '',

            'groupingDateColorHex' => $sectionPalette['text'],
            'groupingDateColorOpacity' => 1,
            'groupingDateColorPalette' => '',

            'hoverTitleColorHex' => $sectionPalette['link'] ?? '#0052a3',
            'hoverTitleColorOpacity' => 1,
            'hoverTitleColorPalette' => '',

            'titleColorHex' => $sectionPalette['link'] ?? '#0066cc',
            'titleColorOpacity' => 1,
            'titleColorPalette' => '',

            'dateColorHex' => $sectionPalette['text'],
            'dateColorOpacity' => 1,
            'dateColorPalette' => '',

            'listItemDateBgColorHex' => $getButtonBg(true),
            'listItemDateBgColorOpacity' => $getButtonBgOpacity(true),
            'listItemDateBgColorType' => 'solid',
            'listItemDateBgColorPalette' => '',

            'detailButtonBgColorHex' => $getButtonBg(),
            'detailButtonBgColorOpacity' => $getButtonBgOpacity(),
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $getButtonBg(true),
            'hoverDetailButtonBgColorOpacity' => $getButtonBgOpacity(true),
            'hoverDetailButtonBgColorPalette' => '',

//            'detailButtonBorderStyle' => 'solid',
//            'detailButtonBorderColorHex' => $getButtonText() ?? $sectionPalette['text'],
//            'detailButtonBorderColorOpacity' => 1,
//            'detailButtonBorderColorPalette' => '',
//
//            "detailButtonBorderWidthType" => "grouped",
//            "detailButtonBorderWidth" => 1,
//            "detailButtonBorderTopWidth" => 1,
//            "detailButtonBorderRightWidth" => 1,
//            "detailButtonBorderBottomWidth" => 1,
//            "detailButtonBorderLeftWidth" => 1,

            'detailButtonColorHex' => $getButtonText(),
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $getButtonText(true),
            'hoverDetailButtonColorOpacity' => $getButtonTextOpacity(true),
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
        ];

        $sectionProperties = array_merge($sectionProperties, $eventTabs, $featured);

        $sectionProperties = $this->filterEventLayoutElementStyles($sectionProperties, $data);

        foreach ($sectionProperties as $key => $value) {
            $properties = 'set_' . $key;
            $this->getDeepWidgetItem($brizyWidget)
                ->getValue()
                ->$properties($value);
        }

        $this->getInsideItemComponent($brizySection)
            ->getValue()
            ->add_items([$brizyWidget]);

        return $brizySection;
    }

    protected function getDeepWidgetItem(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getInsideItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $this->getSectionItemComponent($brizySection);
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

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
            "mobilePaddingBottom" => 50,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function filterEventLayoutElementStyles($sectionProperties, ElementContextInterface $data): array
    {
        return $sectionProperties;
    }

    protected function getTimeOutToSelectorForButton(): int
    {
        return 3;    // A delay is required for the widget to render.
    }

}
