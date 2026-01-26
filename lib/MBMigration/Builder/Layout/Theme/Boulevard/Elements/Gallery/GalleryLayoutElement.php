<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Gallery;

use Exception;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Utils\ColorConverter;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{

    protected function getSlideLocation(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0,0);
    }

    protected function getSlideImageComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function customizationSlide(BrizyComponent $brizySectionItem): BrizyComponent
    {
        $brizySectionItem
            ->addMobilePadding([0,10,0,10])
            ->addTabletPadding([0,10,0,10]);

        $brizySectionItem->getValue()
            ->set_slidesToShow(1)
            ->set_tabletSlidesToShow(1)
            ->set_mobileSlidesToShow(1)

            ->set_sliderPaddingType('ungrouped')
            ->set_sliderPadding(0)
            ->set_sliderPaddingSuffix("px")
            ->set_sliderPaddingTop(10)
            ->set_sliderPaddingTopSuffix("px")
            ->set_sliderPaddingRight(15)
            ->set_sliderPaddingRightSuffix("px")
            ->set_sliderPaddingBottom(30)
            ->set_sliderPaddingBottomSuffix("px")
            ->set_sliderPaddingLeft(15)
            ->set_sliderPaddingLeftSuffix("px");

        return $brizySectionItem;
    }

    protected function customizationSection(BrizyComponent $brizySectionItem):BrizyComponent
    {
        $brizySectionItem->getValue()
            ->set_paddingType('ungrouped')
            ->set_paddingTop(0)
            ->set_paddingBottom(0);

        return $brizySectionItem;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    protected function getHeightSlideStyl(): string
    {
        return "custom";
    }

    protected function getMobileBgSizeType(): string
    {
        return "custom";
    }
    function getMobileBgSize(): string
    {
        return "contain";
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        // Вызываем родительский метод для базовой реализации
        $brizySection = parent::internalTransformToItem($data);
        
        $mbSection = $data->getMbSection();
        
        // Извлекаем цвет фона секции для определения контрастного цвета стрелок
        $sectionSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        
        try {
            $backgroundColorStyles = $this->getDomElementStyles($sectionSelector, ['background-color'], $this->browserPage);
            $properties['background-color'] = ColorConverter::convertColorRgbToHex($backgroundColorStyles['background-color'] ?? '#ffffff');
        } catch (Exception|ElementNotFound|BrowserScriptException|BadJsonProvided $e) {
            $properties['background-color'] = '#ffffff';
        }
        
        // Определяем контрастный цвет стрелок на основе фона секции
        // getArrowColorByBackground возвращает белый (#FFFFFF) для темного фона и черный (#000000) для светлого
        $backgroundColor = is_array($properties['background-color']) 
            ? ($properties['background-color']['color'] ?? $properties['background-color'])
            : $properties['background-color'];
        
        $colorArrows = $this->getArrowColorByBackground('#FFFFFF', $backgroundColor);
        
        // Получаем компонент Carousel (где находятся слайды) - используем getSlideLocation
        // getSlideLocation возвращает компонент на глубине (0,0,0) для Boulevard
        $carouselComponent = $this->getSlideLocation($brizySection);
        
        // Устанавливаем настройки цвета стрелок и точек для Boulevard на компонент Carousel: opacity = 1 (вместо 0.75)
        $carouselComponent->getValue()
            ->set_sliderArrowsColorHex($colorArrows)
            ->set_sliderArrowsColorOpacity(1)  // Boulevard: opacity = 1
            ->set_sliderArrowsColorPalette('')
            
            ->set_hoverSliderArrowsColorHex($colorArrows)
            ->set_hoverSliderArrowsColorOpacity(1)
            ->set_hoverSliderArrowsColorPalette('')
            
            ->set_sliderDotsColorHex($colorArrows)
            ->set_sliderDotsColorOpacity(1)  // Boulevard: opacity = 1 для точек
            ->set_sliderDotsColorPalette('')
            
            ->set_hoverSliderDotsColorHex($colorArrows)
            ->set_hoverSliderDotsColorOpacity(1)
            ->set_hoverSliderDotsColorPalette('');
        
        return $brizySection;
    }
}
