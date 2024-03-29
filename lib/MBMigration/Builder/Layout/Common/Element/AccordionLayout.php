<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class AccordionLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

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
            $brizyAccordionItem = new BrizyComponent($itemJson);
            $brizyAccordionItem->getValue()->set_labelText(strip_tags($mbSectionItem['item'][0]['content']));
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['item'][1],
                $brizyAccordionItem
            );
            $this->handleRichTextItem($elementContext, $this->browserPage);
            $brizyAccordionItems[] = $brizyAccordionItem;
        }

        $brizyAccordionComponent = $this->getAccordionParentComponent($brizySection)->getValue();
        $brizyAccordionComponent->set_items($brizyAccordionItems);

        if ($this->hasImageBackground($mbSection)) {
            $brizyAccordionComponent->set_bgColorOpacity(0);
        }

        return $brizySection;
    }

    abstract protected function getSectionHeaderComponent(BrizyComponent $brizySection): BrizyComponent;

    //{ return $brizySection->getItemWithDepth(0, 0, 0); }
    abstract protected function getAccordionParentComponent(BrizyComponent $brizySection): BrizyComponent;
    //{ return $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0); }
}