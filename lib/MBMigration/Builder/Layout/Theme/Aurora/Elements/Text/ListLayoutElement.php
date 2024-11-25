<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    use SectionStylesAble;

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        if ($photoPosition == 'left') {
            return $brizyComponent->getItemWithDepth(0,0,1);
        } else {
            return $brizyComponent->getItemWithDepth(0,0,0);
        }
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        if ($photoPosition == 'left') {
            return $brizyComponent->getItemWithDepth(0,0,0,0,0);
        } else {
            return $brizyComponent->getItemWithDepth(0,0,1,0,0);
        }

    }

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, $params = []): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }


    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 50,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 50,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType"=> "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
