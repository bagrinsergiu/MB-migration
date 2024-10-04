<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Element\HeadElement;

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

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected)>a", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemSelector(): array
    {#main-navigation > ul:nth-child(1) > li:nth-child(2) > ul > li:nth-child(1) > a
        return ["selector" => "#main-navigation>ul>li.has-sub>ul.sub-navigation>li>a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return $this->getThemeSubMenuItemSelector();
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

    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
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

}
