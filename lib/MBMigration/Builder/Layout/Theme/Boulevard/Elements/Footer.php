<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\FooterElement;

class Footer extends FooterElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
       return $data->getBrizySection();
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        return $data->getBrizySection();
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,

            "mobilePaddingType"=> "grouped",
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
