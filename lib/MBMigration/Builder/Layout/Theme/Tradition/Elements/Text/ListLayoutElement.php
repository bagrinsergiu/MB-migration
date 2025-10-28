<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string         $photoPosition
    ): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 1 : 0);
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string         $photoPosition
    ): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0, 0);
    }

    protected function getItemImageParentComponent(
        BrizyComponent $brizyComponent,
        string         $photoPosition
    ): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0);
    }

    protected function handleMbPhotoItem(ElementContextInterface $data, $parentSectionItem, $photoPosition, $mbItem)
    {
        $imageComponent = $this->getItemImageComponent($parentSectionItem, $photoPosition);
        $elementContext = $data->instanceWithBrizyComponentAndMBSection($mbItem, $imageComponent);
        $this->handleRichTextItem($elementContext, $this->browserPage);

        $elementContext->getBrizySection()->getItemWithDepth(0)->addMargin(0, 0, 0, 0);

        $elementContext = $data->instanceWithBrizyComponentAndMBSection($mbItem, $this->getItemImageParentComponent($parentSectionItem, $photoPosition));
        $this->handleImageParentStyles($elementContext);
    }

    protected function handleImageParentStyles(ElementContextInterface $context): void
    {
        $mbSectionItem = $context->getMbSection();
        $families = $context->getFontFamilies();
        $defaultFont = $context->getDefaultFontFamily();
        $selector = '.photo-content-container:has([data-id="' . ($mbSectionItem['sectionId'] ?? $mbSectionItem['id']) . '"])';
        $properties = [
            'background-color',
            'opacity',
            'border-color',
            'border-width',
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

        $context->getBrizySection()->getItemWithDepth(0)
            ->getValue()
            ->set_paddingType('ungrouped')
            ->set_marginType('ungrouped')
            ->set_borderWidthType('ungrouped')
            ->set_borderStyle('solid')
            ->set_borderColorHex(ColorConverter::convertColorRgbToHex($sectionStyles['border-color']))
            ->set_borderColorOpacity(1)
            ->set_borderColorPalette(null)
            ->set_borderWidth((int)$sectionStyles['border-width'])
            ->set_borderTopWidth((int)$sectionStyles['border-width'])
            ->set_borderRightWidth((int)$sectionStyles['border-width'])
            ->set_borderLeftWidth((int)$sectionStyles['border-width'])
            ->set_borderBottomWidth((int)$sectionStyles['border-width'])
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


        $w = $context->getBrizySection()->getItemWithDepth(0)->getValue();
        $w
        ->set_bgColorOpacity('1.0')
        ->set_bgColorHex(ColorConverter::convertColorRgbToHex($sectionStyles['background-color']))
        ->set_mobileBgColorHex(ColorConverter::convertColorRgbToHex($sectionStyles['background-color']));

    }

    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $itemsKit = $data->getThemeContext()->getBrizyKit();

        $wrapperLine = new BrizyComponent(json_decode($itemsKit['global']['wrapper--line'], true));
        if (!isset($mbSectionItem['item_type']) || $mbSectionItem['item_type'] !== 'title') {
            $titleMb = $this->getByType($mbSectionItem['head'], 'title');
        } else {
            $titleMb['id'] = $mbSectionItem['id'];
        }

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

        $brizySection->getValue()->add_items([$wrapperLine]);

        return $brizySection;
    }

    protected function handleRowListItem(BrizyComponent $brizySection, $position = 'left'): void
    {
        //$brizySection
        //->getItemWithDepth(0)
        //->addPadding(0, 0, 0, 0)
        //->addMargin(15, 0, 0, 0);
    }
}
