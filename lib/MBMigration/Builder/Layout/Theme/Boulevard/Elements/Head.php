<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements;

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
        return $brizySection->getItemWithDepth(0, 0, 0, 1, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return BrizyComponent
     */
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $section = $this->getPageLayout();

        $headStyles = $this->extractBlockBrowserData(
            $data->getMbSection()['sectionId'],
            $data->getFontFamilies(),
            $data->getDefaultFontFamily(),
            $data
        );

        $sectionItem = $this->getSectionItemComponent($section);

        $logoImageComponent = $this->getLogoComponent($section);
        $menuTargetComponent = $this->getTargetMenuComponent($section);

        $headStyles['menu']['itemPadding'] = 0;

        $this->buildMenuItemsAndSetTheMenuUid($data, $menuTargetComponent, $headStyles ?? []);
        $this->handleMenuItemStyle($menuTargetComponent);
        $this->setImageLogo($logoImageComponent, $data->getMbSection());

        $elementContext = $data->instanceWithBrizyComponent($sectionItem);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->getThemeMenuHeaderStyle($headStyles ?? [], $section);

        return $section;
    }

    protected function handleMenuItemStyle(BrizyComponent $component)
    {
        $component->getParent()->getValue()
            ->set_mobileMarginTop(-80)
            ->set_mobileMarginBottom(50);
    }

    protected function getNormalSubMenuStyle($families, $defaultFamilies): array
    {
        $this->browserPage->triggerEvent('hover', 'body');

        $themeSubMenuNotSelectedItemSelector = $this->getThemeSubMenuNotSelectedItemSelector();
        $themeSubMenuItemBGSelector = $this->getThemeSubMenuItemBGSelector();
        $getSubMenuItemParams = [
            'itemSelector' => $themeSubMenuNotSelectedItemSelector,
            'itemBgSelector' => $themeSubMenuItemBGSelector,
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
            'hover' => false,
        ];

        $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $getSubMenuItemParams);

        $menuSubItemDropdownStylesOptions = [
            'nodeSelector' => $this->getThemeSubMenuItemDropDownSelector(),
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
        ];

        $menuSubItemDropdownStyles = $this->browserPage->evaluateScript('brizy.getSubMenuDropdown', $menuSubItemDropdownStylesOptions );

        $menuSubItemStyles['data'] = array_merge($menuSubItemStyles['data'], $menuSubItemDropdownStyles['data']);

        if (isset($menuSubItemStyles['error'])) {
            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            //$this->browserPage->getPageScreen('subNormal_1');

            $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $getSubMenuItemParams);

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);
        }

        return $menuSubItemStyles['data'] ?? [];
    }

    protected function getHoverSubMenuStyle(): array
    {
        $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
            'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
            'className' => $this->getThemeSubMenuItemClassSelected()['className'],
        ]);

        sleep(1);

        $activeMenuSubItemStyles = $this->scrapeStyle($this->getThemeSubMenuSelectedItemSelector()['selector'], ['color']);

        $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
            'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
            'className' => $this->getThemeSubMenuItemClassSelected()['className'],
        ]);

        sleep(1);

        if ($this->browserPage->triggerEvent('hover', $this->getThemeSubMenuNotSelectedItemSelector()['selector'])) {

            $this->browserPage->getPageScreen('A1');

            $entrySubMenu = [
                'itemSelector' => $this->getThemeSubMenuNotSelectedItemSelector(),
                'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                'families' => '',
                'defaultFamily' => [],
                'hover' => true,
            ];

            $hoverMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $entrySubMenu);

            $hoverMenuSubItemStyles['data']['activeSubMenuColorHex'] = ColorConverter::rgba2hex($activeMenuSubItemStyles['color']);
            $hoverMenuSubItemStyles['data']['activeSubMenuColorOpacity'] = 1;
        }

        return $hoverMenuSubItemStyles['data'] ?? [];
    }

    protected function menuItemStylesValueConditions(array &$menuItemStyles) :void
    {

        $borderMenuItemStyles = $this->scrapeStyle('#main-navigation > ul > li',['border-bottom-color']);

        if(!empty($menuItemStyles['data'])){
            $menuItemStyles['data']['activeColorHex'] = $menuItemStyles['data']['colorHex'];
            $menuItemStyles['data']['activeMenuBgColor'] = '#fff';
            $menuItemStyles['data']['activeMenuBgColorOpacity'] = 0.08;
            $menuItemStyles['data']['activeMenuBgColorPalette'] = '';

            $menuItemStyles['data']['mobileMMenuBorderStyle'] = $menuItemStyles['data']['menuPaddingType'];
            $menuItemStyles['data']['mobileMMenuBorderColorHex'] = ColorConverter::convertColorRgbToHex($borderMenuItemStyles['border-bottom-color']);
            $menuItemStyles['data']['mobileMMenuBorderColorOpacity'] = ColorConverter::normalizeOpacity($borderMenuItemStyles['border-bottom-opacity'] ?? 1);

            $menuItemStyles['data']['subMenuBorderStyle'] = 'groove';
            $menuItemStyles['data']['subMenuBorderColorHex'] = ColorConverter::convertColorRgbToHex($borderMenuItemStyles['border-bottom-color']);
            $menuItemStyles['data']['subMenuBorderColorOpacity'] = ColorConverter::normalizeOpacity($borderMenuItemStyles['border-bottom-opacity'] ?? 1);
            $menuItemStyles['data']['subMenuBorderColorPalette'] = '';

            $menuItemStyles['data']['menuBorderStyle'] = ('groove');
            $menuItemStyles['data']['menuBorderColorHex'] = ColorConverter::convertColorRgbToHex($borderMenuItemStyles['border-bottom-color']);
            $menuItemStyles['data']['menuBorderColorOpacity'] = ColorConverter::normalizeOpacity($borderMenuItemStyles['border-bottom-opacity'] ?? 1);
        }
    }

    protected function setImageLogo(BrizyComponent $component, $headItem): BrizyComponent
    {
        $component = parent::setImageLogo($component, $headItem);

        $component->getParent()->getValue()->set_horizontalAlign('center');
        $component->getParent()->addMobileHorizontalContentAlign('left');

        return $component;
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {

    }

    public function isBgHoverItemMenu(): bool
    {
        return true;
    }

    protected function makeGlobalBlock(): bool
    {
        return false;
    }

    public function getMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getMenuHoverItemBgSelector(): array
    {
        return $this->getThemeSubMenuItemBGSelector();
    }

    public function getNotSelectedMenuItemBgSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li > a", "pseudoEl" => ""];
    }

    protected function getStyleFromPseudo(): bool
    {
        return true;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li.selected > ul > li.selected > a", "pseudoEl" => ""];
    }

    protected function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li a", "pseudoEl" => ""];
    }

    protected function getThemeSubMenuItemDropDownSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li.has-sub > ul", "pseudoEl" => ""];
    }

    public function getThemeMenuItemActiveSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.selected", "pseudoEl" => ""];
    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li > a", "pseudoEl" => ""];
        //#main-navigation > ul > li.selected.landing.first.has-sub.current > a
        //#main-navigation > ul > li:nth-child(2) > a
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li.selected > ul > li:not(.selected) > a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#main-navigation > ul > li.selected > ul > li", "className" => "selected"];
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li.selected > ul", "pseudoEl" => ""];
    }

    public function getThemeMobileNavSelector(): array
    {
        return ["selector" => "#mobile-navigation", "pseudoEl" => ""];
    }

    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-navigation > nav > ul > li.first.landing > a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemPaddingSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeMobileBtnSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
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
}
