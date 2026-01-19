<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Theme\Hope\Hope;
use MBMigration\Builder\Utils\ColorConverter;

class GridLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\GridLayoutElement
{
    protected function getItemsPerRow(): int
    {
        return 3;
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(1, 1);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 200;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 200;
    }

    protected function getTypeItemImageComponent(): string
    {
        return 'image';
    }

    protected function getPropertiesItemPhoto(): array
    {
        return [
            "width" => 190,
            "height" => 190,
            "widthSuffix" => "px",
            "heightSuffix" => "px",
            "mobileHeight" => null,
            "mobileHeightSuffix" => null,
            "mobileWidth" => null,
            "mobileWidthSuffix" => null,
            "tabletHeight" => null,
            "tabletHeightSuffix" => null,
            "tabletWidth" => null,
            "tabletWidthSuffix" => null,
            "tabsState" => "normal",
            "maskShape" => "circle",
            "hoverImageSrc" => "",
            "hoverHeight" => 266.73,
            "zoom" => 100,
            "zoomSuffix" => "%"
        ];
    }

    protected function handleTextSection(ElementContextInterface $context)
    {
        $headerParent = $context->getBrizySection();
        $this->handleRichTextItems($context->instanceWithBrizyComponent($headerParent->getItemWithDepth(0)), $this->browserPage, ['title'],'head');
        $this->handleRichTextItems($context->instanceWithBrizyComponent($headerParent->getItemWithDepth(2)), $this->browserPage, ['body'],'head');

        $item = $this->getItemByType($context->getMbSection(), 'title','head');
        $styles = Hope::getStyles(
            '[data-id="' . $item['id'] . '"] div',
            ['border-top-color'],
            $this->browserPage,
            '::before'
        );

        $headerParent->getItemWithDepth(1,0)->getValue()->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));
    }

    protected function handleItemTextAfter(ElementContextInterface $context, ElementContextInterface $itemContext)
    {
        $lineColumn = $itemContext->getBrizySection()->getItemWithDepth(1);
        $item = $context->getMbSection();
        $styles = Hope::getStyles(
            '[data-id="' . $item['id'] . '"] div',
            ['border-top-color'],
            $this->browserPage,
            '::before'
        );

        $lineColumn->getItemWithDepth(0, 0)->getValue()->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));
        $lineColumn->getItemWithDepth(2, 0)->getValue()->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));
    }
}
