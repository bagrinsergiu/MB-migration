<?php

namespace MBMigration\Builder\Layout\Common\Elements\Events;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class EventLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use BrizyQueryBuilderAware;

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

    /**
     * @throws BadJsonProvided
     * @throws BrowserScriptException
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['EventLayoutElement']['main'], true));
        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['EventLayoutElement']['detail'], true));
        $DetailsPageLayout = new EventDetailsPageLayout();

        $mbSection = $data->getMbSection();

        $selector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);

        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $fonts = FontsController::getFontsFamilyFromName('main_text');

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $this->handleRichTextHead($elementContext, $this->browserPage);

        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();

        $DetailsPageLayout->setStyleDetailPage($detailsSection, $sectionPalette);

        $detailCollectionItem = $this->createDetailsCollectionItem(
            $data->getThemeContext()->getBrizyCollectionTypeURI(),
            $detailsSection
        );

        $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $detailCollectionItem['id'] . '" }}');

        $this->getDetailsLinksComponent($brizySection)
            ->getValue()
            ->set_eventDetailPageSource($collectionTypeUri)
            ->set_eventDetailPage("{{placeholder content='$placeholder'}}");

        switch ($mbSection['typeSection']){
            case 'event-tile-layout':
                $eventTabs = [
                    'featuredViewOrder' => 1,
                    "listViewOrder"=> 2,
                    'calendarViewOrder' => 3,
                ];
                break;
            default:
                $eventTabs = [
                    'featuredViewOrder' => 3,
                    "listViewOrder"=> 2,
                    'calendarViewOrder' => 1,
                ];
                break;
        }

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

            'calendarHeadingColorHex' => $sectionPalette['text'],
            'calendarDaysBgColorOpacity' => 1,
            'calendarDaysBgColorPalette' => '',

            'calendarDaysBgColorHex' => $sectionPalette['bg'],
            'calendarHeadingColorOpacity' => 1,
            'calendarHeadingColorPalette' => '',

            'calendarDaysColorHex' => $sectionPalette['text'],
            'calendarDaysColorOpacity' => 1,
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

            'listItemDateColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'listItemDateColorOpacity' => 1,
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

            'listItemDateBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'listItemDateBgColorOpacity' => 1,
            'listItemDateBgColorType' => 'solid',
            'listItemDateBgColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonGradientColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonGradientColorOpacity' => 1,
            'detailButtonGradientColorPalette' => '',

            'hoverViewColorHex' => $sectionPalette['text'],
            'hoverViewColorOpacity' => 0.7,
            'hoverViewColorPalette' => '',

            'viewColorHex' => $sectionPalette['text'],
            'viewColorOpacity' => 1,
            'viewColorPalette' => '',

            'layoutViewTypographyFontFamily' => $fonts,
            'layoutViewTypographyFontStyle' => '',
            'layoutViewTypographyFontFamilyType' => 'upload',
        ];

        $sectionProperties = array_merge($sectionProperties, $eventTabs);

        foreach ($sectionProperties as $key => $value) {
            $properties = 'set_'.$key;
            $brizySection->getItemValueWithDepth(0, 0, 0)
                ->$properties($value);
        }

        return $brizySection;
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

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

}
