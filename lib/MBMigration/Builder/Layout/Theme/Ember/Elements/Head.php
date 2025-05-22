<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;

class Head extends HeadElement
{

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0)->addMarginBottom(-80);
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

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
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
}
