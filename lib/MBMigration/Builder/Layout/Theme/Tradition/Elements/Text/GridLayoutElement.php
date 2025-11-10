<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class GridLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\GridLayoutElement
{
    protected function getItemsPerRow(): int
    {
        return 4;
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getTypeItemImageComponent(): string
    {
        return 'image';
    }

    protected function handleColumItemComponent(ElementContextInterface $context): void
    {
        $mbSectionItem = $context->getMbSection();
        $families = $context->getFontFamilies();
        $defaultFont = $context->getDefaultFontFamily();
        $selector = '[data-id="' . ($mbSectionItem['sectionId'] ?? $mbSectionItem['id']) . '"]';
        $properties = [
            'background-color',
            'opacity',
            'border-color',
            'padding-top',
            'padding-bottom',
            'padding-right',
            'padding-left',
            'margin-top',
            'margin-bottom',
            'margin-left',
            'margin-right',
        ];

        $sectionStyles = $this->getDomElementStyles($selector, $properties, $this->browserPage, $families, $defaultFont);

        $context->getBrizySection()->getValue()
            ->set_paddingType('ungrouped')
            ->set_marginType('ungrouped')
            ->set_borderWidthType('ungrouped')
            ->set_borderStyle('solid')
            ->set_borderColorHex(ColorConverter::convertColorRgbToHex($sectionStyles['border-color']))
            ->set_borderColorOpacity(1)
            ->set_paddingType('grouped')
            ->set_padding(5)
            ->set_borderColorPalette(null)
            ->set_mobilePaddingType('grouped')
            ->set_mobilePadding(5);

        $imageWrapper = $context->getBrizySection()->getItemWithDepth(0);
        $imageWrapper->getValue()->set_marginTop(0);
        $imageWrapper->getValue()->set_mobileMarginTop(0);

        // make buttons to not open in new window
        // https://github.com/bagrinsergiu/MB-Support/issues/826
        $context->getBrizySection()->getItemWithDepth(2, 0)->getValue()->set_linkExternalBlank("off");
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set_fullHeight('auto');
    }

}
