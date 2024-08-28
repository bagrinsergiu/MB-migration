<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class AccordionLayout extends \MBMigration\Builder\Layout\Common\Element\AccordionLayout
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item'], true);
        $brizyAccordionItems = [];

        foreach ($mbSection['items'] as $mbSectionItem) {
            $brizyAccordionItemComponent = new BrizyComponent($itemJson);

            $brizyAccordionItemComponent->getValue()->set_labelText(strip_tags($mbSectionItem['item'][0]['content']));

            $brizyAccordionItem = $this->getAccordionSectionComponent($brizyAccordionItemComponent)->getValue();

            $brizyAccordionItem
                ->set_bgColorHex('#ffffff');

            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['item'][1],
                $brizyAccordionItem
            );
            $this->handleRichTextItem($elementContext, $this->browserPage);
            $brizyAccordionItems[] = $brizyAccordionItem;
        }

        $brizyAccordionComponent = $this->getAccordionParentComponent($brizySection)->getValue();
        $brizyAccordionComponent->set_items($brizyAccordionItems);

        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;
    }

    protected function getAccordionSectionComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0);
    }

    protected function getSectionHeaderComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getAccordionParentComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }
}
