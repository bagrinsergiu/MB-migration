<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Theme\Hope\Hope;
use MBMigration\Builder\Utils\ColorConverter;

class RightMediaElement extends FullMediaElement
{
    protected function getHeaderContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 1, 0);
    }

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1, 0, 0);
    }

    protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1, 0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $mbSectionItem = $data->getMbSection();

        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $brizySectionItemComponent);

        $brizyHeaderContainerComponent = $this->getHeaderContainerComponent($brizySection);
        $elementTextContainerComponentContext = $data->instanceWithBrizyComponent($brizyHeaderContainerComponent);
        $this->handleRichTextItems($elementTextContainerComponentContext, $this->browserPage, ['title']);

        $item = $this->getItemByType($mbSectionItem, 'title');
        $styles = Hope::getStyles(
            '[data-id="' . $item['id'] . '"] div',
            ['border-top-color'],
            $this->browserPage,
            '::before'
        );

        $topLine = new BrizyComponent(json_decode($this->brizyKit['line-left-top'], true));
        $topLine->getItemValueWithDepth(0)->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));

        $bottomLine = new BrizyComponent(json_decode($this->brizyKit['line-left-bottom'], true));
        $bottomLine->getItemValueWithDepth(0)->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));

        $brizyHeaderContainerComponent->getValue()->add_items($topLine, 0);
        $brizyHeaderContainerComponent->getValue()->add_items($bottomLine, 2);

        $brizyTextContainerComponent = $this->getTextContainerComponent($brizySection);
        $elementTextContainerComponentContext = $data->instanceWithBrizyComponent($brizyTextContainerComponent);
        $this->handleRichTextItems($elementTextContainerComponentContext, $this->browserPage, ['body']);

        $this->handleDonationsButton($elementTextContainerComponentContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        $brizyImageWrapperComponent = $this->getImageWrapperComponent($brizySection);
        $brizyImageComponent = $this->getImageComponent($brizySection);

        // configure the image wrapper
        $brizyImageWrapperComponent->getValue()
            ->set_marginType("ungrouped")
            ->set_margin(0)
            ->set_marginSuffix("px")
            ->set_marginTop(10)
            ->set_marginTopSuffix("px")
            ->set_marginRight(0)
            ->set_marginRightSuffix("px")
            ->set_marginBottom(30)
            ->set_marginBottomSuffix("px")
            ->set_marginLeft(0)
            ->set_marginLeftSuffix("px");

        $brizyImageComponent->getValue()
            ->set_width(100)
            ->set_mobileSize(100)
            ->set_widthSuffix('%')
            ->set_height('')
            ->set_heightSuffix('');

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);
        $images = $this->getItemsByCategory($mbSectionItem, 'photo');
        $imageMb = array_pop($images);
        $this->handlePhotoItem(
            $imageMb['id'],
            $imageMb,
            $brizyImageComponent,
            $this->browserPage,
            []
        );

        return $brizySection;
    }


}
