<?php

namespace MBMigration\Builder\Layout\Theme\August\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;
use MBMigration\Builder\Utils\PathSlugExtractor;

class Head extends HeadElement
{

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $currentMigrateSlugPage = $this->themeContext->getSlug();
        $migrateUrl = PathSlugExtractor::getFullUrl($currentMigrateSlugPage);
        $layoutName = $this->themeContext->getLayoutName();
        $browser = $this->themeContext->getBrowser();

        $this->browserPage = $browser->openPage($migrateUrl, $layoutName);
    }

    protected function getNormalSubMenuStyle($families, $defaultFamilies): array
    {
        $data = parent::getNormalSubMenuStyle($families, $defaultFamilies);

        $data['subMenuBgColorOpacity'] = 0.25;
        $data['subMenuBgColorHex'] = "#fff";

        return $data;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

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

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

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

        $brizySection->getItemWithDepth(0)
            ->addCustomCSS('.brz-section__header{height: auto !important;}')
            ->setMobileBgColorStyle(null, 1);

        $brizySection->getItemWithDepth(0, 0, 0, 0)
            ->addHorizontalContentAlign()
            ->addMobileContentAlign()
            ->addMobileMargin();

        $brizySection->getItemWithDepth(0, 0, 1, 0)
            ->addMobileMargin([10,10,10,0]);

        foreach ($sectionlogoOptions as $option => $value) {
            $nameOption = 'set_'.$option;
            $brizySection->getItemWithDepth(0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        return $brizySection;
    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected)>a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemActiveSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.selected a", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li.selected a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li", "className" => "selected"];
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return $this->getThemeSubMenuNotSelectedItemSelector();
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

    protected function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li a", "pseudoEl" => ""];
    }

    public function getMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getMenuHoverItemBgSelector(): array
    {
        return $this->getThemeSubMenuNotSelectedItemSelector();
    }

    public function getNotSelectedMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    protected function getThemeSubMenuItemDropDownSelector(): array
    {
        return ["selector" => "#main-navigation > ul > li.has-sub > ul > li", "pseudoEl" => ""];
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,
            "marginBottom" => -120,
            "bg-color" => [
                "bgColor" => "#ffffff",
                "bgOpacity" => 0.2 //invertion
            ]
        ];
    }
}
