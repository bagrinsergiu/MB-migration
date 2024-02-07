<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class GalleryLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $slideJson = json_decode($this->brizyKit['slide'], true);

        $declaredAutoplay = $mbSection['settings']['sections']['gallery']['autoplay'] ?? true;

        $arrows = $mbSection['settings']['sections']['gallery']['arrows'] ?? true;
        $markers = $mbSection['settings']['sections']['gallery']['markers'] ?? true;
        $autoplay = count($mbSection['items']) <= 1 ? false : $declaredAutoplay;
        $animation = $mbSection['settings']['sections']['gallery']['transition'] ?? 'Slide';

        $slideDuration = 0.5;
        $transitionDuration = 0.1;
        if (isset($mbSection['settings']['sections']['gallery']['slide_duration'])) {
            $slideDuration = (float)$mbSection['settings']['sections']['gallery']['slide_duration'] ?? 0.5;
        }
        if (isset($mbSection['settings']['sections']['gallery']['transition_duration'])) {
            $transitionDuration = (float)$mbSection['settings']['sections']['gallery']['transition_duration'] ?? 0.1;
        }

        $brizySection->getValue()
            ->set_sliderDots($markers ? "circle" : "none")
            ->set_sliderArrows($arrows ? "heavy" : "none")
            ->set_sliderAutoPlay($autoplay ? "on" : "off")
            ->set_animationName($autoplay ? 'slideInRight' : 'none') // as there is only one animation matc
            ->set_animationDuration($transitionDuration * 1000)
            ->set_animationDelay($slideDuration * 1000);

        $brizySectionItems = [];
        foreach ($mbSection['items'] as $mbItem) {
            $brizySectionItem = new BrizyComponent($slideJson);
            $brizySectionItems[] = $this->setSlideImage($brizySectionItem, $mbItem);
        }

        $brizySection->getValue()->set_items($brizySectionItems);

        return $brizySection;
    }

    protected function setSlideImage(BrizyComponent $brizySectionItem, $mbItem): BrizyComponent
    {
        $brizyComponentValue = $this->getSlideImageComponent($brizySectionItem)->getValue();

        $brizyComponentValue
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_imageSrc($mbItem['content'])
            ->set_imageFileName($mbItem['imageFileName']);

        if (isset($mbItem['settings']['slide']['extension'])) {
            $brizyComponentValue->set_imageExtension($mbItem['settings']['slide']['extension']);
        }

        if (isset($mbItem['settings']['slide']['slide_width'])) {
            $brizyComponentValue->set_width($mbItem['settings']['slide']['slide_width']);
            $brizyComponentValue->set_widthSuffix('px');
        }
        if (isset($mbItem['settings']['slide']['slide_height'])) {
            $brizyComponentValue->set_height($mbItem['settings']['slide']['slide_height']);
            $brizyComponentValue->set_heightSuffix('px');
        }

        return $brizySectionItem;
    }

    abstract protected function getSlideImageComponent(BrizyComponent $brizySectionItem);
}