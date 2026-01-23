<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;
use MBMigration\Core\Logger;

class GridLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\GridLayoutElement
{
    protected function getItemsPerRow(): int
    {
        return 3;
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $insideItemComponent = $this->getInsideItemComponent($brizySection);
        $textContainerComponent = $this->getTextContainerComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $insideElementContext = $data->instanceWithBrizyComponent($insideItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->transformItem($insideElementContext, $textContainerComponent, $styleList);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

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
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        // #region agent log
        file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A',
            'location' => 'Aurora/GridLayoutElement.php:83',
            'message' => 'getItemTextContainerComponent called',
            'data' => [
                'componentType' => $brizyComponent->getType(),
                'depth' => '0,0,0'
            ],
            'timestamp' => time() * 1000
        ]) . "\n", FILE_APPEND);
        // #endregion
        
        $result = $brizyComponent->getItemWithDepth(0,0,0);
        
        // #region agent log
        file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A',
            'location' => 'Aurora/GridLayoutElement.php:85',
            'message' => 'getItemTextContainerComponent result',
            'data' => [
                'resultType' => $result->getType(),
                'hasItems' => !empty($result->getValue()->get_items() ?? [])
            ],
            'timestamp' => time() * 1000
        ]) . "\n", FILE_APPEND);
        // #endregion
        
        return $result;
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0,0,0);
    }

    protected function getInsideItemComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0,0,0);
    }
    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $this->getHeaderComponent($brizySection);
    }

    protected function getTypeItemImageComponent(): string
    {
        return 'image';
    }

    protected function getPropertiesItemPhoto(): array
    {
        return [
            "maskShape" => $this->getMaskTypeItemImageComponent()
        ];
    }


    protected function getMaskTypeItemImageComponent(): string
    {
        return 'circle';
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
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

    /**
     * Переопределяем handleColumItemComponent для применения стилей list-item
     * Получает стили из DOM для элементов с class="list-item" и применяет их к Column
     */
    protected function handleColumItemComponent(ElementContextInterface $context): void
    {
        $brizyComponent = $context->getBrizySection();
        $mbItem = $context->getMbSection(); // Это элемент списка, а не секция
        
        // Получаем нормализованные стили для list-item
        $listItemStyles = $this->getListItemStyles($mbItem, $context);
        
        if (!empty($listItemStyles)) {
            $this->applyListItemStyles($brizyComponent, $listItemStyles);
        }
    }

    /**
     * Получает стили для элементов с class="list-item" из DOM
     * Получает стили конкретного элемента list-item по его data-id
     * 
     * @param array $mbItem Элемент списка из исходного проекта
     * @param ElementContextInterface $context Контекст элемента
     * @return array Нормализованные стили для list-item
     */
    protected function getListItemStyles(array $mbItem, ElementContextInterface $context): array
    {
        $itemId = $mbItem['id'] ?? null;
        if (!$itemId) {
            Logger::instance()->debug('GridLayoutElement: item id not found', [
                'mbItem' => $mbItem
            ]);
            return [];
        }

        // Селектор для конкретного элемента list-item
        // В DOM элементы list-item имеют data-id равный id элемента списка
        $itemSelector = '[data-id="' . $itemId . '"]';
        
        // Проверяем, что элемент существует
        if (!$this->hasNode($itemSelector, $this->browserPage)) {
            Logger::instance()->debug('GridLayoutElement: list-item element not found in DOM', [
                'itemId' => $itemId,
                'selector' => $itemSelector
            ]);
            return [];
        }
        
        // Список свойств стилей для получения из DOM
        $styleProperties = [
            'margin-top',
            'margin-right',
            'margin-bottom',
            'margin-left',
            'padding-top',
            'padding-right',
            'padding-bottom',
            'padding-left',
            'border-top-width',
            'border-right-width',
            'border-bottom-width',
            'border-left-width',
            'border-top-style',
            'border-right-style',
            'border-bottom-style',
            'border-left-style',
            'border-top-color',
            'border-right-color',
            'border-bottom-color',
            'border-left-color',
            'border-radius',
            'border-top-left-radius',
            'border-top-right-radius',
            'border-bottom-left-radius',
            'border-bottom-right-radius',
            'background-color',
            'background-image',
            'background-size',
            'background-position',
            'opacity',
            'width',
            'height'
        ];
        
        // Используем getDomElementStyles для получения стилей list-item элемента
        $styles = $this->getDomElementStyles(
            $itemSelector,
            $styleProperties,
            $this->browserPage,
            $context->getFontFamilies(),
            $context->getDefaultFontFamily()
        );

        if (empty($styles)) {
            Logger::instance()->debug('GridLayoutElement: No list-item styles found', [
                'selector' => $itemSelector,
                'itemId' => $itemId
            ]);
            return [];
        }

        // Нормализуем полученные стили
        $normalizedStyles = $this->normalizeListItemStyles($styles);

        Logger::instance()->debug('GridLayoutElement: Normalized list-item styles', [
            'itemId' => $itemId,
            'styles' => $normalizedStyles
        ]);

        return $normalizedStyles;
    }

    /**
     * Нормализует стили list-item
     * Преобразует CSS стили в формат параметров BrizyComponent
     * 
     * @param array $styles Стили из DOM
     * @return array Нормализованные стили в формате параметров шаблона
     */
    protected function normalizeListItemStyles(array $styles): array
    {
        $normalized = [];

        // Margin
        $normalized['marginType'] = 'ungrouped';
        $normalized['marginTop'] = $this->extractNumericValue($styles['margin-top'] ?? '0px');
        $normalized['marginRight'] = $this->extractNumericValue($styles['margin-right'] ?? '0px');
        $normalized['marginBottom'] = $this->extractNumericValue($styles['margin-bottom'] ?? '0px');
        $normalized['marginLeft'] = $this->extractNumericValue($styles['margin-left'] ?? '0px');
        $normalized['marginSuffix'] = 'px';
        $normalized['marginTopSuffix'] = 'px';
        $normalized['marginRightSuffix'] = 'px';
        $normalized['marginBottomSuffix'] = 'px';
        $normalized['marginLeftSuffix'] = 'px';

        // Mobile Margin (уменьшаем пропорционально)
        $normalized['mobileMarginType'] = 'ungrouped';
        $normalized['mobileMarginTop'] = max(0, (int)($normalized['marginTop'] * 0.4));
        $normalized['mobileMarginRight'] = max(0, (int)($normalized['marginRight'] * 0.4));
        $normalized['mobileMarginBottom'] = max(0, (int)($normalized['marginBottom'] * 0.4));
        $normalized['mobileMarginLeft'] = max(0, (int)($normalized['marginLeft'] * 0.4));
        $normalized['mobileMarginSuffix'] = 'px';
        $normalized['mobileMarginTopSuffix'] = 'px';
        $normalized['mobileMarginRightSuffix'] = 'px';
        $normalized['mobileMarginBottomSuffix'] = 'px';
        $normalized['mobileMarginLeftSuffix'] = 'px';

        // Padding
        $normalized['paddingType'] = 'ungrouped';
        $normalized['paddingTop'] = $this->extractNumericValue($styles['padding-top'] ?? '0px');
        $normalized['paddingRight'] = $this->extractNumericValue($styles['padding-right'] ?? '0px');
        $normalized['paddingBottom'] = $this->extractNumericValue($styles['padding-bottom'] ?? '0px');
        $normalized['paddingLeft'] = $this->extractNumericValue($styles['padding-left'] ?? '0px');
        $normalized['paddingSuffix'] = 'px';
        $normalized['paddingTopSuffix'] = 'px';
        $normalized['paddingRightSuffix'] = 'px';
        $normalized['paddingBottomSuffix'] = 'px';
        $normalized['paddingLeftSuffix'] = 'px';

        // Mobile Padding (уменьшаем пропорционально)
        $normalized['mobilePaddingType'] = 'ungrouped';
        $normalized['mobilePaddingTop'] = max(0, (int)($normalized['paddingTop'] * 0.4));
        $normalized['mobilePaddingRight'] = max(0, (int)($normalized['paddingRight'] * 0.4));
        $normalized['mobilePaddingBottom'] = max(0, (int)($normalized['paddingBottom'] * 0.4));
        $normalized['mobilePaddingLeft'] = max(0, (int)($normalized['paddingLeft'] * 0.4));
        $normalized['mobilePaddingSuffix'] = 'px';
        $normalized['mobilePaddingTopSuffix'] = 'px';
        $normalized['mobilePaddingRightSuffix'] = 'px';
        $normalized['mobilePaddingBottomSuffix'] = 'px';
        $normalized['mobilePaddingLeftSuffix'] = 'px';

        // Border Radius
        $borderRadius = $this->extractNumericValue($styles['border-radius'] ?? '0px');
        $normalized['borderRadiusType'] = 'grouped';
        $normalized['borderRadius'] = $borderRadius;
        $normalized['borderRadiusSuffix'] = 'px';
        $normalized['borderTopLeftRadius'] = $this->extractNumericValue($styles['border-top-left-radius'] ?? $borderRadius . 'px');
        $normalized['borderTopRightRadius'] = $this->extractNumericValue($styles['border-top-right-radius'] ?? $borderRadius . 'px');
        $normalized['borderBottomLeftRadius'] = $this->extractNumericValue($styles['border-bottom-left-radius'] ?? $borderRadius . 'px');
        $normalized['borderBottomRightRadius'] = $this->extractNumericValue($styles['border-bottom-right-radius'] ?? $borderRadius . 'px');
        $normalized['borderTopLeftRadiusSuffix'] = 'px';
        $normalized['borderTopRightRadiusSuffix'] = 'px';
        $normalized['borderBottomLeftRadiusSuffix'] = 'px';
        $normalized['borderBottomRightRadiusSuffix'] = 'px';

        // Border (если есть)
        $borderTopWidth = $this->extractNumericValue($styles['border-top-width'] ?? '0px');
        $borderRightWidth = $this->extractNumericValue($styles['border-right-width'] ?? '0px');
        $borderBottomWidth = $this->extractNumericValue($styles['border-bottom-width'] ?? '0px');
        $borderLeftWidth = $this->extractNumericValue($styles['border-left-width'] ?? '0px');
        
        if ($borderTopWidth > 0 || $borderRightWidth > 0 || $borderBottomWidth > 0 || $borderLeftWidth > 0) {
            $normalized['borderWidthType'] = 'ungrouped';
            $normalized['borderWidth'] = max($borderTopWidth, $borderRightWidth, $borderBottomWidth, $borderLeftWidth);
            $normalized['borderTopWidth'] = $borderTopWidth;
            $normalized['borderRightWidth'] = $borderRightWidth;
            $normalized['borderBottomWidth'] = $borderBottomWidth;
            $normalized['borderLeftWidth'] = $borderLeftWidth;
            
            // Border Style
            $borderStyle = $styles['border-top-style'] ?? 'none';
            if ($borderStyle === 'none') {
                $borderStyle = $styles['border-right-style'] ?? 'none';
            }
            if ($borderStyle === 'none') {
                $borderStyle = $styles['border-bottom-style'] ?? 'none';
            }
            if ($borderStyle === 'none') {
                $borderStyle = $styles['border-left-style'] ?? 'none';
            }
            $normalized['borderStyle'] = $borderStyle !== 'none' ? $borderStyle : 'solid';
            
            // Border Color
            $borderColor = $styles['border-top-color'] ?? $styles['border-right-color'] ?? $styles['border-bottom-color'] ?? $styles['border-left-color'] ?? '#000000';
            $normalized['borderColorHex'] = ColorConverter::convertColorRgbToHex($borderColor);
            $normalized['borderColorOpacity'] = 1;
        }

        // Opacity
        $opacity = $styles['opacity'] ?? '1';
        if (is_string($opacity)) {
            $opacity = (float)$opacity;
        }
        $normalized['opacity'] = NumberProcessor::convertToNumeric($opacity);

        return $normalized;
    }

    /**
     * Применяет нормализованные стили list-item к Column компоненту
     * 
     * @param BrizyComponent $brizyComponent Column компонент
     * @param array $styles Нормализованные стили
     */
    protected function applyListItemStyles(BrizyComponent $brizyComponent, array $styles): void
    {
        if (empty($styles)) {
            return;
        }

        $componentValue = $brizyComponent->getValue();

        // Применяем margin
        if (isset($styles['marginType'])) {
            $componentValue->set('marginType', $styles['marginType']);
            $componentValue->set('marginTop', $styles['marginTop'] ?? 0);
            $componentValue->set('marginRight', $styles['marginRight'] ?? 0);
            $componentValue->set('marginBottom', $styles['marginBottom'] ?? 0);
            $componentValue->set('marginLeft', $styles['marginLeft'] ?? 0);
            $componentValue->set('marginSuffix', $styles['marginSuffix'] ?? 'px');
            $componentValue->set('marginTopSuffix', $styles['marginTopSuffix'] ?? 'px');
            $componentValue->set('marginRightSuffix', $styles['marginRightSuffix'] ?? 'px');
            $componentValue->set('marginBottomSuffix', $styles['marginBottomSuffix'] ?? 'px');
            $componentValue->set('marginLeftSuffix', $styles['marginLeftSuffix'] ?? 'px');
        }

        // Применяем mobile margin
        if (isset($styles['mobileMarginType'])) {
            $componentValue->set('mobileMarginType', $styles['mobileMarginType']);
            $componentValue->set('mobileMarginTop', $styles['mobileMarginTop'] ?? 0);
            $componentValue->set('mobileMarginRight', $styles['mobileMarginRight'] ?? 0);
            $componentValue->set('mobileMarginBottom', $styles['mobileMarginBottom'] ?? 0);
            $componentValue->set('mobileMarginLeft', $styles['mobileMarginLeft'] ?? 0);
            $componentValue->set('mobileMarginSuffix', $styles['mobileMarginSuffix'] ?? 'px');
            $componentValue->set('mobileMarginTopSuffix', $styles['mobileMarginTopSuffix'] ?? 'px');
            $componentValue->set('mobileMarginRightSuffix', $styles['mobileMarginRightSuffix'] ?? 'px');
            $componentValue->set('mobileMarginBottomSuffix', $styles['mobileMarginBottomSuffix'] ?? 'px');
            $componentValue->set('mobileMarginLeftSuffix', $styles['mobileMarginLeftSuffix'] ?? 'px');
        }

        // Применяем padding (если отличается от дефолтного)
        if (isset($styles['paddingType']) && 
            ($styles['paddingTop'] > 0 || $styles['paddingRight'] > 0 || 
             $styles['paddingBottom'] > 0 || $styles['paddingLeft'] > 0)) {
            $componentValue->set('paddingType', $styles['paddingType']);
            $componentValue->set('paddingTop', $styles['paddingTop'] ?? 0);
            $componentValue->set('paddingRight', $styles['paddingRight'] ?? 0);
            $componentValue->set('paddingBottom', $styles['paddingBottom'] ?? 0);
            $componentValue->set('paddingLeft', $styles['paddingLeft'] ?? 0);
            $componentValue->set('paddingSuffix', $styles['paddingSuffix'] ?? 'px');
            $componentValue->set('paddingTopSuffix', $styles['paddingTopSuffix'] ?? 'px');
            $componentValue->set('paddingRightSuffix', $styles['paddingRightSuffix'] ?? 'px');
            $componentValue->set('paddingBottomSuffix', $styles['paddingBottomSuffix'] ?? 'px');
            $componentValue->set('paddingLeftSuffix', $styles['paddingLeftSuffix'] ?? 'px');
        }

        // Применяем mobile padding
        if (isset($styles['mobilePaddingType'])) {
            $componentValue->set('mobilePaddingType', $styles['mobilePaddingType']);
            $componentValue->set('mobilePaddingTop', $styles['mobilePaddingTop'] ?? 0);
            $componentValue->set('mobilePaddingRight', $styles['mobilePaddingRight'] ?? 0);
            $componentValue->set('mobilePaddingBottom', $styles['mobilePaddingBottom'] ?? 0);
            $componentValue->set('mobilePaddingLeft', $styles['mobilePaddingLeft'] ?? 0);
            $componentValue->set('mobilePaddingSuffix', $styles['mobilePaddingSuffix'] ?? 'px');
            $componentValue->set('mobilePaddingTopSuffix', $styles['mobilePaddingTopSuffix'] ?? 'px');
            $componentValue->set('mobilePaddingRightSuffix', $styles['mobilePaddingRightSuffix'] ?? 'px');
            $componentValue->set('mobilePaddingBottomSuffix', $styles['mobilePaddingBottomSuffix'] ?? 'px');
            $componentValue->set('mobilePaddingLeftSuffix', $styles['mobilePaddingLeftSuffix'] ?? 'px');
        }

        // Применяем border radius
        if (isset($styles['borderRadiusType']) && ($styles['borderRadius'] ?? 0) > 0) {
            $componentValue->set('borderRadiusType', $styles['borderRadiusType']);
            $componentValue->set('borderRadius', $styles['borderRadius'] ?? 0);
            $componentValue->set('borderRadiusSuffix', $styles['borderRadiusSuffix'] ?? 'px');
            $componentValue->set('borderTopLeftRadius', $styles['borderTopLeftRadius'] ?? 0);
            $componentValue->set('borderTopRightRadius', $styles['borderTopRightRadius'] ?? 0);
            $componentValue->set('borderBottomLeftRadius', $styles['borderBottomLeftRadius'] ?? 0);
            $componentValue->set('borderBottomRightRadius', $styles['borderBottomRightRadius'] ?? 0);
            $componentValue->set('borderTopLeftRadiusSuffix', $styles['borderTopLeftRadiusSuffix'] ?? 'px');
            $componentValue->set('borderTopRightRadiusSuffix', $styles['borderTopRightRadiusSuffix'] ?? 'px');
            $componentValue->set('borderBottomLeftRadiusSuffix', $styles['borderBottomLeftRadiusSuffix'] ?? 'px');
            $componentValue->set('borderBottomRightRadiusSuffix', $styles['borderBottomRightRadiusSuffix'] ?? 'px');
        }

        // Применяем border (если есть)
        if (isset($styles['borderWidthType']) && ($styles['borderWidth'] ?? 0) > 0) {
            $componentValue->set('borderWidthType', $styles['borderWidthType']);
            $componentValue->set('borderWidth', $styles['borderWidth'] ?? 0);
            $componentValue->set('borderTopWidth', $styles['borderTopWidth'] ?? 0);
            $componentValue->set('borderRightWidth', $styles['borderRightWidth'] ?? 0);
            $componentValue->set('borderBottomWidth', $styles['borderBottomWidth'] ?? 0);
            $componentValue->set('borderLeftWidth', $styles['borderLeftWidth'] ?? 0);
            $componentValue->set('borderStyle', $styles['borderStyle'] ?? 'solid');
            $componentValue->set('borderColorHex', $styles['borderColorHex'] ?? '#000000');
            $componentValue->set('borderColorOpacity', $styles['borderColorOpacity'] ?? 1);
        }

        // Применяем opacity (если отличается от 1)
        if (isset($styles['opacity']) && $styles['opacity'] != 1) {
            $componentValue->set('opacity', $styles['opacity']);
        }
    }

    /**
     * Извлечение числового значения из CSS значения (например, "10px" -> 10)
     * 
     * @param string $value CSS значение
     * @return int Числовое значение
     */
    protected function extractNumericValue(string $value): int
    {
        $value = trim($value);
        $numeric = preg_replace('/[^0-9.-]/', '', $value);
        return (int)max(0, $numeric);
    }
}
