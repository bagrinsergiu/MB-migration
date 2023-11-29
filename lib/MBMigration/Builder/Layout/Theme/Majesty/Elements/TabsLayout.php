<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class TabsLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0, 0, 0));
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

        $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0)->set_items($brizyAccordionItems);

        if($this->hasImageBackground($mbSection))
        {
            $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0)->set_bgColorOpacity(0);
        }

        return $brizySection;
    }
}