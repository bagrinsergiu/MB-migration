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
use MBMigration\Layer\Graph\QueryBuilder;

class ListMediaLayout extends AbstractElement
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

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['details'], true));
        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0, 0, 0));

        $mbSection = $data->getMbSection();

        $selector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);

        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

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


        if ($this->canShowHeader($data->getMbSection()) || $this->canShowBody($data->getMbSection())) {
            $value = $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0);
        }

        // create details page
        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
        $detailCollectionItem = $this->createDetailsCollectionItem(
            $collectionTypeUri,
            [
                'items' => [
                    $detailsSection,
                ],
            ]
        );

        $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0)
            ->set_source($collectionTypeUri)
            ->set_detailPage("{{ brizy_dc_url_post id=\"".$detailCollectionItem['id']."\" }}")

            ->set_titleColorHex()
            ->set_titleColorOpacity()
            ->set_titleColorPalette()

            ->set_hoverTitleColorHex()
            ->set_hoverTitleColorOpacity()
            ->set_hoverTitleColorPalette()

            ->set_metaLinksColorHex()
            ->set_metaLinksColorOpacity()
            ->set_metaLinksColorPalette()

            ->set_hoverMetaLinksColorHex()
            ->set_hoverMetaLinksColorOpacity()
            ->set_hoverMetaLinksColorPalette()

            ->set_filterBgColorHex()
            ->set_filterBgColorOpacity()
            ->set_filterBgColorPalette()

            ->set_paginationColorHex()
            ->set_paginationColorOpacity()
            ->set_paginationColorPalette()

            ->set_activePaginationColorHex()
            ->set_activePaginationColorOpacity()
            ->set_activePaginationColorPalette()

            ->set_hoverPaginationColorHex()
            ->set_hoverPaginationColorOpacity()
            ->set_hoverPaginationColorPalette()

            ->set_resultsHeadingColorHex()
            ->set_resultsHeadingColorOpacity()
            ->set_resultsHeadingColorPalette()

            ->set_defaultCategory($data->getThemeContext()->getSlug());

        return $brizySection;
    }
}
