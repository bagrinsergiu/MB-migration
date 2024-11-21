<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\PathSlugExtractor;

class Head extends HeadElement
{
    protected array $headParams = [
        'addMenuItems' => false
    ];
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

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
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

    protected function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li > a", "pseudoEl" => ""];
    }
}
