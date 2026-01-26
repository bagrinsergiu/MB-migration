<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;
use MBMigration\Core\Logger;

class FullText extends FullTextElement
{
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();
        $itemsKit = $data->getThemeContext()->getBrizyKit();

        $brizySection->getItemWithDepth(0)->addMargin(0, 30, 0, 0, '', '%');

        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        $wrapperLine = new BrizyComponent(json_decode($itemsKit['global']['wrapper--line'], true));

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if($showHeader) {
            $titleMb = $this->getItemByType($mbSectionItem, 'title');

            $menuSectionSelector = '[data-id="' . $titleMb['id'] . '"]';
            $wrapperLineStyles = $this->browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => $menuSectionSelector,
                    'styleProperties' => ['border-bottom-color',],
                    'families' => [],
                    'defaultFamily' => '',
                ]
            );

            $headStyle = [
                'line-color' => ColorConverter::convertColorRgbToHex($wrapperLineStyles['data']['border-bottom-color']),
            ];

            $wrapperLine->getItemWithDepth(0)
                ->getValue()
                ->set_borderWidth(1)
                ->set_borderColorHex($headStyle['line-color']);

            $brizySection->getItemWithDepth(0)
                ->getValue()
                ->add_items([$wrapperLine], 1);
        }

        // Обработка фонового изображения снежинок
        $this->handleSnowflakeBackground($brizySection, $mbSectionItem, $data);

        return $brizySection;
    }

    /**
     * Обработка фонового изображения снежинок
     * Получает background-image из DOM и применяет его через customCSS
     *
     * @param BrizyComponent $brizySection
     * @param array $mbSectionItem
     * @param ElementContextInterface $data
     */
    protected function handleSnowflakeBackground(
        BrizyComponent $brizySection,
        array $mbSectionItem,
        ElementContextInterface $data
    ): void {
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();

        // Получаем стили секции, включая background-image
        $sectionStyles = $this->getSectionListStyle($data, $this->browserPage);

        // Получаем Row компонент
        // В Boulevard структура: Section -> Row
        // getSectionItemComponent возвращает Section, поэтому получаем Row через getItemWithDepth(0)
        $rowComponent = $brizySection->getItemWithDepth(0);
        
        // Если Row не найден, используем sectionItemComponent как fallback
        if ($rowComponent === null || $rowComponent->getType() !== 'Row') {
            $rowComponent = $this->getSectionItemComponent($brizySection);
        }

        // Получаем background-color и opacity из настроек секции
        $bgColorHex = '#f5f5ed';
        $bgColorOpacity = 0.81;

        if (isset($sectionStyles['background-color'])) {
            $bgColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);
            $bgColorOpacity = ColorConverter::rgba2opacity($sectionStyles['background-color']);

            // Учитываем отдельное значение opacity, если есть
            if (isset($sectionStyles['opacity'])) {
                $opacity = NumberProcessor::convertToNumeric($sectionStyles['opacity']);
                $bgColorOpacity = $bgColorOpacity * $opacity;
            }
        } else {
            // Если background-color не найден, пробуем получить из настроек секции
            if (isset($mbSectionItem['settings']['sections']['background']['opacity'])) {
                $bgColorOpacity = NumberProcessor::convertToNumeric(
                    $mbSectionItem['settings']['sections']['background']['opacity']
                );
            }
        }

        // Применяем background-color к Row
        $rowComponent->getValue()
            ->set_bgColorType('solid')
            ->set_bgColorHex($bgColorHex)
            ->set_bgColorOpacity($bgColorOpacity)
            ->set_bgColorPalette('')
            ->set_mobileBgColorType('solid')
            ->set_mobileBgColorHex($bgColorHex)
            ->set_mobileBgColorOpacity($bgColorOpacity)
            ->set_mobileBgColorPalette('');

        // Получаем background-image из DOM
        $backgroundImageUrl = null;
        
        // Сначала пробуем получить из основных стилей секции
        if (!empty($sectionStyles['background-image']) && $sectionStyles['background-image'] !== 'none') {
            $backgroundImageUrl = $this->extractBackgroundImageUrl($sectionStyles['background-image']);
            Logger::instance()->debug('FullText Boulevard: Found background-image in sectionStyles', [
                'url' => $backgroundImageUrl,
                'raw' => $sectionStyles['background-image']
            ]);
        }

        // Если не найдено, пробуем разные селекторы и псевдоэлементы
        if (empty($backgroundImageUrl)) {
            $selectors = [
                ['selector' => '[data-id="' . $sectionId . '"]', 'pseudo' => null],
                ['selector' => '[data-id="' . $sectionId . '"]', 'pseudo' => '::before'],
                ['selector' => '[data-id="' . $sectionId . '"]', 'pseudo' => '::after'],
                ['selector' => '[data-id="' . $sectionId . '"] .content-wrapper', 'pseudo' => null],
                ['selector' => '[data-id="' . $sectionId . '"] .content-wrapper', 'pseudo' => '::before'],
                ['selector' => '[data-id="' . $sectionId . '"] > div', 'pseudo' => null],
                ['selector' => '[data-id="' . $sectionId . '"] > div', 'pseudo' => '::before'],
                ['selector' => '[data-id="' . $sectionId . '"] .row', 'pseudo' => null],
                ['selector' => '[data-id="' . $sectionId . '"] .row', 'pseudo' => '::before'],
                ['selector' => '[data-id="' . $sectionId . '"] .column', 'pseudo' => null],
                ['selector' => '[data-id="' . $sectionId . '"] .column', 'pseudo' => '::before'],
            ];

            foreach ($selectors as $selectorConfig) {
                $selector = $selectorConfig['selector'];
                $pseudo = $selectorConfig['pseudo'];
                
                try {
                    $styles = $this->getDomElementStyles(
                        $selector,
                        ['background-image'],
                        $this->browserPage,
                        $families,
                        $defaultFont,
                        $pseudo
                    );

                    if (!empty($styles['background-image']) && $styles['background-image'] !== 'none') {
                        $extractedUrl = $this->extractBackgroundImageUrl($styles['background-image']);
                        if (!empty($extractedUrl)) {
                            $backgroundImageUrl = $extractedUrl;
                            Logger::instance()->debug('FullText Boulevard: Found background-image', [
                                'selector' => $selector,
                                'pseudo' => $pseudo,
                                'url' => $backgroundImageUrl,
                                'raw' => $styles['background-image']
                            ]);
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    Logger::instance()->debug('FullText Boulevard: Error getting styles', [
                        'selector' => $selector,
                        'pseudo' => $pseudo,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        // Если все еще не найдено, пробуем получить через evaluateScript напрямую
        if (empty($backgroundImageUrl)) {
            try {
                $result = $this->browserPage->evaluateScript(
                    'brizy.getStyles',
                    [
                        'selector' => '[data-id="' . $sectionId . '"]',
                        'styleProperties' => ['background-image'],
                        'families' => $families,
                        'defaultFamily' => $defaultFont,
                    ]
                );

                if (!empty($result['data']['background-image']) && $result['data']['background-image'] !== 'none') {
                    $backgroundImageUrl = $this->extractBackgroundImageUrl($result['data']['background-image']);
                    Logger::instance()->debug('FullText Boulevard: Found background-image via evaluateScript', [
                        'url' => $backgroundImageUrl,
                        'raw' => $result['data']['background-image']
                    ]);
                }
            } catch (\Exception $e) {
                Logger::instance()->debug('FullText Boulevard: Error in evaluateScript', [
                    'error' => $e->getMessage()
                ]);
            }
        }


        // Логируем результат поиска
        if (empty($backgroundImageUrl)) {
            Logger::instance()->warning('FullText Boulevard: Background-image not found for section', [
                'sectionId' => $sectionId
            ]);
        } else {
            Logger::instance()->info('FullText Boulevard: Background-image found', [
                'sectionId' => $sectionId,
                'url' => $backgroundImageUrl
            ]);
        }

        // Формируем customCSS
        // Применяем background-color к самому элементу
        $customCSS = 'element{' . PHP_EOL;
        $customCSS .= '++background-color:+' . $bgColorHex . ';' . PHP_EOL;
        $customCSS .= '}' . PHP_EOL;

        // Если есть background-image, добавляем его в customCSS
        // Применяем к .brz-bg, чтобы изображение было видно поверх текстуры
        if (!empty($backgroundImageUrl)) {
            // Применяем background-image к .brz-bg
            // background-image отображается поверх background-color элемента благодаря порядку слоев
            $customCSS .= 'element+>+.brz-bg:not(:has(.brz-bg-image)){' . PHP_EOL;
            $customCSS .= '++background-image:+url("' . $backgroundImageUrl . '");' . PHP_EOL;
            $customCSS .= '++background-repeat:+repeat;' . PHP_EOL;
            // Не применяем background-color здесь, чтобы не перекрывать изображение
            // background-color уже применен к element выше
            $customCSS .= '}' . PHP_EOL;
            $customCSS .= PHP_EOL;
            
            Logger::instance()->info('FullText Boulevard: Added background-image to customCSS', [
                'sectionId' => $sectionId,
                'url' => $backgroundImageUrl,
                'css' => $customCSS
            ]);
        } else {
            Logger::instance()->warning('FullText Boulevard: No background-image URL to add', [
                'sectionId' => $sectionId
            ]);
        }

        // Применяем customCSS к Row
        $rowComponent->addCustomCSS($customCSS);
    }

    /**
     * Извлекает URL из CSS строки background-image
     *
     * @param string $backgroundImage
     * @return string|null
     */
    protected function extractBackgroundImageUrl(string $backgroundImage): ?string
    {
        if (empty($backgroundImage) || $backgroundImage === 'none') {
            return null;
        }

        // Если это уже URL
        if (filter_var($backgroundImage, FILTER_VALIDATE_URL)) {
            return $backgroundImage;
        }

        // Извлекаем URL из CSS строки вида url("...") или url('...') или url(...)
        if (preg_match('/url\(["\']?(.*?)["\']?\)/', $backgroundImage, $matches)) {
            $url = trim($matches[1], "'\"");
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * Конвертирует hex цвет в RGB строку для использования в rgba()
     *
     * @param string $hex
     * @return string
     */
    protected function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return $r . ',+' . $g . ',+' . $b;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    /**
     * Переопределяем handleSectionTexture для Boulevard FullText
     * Мы обрабатываем background-image в handleSnowflakeBackground,
     * поэтому здесь просто возвращаем false, чтобы не применять стандартную обработку
     */
    protected function handleSectionTexture(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles, $options = ['heightType' => 'custom']): bool
    {
        // Для Boulevard FullText мы обрабатываем background-image в handleSnowflakeBackground
        // Поэтому пропускаем стандартную обработку из родительского класса
        return false;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
