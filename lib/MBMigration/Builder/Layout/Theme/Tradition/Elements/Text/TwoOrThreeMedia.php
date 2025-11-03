<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;
use MBMigration\Builder\Utils\ColorConverter;

class TwoOrThreeMedia extends PhotoTextElement
{
    private $imageCount = 0;

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, $this->imageCount++, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getItemImageParentComponent(BrizyComponent $brizySection, $photoPosition = null)
    {
        return $brizySection->getItemWithDepth(0, 1, $this->imageCount - 1, 0, 0);
    }

    protected function getPropertiesMainSection(): array
    {
        return [

            "tabletPaddingType" => "ungrouped",
            "tabletPadding" => 0,
            "tabletPaddingSuffix" => "px",
            "tabletPaddingTop" => 90,
            "tabletPaddingTopSuffix" => "px",
            "tabletPaddingRight" => 20,
            "tabletPaddingRightSuffix" => "px",
            "tabletPaddingBottom" => 90,
            "tabletPaddingBottomSuffix" => "px",
            "tabletPaddingLeft" => 20,
            "tabletPaddingLeftSuffix" => "px",

            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 90,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 90,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 90,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 90,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }

    protected function handleImageParentStyles(ElementContextInterface $context): void
    {
        static $sectionStyles = null;


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

        if (!$sectionStyles)
            $sectionStyles = $this->getDomElementStyles($selector, $properties, $this->browserPage, $families, $defaultFont);

        $context->getBrizySection()->getValue()
            ->set_borderColorHex('')
            ->set_borderColorOpacity(0);
    }
}
