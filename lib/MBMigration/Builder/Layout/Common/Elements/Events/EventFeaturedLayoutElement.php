<?php

namespace MBMigration\Builder\Layout\Common\Elements\Events;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Template\DetailPages\EventDetailsPageLayout;
use MBMigration\Layer\Graph\QueryBuilder;

class EventFeaturedLayoutElement  extends AbstractElement
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
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['EventFeatured']['main'], true));
//        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['EventDetailsPage']['main'], true));
        $DetailsPageLayout = new EventDetailsPageLayout(
            $this->brizyKit['EventDetailsPage']['main'],
            $this->getTopPaddingOfTheFirstElement(),
            $this->getMobileTopPaddingOfTheFirstElement(),
            $this->pageTDO,
            $data,
            $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1'
        );



        $mbSection = $data->getMbSection();
        $mbPageSlug = $data->getThemeContext()->getSlug();

        $selector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);

        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $fonts = FontsController::getFontsFamilyFromName('main_text');

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $textContainerComponent = $this->getTextContainerComponent($brizySection);
        $textContext = $data->instanceWithBrizyComponent($textContainerComponent);
        $this->handleRichTextHead($textContext, $this->browserPage);
        $this->handleRichTextItems($textContext, $this->browserPage);

        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();

        $detailsSection = $DetailsPageLayout->setStyleDetailPage($sectionPalette);

        $detailCollectionItem = $this->createDetailsCollectionItem(
            $data->getThemeContext()->getBrizyCollectionTypeURI(),
            $detailsSection
        );

        $placeholder = base64_encode('{{ brizy_dc_url_post entityType="'.$detailCollectionItem['type']['id'].'" entityId="' . $detailCollectionItem['id'] . '" }}');

        $this->getDetailsLinksComponent($brizySection)
            ->getValue()
            ->set_detailPageSource($collectionTypeUri)
            ->set_detailPage("{{placeholder content='$placeholder'}}");

        $sectionProperties = [
            "showMeta" => "on",
            "showCategory" => "on",
            "showLocation" => "off",
            "showPagination" => "off",

            'category' => $mbPageSlug,

            'detailPageButtonText' => '',

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

            'colorHex' => $sectionPalette['text'],
            'colorOpacity' => 1,
            'cateColorPalette' => '',

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

            'hoverRegisterButtonBgColorType' => 'solid',
            'hoverRegisterButtonGradientType' => 'linear',

            'registerButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'registerButtonColorOpacity' => 1,
            'registerButtonColorPalette' => '',

            'hoverRegisterButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverRegisterButtonColorOpacity' => 0.75,
            'hoverRegisterButtonColorPalette' => '',

            'registerButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['text'],
            'registerButtonBgColorOpacity' => 1,
            'registerButtonBgColorPalette' => '',

            'hoverRegisterButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'hoverRegisterButtonBgColorOpacity' => 0.75,
            'hoverRegisterButtonBgColorPalette' => '',

            'hoverRegisterButtonGradientColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'hoverRegisterButtonGradientColorOpacity' => 0.75,
            'hoverRegisterButtonGradientColorPalette' => '',

            'registerButtonGradientColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'registerButtonGradientColorOpacity' => 1,
            'registerButtonGradientColorPalette' => '',

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'detailButtonGradientColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonGradientColorOpacity' => 1,
            'detailButtonGradientColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['btn'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'hoverDetailButtonGradientColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonGradientColorOpacity' => 0.75,
            'hoverDetailButtonGradientColorPalette' => '',

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
            $brizySection->getItemValueWithDepth(0, 1, 0)
                ->$properties($value);
        }

        return $brizySection;
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0,0,0);
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0);
    }

}
