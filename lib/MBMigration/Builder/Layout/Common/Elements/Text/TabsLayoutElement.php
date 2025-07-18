<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class TabsLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();
        $families = $data->getFontFamilies();

        $this->CustomCSSForSections($brizySection);

        $brizyComponent = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($brizyComponent);

        $sectionSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';

        $tabsElementStyles = $this->getTabsElementStyles($sectionSelector, $this->browserPage, $families);

        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $brizyComponent);

        $elementContext = $data->instanceWithBrizyComponent($this->getTopTextComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item'], true);
        $brizyAccordionItems = [];
        foreach ($mbSection['items'] as $mbSectionItem) {
            $brizyTabItem = new BrizyComponent($itemJson);

            $brizyTabItem->getValue()->set_labelText(strip_tags($mbSectionItem['items'][0]['content']));

            $brizyTabItemText = $this->getTabTextComponent($brizyTabItem);

            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['items'][1],
                $brizyTabItemText
            );

            $this->handleRichTextItem($elementContext, $this->browserPage);

            $this->afterTransformTabs($brizyTabItem);

            $brizyAccordionItems[] = $brizyTabItem;
        }

        $tabContainerComponentValue = $this->getTabContainerComponent($brizySection)->getValue();
        $tabContainerComponentValue->set_items($brizyAccordionItems);

        foreach ($tabsElementStyles as $key => $value) {
            $method = "set_".$key;
            $tabContainerComponentValue
                ->$method($value);
        }

        if($this->hasImageBackground($mbSection))
        {
            $tabContainerComponentValue->set_bgColorOpacity(0);
        }

        $this->afterTransformToTabsItem($brizySection);



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


    protected function  beforeItemTransform(): void
    {
    }

    protected function CustomCSSForSections(BrizyComponent $brizySection): void
    {
        $brizySection
            ->getItemWithDepth(0)
            ->addCustomCSS('.brz-tabs.brz-tabs--horizontal{
padding: 0 !important;
}

.brz-tabs__nav.brz-tabs__nav--style-3 .brz-tabs__nav--item::before,
.brz-tabs__nav.brz-tabs__nav--style-3 .brz-tabs__nav--item::after {
background-color:transparent !important;
}');
    }


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

    protected function afterTransformToTabsItem(BrizyComponent $brizyTabSection)
    {
    }

}
