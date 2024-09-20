<?php

namespace MBMigration\Builder\Layout\Common\Element\Events;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class EventLayout extends AbstractElement
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
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySectionHead = new BrizyComponent(json_decode($this->brizyKit['head'], true));
        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['details'], true));

        $mbSection = $data->getMbSection();

        $selector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);

        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $fonts = FontsController::getFontsFamilyFromName('main_text');

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
        $detailCollectionItem = $this->createDetailsCollectionItem(
            $data->getThemeContext()->getBrizyCollectionTypeURI(),
            [
                $detailsSection,
            ]
        );

        $this->getDetailsLinksComponent($detailsSection)
            ->getValue()
            ->set_source($collectionTypeUri)
            ->set_detailPage("{{ brizy_dc_url_post id=\"".$detailCollectionItem['id']."\" }}");

        $brizySection->getValue()
            ->set_featuredViewOrder(3)
            ->set_calendarViewOrder(1)

            ->set_dateTypographyLineHeight(3)
            ->set_eventsTypographyLineHeight(1.5)

            ->set_dateTypographyFontStyle('')
            ->set_dateTypographyFontFamily(1.5)

            ->set_resultsHeadingColorHex($sectionPalette['text'])
            ->set_resultsHeadingColorOpacity(1)
            ->set_resultsHeadingColorPalette()

            ->set_resultsHeadingTypographyFontFamilyType($fonts)
            ->set_resultsHeadingTypographyFontStyle('')

            ->set_listPaginationArrowsColorHex($sectionPalette['text'])
            ->set_listPaginationArrowsColorOpacity(1)
            ->set_listPaginationArrowsColorPalette('')

            ->set_hoverListPaginationArrowsColorHex($sectionPalette['text'])
            ->set_hoverListPaginationArrowsColorOpacity(1)
            ->set_hoverListPaginationArrowsColorPalette('')

            ->set_listItemMetaColorHex($sectionPalette['text'])
            ->set_listItemMetaColorOpacity(1)
            ->set_listItemMetaColorPalette('')

            ->set_listItemDateColorHex($sectionPalette['text'])
            ->set_listItemDateColorOpacity(1)
            ->set_listItemDateColorPalette('')

            ->set_listTitleColorHex($sectionPalette['text'])
            ->set_listTitleColorOpacity(1)
            ->set_listTitleColorPalette('')

            ->set_groupingDateColorHex($sectionPalette['text'])
            ->set_groupingDateColorOpacity(1)
            ->set_groupingDateColorPalette('')

            ->set_hoverTitleColorHex($sectionPalette['text'])
            ->set_hoverTitleColorOpacity(0.75)
            ->set_hoverTitleColorPalette('')

            ->set_titleColorHex($sectionPalette['text'])
            ->set_titleColorOpacity(1)
            ->set_titleColorPalette('')

            ->set_dateColorHex($sectionPalette['text'])
            ->set_dateColorOpacity(1)
            ->set_dateColorPalette('')

            ->set_listItemDateBgColorHex($sectionPalette['text'])
            ->set_listItemDateBgColorOpacity(1)
            ->set_listItemDateBgColorType('solid')
            ->set_listItemDateBgColorPalette('')

            ->set_detailButtonBgColorHex($sectionPalette['text'])
            ->set_detailButtonBgColorOpacity(1)
            ->set_detailButtonBgColorPalette('')

            ->set_listItemTitleColorHex($sectionPalette['link'])
            ->set_listItemTitleColorOpacity(1)
            ->set_listItemTitleColorPalette('')

            ->set_hoverListItemTitleColorHex($sectionPalette['link'])
            ->set_hoverListItemTitleColorOpacity(0.75)
            ->set_hoverListItemTitleColorPalette('')

            ->set_detailButtonGradientColorHex($sectionPalette['text'])
            ->set_detailButtonGradientColorOpacity(1)
            ->set_detailButtonGradientColorPalette('')

            ->set_hoverViewColorHex($sectionPalette['text'])
            ->set_hoverViewColorOpacity(0.75)
            ->set_hoverViewColorPalette('')

            ->set_viewColorHex($sectionPalette['text'])
            ->set_viewColorOpacity(1)
            ->set_viewColorPalette('')

            ->set_layoutViewTypographyFontFamily($fonts)
            ->set_layoutViewTypographyFontStyle('')
            ->set_layoutViewTypographyFontFamilyType('upload');



        return $brizySection;
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }
}
