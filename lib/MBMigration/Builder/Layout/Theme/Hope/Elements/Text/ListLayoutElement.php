<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Theme\Hope\Hope;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string         $photoPosition,
                       $mbItem = null
    ): BrizyComponent
    {
        if ($mbItem['item_type'] == 'title') {
            $position = $photoPosition == 'left' ? 1 : 0;
            return $brizyComponent->getItemWithDepth(1, 1);
        } else {
            return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 2 : 0);
        }
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string         $photoPosition
    ): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(($photoPosition == 'left' ? 0 : 2), 0, 0);
    }

    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = [], BrizyComponent $brizyParent = null): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();

        $styles = Hope::getStyles(
            '[data-id="' . $mbSectionItem['id'] . '"] div',
            ['border-top-color'],
            $this->browserPage,
            '::before'
        );

        $brizyParent->getItemValueWithDepth(1, 0, 0)->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));
        $brizyParent->getItemValueWithDepth(1, 2, 0)->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));

        return $brizySection;
    }

    protected function handleTextHead(ElementContextInterface $context)
    {
        // put the head text and text body form head in the right places
        $headerParent = $context->getBrizySection();
        $this->handleRichTextItems($context->instanceWithBrizyComponent($headerParent->getItemWithDepth(0)), $this->browserPage, ['title'], 'head');
        $this->handleRichTextItems($context->instanceWithBrizyComponent($headerParent->getItemWithDepth(2)), $this->browserPage, ['body'], 'head');

        $item = $this->getItemByType($context->getMbSection(), 'title', 'head');
        $styles = Hope::getStyles(
            '[data-id="' . $item['id'] . '"] div',
            ['border-top-color'],
            $this->browserPage,
            '::before'
        );

        $headerParent->getItemWithDepth(1, 0)->getValue()->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));
    }
}
