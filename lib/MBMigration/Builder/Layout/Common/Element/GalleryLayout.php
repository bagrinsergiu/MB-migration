<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Core\Logger;

abstract class GalleryLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
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
        //$animation = $mbSection['settings']['sections']['gallery']['transition'] ?? 'Slide';

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
            $brizySectionItem = $this->getSlideImageComponent($brizySectionItem);
            $brizySectionItem = $this->setSlideImage($brizySectionItem, $mbItem);
            $brizySectionItem = $this->setSlideLinks($brizySectionItem, $mbItem);
            $brizySectionItems[] = $brizySectionItem;
        }

        $brizySection->getValue()->set_items($brizySectionItems);

        return $brizySection;
    }

    protected function setSlideImage(BrizyComponent $brizySectionItem, $mbItem): BrizyComponent
    {
        $brizyComponentValue = $brizySectionItem->getValue();
        Logger::instance()->debug('ImageSrc (content): '.$mbItem['content']);
        Logger::instance()->debug('ImageFileName (imageFileName): '.$mbItem['imageFileName']);
        $brizyComponentValue
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_imageSrc($mbItem['content'])
            ->set_imageFileName($mbItem['imageFileName']);

        if (isset($mbItem['settings']['slide']['extension'])) {
            $brizyComponentValue->set_imageExtension($mbItem['settings']['slide']['extension']);
        }

        $brizyComponentValue->set_widthSuffix('px');
        $brizyComponentValue->set_heightSuffix('px');
        $brizyComponentValue->set_mobileWidthSuffix('px');
        $brizyComponentValue->set_mobileHeightSuffix('px');
        $brizyComponentValue->set_tabletWidthSuffix('px');
        $brizyComponentValue->set_tabletHeightSuffix('px');

        if (isset($mbItem['settings']['slide']['slide_width'])) {
            $brizyComponentValue->set_width($mbItem['settings']['slide']['slide_width']);
            $brizyComponentValue->set_imageWidth($mbItem['settings']['slide']['slide_width']);
            $brizyComponentValue->set_tabletWidth($mbItem['settings']['slide']['slide_width']);
            $brizyComponentValue->set_mobileWidth($mbItem['settings']['slide']['slide_width']);
        } elseif (isset($mbItem['settings']['width'])) {
            $brizyComponentValue->set_width($mbItem['settings']['width']);
            $brizyComponentValue->set_imageWidth($mbItem['settings']['width']);
            $brizyComponentValue->set_tabletWidth($mbItem['settings']['width']);
            $brizyComponentValue->set_mobileWidth($mbItem['settings']['width']);
        }

        if (isset($mbItem['settings']['slide']['slide_height'])) {
            $brizyComponentValue->set_height($mbItem['settings']['slide']['slide_height']);
            $brizyComponentValue->set_imageHeight($mbItem['settings']['slide']['slide_height']);
            $brizyComponentValue->set_tabletHeight($mbItem['settings']['slide']['slide_height']);
            $brizyComponentValue->set_mobileHeight($mbItem['settings']['slide']['slide_height']);
        } elseif (isset($mbItem['settings']['height'])) {
            $brizyComponentValue->set_height($mbItem['settings']['height']);
            $brizyComponentValue->set_imageHeight($mbItem['settings']['height']);
            $brizyComponentValue->set_tabletHeight($mbItem['settings']['height']);
            $brizyComponentValue->set_mobileHeight($mbItem['settings']['height']);
        }

        return $brizySectionItem;
    }

    protected function setSlideLinks(BrizyComponent $brizySectionItem, $mbItem): BrizyComponent
    {
        $brizyComponentValue = $brizySectionItem->getValue();

        if (!empty($mbItem['link'])) {
            $new_window = 'off';
            if ($mbItem['new_window']) {
                $new_window = 'on';
            }
            $slash = '/';
            $brizyComponentValue->set_linkType('external');
            $brizyComponentValue->set_linkExternalBlank($new_window);

            $linkType = 'string';
            if (filter_var($mbItem['link'], FILTER_VALIDATE_EMAIL)) {
                $linkType = 'mail';
            }
            if (filter_var($mbItem['link'], FILTER_VALIDATE_URL)) {
                $linkType = 'link';
            }
            if ($this->checkPhoneNumber($mbItem['link'])) {
                $linkType = 'phone';
            }

            switch ($linkType) {
                case 'mail':
                    $brizyComponentValue->set_linkExternal('mailto:'.$mbItem['link']);
                    break;
                case 'phone':
                    $brizyComponentValue->set_linkExternal('tel:'.$mbItem['link']);
                    break;
                case 'string':
                case 'link':
                default:
                    $brizyComponentValue->set_linkExternal($mbItem['link']);
                    break;
            }
        }

        return $brizySectionItem;
    }

    abstract protected function getSlideImageComponent(BrizyComponent $brizySectionItem);

    //abstract protected function getSlideImageComponent(BrizyComponent $brizySectionItem);
    public function checkPhoneNumber($str)
    {
        if (!preg_match("/^(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\$/", $str)) {

            return false;
        }

        $number = preg_replace('/[^0-9]/', '', $str);

        if (ctype_digit($number)) {

            return true;
        } else {

            return false;
        }
    }


}