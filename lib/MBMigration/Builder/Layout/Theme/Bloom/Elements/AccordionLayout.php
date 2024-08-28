<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\TextTools;

class AccordionLayout extends \MBMigration\Builder\Layout\Common\Element\AccordionLayout
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();
        $families = $data->getFontFamilies();

        $sectionSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $backgroundColorStyles = ColorConverter::convertColorRgbToHex(
            $this->getDomElementStyles($sectionSelector, ['background-color'], $this->browserPage));

        $accordionElementStyles = $this->getAccordionElementStyles($sectionSelector, $this->browserPage, $families);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item'], true);
        $brizyAccordionItems = [];

        $brizyAccordionComponent = $this->getAccordionParentComponent($brizySection)->getValue();

        foreach ($accordionElementStyles as $key => $value) {
            $propertiesName = 'set_'.$key;
            $brizyAccordionComponent->$propertiesName($value);
        }

        foreach ($mbSection['items'] as $mbSectionItem) {
            $brizyAccordionItemComponent = new BrizyComponent($itemJson);

            $lableText = TextTools::transformTextBool($mbSectionItem['item'][0]['content'],
                    $accordionElementStyles['uppercase']);

            $brizyAccordionItemComponent->getValue()->set_labelText(strip_tags($lableText));

            $brizyAccordionItem = $this->getAccordionSectionComponent($brizyAccordionItemComponent);

            $brizyAccordionItem->getValue()
                ->set_bgColorHex($backgroundColorStyles['background-color']);

            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['item'][1],
                $brizyAccordionItem
            );
            $this->handleRichTextItem($elementContext, $this->browserPage);

            $brizyAccordionItems[] = $brizyAccordionItemComponent;
        }

        $brizyAccordionComponent->set_items($brizyAccordionItems);

        return $brizySection;
    }

    protected function getAccordionSectionComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
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
