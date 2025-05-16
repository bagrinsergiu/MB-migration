<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;

class Head extends HeadElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getLogoComponent(BrizyComponent $brizySection): BrizyComponent
    {
        $brizySection->getItemWithDepth(0, 0, 0)
            ->getValue()
            ->set_width(30);

        $brizySection->getItemWithDepth(0, 0, 1)
            ->getValue()
            ->set_width(70);

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

    public function getThemeMenuItemActiveSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.selected a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected)>a", "pseudoEl" => ""];
    }
    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub>a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:has(.sub-navigation) .sub-navigation>li>a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li", "className" => "selected"];
    }

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:has(.sub-navigation) .sub-navigation", "pseudoEl" => ""];
    }

    public function getThemeMenuItemPaddingSelector(): array
    {
        return $this->getThemeParentMenuItemSelector();
    }

    public function getThemeMobileNavSelector(): array
    {
        return ["selector" => "#mobile-navigation", "pseudoEl" => ""];
    }

    public function getThemeMobileBtnSelector(): array
    {
        return $this->getThemeMenuItemMobileSelector();
    }

    protected function getPropertiesIconMenuItem(): array
    {
        return [
            'mobileMarginType' => "ungrouped",
            'mobileMargin' => 0,
            'mobileMarginSuffix' => "px",
            'mobileMarginTop' => 10,
            'mobileMarginTopSuffix' => "px",
            'mobileMarginRight' => -21,
            'mobileMarginRightSuffix' => "px",
            'mobileMarginBottom' => 10,
            'mobileMarginBottomSuffix' => "px",
            'mobileMarginLeft' => 0,
            'mobileMarginLeftSuffix' => "px",
        ];
    }

    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li.selected a", "pseudoEl" => ""];
    }

    protected function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li a", "pseudoEl" => ""];
    }

    public function getThemeMenuHeaderStyle($headStyles, $section): BrizyComponent
    {
        if (isset($headStyles['style']['opacity'])) {
            $section->getItemWithDepth(0)
                ->getValue()
                ->set_bgColorOpacity($headStyles['style']['opacity'])
                ->set_mobileBgColorOpacity($headStyles['style']['opacity']);
        }

        if (isset($headStyles['menu']['activeSubMenuColorHex'])) {
            $section->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->set_activeColorHex($headStyles['menu']['activeSubMenuColorHex']);
        }

        $section->getItemWithDepth(0,0,1)
            ->getValue()
            ->set_mobileMarginRight('25')
            ->set_tempMobileMarginRight('25');

        $section->getItemWithDepth(0, 0, 1, 0, 0)
            ->getValue()
            ->set_menuPaddingTop('10')
            ->set_menuPaddingTopSuffix('px')
            ->set_menuPaddingRight('15')
            ->set_menuPaddingRightSuffix('px')
            ->set_menuPaddingBottom('10')
            ->set_menuPaddingBottomSuffix('px')
            ->set_menuPaddingLeft('15')
            ->set_menuPaddingLeftSuffix('px')
            ->set_menuBorderRadius('50')
            ->set_menuBorderRadiusSuffix('px');

        if (isset($headStyles['menu']['mMenuHoverColorHex'])) {
            $section->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->set_hoverMenuBgColorHex($headStyles['menu']['mMenuHoverColorHex']);
        }

        if (isset($headStyles['menu']['mMenuHoverColorOpacity'])) {
            $section->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->set_hoverMenuBgColorOpacity($headStyles['menu']['mMenuHoverColorOpacity']);
        }

        return $section;
    }
}
