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
            ->set_borderColorPalette(null)
            ->set_paddingTop((int)$sectionStyles['padding-top'])
            ->set_paddingBottom((int)$sectionStyles['padding-bottom'])
            ->set_paddingRight((int)$sectionStyles['padding-right'])
            ->set_paddingLeft((int)$sectionStyles['padding-left'])
            ->set_marginLeft((int)$sectionStyles['margin-left'])
            ->set_marginRight((int)$sectionStyles['margin-right'])
            ->set_marginTop((int)$sectionStyles['margin-top'])
            ->set_marginBottom((int)$sectionStyles['margin-bottom'])

            ->set_mobilePaddingType('ungrouped')
            ->set_mobilePadding((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingSuffix('px')
            ->set_mobilePaddingTop((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingTopSuffix('px')
            ->set_mobilePaddingRight((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingRightSuffix('px')
            ->set_mobilePaddingBottom((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingBottomSuffix('px')
            ->set_mobilePaddingLeft((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingLeftSuffix('px');

            $image = $context->getBrizySection()->getItemWithDepth(0);
            $image->getValue()->set_marginTop(0);
    }

}
