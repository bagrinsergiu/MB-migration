<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class PhotoCollageMediaElement extends FullMediaElement
{
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

        $brizyTextContainerComponent = $this->getTextContainerComponent($brizySection);
        $elementTextContainerComponentContext = $data->instanceWithBrizyComponent($brizyTextContainerComponent);
        $this->handleRichTextItems($elementTextContainerComponentContext, $this->browserPage, ['body']);

        $this->handleDonationsButton($elementTextContainerComponentContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ($this->getItemsByCategory($mbSectionItem, 'photo') as $item) {
            $brizyImageComponent = $this->getImageComponent($brizySection);

            $images = $this->getItemsByCategory($mbSectionItem, 'photo');
            $imageMb = array_pop($images);
            $this->handlePhotoItem(
                $imageMb['id'],
                $imageMb,
                $brizyImageComponent,
                $this->browserPage,
                $data->getFontFamilies(),
                $data->getDefaultFontFamily(),
                $imageMb['order_by'] ?? null
            );
        }


        return $brizySection;
    }


}
