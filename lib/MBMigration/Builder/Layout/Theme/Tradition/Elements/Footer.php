<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\FooterElement;

class Footer extends FooterElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,

            "mobilePaddingType" => "grouped",
            "mobilePadding" => 20,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 20,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 20,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();
        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);

        $sortItems = $this->sortItems($mbSection['items']);
        foreach ($sortItems as $i => $item) {
            $column = $this->getFooterColumnElement($brizySectionItemComponent, $item['category'] == 'photo' ? 0 : $i);

            $elementContext = $data->instanceWithBrizyComponentAndMBSection($item, $column);
            $this->handleItemMbSection($item, $elementContext);
        }

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        return $brizySection;
    }

    protected function getFooterColumnElement(BrizyComponent $brizySection, $index): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, $index);
    }

}
