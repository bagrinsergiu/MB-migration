<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class EventListLayout extends AbstractElement
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

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySectionHead = new BrizyComponent(json_decode($this->brizyKit['head'], true));
        $detailsSection = new BrizyComponent(json_decode($this->brizyKit['details'], true));

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($brizySectionHead);
        $this->handleRichTextHead($elementContext, $this->browserPage);

        // header
        $headElement = $data->getThemeContext()->getElementFactory()->getElement('head');
        $headBlock = $headElement->transformToItem($data->getThemeContext()->getMbHeadSection());

        $footerElement = $data->getThemeContext()->getElementFactory()->getElement('head');
        $footerBlock = $footerElement->transformToItem($data->getThemeContext()->getMbFooterSection());
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

        $this->getDetailsComponent($brizySection)
            ->getValue()
            ->set_source($collectionTypeUri)
            ->set_detailPage("{{ brizy_dc_url_post id=\"".$detailCollectionItem['id']."\" }}");


        return $brizySection;
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed
     */
    protected function getDetailsComponent(BrizyComponent $brizySection)
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }
}