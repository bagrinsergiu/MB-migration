<?php

namespace MBMigration\Builder\Layout\Common\Elements\Gallery;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;
use PHPUnit\Exception;

abstract class GalleryLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $rotatorSpeed = 5;

        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $itemImage = new BrizyComponent(json_decode($this->brizyKit['itemImage'], true));

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        try{
            $arrowSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"] .slick-next';
            $arrowsColorStyles = $this->getDomElementStyles($arrowSelector, ['color'], $this->browserPage, [], '','::before');
            $colorArrows = ColorConverter::convertColorRgbToHex($arrowsColorStyles['color']);
        } catch (Exception|ElementNotFound|BrowserScriptException|BadJsonProvided $e){
            $colorArrows = '#FFFFFF';
        }

        $sectionSelector = 'body';
        $backgroundColorStyles = $this->getDomElementStyles($sectionSelector, ['background-color'], $this->browserPage);
        $properties['background-color'] = ColorConverter::convertColorRgbToHex($backgroundColorStyles['background-color']);

        $slideJson = json_decode($this->brizyKit['slide'], true);
        $videoJson = json_decode($this->brizyKit['video'], true);

        $declaredAutoplay = $mbSection['settings']['sections']['gallery']['autoplay'] ?? true;

        $arrows = $mbSection['settings']['sections']['gallery']['arrows'] ?? true;
        $markers = $mbSection['settings']['sections']['gallery']['markers'] ?? true;
        $autoplay = count($mbSection['slide']) <= 1 ? false : $declaredAutoplay;
        //$animation = $mbSection['settings']['sections']['gallery']['transition'] ?? 'Slide';

//        $slideDuration = 0.5;
//        $transitionDuration = 0.1;
//        if (isset($mbSection['settings']['sections']['gallery']['slide_duration'])) {
//            $slideDuration = (float)$mbSection['settings']['sections']['gallery']['slide_duration'] ?? 0.5;
//        }
//        if (isset($mbSection['settings']['sections']['gallery']['transition_duration'])) {
//            $transitionDuration = (float)$mbSection['settings']['sections']['gallery']['transition_duration'] ?? 0.1;
//        }

        $colorArrows = ColorConverter::getContrastColor($properties['background-color'] ?? '#FFFFFF');

        $brizySection->getValue()
            ->set_sliderArrowsColorHex($colorArrows)
            ->set_sliderArrowsColorOpacity(0.75)
            ->set_sliderArrowsColorPalette('')

            ->set_hoverSliderArrowsColorHex($colorArrows)
            ->set_hoverSliderArrowsColorOpacity(1)
            ->set_hoverSliderArrowsColorPalette('')

            ->set_sliderDotsColorHex($colorArrows)
            ->set_sliderDotsColorOpacity(0.75)
            ->set_sliderDotsColorPalette('')

            ->set_hoverSliderDotsColorHex($colorArrows)
            ->set_hoverSliderDotsColorOpacity(1)
            ->set_hoverSliderDotsColorPalette('')

            ->set_fullHeight('custom')
            ->set_sectionHeight(650)

            ->set_mobileFullHeight('custom')
            ->set_mobileSectionHeight(300)

            ->set_tabletSectionHeight(450)
            ->set_tabletSectionHeight(450)

            ->set_sliderDots($markers ? "circle" : "none")
            ->set_sliderArrows($arrows ? "heavy" : "none")
            ->set_sliderAutoPlay($autoplay ? "on" : "off");
//            ->set_animationName($autoplay ? 'slideInRight' : 'none');// as there is only one animation matc
//            ->set_animationDuration($transitionDuration * 1000)
//            ->set_animationDelay($slideDuration * 1000);


        if(isset($mbSection['settings']['sections']['gallery']['transition']) &&
            $mbSection['settings']['sections']['gallery']['transition'] !== 'Slide') {
            $brizySection->getValue()
                ->set_sliderTransition('off');
        } else {
            $brizySection->getValue()
                ->set_sliderTransition('on')
                ->set_sliderAutoPlaySpeed($rotatorSpeed);
        }

        $brizySectionItems = [];

        if (isset($mbSection['settings']['sections']['background']['video'])){
            $brizySectionItem = new BrizyComponent($videoJson);
            $brizyComponentValue = $this->getSlideVideoComponent($brizySectionItem)->getValue();
            $brizyComponentValue
                ->set_media('video')
                ->set_bgVideoType('url')
                ->set_bgVideoCustom('')
                ->set_bgVideo($mbSection['settings']['sections']['background']['video'])
                ->set_bgVideoLoop('on')
                ->set_linkType('external');
            $brizySectionItems[] = $brizySectionItem;

        } else {
            if(count($mbSection['slide']) === 1) {
                $brizySectionItem = new BrizyComponent($slideJson);
                $brizySectionItemImage = $this->getSlideImageComponent($brizySectionItem);

                $this->handleSectionGradient($brizySectionItem, $additionalOptions);

                if(!empty($mbSection['settings']['sections']['background']['photo']) && !empty($mbSection['settings']['sections']['background']['filename'])) {
                    $this->setSlideImage($brizySectionItemImage, $mbSection['settings']['sections']['background'], $properties);
                    $this->setSlideLinks($brizySectionItemImage, $mbSection['settings']['sections']['background']);
                } else {
                    $this->setSlideImage($brizySectionItemImage, $mbSection['slide'][0], $properties);
                    $this->setSlideLinks($brizySectionItemImage, $mbSection['slide'][0]);
                }

                $brizySection->getValue()
                    ->set_slider("off");

                $brizySectionItems[] = $brizySectionItem;
            } else {
                foreach ($mbSection['slide'] as $mbItem) {
                    $brizySectionItem = new BrizyComponent($slideJson);

                    $this->handleSectionGradient($brizySectionItem, $additionalOptions);

                    $brizySectionItemImage = $this->getSlideImageComponent($brizySectionItem);
                    $this->setSlideImage($brizySectionItemImage, $mbItem, $properties);
                    $this->setSlideLinks($brizySectionItemImage, $mbItem);
                    $brizySectionItems[] = $brizySectionItem;
                }
            }
        }

        $brizySection->getValue()->set_items($brizySectionItems);

        return $brizySection;
    }

    protected function setImageItem(BrizyComponent $brizySectionItem, $mbItem, $properties = []): BrizyComponent
    {
        $brizyComponentValue = $brizySectionItem->getItemWithDepth(0)->getValue();
        Logger::instance()->debug('ImageSrc (content): '.$mbItem['content']);
        Logger::instance()->debug('ImageFileName (imageFileName): '.$mbItem['imageFileName']);
        $brizyComponentValue
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_imageSrc($mbItem['content'])
            ->set_imageFileName($mbItem['imageFileName']);

        return $brizySectionItem;
    }

    protected function setSlideImage(BrizyComponent $brizySectionItem, $mbItem, $properties = []): BrizyComponent
    {
        $brizyComponentValue = $brizySectionItem->getValue();
        Logger::instance()->debug('ImageSrc (content): '.($mbItem['content'] ?? $mbItem['photo']));
        Logger::instance()->debug('ImageFileName (imageFileName): '.($mbItem['imageFileName'] ?? $mbItem['filename']));
        $colorCSS = $properties['background-color'] ?? '#ffffff';
        $brizyComponentValue
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_bgImageSrc($mbItem['content'] ?? $mbItem['photo'])
            ->set_bgImageFileName($mbItem['imageFileName'] ?? $mbItem['filename'])
            ->set_customCSS('element{background:' . $colorCSS . '}');

        if (isset($mbItem['settings']['slide']['extension'])) {
            $brizyComponentValue->set_imageExtension($mbItem['settings']['slide']['extension']);
        }

        if(!empty($mbItem['photoOption'])){
            switch ($mbItem['photoOption']){
                case 'parallax-scroll':
                    $brizyComponentValue
                        ->set_sizeType('original')
                        ->set_bgSize('cover')
                        ->set_bgImageType('internal')
                        ->set_bgAttachment('fixed');
                    break;
            }
        } else {
            $brizyComponentValue->set_sizeType('original');
        }

        $brizyComponentValue->set_widthSuffix('px');
        $brizyComponentValue->set_heightSuffix('px');
        $brizyComponentValue->set_mobileWidthSuffix('px');
        $brizyComponentValue->set_mobileHeightSuffix('px');
        $brizyComponentValue->set_tabletWidthSuffix('px');
        $brizyComponentValue->set_tabletHeightSuffix('px');



        if (isset($mbItem['settings']['sections']['gallery']['max_width']) &&
            isset($mbItem['settings']['sections']['gallery']['max_height'])) {
            $brizyComponentValue->set_bgImageWidth($mbItem['settings']['sections']['gallery']['max_width']);
            $brizyComponentValue->set_bgImageHeight($mbItem['settings']['sections']['gallery']['max_height']);
        } else {
            if (isset($mbItem['settings']['slide']['slide_width'])) {
                $brizyComponentValue->set_width($mbItem['settings']['slide']['slide_width']);
                $brizyComponentValue->set_bgImageWidth($mbItem['settings']['slide']['slide_width']);
                $brizyComponentValue->set_tabletWidth($mbItem['settings']['slide']['slide_width']);
                $brizyComponentValue->set_mobileWidth($mbItem['settings']['slide']['slide_width']);
            } elseif (isset($mbItem['settings']['width'])) {
                $brizyComponentValue->set_width($mbItem['settings']['width']);
                $brizyComponentValue->set_bgImageWidth($mbItem['settings']['width']);
                $brizyComponentValue->set_tabletWidth($mbItem['settings']['width']);
                $brizyComponentValue->set_mobileWidth($mbItem['settings']['width']);
            }

            if (isset($mbItem['settings']['slide']['slide_height'])) {
                $brizyComponentValue->set_height($mbItem['settings']['slide']['slide_height']);
                $brizyComponentValue->set_bgImageHeight($mbItem['settings']['slide']['slide_height']);
                $brizyComponentValue->set_tabletHeight($mbItem['settings']['slide']['slide_height']);
                $brizyComponentValue->set_mobileHeight($mbItem['settings']['slide']['slide_height']);
            } elseif (isset($mbItem['settings']['height'])) {
                $brizyComponentValue->set_height($mbItem['settings']['height']);
                $brizyComponentValue->set_bgImageHeight($mbItem['settings']['height']);
                $brizyComponentValue->set_tabletHeight($mbItem['settings']['height']);
                $brizyComponentValue->set_mobileHeight($mbItem['settings']['height']);
            }
        }

        return $brizySectionItem;
    }

    public function setSlideGradient()
    {

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

    protected function getSlideVideoComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 250;
    }


}
