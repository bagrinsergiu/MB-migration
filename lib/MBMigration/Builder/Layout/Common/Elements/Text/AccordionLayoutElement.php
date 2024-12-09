<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class AccordionLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item'], true);
        $brizyAccordionItems = [];
        foreach ($mbSection['items'] as $mbSectionItem) {
            $brizyAccordionItem = new BrizyComponent($itemJson);
            $brizyAccordionItem->getValue()->set_labelText(strip_tags($mbSectionItem['items'][0]['content']));
//            $brizyAccordionItem->getItemWithDepth(0)->getValue()
//                ->set_bgColorHex('#ffffff');
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['items'][1],
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

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
