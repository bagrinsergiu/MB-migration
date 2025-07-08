<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;
use MBMigration\Builder\Utils\PathSlugExtractor;

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

    protected function beforeTransformToItem(ElementContextInterface $data): void
    {
        $menuEnt = $data->getThemeContext()->getBrizyMenuEntity();
        $deepSlug = PathSlugExtractor::findDeepestSlug($menuEnt['list']);
        $menuUrl = PathSlugExtractor::getFullUrl($deepSlug['slug']);
        $currentMigrateSlugPage = $data->getThemeContext()->getSlug();
        $migrateUrl = PathSlugExtractor::getFullUrl($currentMigrateSlugPage);
        $layoutName = $data->getThemeContext()->getLayoutName();
        $browser = $data->getThemeContext()->getBrowser();

        $this->browserPage = $browser->openPage($menuUrl, $layoutName);
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $currentMigrateSlugPage = $this->themeContext->getSlug();
        $migrateUrl = PathSlugExtractor::getFullUrl($currentMigrateSlugPage);
        $layoutName = $this->themeContext->getLayoutName();
        $browser = $this->themeContext->getBrowser();

        $this->browserPage = $browser->openPage($migrateUrl, $layoutName);
    }

    protected function getHoverSubMenuStyle(): array
    {
        if ($this->browserPage->triggerEvent('hover', $this->getThemeParentMenuSelectedItemSelector()['selector'])) {
            $this->browserPage->getPageScreen(_selectedParent);
        }

        $hoverMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', [
            'itemSelector'   => $this->getThemeSubMenuSelectedItemSelector(),
            'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
            'families'       => '',
            'defaultFamily'  => [],
            'hover'          => true,
        ]);

        $this->browserPage->getPageScreen('remove_node_1');

        return $hoverMenuSubItemStyles['data'] ?? [];
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
        return ["selector" => "#main-navigation>ul>li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeParentMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li.selected.has-sub > a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub>ul>li:not(.selected)>a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub>ul>li.selected>a", "className" => "selected"];
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

        $section->getItemWithDepth(0,0,1)
            ->getValue()
            ->set_mobileMarginRight('25')
            ->set_tempMobileMarginRight('25');

        $section->getItemWithDepth(0,0,1,0,0)
            ->getValue()
            ->set_itemPadding('10');

        return $section;
    }

    public function getMenuItemBgSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected) a span", "pseudoEl" => ""];
    }

    public function getMenuHoverItemBgSelector(): array
    {
        return $this->getMenuItemBgSelector();
    }

    public function isBgHoverItemMenu(): bool
    {
        return true;
    }

    public function getNotSelectedMenuItemBgSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected)", "pseudoEl" => ""];
    }
}

