<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Events\EventFeaturedLayoutElement;
use MBMigration\Builder\Utils\ColorConverter;

class EventFeturedLayout extends EventFeaturedLayoutElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        return $brizySection;
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0,0,0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 95;
    }

    protected function getMobileTopMarginOfTheFirstElement(): int
    {
        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();

        return (int) $dtoPageStyle['headerHeight'];
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
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 50,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 50,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }

}
