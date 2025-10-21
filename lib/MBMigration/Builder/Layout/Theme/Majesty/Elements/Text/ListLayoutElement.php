<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    use LineAble;
    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition,
        $mbItem = null
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 1 : 0);
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0,0);
    }

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $this->transformListItem($data, $brizySection, $params);
    }

    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = [], BrizyComponent $brizyParent = null): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();

        if(!isset($mbSectionItem['item_type']) || $mbSectionItem['item_type'] !== 'title'){
            $titleMb = $this->getByType($mbSectionItem['head'], 'title');
        } else {
            $titleMb['id'] =  $mbSectionItem['id'];
        }
        $showHeader = $this->canShowHeader($mbSectionItem);

        if($showHeader && $mbSectionItem['item_type'] === 'title') {
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem,
                $brizySection
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 1);

        } else if ($showHeader && $titleMb['item_type'] === 'title') {
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $titleMb,
                $brizySection
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 1);
        }

        return $brizySection;
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
