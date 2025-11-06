<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class FullText extends FullTextElement
{
    use SectionStylesAble;

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        return $brizySection;
    }

    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }

    protected function afterTransformItem(ElementContextInterface $data, BrizyComponent $brizySection): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $selectId = $mbSectionItem['id'] ?? $mbSectionItem['sectionId'];

        $sectionSelector = '[data-id="' .$selectId. '"] .bg-helper>.bg-opacity';
        $styles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $sectionSelector,
                'styleProperties' => ['background-color','opacity'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $this->handleItemBackground($brizySection, $styles['data']);
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 0,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 0,
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

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }
}
