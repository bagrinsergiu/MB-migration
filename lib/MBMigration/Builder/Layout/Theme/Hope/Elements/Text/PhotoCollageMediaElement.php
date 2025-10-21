<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Theme\Hope\Hope;
use MBMigration\Builder\Utils\ColorConverter;

class PhotoCollageMediaElement extends FullMediaElement
{
    use RichTextAble;

    private $imageCount = 0;

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        $i = $this->imageCount++;

        switch ($i) {
            case 0:
                return $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
            case 1:
                return $brizySection->getItemWithDepth(0, 0, 1, 0, 0);
            case 2:
                return $brizySection->getItemWithDepth(0, 0, 1, 1, 0, 0, 0);
            case 3:
                return $brizySection->getItemWithDepth(0, 0, 1, 1, 1, 0, 0);
            default:
                throw new \Exception('PhotoCollageMediaElement supports maximum 4 images.');
        }
    }

    protected function getHeaderContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 2, 0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 3, 0);
    }

    protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent
    {
        throw new \Exception('PhotoCollageMediaElement supports maximum 4 images.');
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

        // handle line color

        $item = $this->getItemByType($mbSectionItem, 'title');
        $styles = Hope::getStyles(
            '[data-id="' . $item['id'] . '"] div',
            ['border-top-color'],
            $this->browserPage,
            '::before'
        );
        if (isset($styles['border-top-color'])) {
            $brizyComponentValue = $brizySection->getItemValueWithDepth(0, 2, 1, 0, 0);
            $brizyComponentValue->set_borderColorHex(ColorConverter::convertColorRgbToHex($styles['border-top-color']));
        }


        $brizyTextContainerComponent = $this->getTextContainerComponent($brizySection);
        $elementTextContainerComponentContext = $data->instanceWithBrizyComponent($brizyTextContainerComponent);
        $this->handleRichTextItems($elementTextContainerComponentContext, $this->browserPage, ['body']);

        $this->handleDonationsButton($elementTextContainerComponentContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ($this->getItemsByCategory($mbSectionItem, 'photo') as $item) {
            $brizyImageComponent = $this->getImageComponent($brizySection);

            $brizyImageComponent->getValue()
                ->set_imageFileName($item['imageFileName'])
                ->set_imageSrc($item['content']);

            $this->handleLink(
                $mbSectionItem,
                $brizyImageComponent,
                '[data-id="' . $item['id'] . '"] div.photo-container a',
                $this->browserPage);
        }


        return $brizySection;
    }


}
