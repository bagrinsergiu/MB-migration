<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

abstract class FullMediaElementElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;

    protected function getSectionName(): string
    {
        return "FullMedia";
    }

    /**
     * @throws BadJsonProvided
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $mbSectionItem = $data->getMbSection();

        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);

        $brizyTextContainerComponent = $this->getTextContainerComponent($brizySection);
        $elementTextContainerComponentContext = $data->instanceWithBrizyComponent($brizyTextContainerComponent);

        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $brizySectionItemComponent);

        $this->handleOnlyRichTextItems($elementTextContainerComponentContext, $this->browserPage);
        $this->handleDonationsButton($elementTextContainerComponentContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        $brizyImageComponent = $this->getImageComponent($brizySection);

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);
        $images = $this->getItemsByCategory($mbSectionItem, 'photo');
        $imageMb = array_pop($images);
        
        if ($imageMb === null) {
            return $brizySection;
        }
        
        $brizyImageWrapperComponent = $this->handlePhotoItem(
            $imageMb['id'] ?? $imageMb['sectionId'] ?? '',
            $imageMb,
            $brizyImageComponent,
            $this->browserPage,
            $this->customSettings(),
            $this->imageIndexPosition()
        );

        if (!$this->getReturnAddedImageElement()) {
            $brizyImageWrapperComponent = $this->getImageWrapperComponent($brizySection);
        } else {
            $brizyImageComponent = $brizyImageWrapperComponent->getItemWithDepth(0);
        }

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



        return $brizySection;
    }

    protected function getReturnAddedImageElement() :bool
    {
        return true;
    }

    abstract protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent;

    abstract protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent;

    abstract protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent;

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

}
