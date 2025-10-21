<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent,string $photoPosition,$mbItem = null): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 1 : 0);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent, string $photoPosition): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0, 0);
    }

    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = [], BrizyComponent $brizyParent = null): BrizyComponent
    {
        return $brizySection;
    }

    protected function handleItemTextContainerComponent(BrizyComponent $brizySection): void
    {
        $brizySection->addPadding(35, 0, 35, 0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function handleRowListItem(BrizyComponent $brizySection, $position = 'left'): void
    {
        if ($position == 'left') {
            $brizySection
                ->getItemWithDepth(1)
                ->addPadding(15,0,15,20);
        } else {
            $brizySection
                ->getItemWithDepth(0)
                ->addPadding(15,20,15,0);
        }
    }

    protected function getDonationsButtonOptions(): array
    {
        return [
            'mobilePaddingTop' => 10,
            'mobilePaddingRight' => 0,
            'mobilePaddingBottom' => 10,
            'mobilePaddingLeft' => 0,
        ];
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
            "mobilePaddingBottom" => 20,
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

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $brizySection;
    }
}
