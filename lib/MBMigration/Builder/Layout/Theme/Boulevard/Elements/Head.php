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

//        $headStyles = $this->extractBlockBrowserData(
//            $data->getMbSection()['sectionId'],
//            $data->getFontFamilies(),
//            $data->getDefaultFontFamily(),
//            $data
//        );

        // reset color palette
        $sectionItem = $this->getSectionItemComponent($section);

        $logoImageComponent = $this->getLogoComponent($section);
        $menuTargetComponent = $this->getTargetMenuComponent($section);

        // build menu items and set the menu uid
        $this->buildMenuItemsAndSetTheMenuUid($data, $menuTargetComponent, $headStyles ?? []);
        $this->setImageLogo($logoImageComponent, $data->getMbSection());

        $elementContext = $data->instanceWithBrizyComponent($sectionItem);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->getThemeMenuHeaderStyle($headStyles, $section);

        return $section;
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {

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
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li.selected a", "pseudoEl" => ""];
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
        return ["selector" => "#main-navigation>ul>li.selected a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li:not(.selected) > a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li", "className" => "selected"];
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#selected-sub-navigation", "pseudoEl" => ""];
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
