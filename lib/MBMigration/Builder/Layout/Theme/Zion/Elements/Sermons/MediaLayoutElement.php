<?php
namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Sermons;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class MediaLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Sermons\MediaLayoutElement
{

    use LineAble;
    use ShadowAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();

        $showHeader = $this->canShowHeader($mbSectionItem);

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if($showHeader) {
            $titleMb = $this->getItemByType($mbSectionItem, 'title');
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem,
                $brizySection->getItemWithDepth(0)
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 1, null);
        }

        $this->handleShadow($brizySection);

        return $brizySection;
    }

    protected function selectorForStylePagination(string $dataIdSelector): array
    {
        return [
            'text-content' => [
                'selector' => $dataIdSelector . ' .media-player-container .media-header .text-content',
                'properties' => ['color'],
                'resultKey' => 'text'
            ],
            'pagination-previous' => [
                'selector' => $dataIdSelector . ' .pagination .previous a',
                'properties' => ['color', 'opacity'],
                'resultKeys' => ['pagination-normal', 'opacity-pagination-normal']
            ],
            'pagination-active' => [
                'selector' => $dataIdSelector . ' .pagination li.active a',
                'properties' => ['color', 'opacity'],
                'resultKeys' => ['pagination-active', 'opacity-pagination-active']
            ],
            'pagination-active-before' => [
                'selector' => $dataIdSelector . ' .pagination .active a',
                'properties' => ['background-color'],
                'resultKey' => 'pagination-active-bg',
                'pseudo' => ':before'
            ],
            'media-player' => [
                'selector' => $dataIdSelector . ' .media-player-container .media-player',
                'properties' => ['background-color', 'opacity'],
                'resultKeys' => ['bg-color', 'bg-opacity']
            ],
            'media-description' => [
                'selector' => $dataIdSelector . ' .media-player-container .media-description',
                'properties' => ['color'],
                'resultKey' => 'color-text-description'
            ],
            'media-header' => [
                'selector' => $dataIdSelector . ' .media-player-container .media-header',
                'properties' => ['color'],
                'resultKey' => 'color-text-header'
            ],
            'subsection-archive' => [
                'selector' => $dataIdSelector . ' .media-archive-subsection .Select-control',
                'properties' => ['background-color', 'opacity'],
                'resultKey' => 'bg-filter'
            ]
        ];
    }

    protected function getItemColorBgBox($opacity1): int
    {
        return 0;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType" => "ungrouped",
            "paddingTop" => 90,
            "paddingBottom" => 90,

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 70,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 70,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 130;
    }
    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 100;
    }
}
