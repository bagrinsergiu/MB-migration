<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\PathSlugExtractor;

class Head extends HeadElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getLogoComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTargetMenuComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return BrizyComponent
     */
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function beforeTransformToItem(ElementContextInterface $data): void
    {
        // Устанавливаем параметры headParams перед трансформацией
        $this->headParams = [
            'addMenuItems' => false
        ];
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $MbSection = $data->getMbSection();

        $menuSectionSelector = '[data-id="' . $MbSection['sectionId'] . '"]';
        $menuSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $menuSectionSelector,
                'styleProperties' => ['background-color', 'opacity', 'background-image'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $imageSectionSelector = '[data-id="' . $MbSection['sectionId'] . '"] .branding .photo-container img';
        $brandingSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $imageSectionSelector,
                'styleProperties' => ['width', 'height'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $headStyle = [
            'image-width' => ColorConverter::convertColorRgbToHex($brandingSectionStyles['data']['width']),
            'image-height' => ColorConverter::convertColorRgbToHex($brandingSectionStyles['data']['height']),
            'bg-color'=> ColorConverter::rgba2hex($menuSectionStyles['data']['background-color']),
            'bg-opacity' => ColorConverter::rgba2opacity($menuSectionStyles['data']['opacity']),
        ];

        $imageLogoOptions = [
            'sizeType' => 'custom',

            'imageWidth' => $headStyle['image-width'],
            'imageHeight' => $headStyle['image-height'],

            'height' => 100,
            'width' => 300,
            'widthSuffix' => 'px',
            'heightSuffix' => '%',

            'mobileSize' => 52,
            'mobileSizeSuffix' => '%',
            'mobileWidthSuffix' => '%',
            'mobileHeightSuffix' => '%',
        ];

        $activeItemMenuOptions = [
            'activeMenuBorderStyle' => 'solid',
            'activeMenuBorderColorHex' => '#000000',
            'activeMenuBorderColorOpacity' => 0.02,
            'activeMenuBorderColorPalette' => '',
            'activeMenuBorderWidthType' => 'ungrouped',
            'activeMenuBorderWidth' => 3,
            'activeMenuBorderTopWidth' => 0,
            'activeMenuBorderRightWidth' => 0,
            'activeMenuBorderBottomWidth' => 3,
            'activeMenuBorderLeftWidth' => 0,
        ];

        $sectionlogoOptions = [
            'horizontalAlign' => 'center',
            'mobileHorizontalAlign' => 'left',

            'mobileMarginType' => 'ungrouped',
            'mobileMargin' => 0,
            'mobileMarginSuffix' => 'px',
            'mobileMarginTop' => 0,
            'mobileMarginTopSuffix' => 'px',
            'mobileMarginRight' => 0,
            'mobileMarginRightSuffix' => 'px',
            'mobileMarginBottom' => 0,
            'mobileMarginBottomSuffix' => 'px',
            'mobileMarginLeft' => 10,
            'mobileMarginLeftSuffix' => 'px',
        ];

        $mobileIconButtonOptions = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginBottom" => 10,
            "marginBottomSuffix" => "px",
            "marginRight" => 15,
            "marginRightSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",

            "mobileMarginType" => "ungrouped",
            "mobileMarginTopSuffix" => "px",
            "mobileMarginTop" => 0,
            "mobileMarginSuffix" => "px",
            "mobileMarginRight" => 15,
            "mobileMarginRightSuffix" => "px",
            "mobileMarginLeft" => 0,
            "mobileMarginLeftSuffix" => "px",
            "mobileMarginBottomSuffix" => "px",
            "mobileMarginBottom" => 0,

        ];

        $sectionHeaderOptions = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 0,
            "marginTopSuffix" => "px",
            "marginBottom" => 0,
            "marginBottomSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",
        ];

        foreach ($sectionHeaderOptions as $optionName => $value) {
            $nameOption = 'set_'.$optionName;
            $brizySection->getItemWithDepth(0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($sectionlogoOptions as $optionName => $value) {
            $nameOption = 'set_'.$optionName;
            $brizySection->getItemWithDepth(0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($imageLogoOptions as $optionName => $value) {
            $nameOption = 'set_'.$optionName;
            $brizySection->getItemWithDepth(0, 0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($mobileIconButtonOptions as $optionName => $value) {
            $nameOption = 'set_'.$optionName;
            $brizySection->getItemWithDepth(0, 0, 1, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($this->getPropertiesIconMenuItem() as $optionName => $value) {
            $nameOption = 'set_'.$optionName;
            $brizySection->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($activeItemMenuOptions as $optionName => $value) {
            $nameOption = 'set_'.$optionName;
            $brizySection->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        $currentMigrateSlugPage = $data->getThemeContext()->getSlug();
        $migrateUrl = PathSlugExtractor::getFullUrl($currentMigrateSlugPage);
        $layoutName = $data->getThemeContext()->getLayoutName();
        $browser = $data->getThemeContext()->getBrowser();

        $this->browserPage = $browser->openPage($migrateUrl, $layoutName);

        return $brizySection;
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {

    }

    /**
     * Селектор для активного (выбранного) элемента основного меню
     * Исходный проект: c3forchrist.org
     * Структура: #main-navigation > ul > li.selected > a
     */
    public function getThemeMenuItemActiveSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li.selected > a", "pseudoEl" => ""];
    }

    /**
     * Селектор для невыбранных элементов основного меню
     * Исходный проект: c3forchrist.org
     * Структура: #main-navigation > ul > li:not(.selected) > a
     * Используется для извлечения цвета обычного состояния меню
     */
    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li:not(.selected) > a", "pseudoEl" => ""];
    }

    /**
     * Селектор для родительского элемента меню (используется для открытия подменю)
     * Исходный проект: c3forchrist.org
     * Структура: #main-navigation (контейнер основного меню)
     */
    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation", "pseudoEl" => ""];
    }

    /**
     * Селектор для невыбранных элементов подменю
     * Исходный проект: c3forchrist.org
     * Структура: #selected-sub-navigation > ul > li:not(.selected) > a
     */
    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li:not(.selected) > a", "pseudoEl" => ""];
    }

    /**
     * Селектор для добавления класса selected к элементам подменю
     * Исходный проект: c3forchrist.org
     * Структура: #selected-sub-navigation > ul > li
     */
    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li", "className" => "selected"];
    }

    /**
     * Селектор для фона подменю
     * Исходный проект: c3forchrist.org
     * Структура: #selected-sub-navigation
     */
    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#selected-sub-navigation", "pseudoEl" => ""];
    }

    /**
     * Селектор для мобильного меню
     * Исходный проект: c3forchrist.org
     * Структура: #mobile-navigation
     */
    public function getThemeMobileNavSelector(): array
    {
        return ["selector" => "#mobile-navigation", "pseudoEl" => ""];
    }

    /**
     * Селектор для элементов мобильного меню (первый элемент с классами first и landing)
     * Исходный проект: c3forchrist.org
     * Структура: #mobile-navigation > nav > ul > li.first.landing > a
     */
    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-navigation > nav > ul > li.first.landing > a", "pseudoEl" => ""];
    }

    /**
     * Селектор для padding элементов меню
     * Исходный проект: c3forchrist.org
     * Использует тот же селектор, что и обычные элементы меню
     */
    public function getThemeMenuItemPaddingSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    /**
     * Селектор для кнопки мобильного меню
     * Исходный проект: c3forchrist.org
     * Структура: #mobile-nav-button-container
     */
    public function getThemeMobileBtnSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
    }

    public function getPropertiesMainSection(): array
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
            ];
    }

    protected function getPropertiesIconMenuItem(): array
    {
        return [
            "itemPadding" => 30,
            "itemPaddingSuffix" => "px",

            "mobileMMenuSize" => 32,
            "mobileMMenuSizeSuffix" => "px",
            "tabletMMenuSize" => 32,
            "tabletMMenuSizeSuffix" => "px",

            "tabletHorizontalAlign" => "right",

            "tabletMarginType" => "ungrouped",
            "tabletMarginSuffix" => "px",
            "tabletMarginRight" => -10,
            "tabletMarginRightSuffix" => "px",
            "tabletMarginLeft" => 299,
            "tabletMarginLeftSuffix" => "px",

            "tabletPaddingType" => "ungrouped",
            "tabletPadding" => 0,
            "tabletPaddingSuffix" => "px",
            "tabletPaddingTop" => 0,
            "tabletPaddingTopSuffix" => "px",
            "tabletPaddingRight" => 50,
            "tabletPaddingRightSuffix" => "px",
            "tabletPaddingBottom" => 0,
            "tabletPaddingBottomSuffix" => "px",
            "tabletPaddingLeft" => 0,
            "tabletPaddingLeftSuffix" => "px",

            "mobileHorizontalAlign" => "right",

            "mobileMarginType" => "ungrouped",
            "mobileMarginSuffix" => "px",
            "mobileMarginRight" => 0,
            "mobileMarginRightSuffix" => "px",
            "mobileMarginLeft" => 199,
            "mobileMarginLeftSuffix" => "px",

            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 0,
            "mobilePaddingLeftSuffix" => "px",

            "activeMenuBorderStyle" => "solid",
            "activeMenuBorderColorHex" => "#0c0b0b",
            "activeMenuBorderColorOpacity" => 0.3,
            "activeMenuBorderColorPalette" => "",
            "activeMenuBorderWidthType" => "ungrouped",
            "activeMenuBorderWidth" => 2,
            "activeMenuBorderTopWidth" => 0,
            "activeMenuBorderRightWidth" => 0,
            "activeMenuBorderBottomWidth" => 2,
            "activeMenuBorderLeftWidth" => 0,
        ];
    }

    /**
     * Селектор для всех элементов подменю
     * Исходный проект: c3forchrist.org
     * Структура: #selected-sub-navigation > ul > li > a
     */
    protected function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li > a", "pseudoEl" => ""];
    }

    /**
     * Селектор для фона элементов меню
     * Исходный проект: c3forchrist.org
     * Использует тот же селектор, что и обычные элементы меню
     */
    public function getMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    /**
     * Селектор для фона элементов меню при наведении
     * Исходный проект: c3forchrist.org
     * Использует селектор фона подменю
     */
    public function getMenuHoverItemBgSelector(): array
    {
        return $this->getThemeSubMenuItemBGSelector();
    }

    /**
     * Селектор для фона невыбранных элементов меню
     * Исходный проект: c3forchrist.org
     * Использует тот же селектор, что и обычные элементы меню
     */
    public function getNotSelectedMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    /**
     * Селектор для выбранных элементов подменю в мобильном меню
     * Исходный проект: c3forchrist.org
     * Структура: #mobile-navigation > nav > ul > li.selected > a
     */
    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#mobile-navigation > nav > ul > li.selected > a", "pseudoEl" => ""];
    }

    /**
     * Селектор для выпадающего подменю
     * Исходный проект: c3forchrist.org
     * Структура: #mobile-navigation .main-navigation (для мобильного меню)
     * Альтернатива: #main-navigation > ul > li.has-sub > ul (для десктопного меню)
     */
    protected function getThemeSubMenuItemDropDownSelector(): array
    {
        return ["selector" => "#mobile-navigation .main-navigation", "pseudoEl" => ""];
    }

    /**
     * Переопределяем метод для получения обычных стилей меню
     * Для темы Aurora нужно открыть мобильное меню и использовать его для извлечения цветов
     * Исходный проект: c3forchrist.org
     */
    protected function getNormalStyleMenuItems(array $menuItemSelector, array $itemMobileSelector, $families, $defaultFamilies): array
    {
        // Открываем мобильное меню перед извлечением стилей
        $mobileBtnSelector = $this->getThemeMobileBtnSelector();
        if ($mobileBtnSelector && isset($mobileBtnSelector['selector'])) {
            $this->browserPage->triggerEvent('click', $mobileBtnSelector['selector']);
            sleep(1); // Даем время для открытия меню
        }

        // Используем мобильное меню для извлечения цветов вместо десктопного
        // Селектор для обычных пунктов мобильного меню
        $mobileMenuItemSelector = [
            "selector" => "#mobile-navigation > nav > ul > li:not(.selected) > a",
            "pseudoEl" => ""
        ];
        
        // Селектор для активного пункта мобильного меню
        $mobileActiveSelector = [
            "selector" => "#mobile-navigation > nav > ul > li.selected > a",
            "pseudoEl" => ""
        ];

        return $this->browserPage->evaluateScript('brizy.getMenuItem', [
            'itemSelector' => $mobileMenuItemSelector, // Используем мобильное меню вместо десктопного
            'itemActiveSelector' => $mobileActiveSelector, // Активный элемент из мобильного меню
            'itemBgSelector' => $this->getMenuItemBgSelector(),
            'itemPaddingSelector' => $this->getThemeMenuItemPaddingSelector(),
            'itemMobileSelector' => $itemMobileSelector,
            'itemMobileBtnSelector' => $this->getThemeMobileBtnSelector(),
            'itemMobileNavSelector' => $this->getThemeMobileNavSelector(),
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
            'isBgHoverItemMenu' => $this->isBgHoverItemMenu(),
            'hover' => false,
        ]);
    }

    /**
     * Переопределяем метод для получения hover стилей меню
     * Для темы Aurora нужно сначала сделать click на элемент меню, а потом hover
     * Исходный проект: c3forchrist.org
     *
     * Логика: сначала кликаем на элемент меню для активации состояния,
     * затем наводим на него для получения hover стилей
     */
    protected function getHoverStyleMenuItems(array $menuItemSelector, $families, $defaultFamilies): array
    {
        $hoverMenuItemStyles = [];

        // Селектор для элемента меню, на который будем кликать и наводить
        $menuItemBgSelector = $this->getNotSelectedMenuItemBgSelector();
        $clickSelector = $menuItemBgSelector['selector'];

        // Сначала делаем click на элемент меню для активации состояния
        if ($this->browserPage->triggerEvent('click', $clickSelector)) {
            // Небольшая задержка для применения состояния после click
            sleep(1);

            // После click делаем hover на тот же элемент
            if ($this->browserPage->triggerEvent('hover', $clickSelector)) {
                // Даем время для применения hover стилей
                sleep(1);

                $options = [
                    'itemSelector' => $menuItemSelector,
                    'itemBgSelector' => $this->getMenuHoverItemBgSelector(),
                    'itemPaddingSelector' => $this->getThemeMenuItemPaddingSelector(),
                    'families' => $families,
                    'defaultFamily' => $defaultFamilies,
                    'hover' => true,
                    'isBgHoverItemMenu' => $this->isBgHoverItemMenu()
                ];

                $hoverMenuItemStyles = $this->browserPage->evaluateScript('brizy.getMenuItem', $options);
            }
        }

        return $hoverMenuItemStyles;
    }

    /**
     * Корректировка стилей меню для темы Aurora
     * Исходный проект: c3forchrist.org
     * 
     * Метод вызывается после извлечения стилей из исходного сайта.
     * Здесь можно добавить логику для корректировки стилей, если необходимо,
     * но НЕ хардкодить цвета - все цвета должны извлекаться из исходного сайта.
     */
    protected function menuItemStylesValueConditions(array &$menuItemStyles): void
    {
        // Не хардкодим цвета - все цвета извлекаются из исходного сайта
        // Если нужна какая-то корректировка, она должна быть основана на данных из исходного сайта
    }

}
