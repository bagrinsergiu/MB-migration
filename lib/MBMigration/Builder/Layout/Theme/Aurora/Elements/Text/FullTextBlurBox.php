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
use MBMigration\Core\Config;
use MBMigration\Core\Logger;

class FullTextBlurBox extends FullTextElement
{
    /**
     * Получить стили для blur-box из DOM
     *
     * @param ElementContextInterface $data
     * @param BrowserPageInterface $browserPage
     * @return array
     */
    protected function getBlurBoxStyles(ElementContextInterface $data, BrowserPageInterface $browserPage): array
    {
        $mbSectionItem = $data->getMbSection();
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();

        $selector = '[data-id="' . $sectionId . '"] .blur-box';

        // Получаем стили самого blur-box (для бордера и паддингов)
        $blurBoxProperties = [
            'border-color',
            'border-width',
            'border-style',
            'border-top-width',
            'border-right-width',
            'border-bottom-width',
            'border-left-width',
            'padding-top',
            'padding-bottom',
            'padding-left',
            'padding-right',
        ];

        $blurBoxStyles = $this->getDomElementStyles(
            $selector,
            $blurBoxProperties,
            $browserPage,
            $families,
            $defaultFont
        );

        // Получаем стили ::before псевдоэлемента (для background-color и opacity)
        $beforeProperties = [
            'background-color',
            'opacity',
        ];

        $beforeStyles = $this->getDomElementStyles(
            $selector,
            $beforeProperties,
            $browserPage,
            $families,
            $defaultFont,
            '::before'
        );

        // Объединяем стили
        $allStyles = array_merge($blurBoxStyles, $beforeStyles);

        // Если blur-box сам не имеет top/bottom padding (Clover: padding живёт в .content-wrapper),
        // пробуем извлечь только top/bottom из .content-wrapper внутри blur-box.
        // Left/right не берём: внешний Column уже обеспечивает боковые отступы вокруг overlay-бокса.
        $blurBoxHasTopBottomPadding = (!empty($allStyles['padding-top']) && (int)str_replace('px', '', $allStyles['padding-top']) > 0)
            || (!empty($allStyles['padding-bottom']) && (int)str_replace('px', '', $allStyles['padding-bottom']) > 0);

        if (!$blurBoxHasTopBottomPadding) {
            $contentWrapperSelector = '[data-id="' . $sectionId . '"] .content-wrapper';
            $contentWrapperStyles = $this->getDomElementStyles(
                $contentWrapperSelector,
                ['padding-top', 'padding-bottom'],
                $browserPage,
                $families,
                $defaultFont
            );
            foreach (['padding-top', 'padding-bottom'] as $prop) {
                if (!empty($contentWrapperStyles[$prop])) {
                    $allStyles[$prop] = $contentWrapperStyles[$prop];
                }
            }
        }

        // Получаем мобильные padding стили (viewport 767px)
        $mobilePaddingProperties = [
            'padding-top',
            'padding-bottom',
            'padding-left',
            'padding-right',
        ];

        $mobileStyles = $this->getDomElementStylesAtViewport(
            $selector,
            $mobilePaddingProperties,
            $browserPage,
            767,
            1024,
            $families,
            $defaultFont
        );

        // На мобильном: если blur-box не имеет padding, пробуем .content-wrapper.
        // Top/bottom — из viewport 767px (стандартный брейкпоинт Brizy).
        // Left/right — из viewport 390px (реальный мобильный размер), чтобы не получить
        // слишком широкие отступы по бокам на маленьких экранах.
        $blurBoxHasMobileTopBottomPadding = !empty($mobileStyles['padding-top'])
            && (int)str_replace('px', '', $mobileStyles['padding-top']) > 0;

        if (!$blurBoxHasMobileTopBottomPadding) {
            $contentWrapperSelector = '[data-id="' . $sectionId . '"] .content-wrapper';
            $mobileContentWrapperStyles = $this->getDomElementStylesAtViewport(
                $contentWrapperSelector,
                ['padding-top', 'padding-bottom'],
                $browserPage,
                767,
                1024,
                $families,
                $defaultFont
            );
            foreach (['padding-top', 'padding-bottom'] as $prop) {
                if (!empty($mobileContentWrapperStyles[$prop])) {
                    $mobileStyles[$prop] = $mobileContentWrapperStyles[$prop];
                }
            }
        }

        // Left/right на мобильном фиксированы: 20px с каждой стороны внутри overlay-бокса.
        $mobileStyles['padding-left'] = '20px';
        $mobileStyles['padding-right'] = '20px';

        // Добавляем мобильные стили с префиксом mobile-
        foreach ($mobileStyles as $prop => $value) {
            $allStyles['mobile-' . $prop] = $value;
        }

        return $allStyles;
    }

    /**
     * Нормализовать стили blur-box в единый массив BlurBoxStyles
     *
     * @param array $rawStyles
     * @return array
     */
    protected function normalizeBlurBoxStyles(array $rawStyles): array
    {
        $normalized = [];

        // Background-color и opacity из ::before
        if (isset($rawStyles['background-color'])) {
            $normalized['bgColorHex'] = ColorConverter::rgba2hex($rawStyles['background-color']);
            $normalized['bgColorOpacity'] = ColorConverter::rgba2opacity($rawStyles['background-color']);
        } else {
            $normalized['bgColorHex'] = '#000000';
            $normalized['bgColorOpacity'] = 0.5; // значение по умолчанию
        }

        // Opacity из ::before (если есть отдельное значение)
        if (isset($rawStyles['opacity'])) {
            $opacity = NumberProcessor::convertToNumeric($rawStyles['opacity']);
            // Умножаем opacity на bgColorOpacity для итоговой прозрачности
            $normalized['bgColorOpacity'] = $normalized['bgColorOpacity'] * $opacity;
        }

        // Border стили
        if (isset($rawStyles['border-color'])) {
            $normalized['borderColorHex'] = ColorConverter::rgba2hex($rawStyles['border-color']);
            $normalized['borderColorOpacity'] = ColorConverter::rgba2opacity($rawStyles['border-color']);
        } else {
            $normalized['borderColorHex'] = '#ffffff';
            $normalized['borderColorOpacity'] = 1;
        }

        // Border width
        if (isset($rawStyles['border-width'])) {
            $normalized['borderWidth'] = (int)str_replace('px', '', $rawStyles['border-width']);
        } elseif (isset($rawStyles['border-top-width'])) {
            $normalized['borderWidth'] = (int)str_replace('px', '', $rawStyles['border-top-width']);
        } else {
            $normalized['borderWidth'] = 1;
        }

        // Border style
        $normalized['borderStyle'] = $rawStyles['border-style'] ?? 'solid';

        // Border width type (ungrouped если разные значения, grouped если одинаковые)
        $hasDifferentWidths = isset($rawStyles['border-top-width']) &&
                             isset($rawStyles['border-right-width']) &&
                             isset($rawStyles['border-bottom-width']) &&
                             isset($rawStyles['border-left-width']) &&
                             ($rawStyles['border-top-width'] !== $rawStyles['border-right-width'] ||
                              $rawStyles['border-top-width'] !== $rawStyles['border-bottom-width'] ||
                              $rawStyles['border-top-width'] !== $rawStyles['border-left-width']);

        $normalized['borderWidthType'] = $hasDifferentWidths ? 'ungrouped' : 'grouped';

        // Индивидуальные border widths если ungrouped
        if ($hasDifferentWidths) {
            $normalized['borderTopWidth'] = isset($rawStyles['border-top-width'])
                ? (int)str_replace('px', '', $rawStyles['border-top-width'])
                : $normalized['borderWidth'];
            $normalized['borderRightWidth'] = isset($rawStyles['border-right-width'])
                ? (int)str_replace('px', '', $rawStyles['border-right-width'])
                : $normalized['borderWidth'];
            $normalized['borderBottomWidth'] = isset($rawStyles['border-bottom-width'])
                ? (int)str_replace('px', '', $rawStyles['border-bottom-width'])
                : $normalized['borderWidth'];
            $normalized['borderLeftWidth'] = isset($rawStyles['border-left-width'])
                ? (int)str_replace('px', '', $rawStyles['border-left-width'])
                : $normalized['borderWidth'];
        }

        // Padding стили для внутренних отступов вокруг текста
        $normalized['paddingTop'] = isset($rawStyles['padding-top'])
            ? (int)str_replace('px', '', $rawStyles['padding-top'])
            : 0;
        $normalized['paddingBottom'] = isset($rawStyles['padding-bottom'])
            ? (int)str_replace('px', '', $rawStyles['padding-bottom'])
            : 0;
        $normalized['paddingLeft'] = isset($rawStyles['padding-left'])
            ? (int)str_replace('px', '', $rawStyles['padding-left'])
            : 0;
        $normalized['paddingRight'] = isset($rawStyles['padding-right'])
            ? (int)str_replace('px', '', $rawStyles['padding-right'])
            : 0;

        // Мобильные padding (из mobile-padding-* ключей, fallback на desktop значения)
        $normalized['mobilePaddingTop'] = isset($rawStyles['mobile-padding-top'])
            ? (int)str_replace('px', '', $rawStyles['mobile-padding-top'])
            : $normalized['paddingTop'];
        $normalized['mobilePaddingBottom'] = isset($rawStyles['mobile-padding-bottom'])
            ? (int)str_replace('px', '', $rawStyles['mobile-padding-bottom'])
            : $normalized['paddingBottom'];
        $normalized['mobilePaddingLeft'] = isset($rawStyles['mobile-padding-left'])
            ? (int)str_replace('px', '', $rawStyles['mobile-padding-left'])
            : $normalized['paddingLeft'];
        $normalized['mobilePaddingRight'] = isset($rawStyles['mobile-padding-right'])
            ? (int)str_replace('px', '', $rawStyles['mobile-padding-right'])
            : $normalized['paddingRight'];

        return $normalized;
    }

    /**
     * Обработать стили blur-box и применить к компоненту
     *
     * @param ElementContextInterface $data
     * @param BrowserPageInterface $browserPage
     * @param BrizyComponent $component
     * @return BrizyComponent
     */
    protected function handleBlurBoxStyles(
        ElementContextInterface $data,
        BrowserPageInterface $browserPage,
        BrizyComponent $component
    ): BrizyComponent {
        try {
            // 1. Получаем все нужные стили для blur-box
            $rawStyles = $this->getBlurBoxStyles($data, $browserPage);

            if (empty($rawStyles)) {
                Logger::instance()->warning('BlurBox styles not found, using defaults');
                return $component;
            }

            // 2. Нормализуем стили в единый массив
            $blurBoxStyles = $this->normalizeBlurBoxStyles($rawStyles);

            // 3. Применяем стили к компоненту
            $component->getValue()
                ->set_bgColorType('solid')
                ->set_bgColorHex($blurBoxStyles['bgColorHex'])
                ->set_bgColorOpacity($blurBoxStyles['bgColorOpacity'])
                ->set_bgColorPalette('')
                ->set_borderStyle($blurBoxStyles['borderStyle'])
                ->set_borderColorHex($blurBoxStyles['borderColorHex'])
                ->set_borderColorOpacity($blurBoxStyles['borderColorOpacity'])
                ->set_borderColorPalette('')
                ->set_borderWidthType($blurBoxStyles['borderWidthType']);

            if ($blurBoxStyles['borderWidthType'] === 'grouped') {
                $component->getValue()->set_borderWidth($blurBoxStyles['borderWidth']);
            } else {
                $component->getValue()
                    ->set_borderTopWidth($blurBoxStyles['borderTopWidth'] ?? $blurBoxStyles['borderWidth'])
                    ->set_borderRightWidth($blurBoxStyles['borderRightWidth'] ?? $blurBoxStyles['borderWidth'])
                    ->set_borderBottomWidth($blurBoxStyles['borderBottomWidth'] ?? $blurBoxStyles['borderWidth'])
                    ->set_borderLeftWidth($blurBoxStyles['borderLeftWidth'] ?? $blurBoxStyles['borderWidth']);
            }

            // Применяем те же стили для mobile
            $component->getValue()
                ->set_mobileBgColorType('solid')
                ->set_mobileBgColorHex($blurBoxStyles['bgColorHex'])
                ->set_mobileBgColorOpacity($blurBoxStyles['bgColorOpacity'])
                ->set_mobileBgColorPalette('');

            // Применяем внутренние отступы вокруг текста (blur-box / overlay box).
            // Значения берём из DOM (getBlurBoxStyles → .content-wrapper как fallback).
            // Если DOM даёт 0 для top/bottom — используем 88px (эталон с исходного сайта).
            $padTop = ($blurBoxStyles['paddingTop'] ?? 0) ?: 88;
            $padBottom = ($blurBoxStyles['paddingBottom'] ?? 0) ?: 88;
            $padLeft = $blurBoxStyles['paddingLeft'] ?? 0;
            $padRight = $blurBoxStyles['paddingRight'] ?? 0;
            $component->getValue()
                ->set_paddingType('ungrouped')
                ->set_paddingTop($padTop)
                ->set_paddingTopSuffix('px')
                ->set_paddingBottom($padBottom)
                ->set_paddingBottomSuffix('px')
                ->set_paddingLeft($padLeft)
                ->set_paddingLeftSuffix('px')
                ->set_paddingRight($padRight)
                ->set_paddingRightSuffix('px');

            // Mobile padding — из DOM (getBlurBoxStyles при viewport 767px). Если null — берём desktop.
            $mPadTop = $blurBoxStyles['mobilePaddingTop'] ?? $padTop;
            $mPadBottom = $blurBoxStyles['mobilePaddingBottom'] ?? $padBottom;
            $mPadLeft = $blurBoxStyles['mobilePaddingLeft'] ?? $padLeft;
            $mPadRight = $blurBoxStyles['mobilePaddingRight'] ?? $padRight;
            $component->getValue()
                ->set_mobilePaddingType('ungrouped')
                ->set_mobilePadding($mPadTop)
                ->set_mobilePaddingSuffix('px')
                ->set_mobilePaddingTop($mPadTop)
                ->set_mobilePaddingTopSuffix('px')
                ->set_mobilePaddingBottom($mPadBottom)
                ->set_mobilePaddingBottomSuffix('px')
                ->set_mobilePaddingLeft($mPadLeft)
                ->set_mobilePaddingLeftSuffix('px')
                ->set_mobilePaddingRight($mPadRight)
                ->set_mobilePaddingRightSuffix('px')
                ->set_tempMobilePadding($mPadTop)
                ->set_tempMobilePaddingTop($mPadTop)
                ->set_tempMobilePaddingTopSuffix('px')
                ->set_tempMobilePaddingRight($mPadRight)
                ->set_tempMobilePaddingRightSuffix('px')
                ->set_tempMobilePaddingBottom($mPadBottom)
                ->set_tempMobilePaddingBottomSuffix('px')
                ->set_tempMobilePaddingLeft($mPadLeft)
                ->set_tempMobilePaddingLeftSuffix('px');

            $component->getValue()
                ->set_mobileMarginType('grouped')
                ->set_mobileMargin(0)
                ->set_mobileMarginSuffix('px')
                ->set_mobileMarginTop(0)
                ->set_mobileMarginTopSuffix('px')
                ->set_mobileMarginRight(0)
                ->set_mobileMarginRightSuffix('px')
                ->set_mobileMarginBottom(0)
                ->set_mobileMarginBottomSuffix('px')
                ->set_mobileMarginLeft(0)
                ->set_mobileMarginLeftSuffix('px');

        } catch (\Exception $e) {
            Logger::instance()->error('Error handling BlurBox styles: ' . $e->getMessage());
        }

        return $component;
    }

    /**
     * Получить компонент контейнера текста
     * Внутренний Column на глубине (0, 0, 0, 0, 0, 0) для применения стилей blur-box
     * Структура: Section -> SectionItem -> Row -> Column -> Row -> Column
     *
     * @param BrizyComponent $brizySection
     * @return BrizyComponent
     */
    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 0, 0, 0);
    }

    /**
     * Получить компонент секции
     *
     * @param BrizyComponent $brizySection
     * @return BrizyComponent
     */
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    /**
     * Трансформировать элемент с применением стилей
     *
     * @param ElementContextInterface $data
     * @param BrizyComponent $brizySection
     * @param array $params
     * @return BrizyComponent
     */
    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }

    /**
     * Получить стили внешнего Column из исходного сайта
     * Пробует различные селекторы для получения background-color, padding-top, padding-bottom контейнера с изображением
     *
     * @param array $mbSectionItem
     * @param array $sectionStyles
     * @return array
     */
    protected function getOuterColumnStyles($mbSectionItem, $sectionStyles): array
    {
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];

        // Пробуем разные селекторы для получения стилей внешнего Column.
        // Для full-text-blur-box цвет фона задаётся на секции [data-id="..."] или на .bg-opacity (> div > div).
        // Селектор секции идёт первым, чтобы гарантировать корректный цвет (#587c82), а не дефолт шаблона (#9a4646).
        // ВАЖНО: .content-wrapper намеренно исключён из этого списка — его padding соответствует
        // внутреннему overlay-блоку (blur-box), а не внешнему Column с background-image.
        // .content-wrapper читается отдельно в getBlurBoxStyles.
        $outerColumnSelectors = [
            '[data-id="' . $sectionId . '"]',
            '[data-id="' . $sectionId . '"] > div > div',
            '[data-id="' . $sectionId . '"] .row > .column:first-child',
            '[data-id="' . $sectionId . '"] > div',
            '[data-id="' . $sectionId . '"] .row > .column',
        ];

        $families = [];
        $defaultFont = '';

        $resultStyles = [];
        $foundBgColor = false;

        // Пытаемся получить стили из разных селекторов
        $paddingProps = ['padding-top', 'padding-bottom', 'padding-left', 'padding-right'];
        foreach ($outerColumnSelectors as $selector) {
            $styles = $this->getDomElementStyles(
                $selector,
                array_merge(['background-color', 'opacity'], $paddingProps),
                $this->browserPage,
                $families,
                $defaultFont
            );

            // Собираем padding из всех селекторов
            foreach ($paddingProps as $prop) {
                if (isset($styles[$prop]) && !isset($resultStyles[$prop])) {
                    $resultStyles[$prop] = $styles[$prop];
                }
            }

            // Проверяем, что background-color валидный, не прозрачный и слой видим (opacity не 0)
            if (!empty($styles['background-color']) && !$foundBgColor) {
                $bgColor = strtolower(trim($styles['background-color']));
                $transparentValues = [
                    'rgba(0, 0, 0, 0)',
                    'rgba(255, 255, 255, 0)',
                    'transparent',
                    'rgba(0,0,0,0)',
                    'rgba(255,255,255,0)',
                ];
                $opacityNumeric = isset($styles['opacity'])
                    ? NumberProcessor::convertToNumeric($styles['opacity'])
                    : 1.0;

                if (!in_array($bgColor, $transparentValues) && $opacityNumeric >= 0.01) {
                    $resultStyles['background-color'] = $styles['background-color'];
                    if (isset($styles['opacity'])) {
                        $resultStyles['opacity'] = $styles['opacity'];
                    }
                    $foundBgColor = true;
                }
            }
        }

        // Overlay поверх изображения: полупрозрачный цвет из ::before (слой над картинкой)
        $overlayBaseSelectors = [
            '[data-id="' . $sectionId . '"] .brz-bg',
            '[data-id="' . $sectionId . '"] .bg-helper',
            '[data-id="' . $sectionId . '"] > div > div',
            '[data-id="' . $sectionId . '"] .content-wrapper',
            '[data-id="' . $sectionId . '"] .row > .column',
        ];
        $overlayProps = ['background-color', 'opacity'];
        foreach ($overlayBaseSelectors as $sel) {
            $overlayStyles = $this->getDomElementStyles(
                $sel,
                $overlayProps,
                $this->browserPage,
                $families,
                $defaultFont,
                '::before'
            );
            if (!empty($overlayStyles['background-color'])) {
                $bg = strtolower(trim($overlayStyles['background-color']));
                $transparent = in_array($bg, [
                    'rgba(0, 0, 0, 0)', 'rgba(255, 255, 255, 0)',
                    'transparent', 'rgba(0,0,0,0)', 'rgba(255,255,255,0)',
                ], true);
                $op = isset($overlayStyles['opacity'])
                    ? NumberProcessor::convertToNumeric($overlayStyles['opacity'])
                    : ColorConverter::rgba2opacity($overlayStyles['background-color']);
                if (!$transparent && $op > 0.01 && $op < 1) {
                    $resultStyles['overlay-background-color'] = $overlayStyles['background-color'];
                    $resultStyles['overlay-opacity'] = $op;
                    if (isset($overlayStyles['opacity'])) {
                        $resultStyles['overlay-opacity'] = $op * NumberProcessor::convertToNumeric($overlayStyles['opacity']);
                    }
                    break;
                }
            }
        }

        // #region agent log
        Logger::instance()->debug(
            '[FullTextBlurBox] getOuterColumnStyles result: sectionId=' . $sectionId
            . ', resultBgColor=' . ($resultStyles['background-color'] ?? 'null')
            . ', overlayBg=' . ($resultStyles['overlay-background-color'] ?? 'null')
            . ', resultKeys=' . implode(',', array_keys($resultStyles))
        );
        // #endregion

        // Получаем мобильные padding для outerColumn (viewport 767px)
        $mobileOuterPaddingProps = ['padding-top', 'padding-bottom', 'padding-left', 'padding-right'];
        foreach ($outerColumnSelectors as $selector) {
            $mobileStyles = $this->getDomElementStylesAtViewport(
                $selector,
                $mobileOuterPaddingProps,
                $this->browserPage,
                767,
                1024,
                $families,
                $defaultFont
            );
            if (!empty($mobileStyles)) {
                foreach ($mobileStyles as $prop => $value) {
                    if (!isset($resultStyles['mobile-' . $prop])) {
                        $resultStyles['mobile-' . $prop] = $value;
                    }
                }
                break;
            }
        }

        return $resultStyles;
    }

    /**
     * Внешний Column «рамки»: SectionItem -> Row(0) -> Column(0).
     * В шаблоне у этого Column задан bgColorHex (#9a4646) — сюда ставим цвет с исходной страницы.
     *
     * @param BrizyComponent $sectionItem SectionItem (не Section)
     * @return BrizyComponent|null Column или null, если по пути не Column (защита от смены структуры шаблона)
     */
    protected function getOuterFrameColumn(BrizyComponent $sectionItem): ?BrizyComponent
    {
        $outer = $sectionItem->getItemWithDepth(0, 0);
        return ($outer && $outer->getType() === 'Column') ? $outer : null;
    }

    /**
     * Переопределяем handleSectionBackground для установки изображения на внешний Column
     * Градиент остается на SectionItem, изображение устанавливается на внешний Column
     *
     * ВАЖНО: $brizySection здесь это SectionItem, поэтому depth считается относительно него:
     * SectionItem -> Row (0) -> Column (0,0) - это внешний Column для изображения
     *
     * @param BrizyComponent $brizySection (это SectionItem)
     * @param array $mbSectionItem
     * @param array $sectionStyles
     * @param array $options
     */
    protected function handleSectionBackground(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles, $options = ['heightType' => 'custom'])
    {
        if ($brizySection->getType() == 'Section') {
            return;
        }

        // #region agent log
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'] ?? '?';
        $hasPhoto = isset($mbSectionItem['settings']['sections']['background']['photo'])
            && $mbSectionItem['settings']['sections']['background']['photo'] !== '';
        $hasFilename = isset($mbSectionItem['settings']['sections']['background']['filename'])
            && isset($mbSectionItem['settings']['sections']['background']['photo']);
        Logger::instance()->debug(
            '[FullTextBlurBox] handleSectionBackground branch: sectionId=' . $sectionId
            . ', hasBackgroundPhoto=' . ($hasPhoto ? '1' : '0')
            . ', hasFilename=' . ($hasFilename ? '1' : '0')
            . ', sectionStylesBg=' . ($sectionStyles['background-color'] ?? 'null')
        );
        // #endregion

        // Обрабатываем background-color для градиента на внешней секции
        if (isset($sectionStyles['background-color'])) {
            $sectionStyles['background-opacity'] = NumberProcessor::convertToNumeric(
                $sectionStyles['opacity'] ?? ColorConverter::rgba2opacity($sectionStyles['background-color'] ?? 'rgba(255,255,255,1)')
            );
            if (Config::$devMode) {
                $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'] ?? '';
                Logger::instance()->debug('[FullTextBlurBox] Section background source: sectionStyles', ['sectionId' => $sectionId]);
            }
        } else {
            // Цвет динамически с исходной страницы: приоритет — обёртка секции (getOuterColumnStyles), затем body.
            // Фикс: не подставлять body, пока не попробованы стили секции (иначе «рамка» могла становиться красной вместо teal).
            $outerColumnStyles = $this->getOuterColumnStyles($mbSectionItem, $sectionStyles);
            if (!empty($outerColumnStyles['background-color'])) {
                $sectionStyles['background-color'] = $outerColumnStyles['background-color'];
                $sectionStyles['opacity'] = $outerColumnStyles['opacity'] ?? ColorConverter::rgba2opacity($outerColumnStyles['background-color']);
                $sectionStyles['background-opacity'] = NumberProcessor::convertToNumeric(
                    ColorConverter::rgba2opacity($sectionStyles['background-color'])
                );
                if (isset($outerColumnStyles['opacity'])) {
                    $sectionStyles['background-opacity'] *= NumberProcessor::convertToNumeric($outerColumnStyles['opacity']);
                }
                if (Config::$devMode) {
                    $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'] ?? '';
                    Logger::instance()->debug('[FullTextBlurBox] Section background source: outerColumn', ['sectionId' => $sectionId]);
                }
            } else {
                // Fallback: фон body (как раньше)
                $styles = $this->getDomElementStyles(
                    'body',
                    ['background-color', 'opacity'],
                    $this->browserPage,
                    [],
                    []
                );
                $sectionStyles['background-color'] = ColorConverter::convertColorRgbToHex($styles['background-color'] ?? '#ffffff');
                $sectionStyles['opacity'] = ColorConverter::normalizeOpacity($styles['opacity']);
                $sectionStyles['background-opacity'] = NumberProcessor::convertToNumeric($sectionStyles['opacity']);
                if (Config::$devMode) {
                    $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'] ?? '';
                    Logger::instance()->debug('[FullTextBlurBox] Section background source: body (fallback)', ['sectionId' => $sectionId]);
                }
            }
        }

        // Применяем градиент/цвет к внешней секции (SectionItem) - без изображения
        $this->handleItemBackground($brizySection, $sectionStyles);

        if (isset($mbSectionItem['settings']['sections']['background']['photo']) &&
            $mbSectionItem['settings']['sections']['background']['photo'] != '') {
            $background = $mbSectionItem['settings']['sections']['background'];
            if (isset($background['filename']) && isset($background['photo'])) {
                $outerColumn = $this->getOuterFrameColumn($brizySection);

                if ($outerColumn !== null) {
                    // Получаем стили внешнего Column из исходного сайта
                    $outerColumnStyles = $this->getOuterColumnStyles($mbSectionItem, $sectionStyles);

                    // Извлекаем расширение из оригинального имени файла
                    $extension = pathinfo($background['filename'], PATHINFO_EXTENSION);

                    // bgImageSrc - это имя файла после загрузки в Brizy
                    // photo уже содержит имя файла (например: "56b7826206b375035803e2ff1c38e0de.jpg")
                    $bgImageSrc = $background['photo'];

                    // Если photo это URL, извлекаем только имя файла
                    if (filter_var($bgImageSrc, FILTER_VALIDATE_URL)) {
                        $bgImageSrc = basename(parse_url($bgImageSrc, PHP_URL_PATH));
                    }

                    // Получаем размеры изображения из метаданных или настроек
                    $bgImageWidth = null;
                    $bgImageHeight = null;

                    // Проверяем метаданные после загрузки
                    if (isset($background['metadata'])) {
                        $metadata = is_string($background['metadata'])
                            ? json_decode($background['metadata'], true)
                            : $background['metadata'];
                        if (isset($metadata['width'])) {
                            $bgImageWidth = (int)$metadata['width'];
                        }
                        if (isset($metadata['height'])) {
                            $bgImageHeight = (int)$metadata['height'];
                        }
                    }

                    // Если нет в метаданных, проверяем в настройках
                    if ($bgImageWidth === null) {
                        $bgImageWidth = $background['width'] ?? $background['imageWidth'] ?? null;
                    }
                    if ($bgImageHeight === null) {
                        $bgImageHeight = $background['height'] ?? $background['imageHeight'] ?? null;
                    }

                    // Цвет overlay для Column с изображением — из DOM: overlay (::before, полупрозрачный)
                    // или frame; opacity=1 (сплошной) не применяем — перекрывает картинку
                    $bgColorHex = '#ffffff';
                    $bgColorOpacity = 0;
                    if (!empty($outerColumnStyles['overlay-background-color'])) {
                        $bgColorHex = ColorConverter::rgba2hex($outerColumnStyles['overlay-background-color']);
                        $bgColorOpacity = (float)($outerColumnStyles['overlay-opacity'] ?? ColorConverter::rgba2opacity($outerColumnStyles['overlay-background-color']));
                    } elseif (!empty($outerColumnStyles['background-color'])) {
                        $op = ColorConverter::rgba2opacity($outerColumnStyles['background-color']);
                        if (isset($outerColumnStyles['opacity'])) {
                            $op *= NumberProcessor::convertToNumeric($outerColumnStyles['opacity']);
                        }
                        if ($op < 1) {
                            $bgColorHex = ColorConverter::rgba2hex($outerColumnStyles['background-color']);
                            $bgColorOpacity = $op;
                        }
                    }

                    // Padding внешнего Column — только из исходного DOM (getOuterColumnStyles)
                    $paddingTop = isset($outerColumnStyles['padding-top'])
                        ? (int)str_replace('px', '', $outerColumnStyles['padding-top'])
                        : 0;
                    $paddingBottom = isset($outerColumnStyles['padding-bottom'])
                        ? (int)str_replace('px', '', $outerColumnStyles['padding-bottom'])
                        : 0;
                    $paddingLeft = isset($outerColumnStyles['padding-left'])
                        ? (int)str_replace('px', '', $outerColumnStyles['padding-left'])
                        : 0;
                    $paddingRight = isset($outerColumnStyles['padding-right'])
                        ? (int)str_replace('px', '', $outerColumnStyles['padding-right'])
                        : 0;

                    $mobilePaddingTop = isset($outerColumnStyles['mobile-padding-top'])
                        ? (int)str_replace('px', '', $outerColumnStyles['mobile-padding-top'])
                        : 0;
                    $mobilePaddingBottom = isset($outerColumnStyles['mobile-padding-bottom'])
                        ? (int)str_replace('px', '', $outerColumnStyles['mobile-padding-bottom'])
                        : 0;
                    $mobilePaddingLeft = 20;
                    $mobilePaddingRight = 20;

                    $outerColumn->getValue()
                        ->set_bgImageSrc($bgImageSrc)
                        ->set_bgImageFileName($background['filename'])
                        ->set_bgImageExtension($extension)
                        ->set_bgImageType('internal')
                        ->set_heightStyle('custom')
                        ->set_verticalAlign('center')
                        ->set_bgColorType('solid')
                        ->set_bgColorHex($bgColorHex)
                        ->set_bgColorOpacity($bgColorOpacity)
                        ->set_bgColorPalette('')
                        ->set_mobileBgColorType('solid')
                        ->set_mobileBgColorHex($bgColorHex)
                        ->set_mobileBgColorOpacity($bgColorOpacity)
                        ->set_mobileBgColorPalette('')
                        ->set_paddingType('ungrouped')
                        ->set_paddingTop($paddingTop)
                        ->set_paddingTopSuffix('px')
                        ->set_paddingBottom($paddingBottom)
                        ->set_paddingBottomSuffix('px')
                        ->set_paddingLeft($paddingLeft)
                        ->set_paddingLeftSuffix('px')
                        ->set_paddingRight($paddingRight)
                        ->set_paddingRightSuffix('px')
                        ->set_marginType('ungrouped')
                        ->set_marginTop(-100)
                        ->set_marginTopSuffix('px')
                        ->set_marginBottom(-100)
                        ->set_marginBottomSuffix('px')
                        ->set_marginLeft(0)
                        ->set_marginLeftSuffix('px')
                        ->set_marginRight(0)
                        ->set_marginRightSuffix('px')
                        ->set_mobilePaddingType('ungrouped')
                        ->set_mobilePaddingTop($mobilePaddingTop)
                        ->set_mobilePaddingTopSuffix('px')
                        ->set_mobilePaddingBottom($mobilePaddingBottom)
                        ->set_mobilePaddingBottomSuffix('px')
                        ->set_mobilePaddingLeft($mobilePaddingLeft)
                        ->set_mobilePaddingLeftSuffix('px')
                        ->set_mobilePaddingRight($mobilePaddingRight)
                        ->set_mobilePaddingRightSuffix('px')
                        ->set_mobileMarginType('grouped')
                        ->set_mobileMargin(0)
                        ->set_mobileMarginSuffix('px')
                        ->set_mobileMarginTop(0)
                        ->set_mobileMarginTopSuffix('px')
                        ->set_mobileMarginRight(0)
                        ->set_mobileMarginRightSuffix('px')
                        ->set_mobileMarginBottom(0)
                        ->set_mobileMarginBottomSuffix('px')
                        ->set_mobileMarginLeft(0)
                        ->set_mobileMarginLeftSuffix('px');

                    if ($bgImageWidth !== null) {
                        $outerColumn->getValue()->set_bgImageWidth((int)$bgImageWidth);
                    }
                    if ($bgImageHeight !== null) {
                        $outerColumn->getValue()->set_bgImageHeight((int)$bgImageHeight);
                    }

                    // Настройки для изображения
                    if (isset($background['photoOption'])) {
                        switch ($background['photoOption']) {
                            case 'parallax-scroll':
                                $outerColumn->getValue()->set_bgAttachment('animated');
                                break;
                            case 'parallax-fixed':
                                $outerColumn->getValue()->set_bgAttachment('fixed');
                                break;
                            case 'fill':
                                $outerColumn->getValue()->set_bgAttachment('none');
                                break;
                            case 'tile':
                                $outerColumn->getValue()->set_bgRepeat('on');
                                break;
                        }
                    }
                }
            } else {
                // Нет фонового изображения: внешний Column иначе остаётся с цветом из шаблона (#9a4646).
                $outerColumn = $this->getOuterFrameColumn($brizySection);
                // #region agent log
                $sectionIdLog2 = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'] ?? '?';
                Logger::instance()->debug(
                    '[FullTextBlurBox] has_photo_no_filename_branch: sectionId=' . $sectionIdLog2
                    . ', outerColumnNull=' . ($outerColumn === null ? '1' : '0')
                );
                // #endregion
                if ($outerColumn !== null) {
                    $outerColumnStyles = $this->getOuterColumnStyles($mbSectionItem, $sectionStyles);
                    if (!empty($outerColumnStyles['background-color'])) {
                        $bgColorHex = ColorConverter::rgba2hex($outerColumnStyles['background-color']);
                        $bgColorOpacity = ColorConverter::rgba2opacity($outerColumnStyles['background-color']);
                        if (isset($outerColumnStyles['opacity'])) {
                            $bgColorOpacity *= NumberProcessor::convertToNumeric($outerColumnStyles['opacity']);
                        }
                    } elseif (!empty($sectionStyles['background-color'])) {
                        // getOuterColumnStyles пустой (DOM/селекторы не дали цвета) — берём тот же цвет, что уже применили к SectionItem
                        $bgColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);
                        $bgColorOpacity = NumberProcessor::convertToNumeric(
                            $sectionStyles['background-opacity'] ?? ColorConverter::rgba2opacity($sectionStyles['background-color'])
                        );
                    } else {
                        $bgColorHex = null;
                        $bgColorOpacity = null;
                    }
                    // #region agent log
                    Logger::instance()->debug(
                        '[FullTextBlurBox] has_photo_no_filename_bgColor: sectionId=' . $sectionIdLog2
                        . ', bgColorHex=' . ($bgColorHex ?? 'null')
                        . ', willSet=' . ($bgColorHex !== null ? '1' : '0')
                    );
                    // #endregion
                    if ($bgColorHex !== null) {
                        $outerColumn->getValue()
                            ->set_bgColorType('solid')
                            ->set_bgColorHex($bgColorHex)
                            ->set_bgColorOpacity($bgColorOpacity)
                            ->set_bgColorPalette('')
                            ->set_mobileBgColorType('solid')
                            ->set_mobileBgColorHex($bgColorHex)
                            ->set_mobileBgColorOpacity($bgColorOpacity)
                            ->set_mobileBgColorPalette('');
                    }
                    $mPadT = isset($outerColumnStyles['mobile-padding-top'])
                        ? (int)str_replace('px', '', $outerColumnStyles['mobile-padding-top']) : 0;
                    $mPadB = isset($outerColumnStyles['mobile-padding-bottom'])
                        ? (int)str_replace('px', '', $outerColumnStyles['mobile-padding-bottom']) : 0;
                    $outerColumn->getValue()
                        ->set_mobilePaddingType('ungrouped')
                        ->set_mobilePaddingTop($mPadT)
                        ->set_mobilePaddingTopSuffix('px')
                        ->set_mobilePaddingBottom($mPadB)
                        ->set_mobilePaddingBottomSuffix('px')
                        ->set_mobilePaddingLeft(20)
                        ->set_mobilePaddingLeftSuffix('px')
                        ->set_mobilePaddingRight(20)
                        ->set_mobilePaddingRightSuffix('px')
                        ->set_mobileMarginType('grouped')
                        ->set_mobileMargin(0)
                        ->set_mobileMarginSuffix('px')
                        ->set_mobileMarginTop(0)
                        ->set_mobileMarginTopSuffix('px')
                        ->set_mobileMarginRight(0)
                        ->set_mobileMarginRightSuffix('px')
                        ->set_mobileMarginBottom(0)
                        ->set_mobileMarginBottomSuffix('px')
                        ->set_mobileMarginLeft(0)
                        ->set_mobileMarginLeftSuffix('px');
                }
            }
        } else {
                // Секция без фонового фото: шаблон оставляет у внешнего Column #9a4646 — перезаписываем цветом с исходной страницы
                $outerColumn = $this->getOuterFrameColumn($brizySection);
                // #region agent log
                $sectionIdLog = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'] ?? '?';
                Logger::instance()->debug(
                    '[FullTextBlurBox] no_photo_branch: sectionId=' . $sectionIdLog
                    . ', outerColumnNull=' . ($outerColumn === null ? '1' : '0')
                );
                // #endregion
                if ($outerColumn !== null) {
                    $outerColumnStyles = $this->getOuterColumnStyles($mbSectionItem, $sectionStyles);
                    if (!empty($outerColumnStyles['background-color'])) {
                        $bgColorHex = ColorConverter::rgba2hex($outerColumnStyles['background-color']);
                        $bgColorOpacity = ColorConverter::rgba2opacity($outerColumnStyles['background-color']);
                        if (isset($outerColumnStyles['opacity'])) {
                            $bgColorOpacity *= NumberProcessor::convertToNumeric($outerColumnStyles['opacity']);
                        }
                    } elseif (!empty($sectionStyles['background-color'])) {
                        $bgColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);
                        $bgColorOpacity = NumberProcessor::convertToNumeric(
                            $sectionStyles['background-opacity'] ?? ColorConverter::rgba2opacity($sectionStyles['background-color'])
                        );
                    } else {
                        $bgColorHex = null;
                        $bgColorOpacity = null;
                    }
                    // #region agent log
                    Logger::instance()->debug(
                        '[FullTextBlurBox] no_photo_bgColor: sectionId=' . $sectionIdLog
                        . ', bgColorHex=' . ($bgColorHex ?? 'null')
                        . ', outerColumnStylesBg=' . ($outerColumnStyles['background-color'] ?? 'null')
                        . ', sectionStylesBg=' . ($sectionStyles['background-color'] ?? 'null')
                        . ', willSet=' . ($bgColorHex !== null ? '1' : '0')
                    );
                    // #endregion
                    if ($bgColorHex !== null) {
                        $outerColumn->getValue()
                            ->set_bgColorType('solid')
                            ->set_bgColorHex($bgColorHex)
                            ->set_bgColorOpacity($bgColorOpacity)
                            ->set_bgColorPalette('')
                            ->set_mobileBgColorType('solid')
                            ->set_mobileBgColorHex($bgColorHex)
                            ->set_mobileBgColorOpacity($bgColorOpacity)
                            ->set_mobileBgColorPalette('');
                    }
                    $mPadT2 = isset($outerColumnStyles['mobile-padding-top'])
                        ? (int)str_replace('px', '', $outerColumnStyles['mobile-padding-top']) : 0;
                    $mPadB2 = isset($outerColumnStyles['mobile-padding-bottom'])
                        ? (int)str_replace('px', '', $outerColumnStyles['mobile-padding-bottom']) : 0;
                    $outerColumn->getValue()
                        ->set_mobilePaddingType('ungrouped')
                        ->set_mobilePaddingTop($mPadT2)
                        ->set_mobilePaddingTopSuffix('px')
                        ->set_mobilePaddingBottom($mPadB2)
                        ->set_mobilePaddingBottomSuffix('px')
                        ->set_mobilePaddingLeft(20)
                        ->set_mobilePaddingLeftSuffix('px')
                        ->set_mobilePaddingRight(20)
                        ->set_mobilePaddingRightSuffix('px')
                        ->set_mobileMarginType('grouped')
                        ->set_mobileMargin(0)
                        ->set_mobileMarginSuffix('px')
                        ->set_mobileMarginTop(0)
                        ->set_mobileMarginTopSuffix('px')
                        ->set_mobileMarginRight(0)
                        ->set_mobileMarginRightSuffix('px')
                        ->set_mobileMarginBottom(0)
                        ->set_mobileMarginBottomSuffix('px')
                        ->set_mobileMarginLeft(0)
                        ->set_mobileMarginLeftSuffix('px');
                }
            }

            // Настройка высоты секции (только когда у секции задано фоновое фото)
            if (isset($mbSectionItem['settings']['sections']['background']['photo']) &&
                $mbSectionItem['settings']['sections']['background']['photo'] != '') {
                if ($options['heightType'] == 'auto') {
                    $brizySection
                        ->getParent()
                        ->getValue()
                        ->set_sectionHeight(500)
                        ->set_fullHeight('auto');
                } else if ($options['heightType'] == 'custom') {
                    $heightValue = isset($sectionStyles['height']) ? (int)str_replace('px', '', $sectionStyles['height']) : 500;
                    $brizySection
                        ->getParent()
                        ->getValue()
                        ->set_sectionHeight($heightValue)
                        ->set_fullHeight('custom');
                }
            }
    }

    /**
     * Переопределяем handleSectionStyles чтобы не устанавливать паддинги на SectionItem
     * Паддинги должны быть только на внешнем Column
     * Используем стандартную логику из трейта, но убираем установку паддингов
     *
     * @param ElementContextInterface $data
     * @param BrowserPageInterface $browserPage
     * @param array $additionalOptions
     * @return BrizyComponent
     */
    protected function handleSectionStyles(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
                                $additionalOptions = []
    ): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

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

        // Устанавливаем градиент/фон через handleSectionBackground
        $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles, $options);

        // НЕ устанавливаем паддинги на SectionItem - они должны быть только на внешнем Column
        $brizySection->getValue()
            ->set_paddingType('ungrouped')
            ->set_paddingTop(0)
            ->set_paddingBottom(0)
            ->set_paddingRight(0)
            ->set_paddingLeft(0)
            ->set_marginType('ungrouped')
            ->set_marginLeft((int)($sectionStyles['margin-left'] ?? 0))
            ->set_marginRight((int)($sectionStyles['margin-right'] ?? 0))
            ->set_marginTop((int)($sectionStyles['margin-top'] ?? 0))
            ->set_marginBottom((int)($sectionStyles['margin-bottom'] ?? 0))
            ->set_fullHeight('custom')
            ->set_sectionHeight((int)($sectionStyles['height'] ?? 0))
            ->set_mobileBgSize('cover')
            ->set_mobileBgSizeType('original')
            ->set_mobileBgRepeat('off')
            ->set_mobilePaddingType('ungrouped')
            ->set_mobilePadding((int)($sectionStyles['padding-top'] ?? 0))
            ->set_mobilePaddingSuffix('px')
            ->set_mobilePaddingTop(0)
            ->set_mobilePaddingTopSuffix('px')
            ->set_mobilePaddingRight(20)
            ->set_mobilePaddingRightSuffix('px')
            ->set_mobilePaddingBottom(0)
            ->set_mobilePaddingBottomSuffix('px')
            ->set_mobilePaddingLeft(20)
            ->set_mobilePaddingLeftSuffix('px')
            ->set_mobileMarginType('ungrouped')
            ->set_mobileMargin($this->getMobileMarginForHeaderImage($mbSectionItem, $sectionStyles))
            ->set_mobileMarginSuffix('px')
            ->set_mobileMarginTop($this->getMobileMarginTopForHeaderImage($mbSectionItem, $sectionStyles))
            ->set_mobileMarginTopSuffix('px')
            ->set_mobileMarginRight((int)($sectionStyles['margin-right'] ?? 0))
            ->set_mobileMarginRightSuffix('px')
            ->set_mobileMarginBottom($this->getMobileMarginBottomForHeaderImage($mbSectionItem, $sectionStyles))
            ->set_mobileMarginBottomSuffix('px')
            ->set_mobileMarginLeft((int)($sectionStyles['margin-left'] ?? 0))
            ->set_mobileMarginLeftSuffix('px');

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
     * Task 3: для секций с header image — убираем top margin на mobile (меньше отступ сверху)
     */
    protected function getMobileMarginTopForHeaderImage(array $mbSectionItem, array $sectionStyles): int
    {
        $hasPhoto = isset($mbSectionItem['settings']['sections']['background']['photo'])
            && $mbSectionItem['settings']['sections']['background']['photo'] !== '';
        return $hasPhoto ? 0 : (int)($sectionStyles['margin-top'] ?? 0);
    }

    /**
     * Task 3: для секций с header image — убираем bottom margin на mobile (стыковка со следующим блоком)
     */
    protected function getMobileMarginBottomForHeaderImage(array $mbSectionItem, array $sectionStyles): int
    {
        $hasPhoto = isset($mbSectionItem['settings']['sections']['background']['photo'])
            && $mbSectionItem['settings']['sections']['background']['photo'] !== '';
        return $hasPhoto ? 0 : (int)($sectionStyles['margin-bottom'] ?? 0);
    }

    /**
     * Task 3: для секций с header image — base margin 0 на mobile
     */
    protected function getMobileMarginForHeaderImage(array $mbSectionItem, array $sectionStyles): int
    {
        $hasPhoto = isset($mbSectionItem['settings']['sections']['background']['photo'])
            && $mbSectionItem['settings']['sections']['background']['photo'] !== '';
        return $hasPhoto ? 0 : (int)($sectionStyles['margin-bottom'] ?? 0);
    }

    /**
     * Переопределяем метод для применения стилей blur-box
     *
     * @param ElementContextInterface $data
     * @return BrizyComponent
     * @throws \Exception
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $mbS = $data->getMbSection();

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $textContainerComponent = $this->getTextContainerComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge(
            $data->getThemeContext()->getPageDTO()->getPageStyleDetails(),
            $this->getPropertiesMainSection()
        );

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->transformItem($elementContext, $textContainerComponent, $styleList);

        // Применяем стили blur-box к текстовому контейнеру (Column)
        $this->handleBlurBoxStyles($elementContext, $this->browserPage, $textContainerComponent);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($textContainerComponent);
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonationsButton(
            $elementContext,
            $this->browserPage,
            $this->brizyKit,
            $this->getDonationsButtonOptions(),
            $this->getDonationButtonTextTransform()
        );

        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    /**
     * Переопределяем метод, чтобы не применять хардкодные мобильные отступы (25/20) к SectionItem.
     * Мобильные отступы секции берутся динамически из источника в handleSectionStyles().
     */
    protected function getPropertiesMainSection(): array
    {
        return [];
    }
}
