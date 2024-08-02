<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

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

    /**
     * @param BrizyComponent $brizySection
     * @return BrizyComponent
     */
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        // because of a fantastic idea to not have the option the place the icon per menu item
        $menuComponent = $brizySection->getItemWithDepth(0, 0, 1, 0, 0);
        $menuComponent->getValue()->set_iconPosition('right');
    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub>a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub .sub-navigation>li>a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub .sub-navigation", "pseudoEl" => ":before"];
    }

    public function getThemeMenuItemPaddingSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }
}
