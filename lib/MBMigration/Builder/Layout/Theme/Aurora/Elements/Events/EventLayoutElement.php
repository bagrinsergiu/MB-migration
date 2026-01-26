<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class EventLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Events\EventLayoutElement
{

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $insideItemComponent = $this->getInsideItemComponent($brizySection);
        $textContainerComponent = $this->getTextContainerComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $insideElementContext = $data->instanceWithBrizyComponent($insideItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->transformItem($insideElementContext, $textContainerComponent, $styleList);

        return $brizySection;
    }

    protected function afterTransformItem(ElementContextInterface $data, BrizyComponent $brizySection): void
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
    }

    protected function getDeepWidgetItem(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getInsideItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $this->getInsideItemComponent($brizySection);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
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

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
