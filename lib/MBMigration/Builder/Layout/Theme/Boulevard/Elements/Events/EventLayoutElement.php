<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Events;

use Exception;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Utils\ColorConverter;

class EventLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Events\EventLayoutElement
{
    use LineAble;
    use ShadowAble;

    /**
     * @var \MBMigration\Builder\Layout\Common\ThemeInterface|null
     */
    private $currentThemeInstance = null;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        // Сохраняем themeInstance для использования в createDetailsCollectionItem
        $this->currentThemeInstance = $data->getThemeInstance();

        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();

        $brizySection->getItemWithDepth(0)->addMargin(0, 30, 0, 0, '', '%');

        $showHeader = $this->canShowHeader($mbSectionItem);

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if($showHeader) {
            $titleMb = $this->getItemByType($mbSectionItem, 'title');
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem,
                $brizySection->getItemWithDepth(0)
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 1, null, '');
        }

        $this->handleShadow($brizySection);

        return $brizySection;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    /**
     * Переопределяем метод для создания полной страницы деталей с head и footer
     */
    protected function createDetailsCollectionItem($collectionTypeUri, $pageData, $slug = 'event-detail', $title = 'Event Detail')
    {
        // Получаем layout для детальной страницы (используем тот же layout, что и для основной страницы)
        $kit = $this->themeContext->getBrizyKit();
        try {
            $layout = json_decode($kit['Theme']['Boulevard']['layout'], true);
            $detailPageLayout = new BrizyComponent($layout);
        } catch (Exception $e) {
            $layout = ['value' => ['items' => []]];
            $detailPageLayout = new BrizyComponent($layout);
        }

        // Применяем стили layout (как в Boulevard::handleLayoutStyle)
        $this->handleDetailPageLayoutStyle($detailPageLayout);

        // Получаем ElementFactory и BrowserPage
        $elementFactory = $this->themeContext->getElementFactory();
        $browserPage = $this->themeContext->getBrowserPage();
        $themeInstance = $this->currentThemeInstance;

        if (!$themeInstance) {
            // Fallback: создаем новый экземпляр темы, если не был сохранен
            $themeName = $this->themeContext->getThemeName();
            $themeClass = "\\MBMigration\\Builder\\Layout\\Theme\\{$themeName}\\{$themeName}";
            if (class_exists($themeClass)) {
                $themeInstance = new $themeClass();
                $themeInstance->setThemeContext($this->themeContext);
            } else {
                throw new \Exception("Cannot create theme instance for {$themeName}");
            }
        }

        // Создаем ElementContext для head
        $headContext = ElementContext::instance(
            $themeInstance,
            $this->themeContext,
            $this->themeContext->getMbHeadSection(),
            $detailPageLayout,
            $detailPageLayout->getItemWithDepth(0, 0, 0),
            $this->themeContext->getBrizyMenuEntity(),
            $this->themeContext->getBrizyMenuItems(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );

        // Добавляем head в layout (как в Boulevard::transformBlocks)
        $headElement = $elementFactory->getElement('head', $browserPage);
        $headElement->transformToItem($headContext);

        // Добавляем детали в layout (в секцию контента, как в Boulevard)
        // $pageData уже является BrizyComponent с деталями
        if ($pageData instanceof BrizyComponent) {
            $pageData->addPadding(0,0,0,0)
                ->addTabletPadding();
            $detailPageLayout->getItemWithDepth(0, 0, 1)->getValue()->add_items([$pageData]);
        }

        // Добавляем footer в layout (как в Boulevard::transformBlocks)
        $footerContext = ElementContext::instance(
            $themeInstance,
            $this->themeContext,
            $this->themeContext->getMbFooterSection(),
            $detailPageLayout,
            $detailPageLayout->getItemWithDepth(0, 0, 0),
            $this->themeContext->getBrizyMenuEntity(),
            $this->themeContext->getBrizyMenuItems(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );
        $footerElement = $elementFactory->getElement('footer', $browserPage);
        $footerElement->transformToItem($footerContext);

        // Передаем весь layout как один компонент (как в Boulevard::transformBlocks)
        return parent::createDetailsCollectionItem($collectionTypeUri, $detailPageLayout, $slug, $title);
    }

    /**
     * Применяет стили layout для детальной страницы (аналогично Boulevard::handleLayoutStyle)
     */
    protected function handleDetailPageLayoutStyle(BrizyComponent $brizyComponent): BrizyComponent
    {
        $brizyComponent->getItemWithDepth(0, 0, 0)
            ->getValue()
            ->set_borderStyle('none')
            ->set_width(15);

        $brizyComponent->getItemWithDepth(0, 0, 1)
            ->getValue()
            ->set_width(85);

        $brizyComponent->getItemWithDepth(0)
            ->addMobilePadding();

        $brizyComponent->getItemWithDepth(0, 0, 1)
            ->addMobileMargin()
            ->addMobilePadding()
            ->addTabletPadding()
            ->addPadding(0,0,0,0);

        return $brizyComponent;
    }

    protected function filterEventLayoutElementStyles($sectionProperties, ElementContextInterface $data): array
    {
        $mbSection = $data->getMbSection();
        $sectionSelector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';
        $calendarCellSelector = $this->resolveCalendarCellSelector($sectionSelector);

        try {
            $sectionStyles = $this->getDomElementStyles(
                $sectionSelector,
                [
                    'background-color',
                    'color',
                    'border-color',
                    'border-bottom-color',
                ],
                $this->browserPage
            );

            $calendarCellStyles = $this->getDomElementStyles(
                $calendarCellSelector,
                [
                    'border-color',
                    'border-style',
                    'border-width',
                    'border-top-width',
                    'border-right-width',
                    'border-bottom-width',
                    'border-left-width',
                    'border-bottom-color',
                    'border-bottom-style',
                ],
                $this->browserPage
            );
        } catch (Exception|ElementNotFound|BrowserScriptException|BadJsonProvided $exception) {
            return $sectionProperties;
        }

        $backgroundColorHex = $this->normalizeDomColorToHex($sectionStyles['background-color'] ?? null);
        $textColorHex = $this->normalizeDomColorToHex($sectionStyles['color'] ?? null);

        $borderSourceStyles = !empty($calendarCellStyles) ? $calendarCellStyles : $sectionStyles;
        $borderColorHex = $this->normalizeDomColorToHex(
            $borderSourceStyles['border-bottom-color'] ?? $borderSourceStyles['border-color'] ?? null
        );

        if ($backgroundColorHex !== null) {
            $sectionProperties['calendarDaysBgColorHex'] = $backgroundColorHex;
        }

        if ($textColorHex === null && $backgroundColorHex !== null) {
            $textColorHex = ColorConverter::getContrastColor($backgroundColorHex);
        }

        if ($textColorHex !== null) {
            $sectionProperties['calendarHeadingColorHex'] = $textColorHex;
            $sectionProperties['calendarDaysColorHex'] = $textColorHex;
        }

        if ($borderColorHex !== null) {
            $sectionProperties['calendarBorderColorHex'] = $borderColorHex;
            $sectionProperties['calendarDaysBorderColorHex'] = $borderColorHex;
        }

        $borderOpacity = $this->extractOpacity($borderSourceStyles['border-bottom-color'] ?? $borderSourceStyles['border-color'] ?? null);
        if ($borderOpacity !== null) {
            $sectionProperties['calendarBorderColorOpacity'] = $borderOpacity;
            $sectionProperties['calendarDaysBorderColorOpacity'] = $borderOpacity;
        }

        $borderStyle = $this->normalizeBorderStyle(
            $borderSourceStyles['border-bottom-style'] ?? $borderSourceStyles['border-style'] ?? null
        );
        if ($borderStyle !== null) {
            $sectionProperties['calendarBorderStyle'] = $borderStyle;
            $sectionProperties['calendarDaysBorderStyle'] = $borderStyle;
        }

        $borderTopWidth = $this->normalizeBorderWidth($borderSourceStyles['border-top-width'] ?? null);
        $borderRightWidth = $this->normalizeBorderWidth($borderSourceStyles['border-right-width'] ?? null);
        $borderBottomWidth = $this->normalizeBorderWidth($borderSourceStyles['border-bottom-width'] ?? null);
        $borderLeftWidth = $this->normalizeBorderWidth($borderSourceStyles['border-left-width'] ?? null);

        if ($borderTopWidth !== null || $borderRightWidth !== null || $borderBottomWidth !== null || $borderLeftWidth !== null) {
            $sectionProperties['calendarDaysBorderWidthType'] = 'grouped';
            $sectionProperties['calendarBorderWidthType'] = 'grouped';

            $top = $borderTopWidth ?? $sectionProperties['calendarDaysBorderTopWidth'] ?? $sectionProperties['calendarBorderWidth'] ?? 1;
            $right = $borderRightWidth ?? $sectionProperties['calendarDaysBorderRightWidth'] ?? $sectionProperties['calendarBorderWidth'] ?? 1;
            $bottom = $borderBottomWidth ?? $sectionProperties['calendarDaysBorderBottomWidth'] ?? $sectionProperties['calendarBorderWidth'] ?? 1;
            $left = $borderLeftWidth ?? $sectionProperties['calendarDaysBorderLeftWidth'] ?? $sectionProperties['calendarBorderWidth'] ?? 1;

            $sectionProperties['calendarDaysBorderTopWidth'] = $top;
            $sectionProperties['calendarDaysBorderRightWidth'] = $right;
            $sectionProperties['calendarDaysBorderBottomWidth'] = $bottom;
            $sectionProperties['calendarDaysBorderLeftWidth'] = $left;

            $groupedWidth = max($top, $right, $bottom, $left);
            $sectionProperties['calendarDaysBorderWidth'] = $groupedWidth;
            $sectionProperties['calendarBorderWidth'] = $groupedWidth;
        } else {
            $singleWidth = $this->normalizeBorderWidth($borderSourceStyles['border-width'] ?? null);
            if ($singleWidth !== null) {
                $sectionProperties['calendarDaysBorderWidthType'] = 'grouped';
                $sectionProperties['calendarBorderWidthType'] = 'grouped';
                $sectionProperties['calendarDaysBorderWidth'] = $singleWidth;
                $sectionProperties['calendarDaysBorderTopWidth'] = $singleWidth;
                $sectionProperties['calendarDaysBorderRightWidth'] = $singleWidth;
                $sectionProperties['calendarDaysBorderBottomWidth'] = $singleWidth;
                $sectionProperties['calendarDaysBorderLeftWidth'] = $singleWidth;
                $sectionProperties['calendarBorderWidth'] = $singleWidth;
            }
        }

        // Boulevard: hover link colors — opacity 0.80
        $hoverLinkOpacityParams = [
            'hoverTitleColorOpacity',
            'hoverListItemTitleColorOpacity',
            'hoverEventsColorOpacity',
        ];
        foreach ($hoverLinkOpacityParams as $param) {
            if (isset($sectionProperties[$param])) {
                $sectionProperties[$param] = 0.80;
            }
        }

        return $sectionProperties;
    }

    private function resolveCalendarCellSelector(string $sectionSelector): string
    {
        $selectors = [
            $sectionSelector . ' td.fc-day.fc-widget-content',
            $sectionSelector . ' td.fc-day',
            $sectionSelector . ' .fc-day-grid td',
            $sectionSelector . ' table td',
        ];

        foreach ($selectors as $selector) {
            if ($this->hasNode($selector, $this->browserPage)) {
                return $selector;
            }
        }

        return $sectionSelector;
    }

    private function normalizeDomColorToHex(?string $color): ?string
    {
        if (empty($color)) {
            return null;
        }

        $hexColor = ColorConverter::rgba2hex($color);

        if (!is_string($hexColor) || !preg_match('/^#([a-fA-F0-9]{6})$/', $hexColor)) {
            return null;
        }

        return $hexColor;
    }

    private function extractOpacity(?string $color): ?float
    {
        if (empty($color)) {
            return null;
        }

        $opacity = (float) ColorConverter::rgba2opacity($color);

        // Transparent/computed fallback colors (alpha=0) should not override
        // the widget defaults, otherwise calendar borders disappear.
        if ($opacity <= 0) {
            return null;
        }

        return $opacity;
    }

    private function normalizeBorderStyle(?string $borderStyle): ?string
    {
        if (empty($borderStyle)) {
            return null;
        }

        $normalized = strtolower(trim($borderStyle));
        $allowedStyles = ['solid', 'dotted', 'dashed', 'double', 'none'];

        if (!in_array($normalized, $allowedStyles, true)) {
            return null;
        }

        return $normalized;
    }

    private function normalizeBorderWidth(?string $borderWidth): ?int
    {
        if (empty($borderWidth)) {
            return null;
        }

        $normalized = str_ireplace('px', '', trim($borderWidth));
        if (!is_numeric($normalized)) {
            return null;
        }

        $value = (int) round((float) $normalized);
        if ($value < 0) {
            return 0;
        }

        return $value;
    }
}
