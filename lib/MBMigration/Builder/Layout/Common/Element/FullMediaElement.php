<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class FullMediaElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DanationsAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $mbSectionItem = $data->getMbSection();

        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $this->setTopPaddingOfTheFirstElement($data, $brizySectionItemComponent);

        $brizySectionItemComponent = $this->getTextContainerComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

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
            ->set_widthSuffix('%')
            ->set_height('')
            ->set_heightSuffix('');

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);
        $images = $this->getItemsByCategory($mbSectionItem, 'photo');
        $imageMb = array_pop($images);
        $this->handlePhotoItem($imageMb['id'],$imageMb,$brizyImageComponent,$this->browserPage,$data->getFontFamilies(),$data->getDefaultFontFamily());

        return $brizySection;
    }

    abstract protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent;

    abstract protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent;

    abstract protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent;
}
