<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;
use MBMigration\Builder\Media\MediaController;
use MBMigration\Browser\BrowserPageInterface;

class FullTextBlurBox extends FullTextElement
{
    use SectionStylesAble;

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getInsideItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        // Возвращаем внутренний Column (0,0,0,0,0) - Column внутри Row внутри внешнего Column
        // Структура: SectionItem(0) -> Row(0,0) -> Column(0,0,0) -> Row(0,0,0,0) -> Column(0,0,0,0,0)
        $innerColumn = $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
        
        // Если внутренний Column не найден, пытаемся получить его через альтернативный путь
        if (!$innerColumn) {
            // Попробуем получить через внешний Column -> Row -> Column
            $outerColumn = $brizySection->getItemWithDepth(0, 0, 0);
            if ($outerColumn) {
                $innerRow = $outerColumn->getItemWithDepth(0);
                if ($innerRow) {
                    $innerColumn = $innerRow->getItemWithDepth(0);
                }
            }
        }
        
        return $innerColumn ?: $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        // Возвращаем внутренний Column (0,0,0,0,0) - Column внутри Row внутри внешнего Column
        // Структура: SectionItem(0) -> Row(0,0) -> Column(0,0,0) -> Row(0,0,0,0) -> Column(0,0,0,0,0)
        $innerColumn = $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
        
        // Если внутренний Column не найден, пытаемся получить его через альтернативный путь
        if (!$innerColumn) {
            // Попробуем получить через внешний Column -> Row -> Column
            $outerColumn = $brizySection->getItemWithDepth(0, 0, 0);
            if ($outerColumn) {
                $innerRow = $outerColumn->getItemWithDepth(0);
                if ($innerRow) {
                    $innerColumn = $innerRow->getItemWithDepth(0);
                }
            }
        }
        
        return $innerColumn ?: $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $brizySection->getValue()->set_marginBottum(0);

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $insideItemComponent = $this->getInsideItemComponent($brizySection);
        $textContainerComponent = $this->getTextContainerComponent($brizySection);

        // Проверяем, что внутренний Column существует
        if (!$insideItemComponent || !$textContainerComponent) {
            // Если внутренний Column не найден, используем внешний Column как fallback
            $insideItemComponent = $brizySection->getItemWithDepth(0, 0, 0);
            $textContainerComponent = $brizySection->getItemWithDepth(0, 0, 0);
        }

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $insideElementContext = $data->instanceWithBrizyComponent($insideItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->transformItem($insideElementContext, $textContainerComponent, $styleList);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $fff = json_encode( $data->getThemeContext()->getFamilies());
        // Используем insideElementContext для добавления RichText элементов во внутренний Column
        $this->handleRichTextItems($insideElementContext, $this->browserPage);
        $this->handleDonationsButton($insideElementContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        return $brizySection;
    }

    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }

    protected function afterTransformItem(ElementContextInterface $data, BrizyComponent $brizySection): void
    {
        $mbSectionItem = $data->getMbSection();
        $selectId = $mbSectionItem['id'] ?? $mbSectionItem['sectionId'];

        $sectionSelector = '[data-id="' .$selectId. '"] .bg-helper>.bg-opacity';
        $styles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $sectionSelector,
                'styleProperties' => ['background-color','opacity'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        // Получаем градиент из дополнительных опций, если он есть
        $additionalOptions = $data->getThemeContext()->getPageDTO()->getPageStyleDetails();
        if (!empty($additionalOptions['bg-gradient'])) {
            $styles['data']['bg-gradient'] = $additionalOptions['bg-gradient'];
        }

        // Устанавливаем градиент или цвет фона на SectionItem
        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        if (!empty($styles['data']['bg-gradient'])) {
            $this->handleSectionGradient($sectionItemComponent, $styles['data']);
            
            // Добавляем параметры для градиента на SectionItem
            $sectionItemValue = $sectionItemComponent->getValue();
            $sectionItemValue->set('gradientActivePointer', 'finishPointer');
        } else {
            $this->handleItemBackground($sectionItemComponent, $styles['data']);
        }

        // Обработка blur-box для full-text-blur-box элемента
        $this->handleBlurBoxStyles($data, $brizySection, $selectId);
    }

    /**
     * Переопределяем handleSectionStyles, чтобы предотвратить установку фонового изображения через handleSectionTexture
     */
    protected function handleSectionStyles(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
                                $additionalOptions = []
    ): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        // Проверяем наличие blur-box в секции
        $selectId = $mbSectionItem['id'] ?? $mbSectionItem['sectionId'];
        $blurBoxSelector = '[data-id="' . $selectId . '"] .blur-box';
        $hasBlurBox = $this->hasNode($blurBoxSelector, $browserPage);

        $sectionStyles = $this->getSectionListStyle($data, $browserPage);

        if (!empty($additionalOptions['bg-gradient'])) {
            $sectionStyles['bg-gradient'] = $additionalOptions['bg-gradient'];
            unset($additionalOptions['bg-gradient']);
        }

        if (!empty($additionalOptions['bg-color'])) {
            $sectionStyles['background-color'] = $additionalOptions['bg-color']['bgColor'];
            $sectionStyles['background-opacity'] = $additionalOptions['bg-color']['bgOpacity'];
            $sectionStyles['opacity'] = $additionalOptions['bg-color']['bgOpacity'];
            unset($additionalOptions['bg-color']);
        }

        $options = ['heightType' => $this->getHeightTypeHandleSectionStyles()];

        // Для blur-box элементов не вызываем handleSectionTexture, чтобы не устанавливать фоновое изображение через customCSS
        if ($hasBlurBox) {
            // Удаляем background-image из sectionStyles, чтобы handleSectionTexture не сработал
            unset($sectionStyles['background-image']);
        }

        $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles, $options);

        // Вызываем handleSectionTexture только если нет blur-box
        if (!$hasBlurBox) {
            $this->handleSectionTexture($brizySection, $mbSectionItem, $sectionStyles, $options);
        }

        // Устанавливаем padding и margin
        $brizySection->getValue()
            ->set_paddingType('ungrouped')
            ->set_paddingTop(0)
            ->set_paddingBottom(0)
            ->set_paddingRight(0)
            ->set_paddingLeft(0)
            ->set_marginType('ungrouped')
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_marginLeft(0)
            ->set_marginRight(0);

        // Устанавливаем дополнительные опции
        foreach ($additionalOptions as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $method = 'set_' . $key;
            if (method_exists($brizySection->getValue(), $method)) {
                $brizySection->getValue()->$method($value);
            }
        }

        return $brizySection;
    }

    /**
     * Переопределяем handleSectionBackground, чтобы не устанавливать фоновое изображение на SectionItem для blur-box
     * Но устанавливаем градиент, если он есть
     */
    protected function handleSectionBackground(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles, $options = ['heightType' => 'custom'])
    {
        // Проверяем наличие blur-box в секции
        $selectId = $mbSectionItem['id'] ?? $mbSectionItem['sectionId'];
        $blurBoxSelector = '[data-id="' . $selectId . '"] .blur-box';
        $hasBlurBox = $this->hasNode($blurBoxSelector, $this->browserPage);

        // Если есть blur-box, не устанавливаем фоновое изображение на SectionItem
        // Оно будет установлено на внешний Column в handleBlurBoxStyles
        if ($hasBlurBox) {
            // Устанавливаем градиент, если он есть в sectionStyles
            if (!empty($sectionStyles['bg-gradient'])) {
                $this->handleSectionGradient($brizySection, $sectionStyles);
            } else {
                // Устанавливаем только цвет фона и другие стили, но не изображение
                if (isset($sectionStyles['background-color'])) {
                    $sectionStyles['background-opacity'] = NumberProcessor::convertToNumeric(
                        $sectionStyles['opacity'] ?? ColorConverter::rgba2opacity($sectionStyles['background-color'] ?? 'rgba(255,255,255,1)')
                    );
                }
                $this->handleItemBackground($brizySection, $sectionStyles);
            }
            return;
        }

        // Для обычных секций вызываем родительский метод
        parent::handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles, $options);
    }


    /**
     * Обработка стилей для blur-box элемента
     * Извлекает все стили с исходной страницы и применяет их к нужным компонентам
     */
    protected function handleBlurBoxStyles(ElementContextInterface $data, BrizyComponent $brizySection, $selectId): void
    {
        // Проверяем наличие blur-box в секции
        $blurBoxSelector = '[data-id="' . $selectId . '"] .blur-box';
        $hasBlurBox = $this->hasNode($blurBoxSelector, $this->browserPage);

        if (!$hasBlurBox) {
            return;
        }

        $mbSectionItem = $data->getMbSection();
        
        // Получаем внешний Column (0,0,0) - первый Column в первой Row в SectionItem
        $outerColumn = $brizySection->getItemWithDepth(0, 0, 0);
        
        // Получаем внутренний Column (0,0,0,0,0)
        $innerColumn = $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
        
        // Получаем SectionItem
        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        
        // 1. Извлекаем фоновое изображение из секции data-id="942277" (исходная секция)
        // Фон берется с секции, а не с body
        $imageUrl = '';
        $imageFileName = '';
        
        // Используем исходный sectionId для получения фонового изображения
        $originalSectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];
        
        // Получаем фоновое изображение из настроек секции
        if (isset($mbSectionItem['settings']['sections']['background']['photo']) && !empty($mbSectionItem['settings']['sections']['background']['photo'])) {
            $background = $mbSectionItem['settings']['sections']['background'];
            $rawImageUrl = $background['photo'];
            $imageFileName = $background['filename'] ?? '';
            
            $validatedUrl = MediaController::validateBgImag($rawImageUrl);
            $imageUrl = $validatedUrl ? $validatedUrl : $rawImageUrl;
            
            if (empty($imageFileName) && preg_match('/([^\/]+)\.(jpg|jpeg|png|gif|webp)$/i', $imageUrl, $fileMatches)) {
                $imageFileName = $fileMatches[1] . '.' . $fileMatches[2];
            }
        }
        
        // Если нет в настройках, пытаемся получить из DOM секции
        if (empty($imageUrl)) {
            $sectionBgSelector = '[data-id="' . $originalSectionId . '"]';
            $sectionBgStyles = $this->getDomElementStyles(
                $sectionBgSelector,
                ['background-image'],
                $this->browserPage,
                $data->getFontFamilies(),
                $data->getDefaultFontFamily()
            );
            
            if (!empty($sectionBgStyles['background-image']) && $sectionBgStyles['background-image'] !== 'none') {
                $bgImageUrl = $sectionBgStyles['background-image'];
                if (preg_match('/url\(["\']?([^"\']+)["\']?\)/', $bgImageUrl, $matches)) {
                    $imageUrl = $matches[1];
                    if (preg_match('/([^\/]+)\.(jpg|jpeg|png|gif|webp)$/i', $imageUrl, $fileMatches)) {
                        $imageFileName = $fileMatches[1] . '.' . $fileMatches[2];
                    }
                }
            }
        }
        
        // Если все еще нет, проверяем .blur-box .has-background
        if (empty($imageUrl)) {
            $blurBoxBgSelector = '[data-id="' . $originalSectionId . '"] .blur-box .has-background';
            $blurBoxBgStyles = $this->getDomElementStyles(
                $blurBoxBgSelector,
                ['background-image'],
                $this->browserPage,
                $data->getFontFamilies(),
                $data->getDefaultFontFamily()
            );
            
            if (!empty($blurBoxBgStyles['background-image']) && $blurBoxBgStyles['background-image'] !== 'none') {
                $bgImageUrl = $blurBoxBgStyles['background-image'];
                if (preg_match('/url\(["\']?([^"\']+)["\']?\)/', $bgImageUrl, $matches)) {
                    $imageUrl = $matches[1];
                    if (preg_match('/([^\/]+)\.(jpg|jpeg|png|gif|webp)$/i', $imageUrl, $fileMatches)) {
                        $imageFileName = $fileMatches[1] . '.' . $fileMatches[2];
                    }
                }
            }
        }
        
        // 2. Устанавливаем фоновое изображение на внешний Column
        if ($outerColumn && !empty($imageUrl)) {
            $outerValue = $outerColumn->getValue();
            
            // Основные параметры изображения
            $outerValue->set_bgImageSrc($imageUrl)
                ->set_bgImageFileName($imageFileName)
                ->set_bgImageType('internal')
                ->set_bgSize('cover')
                ->set_bgColorType('none')
                ->set_bgColorOpacity(0)
                ->set_heightStyle('custom')
                ->set_height(600)
                ->set_heightSuffix('px')
                ->set_verticalAlign('center')
                ->set_width(100);
            
            // Padding - извлекаем из секции или используем значения по умолчанию
            $outerValue->set_paddingType('ungrouped')
                ->set_padding(80)
                ->set_paddingSuffix('px')
                ->set_paddingTop(0)
                ->set_paddingTopSuffix('px')
                ->set_paddingRight(80)
                ->set_paddingRightSuffix('px')
                ->set_paddingBottom(0)
                ->set_paddingBottomSuffix('px')
                ->set_paddingLeft(80)
                ->set_paddingLeftSuffix('px');
            
            // Tablet padding
            $outerValue->set_tabletPaddingType('grouped')
                ->set_tabletPadding(25)
                ->set_tabletPaddingSuffix('px')
                ->set_tabletPaddingTop(25)
                ->set_tabletPaddingTopSuffix('px')
                ->set_tabletPaddingRight(25)
                ->set_tabletPaddingRightSuffix('px')
                ->set_tabletPaddingBottom(25)
                ->set_tabletPaddingBottomSuffix('px')
                ->set_tabletPaddingLeft(25)
                ->set_tabletPaddingLeftSuffix('px');
            
            // Mobile padding
            $outerValue->set_mobilePaddingType('grouped')
                ->set_mobilePadding(35)
                ->set_mobilePaddingSuffix('px')
                ->set_mobilePaddingTop(35)
                ->set_mobilePaddingTopSuffix('px')
                ->set_mobilePaddingRight(35)
                ->set_mobilePaddingRightSuffix('px')
                ->set_mobilePaddingBottom(35)
                ->set_mobilePaddingBottomSuffix('px')
                ->set_mobilePaddingLeft(35)
                ->set_mobilePaddingLeftSuffix('px');
            
            // Mobile margin
            $outerValue->set_mobileMarginType('ungrouped')
                ->set_mobileMargin(10)
                ->set_mobileMarginSuffix('px')
                ->set_mobileMarginTop(0)
                ->set_mobileMarginTopSuffix('px')
                ->set_mobileMarginRight(0)
                ->set_mobileMarginRightSuffix('px')
                ->set_mobileMarginBottom(0)
                ->set_mobileMarginBottomSuffix('px')
                ->set_mobileMarginLeft(0)
                ->set_mobileMarginLeftSuffix('px');
            
            // Дополнительные параметры для внешнего Column
            $outerValue->set('bgImageExtension', 'jpg')
                ->set('bgImageWidth', 394)
                ->set('bgImageHeight', 394)
                ->set('gradientColorHex', '#009900')
                ->set('gradientColorOpacity', 1)
                ->set('gradientColorPalette', '')
                ->set('gradientType', 'linear')
                ->set('gradientStartPointer', 0)
                ->set('gradientFinishPointer', 100)
                ->set('gradientActivePointer', 'startPointer')
                ->set('gradientLinearDegree', 90)
                ->set('gradientRadialDegree', 90)
                ->set('tabsState', 'normal');
        }
        
        // 3. Очищаем фоновое изображение с SectionItem и удаляем его из customCSS
        if ($sectionItemComponent) {
            $sectionItemComponent->getValue()
                ->set_bgImageSrc('')
                ->set_bgImageFileName('')
                ->set_bgImageType('none');
            
            // Полностью очищаем customCSS от фонового изображения
            $customCSS = $sectionItemComponent->getValue()->get_customCSS() ?? '';
            if (!empty($customCSS)) {
                // Удаляем строки с background-image
                $customCSS = preg_replace('/[^\n]*background-image[^\n]*\n?/', '', $customCSS);
                // Удаляем блоки с .brz-bg:not(:has(.brz-bg-image))
                $customCSS = preg_replace('/\.brz-section__item\s*>\s*\.brz-bg:not\(:has\(\.brz-bg-image\)\)\s*\{[^}]*\}/s', '', $customCSS);
                // Удаляем пустые строки и лишние пробелы
                $customCSS = preg_replace('/\n\s*\n/', "\n", $customCSS);
                $customCSS = trim($customCSS);
                $sectionItemComponent->getValue()->set_customCSS($customCSS);
            }
        }

        // 4. Извлекаем и устанавливаем стили для внутреннего Column из .group элемента
        if ($innerColumn) {
            // Используем исходный sectionId для получения стилей
            $textContainerSelector = '[data-id="' . $originalSectionId . '"] .group';
            $textContainerStyles = $this->getDomElementStyles(
                $textContainerSelector,
                [
                    'background-color', 
                    'opacity', 
                    'padding-top', 
                    'padding-right', 
                    'padding-bottom', 
                    'padding-left',
                    'margin-top',
                    'margin-right', 
                    'margin-bottom', 
                    'margin-left',
                    'border-color', 
                    'border-width',
                    'border-top-width',
                    'border-right-width',
                    'border-bottom-width',
                    'border-left-width',
                    'border-style'
                ],
                $this->browserPage,
                $data->getFontFamilies(),
                $data->getDefaultFontFamily()
            );

            $bgColor = !empty($textContainerStyles['background-color']) 
                ? ColorConverter::rgba2hex($textContainerStyles['background-color'])
                : '#000000';
            
            $opacity = !empty($textContainerStyles['opacity']) 
                ? (float)$textContainerStyles['opacity'] 
                : 0.45;
            
            if ($opacity >= 1.0 && !empty($textContainerStyles['background-color'])) {
                $opacity = ColorConverter::rgba2opacity($textContainerStyles['background-color']);
            }
            
            if ($opacity >= 1.0) {
                $opacity = 0.45;
            }
            
            // Извлекаем padding из отдельных свойств
            $paddingTop = !empty($textContainerStyles['padding-top']) 
                ? (int)str_replace('px', '', $textContainerStyles['padding-top']) 
                : 60;
            $paddingRight = !empty($textContainerStyles['padding-right']) 
                ? (int)str_replace('px', '', $textContainerStyles['padding-right']) 
                : 60;
            $paddingBottom = !empty($textContainerStyles['padding-bottom']) 
                ? (int)str_replace('px', '', $textContainerStyles['padding-bottom']) 
                : 60;
            $paddingLeft = !empty($textContainerStyles['padding-left']) 
                ? (int)str_replace('px', '', $textContainerStyles['padding-left']) 
                : 60;
            
            // Извлекаем margin из отдельных свойств
            $marginTop = !empty($textContainerStyles['margin-top']) 
                ? (int)str_replace('px', '', $textContainerStyles['margin-top']) 
                : 0;
            $marginRight = !empty($textContainerStyles['margin-right']) 
                ? (int)str_replace('px', '', $textContainerStyles['margin-right']) 
                : 60;
            $marginBottom = !empty($textContainerStyles['margin-bottom']) 
                ? (int)str_replace('px', '', $textContainerStyles['margin-bottom']) 
                : 0;
            $marginLeft = !empty($textContainerStyles['margin-left']) 
                ? (int)str_replace('px', '', $textContainerStyles['margin-left']) 
                : 60;
            
            // Извлекаем border
            $borderColor = !empty($textContainerStyles['border-color']) 
                ? ColorConverter::rgba2hex($textContainerStyles['border-color'])
                : '#fcfcfc';
            
            $borderStyle = !empty($textContainerStyles['border-style']) && $textContainerStyles['border-style'] !== 'none'
                ? $textContainerStyles['border-style']
                : 'solid';
            
            // Извлекаем border-width из отдельных свойств или общего
            $borderWidth = 1;
            if (!empty($textContainerStyles['border-top-width'])) {
                $borderWidth = (int)str_replace('px', '', $textContainerStyles['border-top-width']);
            } elseif (!empty($textContainerStyles['border-width'])) {
                $borderWidth = (int)str_replace('px', '', $textContainerStyles['border-width']);
            }
            
            // Извлекаем отдельные border-width если есть
            $borderTopWidth = !empty($textContainerStyles['border-top-width']) 
                ? (int)str_replace('px', '', $textContainerStyles['border-top-width']) 
                : $borderWidth;
            $borderRightWidth = !empty($textContainerStyles['border-right-width']) 
                ? (int)str_replace('px', '', $textContainerStyles['border-right-width']) 
                : $borderWidth;
            $borderBottomWidth = !empty($textContainerStyles['border-bottom-width']) 
                ? (int)str_replace('px', '', $textContainerStyles['border-bottom-width']) 
                : $borderWidth;
            $borderLeftWidth = !empty($textContainerStyles['border-left-width']) 
                ? (int)str_replace('px', '', $textContainerStyles['border-left-width']) 
                : $borderWidth;
            
            $innerValue = $innerColumn->getValue();
            
            // Основные параметры внутреннего Column
            $innerValue->set_width(100)
                ->set_verticalAlign('between')
                ->set_bgColorType('solid')
                ->set_bgColorHex($bgColor)
                ->set_bgColorOpacity($opacity)
                ->set_mobileBgColorType('solid')
                ->set_mobileBgColorHex($bgColor)
                ->set_mobileBgColorOpacity($opacity)
                ->set_borderStyle($borderStyle)
                ->set_borderColorHex($borderColor)
                ->set_borderColorOpacity(1)
                ->set_borderWidthType('grouped')
                ->set_borderWidth($borderWidth);
            
            // Padding
            $innerValue->set_paddingType('ungrouped')
                ->set_padding(15)
                ->set_paddingSuffix('px')
                ->set_paddingTop($paddingTop)
                ->set_paddingTopSuffix('px')
                ->set_paddingRight($paddingRight)
                ->set_paddingRightSuffix('px')
                ->set_paddingBottom($paddingBottom)
                ->set_paddingBottomSuffix('px')
                ->set_paddingLeft($paddingLeft)
                ->set_paddingLeftSuffix('px');
            
            // Margin
            $innerValue->set_marginType('ungrouped')
                ->set_margin(0)
                ->set_marginSuffix('px')
                ->set_marginTop($marginTop)
                ->set_marginTopSuffix('px')
                ->set_marginRight($marginRight)
                ->set_marginRightSuffix('px')
                ->set_marginBottom($marginBottom)
                ->set_marginBottomSuffix('px')
                ->set_marginLeft($marginLeft)
                ->set_marginLeftSuffix('px');
            
            // Border
            $innerValue->set_borderTopWidth($borderTopWidth)
                ->set_borderRightWidth($borderRightWidth)
                ->set_borderBottomWidth($borderBottomWidth)
                ->set_borderLeftWidth($borderLeftWidth);
            
            // Mobile margin
            $innerValue->set_mobileMarginType('ungrouped')
                ->set_mobileMargin(10)
                ->set_mobileMarginSuffix('px')
                ->set_mobileMarginTop(0)
                ->set_mobileMarginTopSuffix('px')
                ->set_mobileMarginRight(0)
                ->set_mobileMarginRightSuffix('px')
                ->set_mobileMarginBottom(0)
                ->set_mobileMarginBottomSuffix('px')
                ->set_mobileMarginLeft(0)
                ->set_mobileMarginLeftSuffix('px');
            
            // Дополнительные параметры для внутреннего Column
            $innerValue->set('gradientColorHex', '#009900')
                ->set('gradientColorOpacity', 1)
                ->set('gradientColorPalette', '')
                ->set('gradientType', 'linear')
                ->set('gradientStartPointer', 0)
                ->set('gradientFinishPointer', 100)
                ->set('gradientActivePointer', 'startPointer')
                ->set('gradientLinearDegree', 90)
                ->set('gradientRadialDegree', 90)
                ->set('tabsState', 'normal')
                ->set('mobileGradientColorHex', '#009900')
                ->set('mobileGradientColorOpacity', 1)
                ->set('mobileGradientColorPalette', '')
                ->set('mobileGradientType', 'linear')
                ->set('mobileGradientStartPointer', 0)
                ->set('mobileGradientFinishPointer', 100)
                ->set('mobileGradientActivePointer', 'startPointer')
                ->set('mobileGradientLinearDegree', 90)
                ->set('mobileGradientRadialDegree', 90)
                ->set('bgColorPalette', '')
                ->set('mobileBgColorPalette', '')
                ->set('borderColorPalette', '');
        }
    }

    /**
     * Получить селектор для custom CSS секции
     */
    protected function getSelectorSectionCustomCSS(): string
    {
        return '.brz-section__item';
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 0,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 0,
            "mobilePaddingLeftSuffix" => "px",

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
        ];
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }
}
