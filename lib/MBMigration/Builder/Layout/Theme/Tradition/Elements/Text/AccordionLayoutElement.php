<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class AccordionLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\AccordionLayoutElement
{
    protected function getAccordionSectionComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getSectionHeaderComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getAccordionParentComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function handleAccordeonSyles(ElementContextInterface $context): void
    {
        $mbSectionItem = $context->getMbSection();
        $families = $context->getFontFamilies();
        $defaultFont = $context->getDefaultFontFamily();
        $selector = '[data-id="' . ($mbSectionItem['sectionId'] ?? $mbSectionItem['id']) . '"] .accordion-item';
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
            'border-top-width',
            'border-bottom-width',
            'border-left-width',
            'border-right-width',
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
            ->set_borderLeftWidth((int)$sectionStyles['border-left-width'])
            ->set_borderRightWidth((int)$sectionStyles['border-right-width'])
            ->set_borderTopWidth((int)$sectionStyles['border-top-width'])
            ->set_borderBottomWidth((int)$sectionStyles['border-bottom-width'])

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

    }
}
