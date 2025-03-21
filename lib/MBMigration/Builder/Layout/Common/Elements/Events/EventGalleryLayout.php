<?php

namespace MBMigration\Builder\Layout\Common\Elements\Events;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\ButtonAble;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Template\DetailPages\EventDetailsPageLayout;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class EventGalleryLayout extends AbstractElement
{
    use SectionStylesAble;
    use RichTextAble;
    use BrizyQueryBuilderAware;
    use CssPropertyExtractorAware;
    use ButtonAble;

    public function __construct($brizyKit, BrowserPageInterface $browserPage, QueryBuilder $queryBuilder)
    {
        parent::__construct($brizyKit, $browserPage);
        $this->setQueryBuilder($queryBuilder);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['EventGalleryLayoutElement']['main'], true));
        $brizyWidget = new BrizyComponent(json_decode($this->brizyKit['EventGalleryLayoutElement']['widget'], true));

//        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['EventLayoutElement']['detail'], true));
        $DetailsPageLayout = new EventDetailsPageLayout(
            $this->brizyKit['EventGalleryLayoutElement']['detail'],
            $this->getTopPaddingOfTheFirstElement(),
            $this->getMobileTopPaddingOfTheFirstElement(),
            $this->pageTDO,
            $data
        );

        $mbSection = $data->getMbSection();

        $selector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);

        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $fonts = FontsController::getFontsFamilyFromName('main_text');

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent, [], $this->getAdditionalTopPaddingOfTheFirstElement());

        if(!empty($mbSection['head'])){
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

        $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $detailCollectionItem['id'] . '" }}');

        $this->getDetailsLinksComponent($brizyWidget)
            ->getValue()
            ->set_source($collectionTypeUri)
            ->set_detailPage("{{placeholder content='$placeholder'}}");

        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

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

            'calendarDaysBgColorHex' => $sectionPalette['bg'] ?? $basicButtonStyleNormal['background-color'],
            'calendarDaysBgColorOpacity' => $basicButtonStyleNormal['background-color-opacity'] ?? 1,
            'calendarDaysBgColorPalette' => '',

            'calendarHeadingColorHex' => $sectionPalette['text'] ?? $basicButtonStyleNormal['color'],
            'calendarHeadingColorOpacity' =>  $basicButtonStyleNormal['color-opacity'] ?? 1,
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

            'listItemDateColorHex' => $basicButtonStyleHover['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'listItemDateColorOpacity' => $basicButtonStyleHover['color-opacity'] ?? 1,
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

            'hoverDetailButtonBgColorHex' => $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'hoverDetailButtonBgColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ?? 0.75,
            'hoverDetailButtonBgColorPalette' => '',

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

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $basicButtonStyleHover['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => $basicButtonStyleHover['color-opacity'] ?? 0.75,
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

        foreach ($sectionProperties as $key => $value) {
            $properties = 'set_'.$key;
            $brizyWidget->getItemWithDepth(0)->getValue()
                ->$properties($value);
        }

        $brizyWidget
            ->getItemWithDepth(0)
            ->typography()
            ->titleTypography()
            ->dataTypography()
            ->previewTypography()
            ->detailButtonTypography()
            ->subscribeEventButtonTypography();

        $sectionItemComponent->getValue()->add_items([$brizyWidget]);

        return $brizySection;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 200;
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
