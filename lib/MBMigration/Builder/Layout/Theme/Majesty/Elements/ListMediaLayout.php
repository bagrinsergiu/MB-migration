<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyMinistryBrandsSermonLayout;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Layer\Graph\QueryBuilder;

class ListMediaLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use MbSectionUtils;
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

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['details'], true));
        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0, 0, 0));

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
        $headElement = $data->getThemeContext()->getElementFactory()->getElement('head');
        $elementContext = $data->instanceWithMBSection($data->getThemeContext()->getMbHeadSection());
        $headBlock = $headElement->transformToItem($elementContext);

        $footerElement = $data->getThemeContext()->getElementFactory()->getElement('footer');
        $elementContext = $data->instanceWithMBSection($data->getThemeContext()->getMbFooterSection());
        $footerBlock = $footerElement->transformToItem($elementContext);

        if ($this->canShowHeader($data->getMbSection()) || $this->canShowBody($data->getMbSection())) {
            $value = $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0);
        }

        // create details page
        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
        $detailCollectionItem = $this->createDetailsCollectionItem(
            $collectionTypeUri,
            [
                'items' => [
                    $headBlock,
                    $detailsSection,
                    $footerBlock,
                ],
            ]
        );

        $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0)
            ->set_source($collectionTypeUri)
            ->set_detailPage("{{ brizy_dc_url_post id=\"".$detailCollectionItem['id']."\" }}");

        return $brizySection;
    }
}