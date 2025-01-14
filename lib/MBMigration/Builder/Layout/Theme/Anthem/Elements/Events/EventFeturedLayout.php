<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Events\EventFeaturedLayoutElement;
use MBMigration\Builder\Utils\ColorConverter;

class EventFeturedLayout extends EventFeaturedLayoutElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();
        $itemsKit = $data->getThemeContext()->getBrizyKit();

        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        $wrapperLine = new BrizyComponent(json_decode($itemsKit['global']['wrapper--line'], true));

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if($showHeader) {
            $titleMb = $this->getItemByType($mbSectionItem, 'title');

            $menuSectionSelector = '[data-id="' . $titleMb['id'] . '"]';
            $wrapperLineStyles = $this->browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => $menuSectionSelector,
                    'styleProperties' => ['border-bottom-color',],
                    'families' => [],
                    'defaultFamily' => '',
                ]
            );

            $headStyle = [
                'line-color' => ColorConverter::convertColorRgbToHex($wrapperLineStyles['data']['border-bottom-color']),
            ];

            $wrapperLine->getItemWithDepth(0)
                ->getValue()
                ->set_borderColorHex($headStyle['line-color']);


            $brizySection->getItemWithDepth(0)
                ->getValue()
                ->add_items([$wrapperLine], 1);
        }

        return $brizySection;
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0,0,0);
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
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 30;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

}
