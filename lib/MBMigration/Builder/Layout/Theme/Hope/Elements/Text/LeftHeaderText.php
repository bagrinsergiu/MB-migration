<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;
use MBMigration\Builder\Layout\Theme\Hope\Hope;
use MBMigration\Builder\Utils\ColorConverter;

class LeftHeaderText extends FullTextElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getHeaderContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $textContainerComponent = $this->getTextContainerComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->transformItem($elementContext, $textContainerComponent, $styleList);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($header = $this->getHeaderContainerComponent($brizySection));
        $this->handleRichTextItems($elementContext, $this->browserPage, ['title']);

        $elementContext = $data->instanceWithBrizyComponent($textContainerComponent);
        $this->handleRichTextItems($elementContext, $this->browserPage, ['body']);
        $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        $mbSectionItem = $data->getMbSection();

        $showHeader = $this->canShowHeader($mbSectionItem);

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if ($showHeader) {

            $item = $this->getItemByType($mbSectionItem, 'title');
            $styles = Hope::getStyles(
                '[data-id="' . $item['id'] . '"] div',
                ['border-top-color'],
                $this->browserPage,
                '::before'
            );

            $topLine = new BrizyComponent(json_decode($this->brizyKit['line-left-top'], true));
            $convertColorRgbToHex = ColorConverter::convertColorRgbToHex($styles['border-top-color']);
            $topLine->getItemValueWithDepth(0)->set_borderColorHex($convertColorRgbToHex);

            $bottomLine = new BrizyComponent(json_decode($this->brizyKit['line-left-bottom'], true));
            $bottomLine->getItemValueWithDepth(0)->set_borderColorHex($convertColorRgbToHex);

            $header->getValue()->add_items($topLine, 0);
            $header->getValue()->add_items($bottomLine, 2);
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
