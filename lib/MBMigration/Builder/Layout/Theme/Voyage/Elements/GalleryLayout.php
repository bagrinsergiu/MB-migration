<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class GalleryLayout extends \MBMigration\Builder\Layout\Common\Element\GalleryLayout
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem)
    {
        return $brizySectionItem->getItemWithDepth(0, 0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::transformToItem($data);

        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();
        $brizySection = $data->getBrizySection();

        $selectorSectionStyles = '[data-id="'.$mbSectionItem['sectionId'].'"]';
        $properties = [
            'color',
            'background-color',
            'opacity',
            'border-bottom-color',
            'padding-top',
            'padding-bottom',
            'padding-right',
            'padding-left',
            'margin-top',
            'margin-bottom',
            'margin-left',
            'margin-right',
            'height',
        ];
        $sectionStyles = $this->getDomElementStyles(
            $selectorSectionStyles,
            $properties,
            $this->browserPage,
            $families,
            $defaultFont
        );

        // set section height
        $component = $brizySection->getParent();
        if (in_array($brizySection->getType(), ['Section', 'SectionFooter', 'SectionHeader'])) {
            $component = $brizySection;
        }

        $component
            ->getValue()
            ->set_fullHeight('custom')
            ->set_sectionHeight((int)$sectionStyles['height'])
            ->set_sectionHeightSuffix(strpos($sectionStyles['height'], 'px') !== false ? 'px' : '%');

        return $brizySection;
    }


}