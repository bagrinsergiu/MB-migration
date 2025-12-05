<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\FooterElement;

class Footer extends FooterElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $brizySection = $this->pageLayout;

        $mbSection = $data->getMbSection();
        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);

        $sortItems = $this->sortItems($mbSection['items']);
        foreach ($sortItems as $items) {
            $column = $this->getFooterColumnElement($brizySectionItemComponent, $items['group']);
            $elementContext = $data->instanceWithBrizyComponent($column)
                ->instanceWithBrizyCustomSettings([
                    "Wrapper" => [
                        "customSettings" => [
                            "showOnMobile" => "off",
                            "elementPosition" => "absolute",
                            "offsetYAlignment" => "bottom",
                            "offsetY" => 55,
                            "offsetYSuffix" => "px",
                            "tabletOffsetX" => 55,
                            "tabletOffsetXSuffix" => "px",
                            "width" => 100,
                            "widthSuffix" => "%",
                            "marginType" => "ungrouped",
                            "margin" => 0,
                            "marginSuffix" => "px",
                            "marginTop" => 10,
                            "marginTopSuffix" => "px",
                            "marginRight" => 0,
                            "marginRightSuffix" => "px",
                            "marginBottom" => 10,
                            "marginBottomSuffix" => "px",
                            "marginLeft" => 20,
                            "marginLeftSuffix" => "px",
                        ],
                        "implement" => "all"  // Options: "all", "one", or integer (e.g., 0 for first, 1 for second)
                    ],
                    "Cloneable" => [
                        "customSettings" => [
                            "showOnMobile" => "off",
                            "elementPosition" => "absolute",
                            "offsetYAlignment" => "bottom",
                            "offsetY" => 10,
                            "offsetYSuffix" => "px",
                            "tabletOffsetX" => 10,
                            "tabletOffsetXSuffix" => "px",
                            "width" => 100,
                            "widthSuffix" => "%",
                            "marginType" => "ungrouped",
                            "margin" => 0,
                            "marginSuffix" => "px",
                            "marginTop" => 10,
                            "marginTopSuffix" => "px",
                            "marginRight" => 0,
                            "marginRightSuffix" => "px",
                            "marginBottom" => 10,
                            "marginBottomSuffix" => "px",
                            "marginLeft" => 20,
                            "marginLeftSuffix" => "px",
                        ],
                        "implement" => "all"  // Options: "all", "one", or integer (e.g., 0 for first, 1 for second)
                    ]
                ]);
            $this->handleItemMbSection($items, $elementContext);
        }

        return $brizySection;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function makeGlobalBlock(): bool
    {
        return false;
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

}
