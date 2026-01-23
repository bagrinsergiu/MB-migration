<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Gallery;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;
use MBMigration\Core\Logger;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0,0);
        //return $brizySectionItem;
    }

    protected function getSlideVideoComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType"=> "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function sectionIndentations(BrizyComponent $section)
    {
    }

    /**
     * Обработка и нормализация стилей для slides
     * Получает стили каждого слайда из DOM и нормализует их
     * 
     * @param ElementContextInterface $data Контекст элемента
     * @param array $mbSectionItem Элемент секции из исходного проекта
     * @return array Нормализованные стили для всех slides (SlidesStyles)
     */
    protected function handleStyle(ElementContextInterface $data, array $mbSectionItem): array
    {
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];
        $sectionSelector = '[data-id="' . $sectionId . '"]';
        
        // Список свойств стилей для получения из DOM
        $styleProperties = [
            'height',
            'min-height',
            'max-height',
            'width',
            'padding-top',
            'padding-right',
            'padding-bottom',
            'padding-left',
            'margin-top',
            'margin-right',
            'margin-bottom',
            'margin-left',
            'border-radius',
            'border-top-left-radius',
            'border-top-right-radius',
            'border-bottom-left-radius',
            'border-bottom-right-radius',
            'background-size',
            'background-position',
            'opacity'
        ];
        
        // Получаем стили секции для fallback
        $sectionStyles = $this->getDomElementStyles(
            $sectionSelector,
            $styleProperties,
            $this->browserPage,
            $data->getFontFamilies(),
            $data->getDefaultFontFamily()
        );
        
        // Получаем количество slides
        $slidesCount = count($mbSectionItem['slide'] ?? []);
        if ($slidesCount === 0) {
            $slidesCount = 1; // Минимум один slide
        }
        
        // Массив для хранения нормализованных стилей всех slides
        $slidesStyles = [];
        
        // Получаем стили для каждого slide
        for ($i = 0; $i < $slidesCount; $i++) {
            // Пытаемся найти конкретный slide по индексу
            $slideSelector = $sectionSelector . ' .slick-slide:nth-child(' . ($i + 1) . '), ' .
                           $sectionSelector . ' .slide:nth-child(' . ($i + 1) . ')';
            
            $slideStyles = $this->getDomElementStyles(
                $slideSelector,
                $styleProperties,
                $this->browserPage,
                $data->getFontFamilies(),
                $data->getDefaultFontFamily()
            );
            
            // Если не нашли конкретный slide, используем стили секции
            if (empty($slideStyles)) {
                $slideStyles = $sectionStyles;
            }
            
            // Нормализуем стили для текущего slide
            $normalizedStyles = $this->normalizeSlideStyles($slideStyles, $sectionStyles);
            
            $slidesStyles[] = $normalizedStyles;
        }
        
        // Если не получили стили для slides, создаем один нормализованный набор из стилей секции
        if (empty($slidesStyles)) {
            $normalizedStyles = $this->normalizeSlideStyles($sectionStyles, $sectionStyles);
            $slidesStyles[] = $normalizedStyles;
        }
        
        Logger::instance()->debug('GalleryLayoutElement: Normalized slides styles', [
            'slidesCount' => count($slidesStyles),
            'styles' => $slidesStyles
        ]);
        
        return $slidesStyles;
    }
    
    /**
     * Нормализация стилей одного slide
     * Преобразует CSS стили в формат параметров BrizyComponent
     * 
     * @param array $slideStyles Стили slide из DOM
     * @param array $sectionStyles Стили секции (для fallback)
     * @return array Нормализованные стили в формате параметров шаблона
     */
    protected function normalizeSlideStyles(array $slideStyles, array $sectionStyles): array
    {
        $normalized = [];
        
        // Height - ВАЖНО: это высота для Column (картинки)
        $height = $this->extractNumericValue($slideStyles['height'] ?? $sectionStyles['height'] ?? '650px');
        $normalized['height'] = $height;
        $normalized['heightStyle'] = 'custom';
        
        // Background Size
        $bgSize = $slideStyles['background-size'] ?? $sectionStyles['background-size'] ?? 'cover';
        $normalized['bgSize'] = $this->normalizeBackgroundSize($bgSize);
        $normalized['bgSizeType'] = 'original';
        
        // Padding
        $normalized['paddingType'] = 'ungrouped';
        $normalized['paddingTop'] = $this->extractNumericValue($slideStyles['padding-top'] ?? $sectionStyles['padding-top'] ?? '0px');
        $normalized['paddingRight'] = $this->extractNumericValue($slideStyles['padding-right'] ?? $sectionStyles['padding-right'] ?? '0px');
        $normalized['paddingBottom'] = $this->extractNumericValue($slideStyles['padding-bottom'] ?? $sectionStyles['padding-bottom'] ?? '0px');
        $normalized['paddingLeft'] = $this->extractNumericValue($slideStyles['padding-left'] ?? $sectionStyles['padding-left'] ?? '0px');
        $normalized['paddingSuffix'] = 'px';
        $normalized['paddingTopSuffix'] = 'px';
        $normalized['paddingRightSuffix'] = 'px';
        $normalized['paddingBottomSuffix'] = 'px';
        $normalized['paddingLeftSuffix'] = 'px';
        
        // Tablet Padding
        $normalized['tabletPaddingType'] = 'ungrouped';
        $normalized['tabletPaddingTop'] = max(0, (int)($normalized['paddingTop'] * 0.2));
        $normalized['tabletPaddingBottom'] = max(0, (int)($normalized['paddingBottom'] * 0.2));
        $normalized['tabletPaddingTopSuffix'] = 'px';
        $normalized['tabletPaddingBottomSuffix'] = 'px';
        
        // Mobile Padding
        $normalized['mobilePaddingType'] = 'ungrouped';
        $normalized['mobilePaddingTop'] = max(0, (int)($normalized['paddingTop'] * 0.4));
        $normalized['mobilePaddingRight'] = max(0, (int)($normalized['paddingRight'] * 0.6));
        $normalized['mobilePaddingBottom'] = max(0, (int)($normalized['paddingBottom'] * 0.4));
        $normalized['mobilePaddingLeft'] = max(0, (int)($normalized['paddingLeft'] * 0.6));
        $normalized['mobilePaddingSuffix'] = 'px';
        $normalized['mobilePaddingTopSuffix'] = 'px';
        $normalized['mobilePaddingRightSuffix'] = 'px';
        $normalized['mobilePaddingBottomSuffix'] = 'px';
        $normalized['mobilePaddingLeftSuffix'] = 'px';
        
        // Margin
        $normalized['marginType'] = 'ungrouped';
        $normalized['marginTop'] = $this->extractNumericValue($slideStyles['margin-top'] ?? $sectionStyles['margin-top'] ?? '0px');
        $normalized['marginRight'] = $this->extractNumericValue($slideStyles['margin-right'] ?? $sectionStyles['margin-right'] ?? '0px');
        $normalized['marginBottom'] = $this->extractNumericValue($slideStyles['margin-bottom'] ?? $sectionStyles['margin-bottom'] ?? '0px');
        $normalized['marginLeft'] = $this->extractNumericValue($slideStyles['margin-left'] ?? $sectionStyles['margin-left'] ?? '0px');
        $normalized['marginSuffix'] = 'px';
        $normalized['marginTopSuffix'] = 'px';
        $normalized['marginRightSuffix'] = 'px';
        $normalized['marginBottomSuffix'] = 'px';
        $normalized['marginLeftSuffix'] = 'px';
        
        // Mobile Margin
        $normalized['mobileMarginType'] = 'ungrouped';
        $normalized['mobileMargin'] = 10;
        $normalized['mobileMarginTop'] = 0;
        $normalized['mobileMarginRight'] = 0;
        $normalized['mobileMarginBottom'] = 0;
        $normalized['mobileMarginLeft'] = 0;
        $normalized['mobileMarginSuffix'] = 'px';
        $normalized['mobileMarginTopSuffix'] = 'px';
        $normalized['mobileMarginRightSuffix'] = 'px';
        $normalized['mobileMarginBottomSuffix'] = 'px';
        $normalized['mobileMarginLeftSuffix'] = 'px';
        
        // Border Radius
        $borderRadius = $this->extractNumericValue($slideStyles['border-radius'] ?? $sectionStyles['border-radius'] ?? '0px');
        $normalized['borderRadius'] = $borderRadius;
        $normalized['borderTopLeftRadius'] = $this->extractNumericValue($slideStyles['border-top-left-radius'] ?? $sectionStyles['border-top-left-radius'] ?? $borderRadius . 'px');
        $normalized['borderTopRightRadius'] = $this->extractNumericValue($slideStyles['border-top-right-radius'] ?? $sectionStyles['border-top-right-radius'] ?? $borderRadius . 'px');
        $normalized['borderBottomLeftRadius'] = $this->extractNumericValue($slideStyles['border-bottom-left-radius'] ?? $sectionStyles['border-bottom-left-radius'] ?? $borderRadius . 'px');
        $normalized['borderBottomRightRadius'] = $this->extractNumericValue($slideStyles['border-bottom-right-radius'] ?? $sectionStyles['border-bottom-right-radius'] ?? $borderRadius . 'px');
        
        // Opacity
        $opacity = $slideStyles['opacity'] ?? $sectionStyles['opacity'] ?? '1';
        if (is_string($opacity)) {
            $opacity = (float)$opacity;
        }
        $normalized['opacity'] = NumberProcessor::convertToNumeric($opacity);
        
        return $normalized;
    }
    
    /**
     * Извлечение числового значения из CSS значения (например, "10px" -> 10)
     */
    protected function extractNumericValue(string $value): int
    {
        $value = trim($value);
        $numeric = preg_replace('/[^0-9.-]/', '', $value);
        return (int)max(0, $numeric);
    }
    
    /**
     * Нормализация background-size
     */
    protected function normalizeBackgroundSize(string $bgSize): string
    {
        $bgSize = strtolower(trim($bgSize));
        
        if (in_array($bgSize, ['cover', 'contain', 'auto'])) {
            return $bgSize;
        }
        
        // Если это конкретные размеры, возвращаем 'cover' по умолчанию
        return 'cover';
    }

    /**
     * Переопределяем applySlideStyles для Aurora - применяем стили только к Column (картинке)
     */
    protected function applySlideStyles(BrizyComponent $brizySectionItem, BrizyComponent $brizySectionItemImage, array $styles): void
    {
        if (empty($styles)) {
            return;
        }

        // Применяем стили только к Column (картинке), а не к SectionItem
        // Column - это getItemWithDepth(0,0,0) = картинка внутри слайда
        $imageValue = $brizySectionItemImage->getValue();

        // Список свойств, которые не должны перезаписываться (уже установлены в setSlideImage)
        $protectedImageProperties = [
            'bgImageSrc', 'bgImageFileName', 'bgImageType', 'bgImageWidth', 'bgImageHeight',
            'imageExtension', 'customCSS', 'sizeType', 'width', 'height',
            'widthSuffix', 'heightSuffix', 'tabletWidthSuffix', 'tabletHeightSuffix',
            'mobileBgSizeType', 'mobileBgSize', 'mobileHeightSuffix',
            'marginTop', 'marginBottom' // Эти свойства уже установлены в setSlideImage
        ];

        // Применяем стили к Column (картинке)
        $imageStyles = [
            'height', 'heightStyle', // ВАЖНО: высота для картинки
            'bgSize', 'bgSizeType',
            'paddingType', 'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft',
            'paddingTopSuffix', 'paddingRightSuffix', 'paddingBottomSuffix', 'paddingLeftSuffix',
            'tabletPaddingType', 'tabletPaddingTop', 'tabletPaddingBottom',
            'tabletPaddingTopSuffix', 'tabletPaddingBottomSuffix',
            'mobilePaddingType', 'mobilePaddingTop', 'mobilePaddingRight', 'mobilePaddingBottom', 'mobilePaddingLeft',
            'mobilePaddingSuffix', 'mobilePaddingTopSuffix', 'mobilePaddingRightSuffix',
            'mobilePaddingBottomSuffix', 'mobilePaddingLeftSuffix',
            'marginType', 'marginTop', 'marginRight', 'marginBottom', 'marginLeft',
            'marginSuffix', 'marginTopSuffix', 'marginRightSuffix', 'marginBottomSuffix', 'marginLeftSuffix',
            'mobileMarginType', 'mobileMargin', 'mobileMarginTop', 'mobileMarginRight',
            'mobileMarginBottom', 'mobileMarginLeft',
            'mobileMarginSuffix', 'mobileMarginTopSuffix', 'mobileMarginRightSuffix',
            'mobileMarginBottomSuffix', 'mobileMarginLeftSuffix',
            'borderRadius', 'borderTopLeftRadius', 'borderTopRightRadius',
            'borderBottomLeftRadius', 'borderBottomRightRadius',
            'opacity'
        ];

        foreach ($imageStyles as $key) {
            if (!isset($styles[$key]) || in_array($key, $protectedImageProperties)) {
                continue;
            }

            $method = 'set_' . $key;
            if (method_exists($imageValue, $method)) {
                try {
                    $imageValue->$method($styles[$key]);
                } catch (\Exception $e) {
                    Logger::instance()->debug("Failed to set style {$key} on image: " . $e->getMessage());
                }
            } else {
                try {
                    $imageValue->set($key, $styles[$key]);
                } catch (\Exception $e) {
                    Logger::instance()->debug("Failed to set property {$key} on image: " . $e->getMessage());
                }
            }
        }
    }
}
