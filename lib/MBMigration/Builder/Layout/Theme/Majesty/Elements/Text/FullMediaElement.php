<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullMediaElementElement;

class FullMediaElement extends FullMediaElementElement
{
    use LineAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();
        $showHeader = $this->canShowHeader($mbSectionItem);
        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if($showHeader) {
            $titleMb = $this->getItemByType($mbSectionItem, 'title');
            $image = $brizySection->getItemWithDepth(0);
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $titleMb,
                $image
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 2);
        }

        return $brizySection;
    }
    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0, 0, 0, 0);
    }

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 1, 0);
    }

    protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
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
        ];
    }
}
