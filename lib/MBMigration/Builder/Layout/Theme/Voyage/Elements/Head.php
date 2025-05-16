<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

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
        $menuEnt = $data->getThemeContext()->getBrizyMenuEntity();
        $deepSlug = PathSlugExtractor::findDeepestSlug($menuEnt['list']);
        $menuUrl = PathSlugExtractor::getFullUrl($deepSlug['slug']);
        $layoutName = $data->getThemeContext()->getLayoutName();
        $browser = $data->getThemeContext()->getBrowser();

        $this->browserPage = $browser->openPage($menuUrl, $layoutName);
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        // because of a fantastic idea to not have the option the place the icon per menu item

        $currentMigrateSlugPage = $this->themeContext->getSlug();
        $migrateUrl = PathSlugExtractor::getFullUrl($currentMigrateSlugPage);
        $layoutName = $this->themeContext->getLayoutName();
        $browser = $this->themeContext->getBrowser();

        $this->browserPage = $browser->openPage($migrateUrl, $layoutName);

        $menuPadding = [
            "mobileMarginType" => "ungrouped",
            "mobileMargin" => 0,
            "mobileMarginSuffix" => "px",
            "mobileMarginTop" => 10,
            "mobileMarginTopSuffix" => "px",
            "mobileMarginRight" => 12,
            "mobileMarginRightSuffix" => "px",
            "mobileMarginBottom" => 10,
            "mobileMarginBottomSuffix" => "px",
            "mobileMarginLeft" => 0,
            "mobileMarginLeftSuffix" => "px",
        ];

        $brizySection->getItemWithDepth(0, 0, 0, 0)
            ->addHorizontalContentAlign()
            ->addMobileContentAlign()
            ->addMobileMargin([0,0,0,10]);

        $brizySection->getItemWithDepth(0, 0, 1, 0)
            ->addMobileMargin([10,10,10,0]);

        foreach ($menuPadding as $option => $value) {
            $nameOption = 'set_'.$option;
            $brizySection->getItemWithDepth(0, 0, 1, 0 )
                ->getValue()
                ->$nameOption($value);
        }

        $menuComponent = $brizySection->getItemWithDepth(0, 0, 1, 0, 0);
        $menuComponent->getValue()
            ->set_iconPosition('right');
    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemActiveSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.selected a", "pseudoEl" => ""];
    }

    public function getThemeMobileBtnSelector(): array
    {
        return $this->getThemeMenuItemMobileSelector();
    }

    public function getThemeMobileNavSelector(): array
    {
        return ["selector" => "#mobile-navigation", "pseudoEl" => ""];
    }

    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub .sub-navigation li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#selected-sub-navigation ul li", "className" => "selected"];
    }

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub .sub-navigation", "pseudoEl" => ":before"];
    }

    public function getThemeMenuItemPaddingSelector(): array
    {
        return $this->getThemeMenuItemSelector();
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

    protected function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#selected-sub-navigation ul li", "pseudoEl" => ""];
    }

    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li.selected a", "pseudoEl" => ""];
    }
}
