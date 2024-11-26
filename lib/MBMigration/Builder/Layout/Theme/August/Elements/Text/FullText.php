<?php

namespace MBMigration\Builder\Layout\Theme\August\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class FullText extends FullTextElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0,0,0);
    }

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

    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
