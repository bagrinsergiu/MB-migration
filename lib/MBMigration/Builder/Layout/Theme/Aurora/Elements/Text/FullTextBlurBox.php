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
     * Извлекает все стили с исходной страницы, нормализует их в BlurBoxStyles и применяет к нужным компонентам
     *
     * @param ElementContextInterface $data Контекст элемента с данными секции
     * @param BrizyComponent $brizySection Компонент Brizy секции
     * @param string $selectId ID секции для селектора
     *
     * Структура BlurBoxStyles:
     * [
     *   'background' => [
     *     'imageUrl' => string,
     *     'imageFileName' => string,
     *     'size' => string,
     *     'height' => int,
     *     'padding' => ['type' => string, 'top' => int, 'right' => int, 'bottom' => int, 'left' => int, 'suffix' => string],
     *     'tabletPadding' => [...],
     *     'mobilePadding' => [...],
     *     'mobileMargin' => [...]
     *   ],
     *   'overlay' => [
     *     'bgColorHex' => string,
     *     'bgColorOpacity' => float,
     *     'borderStyle' => string,
     *     'borderColorHex' => string,
     *     'borderWidth' => int,
     *     'borderTopWidth' => int,
     *     'borderRightWidth' => int,
     *     'borderBottomWidth' => int,
     *     'borderLeftWidth' => int,
     *     'padding' => [...],
     *     'margin' => [...],
     *     'mobileMargin' => [...]
     *   ]
     * ]
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

        // Получаем компоненты
        $outerColumn = $brizySection->getItemWithDepth(0, 0, 0);
        $innerColumn = $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
        $sectionItemComponent = $this->getSectionItemComponent($brizySection);

        // Собираем и нормализуем все стили blur-box в единый массив BlurBoxStyles
        $blurBoxStyles = $this->collectBlurBoxStyles($data, $mbSectionItem);

        // Применяем стили к outerColumn (фон секции)
        if ($outerColumn && !empty($blurBoxStyles['background']['imageUrl'])) {
            $this->applyBackgroundStyles($outerColumn, $blurBoxStyles['background']);
        }

        // Очищаем фоновое изображение с SectionItem
        if ($sectionItemComponent) {
            $this->clearSectionItemBackground($sectionItemComponent);
        }

        // Применяем стили к innerColumn (overlay с текстом)
        if ($innerColumn) {
            $this->applyOverlayStyles($innerColumn, $blurBoxStyles['overlay']);
        }
    }

    /**
     * Собирает и нормализует все стили blur-box из DOM и настроек секции
     *
     * @param ElementContextInterface $data Контекст элемента
     * @param array $mbSectionItem Данные секции из MB
     * @return array Нормализованный массив BlurBoxStyles
     */
    protected function collectBlurBoxStyles(ElementContextInterface $data, array $mbSectionItem): array
    {
        $originalSectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];

        // Собираем стили фона (background)
        $backgroundStyles = $this->collectBackgroundStyles($data, $mbSectionItem, $originalSectionId);

        // Собираем стили overlay (внутренний контейнер .group)
        $overlayStyles = $this->collectOverlayStyles($data, $originalSectionId);

        return [
            'background' => $backgroundStyles,
            'overlay' => $overlayStyles
        ];
    }

    /**
     * Собирает стили фона для outerColumn
     * 
     * @param ElementContextInterface $data Контекст элемента
     * @param array $mbSectionItem Данные секции
     * @param string $originalSectionId ID исходной секции
     * @return array Нормализованные стили фона
     */
    protected function collectBackgroundStyles(ElementContextInterface $data, array $mbSectionItem, string $originalSectionId): array
    {
        $imageUrl = '';
        $imageFileName = '';
        
        // 1. Пытаемся получить из настроек секции
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
        
        // 2. Если нет в настройках, пытаемся получить из DOM секции
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
        
        // 3. Если все еще нет, проверяем .blur-box .has-background
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
        
        // 4. Получаем padding из .content-wrapper (родитель .group)
        $contentWrapperSelector = '[data-id="' . $originalSectionId . '"] .content-wrapper';
        $contentWrapperStyles = $this->getDomElementStyles(
            $contentWrapperSelector,
            ['padding-top', 'padding-right', 'padding-bottom', 'padding-left'],
            $this->browserPage,
            $data->getFontFamilies(),
            $data->getDefaultFontFamily()
        );
        
        // Нормализуем padding из .content-wrapper или используем значения по умолчанию
        $paddingTop = !empty($contentWrapperStyles['padding-top'])
            ? (int)NumberProcessor::convertToInt($contentWrapperStyles['padding-top'])
            : 95;
        $paddingRight = !empty($contentWrapperStyles['padding-right'])
            ? (int)NumberProcessor::convertToInt($contentWrapperStyles['padding-right'])
            : 115;
        $paddingBottom = !empty($contentWrapperStyles['padding-bottom'])
            ? (int)NumberProcessor::convertToInt($contentWrapperStyles['padding-bottom'])
            : 95;
        $paddingLeft = !empty($contentWrapperStyles['padding-left'])
            ? (int)NumberProcessor::convertToInt($contentWrapperStyles['padding-left'])
            : 115;
        
        return [
            'imageUrl' => $imageUrl,
            'imageFileName' => $imageFileName,
            'size' => 'cover',
            'height' => 600,
            'heightSuffix' => 'px',
            'padding' => [
                'type' => 'ungrouped',
                'top' => $paddingTop,
                'right' => $paddingRight,
                'bottom' => $paddingBottom,
                'left' => $paddingLeft,
                'suffix' => 'px'
            ],
            'tabletPadding' => [
                'type' => 'grouped',
                'top' => 25,
                'right' => 25,
                'bottom' => 25,
                'left' => 25,
                'suffix' => 'px'
            ],
            'mobilePadding' => [
                'type' => 'grouped',
                'top' => 35,
                'right' => 35,
                'bottom' => 35,
                'left' => 35,
                'suffix' => 'px'
            ],
            'mobileMargin' => [
                'type' => 'ungrouped',
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'suffix' => 'px'
            ]
        ];
    }

    /**
     * Собирает стили overlay для innerColumn
     * Проверяет псевдоэлемент ::before у .blur-box, .bg-opacity, .group и другие элементы для определения стилей overlay
     * 
     * @param ElementContextInterface $data Контекст элемента
     * @param string $originalSectionId ID исходной секции
     * @return array Нормализованные стили overlay
     */
    protected function collectOverlayStyles(ElementContextInterface $data, string $originalSectionId): array
    {
        // 1. Сначала проверяем псевдоэлемент ::before у .blur-box (основной источник overlay)
        $blurBoxSelector = '[data-id="' . $originalSectionId . '"] .blur-box';
        $blurBoxBeforeStyles = $this->getDomElementStyles(
            $blurBoxSelector,
            [
                'background-color',
                'opacity',
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
            $data->getDefaultFontFamily(),
            '::before'
        );

        // 2. Пытаемся получить стили из .bg-opacity (альтернативный источник)
        $bgOpacitySelector = '[data-id="' . $originalSectionId . '"] .bg-opacity';
        $bgOpacityStyles = $this->getDomElementStyles(
            $bgOpacitySelector,
            [
                'background-color',
                'opacity'
            ],
            $this->browserPage,
            $data->getFontFamilies(),
            $data->getDefaultFontFamily()
        );

        // 3. Проверяем .group для padding/margin и border
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

        // Нормализуем background-color и opacity
        // Приоритет: ::before у .blur-box > .bg-opacity > .group
        $bgColor = '#000000';
        $opacity = 0.45;
        
        if (!empty($blurBoxBeforeStyles['background-color']) && $blurBoxBeforeStyles['background-color'] !== 'rgba(0, 0, 0, 0)') {
            // Используем стили из ::before псевдоэлемента
            $bgColor = ColorConverter::rgba2hex($blurBoxBeforeStyles['background-color']);
            if (!empty($blurBoxBeforeStyles['opacity']) && (float)$blurBoxBeforeStyles['opacity'] > 0) {
                $opacity = (float)$blurBoxBeforeStyles['opacity'];
            } else {
                $opacity = ColorConverter::rgba2opacity($blurBoxBeforeStyles['background-color']);
            }
        } elseif (!empty($bgOpacityStyles['background-color']) && $bgOpacityStyles['background-color'] !== 'rgba(0, 0, 0, 0)') {
            $bgColor = ColorConverter::rgba2hex($bgOpacityStyles['background-color']);
            if (!empty($bgOpacityStyles['opacity']) && (float)$bgOpacityStyles['opacity'] > 0) {
                $opacity = (float)$bgOpacityStyles['opacity'];
            } else {
                $opacity = ColorConverter::rgba2opacity($bgOpacityStyles['background-color']);
            }
        } elseif (!empty($textContainerStyles['background-color']) && $textContainerStyles['background-color'] !== 'rgba(0, 0, 0, 0)') {
            $bgColor = ColorConverter::rgba2hex($textContainerStyles['background-color']);
            if (!empty($textContainerStyles['opacity'])) {
                $opacity = (float)$textContainerStyles['opacity'];
            } else {
                $opacity = ColorConverter::rgba2opacity($textContainerStyles['background-color']);
            }
        }

        // Если opacity >= 1.0, но background-color имеет альфа-канал, используем его
        if ($opacity >= 1.0 && !empty($blurBoxBeforeStyles['background-color'])) {
            $opacity = ColorConverter::rgba2opacity($blurBoxBeforeStyles['background-color']);
        } elseif ($opacity >= 1.0 && !empty($bgOpacityStyles['background-color'])) {
            $opacity = ColorConverter::rgba2opacity($bgOpacityStyles['background-color']);
        } elseif ($opacity >= 1.0 && !empty($textContainerStyles['background-color'])) {
            $opacity = ColorConverter::rgba2opacity($textContainerStyles['background-color']);
        }

        // Если все еще >= 1.0, используем значение по умолчанию
        if ($opacity >= 1.0) {
            $opacity = 0.45;
        }

        // Нормализуем padding - используем значения по умолчанию, так как в .group они обычно 0
        $paddingTop = !empty($textContainerStyles['padding-top']) && (int)NumberProcessor::convertToInt($textContainerStyles['padding-top']) > 0
            ? (int)NumberProcessor::convertToInt($textContainerStyles['padding-top'])
            : 60;
        $paddingRight = !empty($textContainerStyles['padding-right']) && (int)NumberProcessor::convertToInt($textContainerStyles['padding-right']) > 0
            ? (int)NumberProcessor::convertToInt($textContainerStyles['padding-right'])
            : 60;
        $paddingBottom = !empty($textContainerStyles['padding-bottom']) && (int)NumberProcessor::convertToInt($textContainerStyles['padding-bottom']) > 0
            ? (int)NumberProcessor::convertToInt($textContainerStyles['padding-bottom'])
            : 60;
        $paddingLeft = !empty($textContainerStyles['padding-left']) && (int)NumberProcessor::convertToInt($textContainerStyles['padding-left']) > 0
            ? (int)NumberProcessor::convertToInt($textContainerStyles['padding-left'])
            : 60;

        // Нормализуем margin
        $marginTop = !empty($textContainerStyles['margin-top'])
            ? (int)NumberProcessor::convertToInt($textContainerStyles['margin-top'])
            : 0;
        $marginRight = !empty($textContainerStyles['margin-right'])
            ? (int)NumberProcessor::convertToInt($textContainerStyles['margin-right'])
            : 60;
        $marginBottom = !empty($textContainerStyles['margin-bottom'])
            ? (int)NumberProcessor::convertToInt($textContainerStyles['margin-bottom'])
            : 0;
        $marginLeft = !empty($textContainerStyles['margin-left'])
            ? (int)NumberProcessor::convertToInt($textContainerStyles['margin-left'])
            : 60;

        // Нормализуем border
        // Приоритет: ::before у .blur-box > .group
        $borderColor = '#fcfcfc';
        $borderStyle = 'solid';
        
        if (!empty($blurBoxBeforeStyles['border-color']) && $blurBoxBeforeStyles['border-color'] !== 'rgba(0, 0, 0, 0)') {
            $borderColor = ColorConverter::rgba2hex($blurBoxBeforeStyles['border-color']);
            if (!empty($blurBoxBeforeStyles['border-style']) && $blurBoxBeforeStyles['border-style'] !== 'none') {
                $borderStyle = $blurBoxBeforeStyles['border-style'];
            }
        } elseif (!empty($textContainerStyles['border-color']) && $textContainerStyles['border-color'] !== 'rgba(0, 0, 0, 0)') {
            $borderColor = ColorConverter::rgba2hex($textContainerStyles['border-color']);
            if (!empty($textContainerStyles['border-style']) && $textContainerStyles['border-style'] !== 'none') {
                $borderStyle = $textContainerStyles['border-style'];
            }
        }

        // Нормализуем border-width - приоритет: ::before > .group
        $borderWidth = 1;
        if (!empty($blurBoxBeforeStyles['border-top-width']) && (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-top-width']) > 0) {
            $borderWidth = (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-top-width']);
        } elseif (!empty($blurBoxBeforeStyles['border-width']) && (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-width']) > 0) {
            $borderWidth = (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-width']);
        } elseif (!empty($textContainerStyles['border-top-width']) && (int)NumberProcessor::convertToInt($textContainerStyles['border-top-width']) > 0) {
            $borderWidth = (int)NumberProcessor::convertToInt($textContainerStyles['border-top-width']);
        } elseif (!empty($textContainerStyles['border-width']) && (int)NumberProcessor::convertToInt($textContainerStyles['border-width']) > 0) {
            $borderWidth = (int)NumberProcessor::convertToInt($textContainerStyles['border-width']);
        } elseif (!empty($borderColor) && $borderColor !== '#fcfcfc') {
            // Если border-color задан, но border-width = 0, используем 1px по умолчанию
            $borderWidth = 1;
        }

        $borderTopWidth = 1;
        if (!empty($blurBoxBeforeStyles['border-top-width']) && (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-top-width']) > 0) {
            $borderTopWidth = (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-top-width']);
        } elseif (!empty($textContainerStyles['border-top-width']) && (int)NumberProcessor::convertToInt($textContainerStyles['border-top-width']) > 0) {
            $borderTopWidth = (int)NumberProcessor::convertToInt($textContainerStyles['border-top-width']);
        } else {
            $borderTopWidth = $borderWidth;
        }

        $borderRightWidth = 1;
        if (!empty($blurBoxBeforeStyles['border-right-width']) && (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-right-width']) > 0) {
            $borderRightWidth = (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-right-width']);
        } elseif (!empty($textContainerStyles['border-right-width']) && (int)NumberProcessor::convertToInt($textContainerStyles['border-right-width']) > 0) {
            $borderRightWidth = (int)NumberProcessor::convertToInt($textContainerStyles['border-right-width']);
        } else {
            $borderRightWidth = $borderWidth;
        }

        $borderBottomWidth = 1;
        if (!empty($blurBoxBeforeStyles['border-bottom-width']) && (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-bottom-width']) > 0) {
            $borderBottomWidth = (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-bottom-width']);
        } elseif (!empty($textContainerStyles['border-bottom-width']) && (int)NumberProcessor::convertToInt($textContainerStyles['border-bottom-width']) > 0) {
            $borderBottomWidth = (int)NumberProcessor::convertToInt($textContainerStyles['border-bottom-width']);
        } else {
            $borderBottomWidth = $borderWidth;
        }

        $borderLeftWidth = 1;
        if (!empty($blurBoxBeforeStyles['border-left-width']) && (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-left-width']) > 0) {
            $borderLeftWidth = (int)NumberProcessor::convertToInt($blurBoxBeforeStyles['border-left-width']);
        } elseif (!empty($textContainerStyles['border-left-width']) && (int)NumberProcessor::convertToInt($textContainerStyles['border-left-width']) > 0) {
            $borderLeftWidth = (int)NumberProcessor::convertToInt($textContainerStyles['border-left-width']);
        } else {
            $borderLeftWidth = $borderWidth;
        }

        return [
            'bgColorHex' => $bgColor,
            'bgColorOpacity' => $opacity,
            'borderStyle' => $borderStyle,
            'borderColorHex' => $borderColor,
            'borderWidth' => $borderWidth,
            'borderTopWidth' => $borderTopWidth,
            'borderRightWidth' => $borderRightWidth,
            'borderBottomWidth' => $borderBottomWidth,
            'borderLeftWidth' => $borderLeftWidth,
            'padding' => [
                'type' => 'ungrouped',
                'top' => $paddingTop,
                'right' => $paddingRight,
                'bottom' => $paddingBottom,
                'left' => $paddingLeft,
                'suffix' => 'px'
            ],
            'margin' => [
                'type' => 'ungrouped',
                'top' => $marginTop,
                'right' => $marginRight,
                'bottom' => $marginBottom,
                'left' => $marginLeft,
                'suffix' => 'px'
            ],
            'mobileMargin' => [
                'type' => 'ungrouped',
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'suffix' => 'px'
            ]
        ];
    }

    /**
     * Применяет стили фона к outerColumn
     *
     * @param BrizyComponent $outerColumn Компонент внешнего Column
     * @param array $backgroundStyles Стили фона из BlurBoxStyles
     */
    protected function applyBackgroundStyles(BrizyComponent $outerColumn, array $backgroundStyles): void
    {
        $outerValue = $outerColumn->getValue();

        // Основные параметры изображения
        $outerValue->set_bgImageSrc($backgroundStyles['imageUrl'])
            ->set_bgImageFileName($backgroundStyles['imageFileName'])
            ->set_bgImageType('internal')
            ->set_bgSize($backgroundStyles['size'])
            ->set_bgColorType('none')
            ->set_bgColorOpacity(0)
            ->set_heightStyle('custom')
            ->set_height($backgroundStyles['height'])
            ->set_heightSuffix($backgroundStyles['heightSuffix'])
            ->set_verticalAlign('center')
            ->set_width(100);

        // Padding
        $padding = $backgroundStyles['padding'];
        $outerValue->set_paddingType($padding['type'])
            ->set_padding(15)
            ->set_paddingSuffix($padding['suffix'])
            ->set_paddingTop($padding['top'])
            ->set_paddingTopSuffix($padding['suffix'])
            ->set_paddingRight($padding['right'])
            ->set_paddingRightSuffix($padding['suffix'])
            ->set_paddingBottom($padding['bottom'])
            ->set_paddingBottomSuffix($padding['suffix'])
            ->set_paddingLeft($padding['left'])
            ->set_paddingLeftSuffix($padding['suffix']);

        // Tablet padding
        $tabletPadding = $backgroundStyles['tabletPadding'];
        $outerValue->set_tabletPaddingType($tabletPadding['type'])
            ->set_tabletPadding($tabletPadding['top'])
            ->set_tabletPaddingSuffix($tabletPadding['suffix'])
            ->set_tabletPaddingTop($tabletPadding['top'])
            ->set_tabletPaddingTopSuffix($tabletPadding['suffix'])
            ->set_tabletPaddingRight($tabletPadding['right'])
            ->set_tabletPaddingRightSuffix($tabletPadding['suffix'])
            ->set_tabletPaddingBottom($tabletPadding['bottom'])
            ->set_tabletPaddingBottomSuffix($tabletPadding['suffix'])
            ->set_tabletPaddingLeft($tabletPadding['left'])
            ->set_tabletPaddingLeftSuffix($tabletPadding['suffix']);

        // Mobile padding
        $mobilePadding = $backgroundStyles['mobilePadding'];
        $outerValue->set_mobilePaddingType($mobilePadding['type'])
            ->set_mobilePadding($mobilePadding['top'])
            ->set_mobilePaddingSuffix($mobilePadding['suffix'])
            ->set_mobilePaddingTop($mobilePadding['top'])
            ->set_mobilePaddingTopSuffix($mobilePadding['suffix'])
            ->set_mobilePaddingRight($mobilePadding['right'])
            ->set_mobilePaddingRightSuffix($mobilePadding['suffix'])
            ->set_mobilePaddingBottom($mobilePadding['bottom'])
            ->set_mobilePaddingBottomSuffix($mobilePadding['suffix'])
            ->set_mobilePaddingLeft($mobilePadding['left'])
            ->set_mobilePaddingLeftSuffix($mobilePadding['suffix']);

        // Mobile margin
        $mobileMargin = $backgroundStyles['mobileMargin'];
        $outerValue->set_mobileMarginType($mobileMargin['type'])
            ->set_mobileMargin(10)
            ->set_mobileMarginSuffix($mobileMargin['suffix'])
            ->set_mobileMarginTop($mobileMargin['top'])
            ->set_mobileMarginTopSuffix($mobileMargin['suffix'])
            ->set_mobileMarginRight($mobileMargin['right'])
            ->set_mobileMarginRightSuffix($mobileMargin['suffix'])
            ->set_mobileMarginBottom($mobileMargin['bottom'])
            ->set_mobileMarginBottomSuffix($mobileMargin['suffix'])
            ->set_mobileMarginLeft($mobileMargin['left'])
            ->set_mobileMarginLeftSuffix($mobileMargin['suffix']);

        // Дополнительные параметры
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

    /**
     * Очищает фоновое изображение с SectionItem и удаляет его из customCSS
     *
     * @param BrizyComponent $sectionItemComponent Компонент SectionItem
     */
    protected function clearSectionItemBackground(BrizyComponent $sectionItemComponent): void
    {
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

    /**
     * Применяет стили overlay к innerColumn
     *
     * @param BrizyComponent $innerColumn Компонент внутреннего Column
     * @param array $overlayStyles Стили overlay из BlurBoxStyles
     */
    protected function applyOverlayStyles(BrizyComponent $innerColumn, array $overlayStyles): void
    {
        $innerValue = $innerColumn->getValue();

        // Основные параметры внутреннего Column
        $innerValue->set_width(100)
            ->set_verticalAlign('between')
            ->set_bgColorType('solid')
            ->set_bgColorHex($overlayStyles['bgColorHex'])
            ->set_bgColorOpacity($overlayStyles['bgColorOpacity'])
            ->set_mobileBgColorType('solid')
            ->set_mobileBgColorHex($overlayStyles['bgColorHex'])
            ->set_mobileBgColorOpacity($overlayStyles['bgColorOpacity'])
            ->set_borderStyle($overlayStyles['borderStyle'])
            ->set_borderColorHex($overlayStyles['borderColorHex'])
            ->set_borderColorOpacity(1)
            ->set_borderWidthType('grouped')
            ->set_borderWidth($overlayStyles['borderWidth']);

        // Padding
        $padding = $overlayStyles['padding'];
        $innerValue->set_paddingType($padding['type'])
            ->set_padding(15)
            ->set_paddingSuffix($padding['suffix'])
            ->set_paddingTop($padding['top'])
            ->set_paddingTopSuffix($padding['suffix'])
            ->set_paddingRight($padding['right'])
            ->set_paddingRightSuffix($padding['suffix'])
            ->set_paddingBottom($padding['bottom'])
            ->set_paddingBottomSuffix($padding['suffix'])
            ->set_paddingLeft($padding['left'])
            ->set_paddingLeftSuffix($padding['suffix']);

        // Margin
        $margin = $overlayStyles['margin'];
        $innerValue->set_marginType($margin['type'])
            ->set_margin(0)
            ->set_marginSuffix($margin['suffix'])
            ->set_marginTop($margin['top'])
            ->set_marginTopSuffix($margin['suffix'])
            ->set_marginRight($margin['right'])
            ->set_marginRightSuffix($margin['suffix'])
            ->set_marginBottom($margin['bottom'])
            ->set_marginBottomSuffix($margin['suffix'])
            ->set_marginLeft($margin['left'])
            ->set_marginLeftSuffix($margin['suffix']);

        // Border
        $innerValue->set_borderTopWidth($overlayStyles['borderTopWidth'])
            ->set_borderRightWidth($overlayStyles['borderRightWidth'])
            ->set_borderBottomWidth($overlayStyles['borderBottomWidth'])
            ->set_borderLeftWidth($overlayStyles['borderLeftWidth']);

        // Mobile margin
        $mobileMargin = $overlayStyles['mobileMargin'];
        $innerValue->set_mobileMarginType($mobileMargin['type'])
            ->set_mobileMargin(10)
            ->set_mobileMarginSuffix($mobileMargin['suffix'])
            ->set_mobileMarginTop($mobileMargin['top'])
            ->set_mobileMarginTopSuffix($mobileMargin['suffix'])
            ->set_mobileMarginRight($mobileMargin['right'])
            ->set_mobileMarginRightSuffix($mobileMargin['suffix'])
            ->set_mobileMarginBottom($mobileMargin['bottom'])
            ->set_mobileMarginBottomSuffix($mobileMargin['suffix'])
            ->set_mobileMarginLeft($mobileMargin['left'])
            ->set_mobileMarginLeftSuffix($mobileMargin['suffix']);

        // Дополнительные параметры
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
