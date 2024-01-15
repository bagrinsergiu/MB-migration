<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Element\FooterElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class Footer extends FooterElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getTopSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0,0,0);
    }

    protected function getBottomSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0,1,0);
    }


    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {

            $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
            $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
            $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);
            $this->handleSectionStyles($elementContext, $this->browserPage);


            $mbSectionItem = $data->getMbSection();

            // sort items
            $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

            $mbItem = $mbSectionItem['items'][0];
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbItem,
                $this->getTopSectionItemComponent($brizySectionItemComponent)
            );
            $this->handleRichTextItem(
                $elementContext,
                $this->browserPage
            );

            $mbItem = $mbSectionItem['items'][1];
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbItem,
                $this->getBottomSectionItemComponent($brizySectionItemComponent)
            );
            $this->handleRichTextItem(
                $elementContext,
                $this->browserPage
            );


            return $brizySection;
        });
    }
}