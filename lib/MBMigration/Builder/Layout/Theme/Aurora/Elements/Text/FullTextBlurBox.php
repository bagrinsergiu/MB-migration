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

            // Применяем внутренние отступы вокруг текста
            $component->getValue()
                ->set_paddingType('ungrouped')
                ->set_paddingTop($blurBoxStyles['paddingTop'] ?? 0)
                ->set_paddingTopSuffix('px')
                ->set_paddingBottom($blurBoxStyles['paddingBottom'] ?? 0)
                ->set_paddingBottomSuffix('px')
                ->set_paddingLeft($blurBoxStyles['paddingLeft'] ?? 0)
                ->set_paddingLeftSuffix('px')
                ->set_paddingRight($blurBoxStyles['paddingRight'] ?? 0)
                ->set_paddingRightSuffix('px');

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
        $outerColumnSelectors = [
            '[data-id="' . $sectionId . '"]',
            '[data-id="' . $sectionId . '"] > div > div',
            '[data-id="' . $sectionId . '"] .content-wrapper',
            '[data-id="' . $sectionId . '"] .row > .column:first-child',
            '[data-id="' . $sectionId . '"] > div',
            '[data-id="' . $sectionId . '"] .row > .column',
        ];

        $families = [];
        $defaultFont = '';

        $resultStyles = [];
        $foundBgColor = false;

        // Пытаемся получить стили из разных селекторов
        foreach ($outerColumnSelectors as $selector) {
            $styles = $this->getDomElementStyles(
                $selector,
                ['background-color', 'opacity', 'padding-top', 'padding-bottom'],
                $this->browserPage,
                $families,
                $defaultFont
            );

            // Собираем padding-top и padding-bottom из всех селекторов
            if (isset($styles['padding-top']) && !isset($resultStyles['padding-top'])) {
                $resultStyles['padding-top'] = $styles['padding-top'];
            }
            if (isset($styles['padding-bottom']) && !isset($resultStyles['padding-bottom'])) {
                $resultStyles['padding-bottom'] = $styles['padding-bottom'];
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

        // #region agent log
        Logger::instance()->debug(
            '[FullTextBlurBox] getOuterColumnStyles result: sectionId=' . $sectionId
            . ', resultBgColor=' . ($resultStyles['background-color'] ?? 'null')
            . ', resultKeys=' . implode(',', array_keys($resultStyles))
        );
        // #endregion

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

                    // Получаем background-color и opacity для внешнего Column
                    // Если не найден цвет из исходного сайта, используем прозрачный фон
                    // чтобы не перекрывать изображение
                    $bgColorHex = '#ffffff';
                    $bgColorOpacity = 0;

                    if (!empty($outerColumnStyles['background-color'])) {
                        $bgColorHex = ColorConverter::rgba2hex($outerColumnStyles['background-color']);
                        $bgColorOpacity = ColorConverter::rgba2opacity($outerColumnStyles['background-color']);

                        // Если есть отдельное значение opacity, учитываем его
                        if (isset($outerColumnStyles['opacity'])) {
                            $opacity = NumberProcessor::convertToNumeric($outerColumnStyles['opacity']);
                            $bgColorOpacity = $bgColorOpacity * $opacity;
                        }
                    }

                    // Получаем padding-top и padding-bottom для внешнего Column из исходного сайта
                    $paddingTop = 0;
                    $paddingBottom = 0;

                    if (isset($outerColumnStyles['padding-top'])) {
                        $paddingTop = (int)str_replace('px', '', $outerColumnStyles['padding-top']);
                    }
                    if (isset($outerColumnStyles['padding-bottom'])) {
                        $paddingBottom = (int)str_replace('px', '', $outerColumnStyles['padding-bottom']);
                    }

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
                        ->set_paddingBottomSuffix('px');

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
        // Устанавливаем паддинги в 0, чтобы они не применялись
        // Устанавливаем только margin и высоту (как в стандартном методе, но без паддингов)
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
            ->set_mobilePadding(0)
            ->set_mobilePaddingSuffix('px')
            ->set_mobilePaddingTop(0)
            ->set_mobilePaddingTopSuffix('px')
            ->set_mobilePaddingRight(0)
            ->set_mobilePaddingRightSuffix('px')
            ->set_mobilePaddingBottom(0)
            ->set_mobilePaddingBottomSuffix('px')
            ->set_mobilePaddingLeft(0)
            ->set_mobilePaddingLeftSuffix('px')
            ->set_mobileMarginType('ungrouped')
            ->set_mobileMargin((int)($sectionStyles['margin-bottom'] ?? 0))
            ->set_mobileMarginSuffix('px')
            ->set_mobileMarginTop((int)($sectionStyles['margin-top'] ?? 0))
            ->set_mobileMarginTopSuffix('px')
            ->set_mobileMarginRight((int)($sectionStyles['margin-right'] ?? 0))
            ->set_mobileMarginRightSuffix('px')
            ->set_mobileMarginBottom((int)($sectionStyles['margin-bottom'] ?? 0))
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
}
