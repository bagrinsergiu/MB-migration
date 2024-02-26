<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Layer\Graph\QueryBuilder;

class EventListLayout extends AbstractElement
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

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySectionHead = new BrizyComponent(json_decode($this->brizyKit['head'], true));
        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['details'], true));

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($brizySectionHead);
        $this->handleRichTextHead($elementContext, $this->browserPage);

        // header
        $headElement = $data->getThemeContext()->getElementFactory()->getElement('head');
        $headBlock = $headElement->internalTransformToItem($data->getThemeContext()->getMbHeadSection());

        $footerElement = $data->getThemeContext()->getElementFactory()->getElement('head');
        $footerBlock = $footerElement->internalTransformToItem($data->getThemeContext()->getMbFooterSection());
        // footer

        // create details page
       // create details page
        $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
        $detailCollectionItem = $this->createDetailsCollectionItem(
            $data->getThemeContext()->getBrizyCollectionTypeURI(),
            [
                $headBlock,
                $detailsSection,
                $footerBlock,
            ]
        );

        $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0)
            ->set_source($collectionTypeUri)
            ->set_detailPage("{{ brizy_dc_url_post id=\"".$detailCollectionItem['id']."\" }}");


        return $brizySection;
    }
}