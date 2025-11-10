<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;
use MBMigration\Builder\Utils\ColorConverter;

class SideMedia extends PhotoTextElement
{
    private $imagePosition = 'left';

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        if ($this->imagePosition == 'right') {
            return $brizySection->getItemWithDepth(0, 0, 1, 0, 0, 0);
        }
        return $brizySection->getItemWithDepth(0, 0, 0, 0, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        if ($this->imagePosition == 'right') {
            return $brizySection->getItemWithDepth(0, 0, 0);
        }
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();

        $text = new BrizyComponent(json_decode($this->brizyKit['textCol'], true));
        $image = new BrizyComponent(json_decode($this->brizyKit['imageCol'], true));
        $main = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $main->getItemWithDepth(0, 0)->getValue()->set_items([$image, $text]);

        if (isset($mbSection['settings']['sections']['text']['photo_position'])) {
            $this->imagePosition = 'right';
            $main->getItemWithDepth(0, 0)->getValue()->set_items([$text, $image]);
        }

        $this->brizyKit['main'] = json_encode($main);
        $brizySection = parent::internalTransformToItem($data);
        return $brizySection;
    }

    protected function getItemImageParentComponent(BrizyComponent $brizySection, $photoPosition = null): BrizyComponent
    {
        if ($this->imagePosition == 'right') {
            return $brizySection->getItemWithDepth(0, 0, 1);
        }
        return $brizySection->getItemWithDepth(0, 0, 0);
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

        $brizyComponent = $context->getBrizySection()->getItemWithDepth(0);
        $brizyComponent
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


        $image = $brizyComponent->getItemWithDepth(0, 0)
            ->getValue()
            ->set_sizeType(null)
            ->set_width(100)
            ->set_height(100)
            ->set_mobileWidth(100)
            ->set_mobileHeight(100)
            ->set_widthSuffix("%")
            ->set_mobileWidthSuffix("%")
            ->set_heightSuffix("%")
            ->set_mobileHeightSuffix("%")
            ->set_marginLeft(0)
            ->set_marginRight(0)
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_mobileMarginLeft(0)
            ->set_mobileMarginRight(0)
            ->set_mobileMarginTop(0)
            ->set_mobileMarginBottom(0);

        $image = $brizyComponent->getItemWithDepth(0)
            ->getValue()
            ->set_marginLeft(0)
            ->set_marginRight(0)
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_mobileMarginLeft(0)
            ->set_mobileMarginRight(0)
            ->set_mobileMarginTop(0)
            ->set_mobileMarginBottom(0);


    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "addingType" => "ungrouped",
            "paddingTop" => 90,
            "paddingBottom" => 90,
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 35,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 35,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function setTopPaddingOfTheFirstElement(
        ElementContextInterface $data,
        BrizyComponent          $section,
        array                   $additionalOptions = [],
        int                     $additionalConstantHeight = 0,
        bool                    $mustBeAdded = false
    ): void
    {

    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set_fullHeight('auto');
    }
}
