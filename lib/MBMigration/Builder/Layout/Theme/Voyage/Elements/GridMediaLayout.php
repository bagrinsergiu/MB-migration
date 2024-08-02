<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyMinistryBrandsSermonLayout;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

class GridMediaLayout extends AbstractElement
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
        $this->queryBuilder = $queryBuilder;
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['details'], true));
        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0, 0, 0));

        $mbSection = $data->getMbSection();

        $selector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);

        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $playerStyle['text'] = ColorConverter::convertColorRgbToHex($this->getDomElementStyles(
            $selector . ' .media-player-container .media-header .text-content',
            ['color'],
            $this->browserPage,
        ));

        $playerStyle['bg-opacity'] = ColorConverter::convertColorRgbToHex($this->getDomElementStyles(
            $selector . ' .pagination .previous a',
            ['opacity'],
            $this->browserPage,
        ));

        $playerStyle['pagination'] = ColorConverter::convertColorRgbToHex($this->getDomElementStyles(
            $selector . ' .pagination .previous a',
            ['color'],
            $this->browserPage,
        ));

        $playerStyle['pagination-active'] = ColorConverter::convertColorRgbToHex($this->getDomElementStyles(
            $selector . ' .pagination .active a',
            ['color'],
            $this->browserPage,
        ));

        $playerStyle['pagination-opacity'] = ColorConverter::convertColorRgbToHex($this->getDomElementStyles(
            $selector . ' .pagination .active a',
            ['opacity'],
            $this->browserPage,
        ));

        $playerStyle['bg'] = ColorConverter::convertColorRgbToHex($this->getDomElementStyles(
            $selector . ' .media-player-container .media-player',
            ['background-color'],
            $this->browserPage,
        ));

        $this->handleRichTextHeadFromItems(
            $elementContext,
            $this->browserPage,
            function (ElementContextInterface $itemElementContext) use ($data, $brizySection) {
                $mbSectionItem = $itemElementContext->getMbSection();

                if ($mbSectionItem['category'] == 'media') {
                    $brizySection->getItemWithDepth(0, 1, 0, 0)->getValue()->add_items(
                        [new BrizyMinistryBrandsSermonLayout($data->getThemeContext()->getBrizyCollectionItemURI())]
                    );
                } else {
                    $this->handleRichTextItem(
                        $itemElementContext,
                        $this->browserPage
                    );
                }
            }
        );

        // sections styles
        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        // create details page
        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
        $detailCollectionItem = $this->createDetailsCollectionItem(
            $data->getThemeContext()->getBrizyCollectionTypeURI(),
            [
                $detailsSection,
            ]
        );

        $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0)
            ->set_source($collectionTypeUri)
            ->set_detailPage("{{ brizy_dc_url_post id=\"".$detailCollectionItem['id']."\" }}")

            ->set_titleColorHex($sectionPalette['link'])
            ->set_titleColorOpacity(1)
            ->set_titleColorPalette('')

            ->set_hoverTitleColorHex($sectionPalette['link'])
            ->set_hoverTitleColorOpacity(0.75)
            ->set_hoverTitleColorPalette('')

            ->set_metaLinksColorHex($sectionPalette['link'])
            ->set_metaLinksColorOpacity(1)
            ->set_metaLinksColorPalette('')

            ->set_hoverMetaLinksColorHex($sectionPalette['link'])
            ->set_hoverMetaLinksColorOpacity(0.75)
            ->set_hoverMetaLinksColorPalette('')

            ->set_filterBgColorHex($playerStyle['bg']['background-color'])
            ->set_filterBgColorOpacity(floatval($playerStyle['bg-opacity']['opacity'] ?? 1))
            ->set_filterBgColorPalette('')

            ->set_paginationColorHex($playerStyle['pagination']['color'])
            ->set_paginationColorOpacity(floatval($playerStyle['pagination-opacity']['opacity'] ?? 1))
            ->set_paginationColorPalette('')

            ->set_activePaginationColorHex($playerStyle['pagination-active']['color'])
            ->set_activePaginationColorOpacity(1)
            ->set_activePaginationColorPalette('')

            ->set_hoverPaginationColorHex($playerStyle['pagination']['color'])
            ->set_hoverPaginationColorOpacity(0.75)
            ->set_hoverPaginationColorPalette('')

            ->set_resultsHeadingColorHex($playerStyle['text']['color'])
            ->set_resultsHeadingColorOpacity(1)
            ->set_resultsHeadingColorPalette('')

            ->set_inputHex($playerStyle['text']['color'])
            ->set_inputOpacity(1)
            ->set_inputPalette('')

            ->set_defaultCategory($data->getThemeContext()->getSlug());

        return $brizySection;
    }


}
