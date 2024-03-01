<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class TabsLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getTopTextComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item'], true);
        $brizyAccordionItems = [];
        foreach ($mbSection['items'] as $mbSectionItem) {
            $brizyTabItem = new BrizyComponent($itemJson);
            $brizyTabItem->getValue()->set_labelText(strip_tags($mbSectionItem['item'][0]['content']));
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['item'][1],
                $brizyTabItem
            );
            $this->handleRichTextItem($elementContext, $this->browserPage);
            $brizyAccordionItems[] = $brizyTabItem;
        }

        $tabContainerComponentValue = $this->getTabContainerComponent($brizySection)->getValue();
        $tabContainerComponentValue->set_items($brizyAccordionItems);

        if($this->hasImageBackground($mbSection))
        {
            $tabContainerComponentValue->set_bgColorOpacity(0);
        }

        return $brizySection;
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    abstract protected function getTopTextComponent(BrizyComponent $brizySection): BrizyComponent;

    /**
     * @param BrizyComponent $brizySection
     * @return mixed
     */
    abstract protected function getTabContainerComponent(BrizyComponent $brizySection): BrizyComponent;

}