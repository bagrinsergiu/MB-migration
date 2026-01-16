<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;
use MBMigration\Builder\Layout\Theme\Hope\Hope;
use MBMigration\Builder\Utils\ColorConverter;

class FullText extends FullTextElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
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

        if ($showHeader) {
            $item = $this->getItemByType($mbSectionItem, 'title');
            $styles = Hope::getStyles(
                '[data-id="' . $item['id'] . '"] div',
                ['border-top-color'],
                $this->browserPage,
                '::before'
            );

            $wrapperLine->getItemWithDepth(0)
                ->getValue()
                ->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));


            $brizySection->getItemWithDepth(0)
                ->getValue()
                ->add_items([$wrapperLine], 1);
        }

        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 200;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
