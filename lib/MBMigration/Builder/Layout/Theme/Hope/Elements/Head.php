<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;
use MBMigration\Builder\Utils\ColorConverter;

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

    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $MbSection = $data->getMbSection();
        $this->browserPage->triggerEvent('click', '#mobile-nav-button');

        $itemStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '#mobile-navigation li.landing:not(.selected) a span',
                'styleProperties' => ['color', 'opacity'],

                'families' => [],
                'defaultFamily' => '',
            ]
        );
        $itemCurrentStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '#mobile-navigation li.selected a span',
                'styleProperties' => ['color', 'opacity'],

                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $itemHoveStyles = ['data' => []];

        if ($this->browserPage->triggerEvent('hover', '#mobile-navigation li.landing:not(.selected) a span')) {
            $itemHoveStyles = $this->browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => '#mobile-navigation li.landing:not(.selected) a span',
                    'styleProperties' => ['color', 'opacity'],

                    'families' => [],
                    'defaultFamily' => '',
                ]
            );
        }


        $bgSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => 'body',
                'styleProperties' => ['background-color', 'opacity'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $menu = $this->getTargetMenuComponent($brizySection);
        $convertColorRgbToHex = ColorConverter::convertColorRgbToHex($itemStyles['data']['color']);
        $convertColorRgbToHexHover = ColorConverter::convertColorRgbToHex($itemHoveStyles['data']['color']);
        $menu->getValue()
            ->set_mMenuBgColorHex(ColorConverter::convertColorRgbToHex($bgSectionStyles['data']['background-color']))
            ->set_mobileMMenuBgColorHex(ColorConverter::convertColorRgbToHex($bgSectionStyles['data']['background-color']))
            ->set_tabletMMenuBgColorHex(ColorConverter::convertColorRgbToHex($bgSectionStyles['data']['background-color']))
            ->set_mMenuBgColorOpacity(ColorConverter::rgba2opacity($bgSectionStyles['data']['background-color']))
            ->set_mobileMMenuBgColorOpacity(ColorConverter::rgba2opacity($bgSectionStyles['data']['background-color']))
            ->set_tabletMMenuBgColorOpacity(ColorConverter::rgba2opacity($bgSectionStyles['data']['background-color']))

            ->set_mMenuColorHex($convertColorRgbToHex['color'])
            ->set_mobileMMenuColorHex($convertColorRgbToHex['color'])
            ->set_tabletMMenuColorHex($convertColorRgbToHex['color'])

            ->set_hoverMMenuColorHex($convertColorRgbToHexHover['color'])
            ->set_hoverMMenuColorOpacity(1)

            ->set_mMenuColorOpacity($convertColorRgbToHex['opacity'])
            ->set_mobileMMenuColorOpacity($convertColorRgbToHex['opacity'])
            ->set_tabletMMenuColorOpacity($convertColorRgbToHex['opacity'])

            ->set_activeColorHex(ColorConverter::convertColorRgbToHex($itemCurrentStyles['data']['color']))
            ->set_activeMMenuColorHex(ColorConverter::convertColorRgbToHex($itemCurrentStyles['data']['color']))
            ->set_activeMMenuColorOpacity(1)
            ->set_activeColorPalette('')
            ->set_subMenuHoverColorPalette('');

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
            'bg-color' => ColorConverter::rgba2hex($bgSectionStyles['data']['background-color']),
            'bg-opacity' => ColorConverter::rgba2opacity($bgSectionStyles['data']['opacity']),
        ];

        $brizySection->getItemWithDepth(0)
            ->getValue()
            ->set_bgColorHex($headStyle['bg-color'])
            ->set_bgColorOpacity($headStyle['bg-opacity'])
            ->set_mobileBgColorType('solid')
            ->set_mobileBgColorHex($headStyle['bg-color'])
            ->set_mobileBgColorPalette('')
            ->set_mobileBgColorOpacity($headStyle['bg-opacity']);

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
            'activeMenuBorderStyle' => 'none',
            'activeMenuBorderColorHex' => $headStyle['bg-color'],
            'activeMenuBorderColorOpacity' => $headStyle['bg-opacity'],
            'activeMenuBorderColorPalette' => '',
            'activeMenuBorderWidthType' => 'ungrouped',
            'activeMenuBorderWidth' => 0,
            'activeMenuBorderTopWidth' => 0,
            'activeMenuBorderRightWidth' => 0,
            'activeMenuBorderBottomWidth' => 0,
            'activeMenuBorderLeftWidth' => 0,
        ];

        $sectionlogoOptions = [
            'horizontalAlign' => 'center',
            'mobileHorizontalAlign' => 'left',

            'mobileMarginType' => 'grouped',
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
            "marginRight" => 20,
            "marginRightSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",
        ];

        foreach ($sectionlogoOptions as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $brizySection->getItemWithDepth(0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($imageLogoOptions as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($mobileIconButtonOptions as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $brizySection->getItemWithDepth(0, 0, 1)
                ->getValue()
                ->$nameOption($value);
        }


        foreach ($this->getPropertiesIconMenuItem() as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $brizySection->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($activeItemMenuOptions as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $brizySection->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        return $brizySection;
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {

    }

    public function getThemeMenuItemActiveSelector(): array
    {
        return ["selector" => "li.selected a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-content #main-navigation>ul>li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container button", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-content #main-navigation  li:not(.selected):nth-of-type(1) > a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#mobile-navigation #main-navigation ul:nth-of-type(2) li", "className" => "selected"];
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#main-content  #main-navigation  ul", "className" => "selected"];
    }

    public function getThemeMobileNavSelector(): array
    {
        return ["selector" => "#mobile-navigation", "pseudoEl" => ""];
    }

    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-navigation  li.first.landing > a", "pseudoEl" => ""];
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

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,

            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 10,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    public function getMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getMenuHoverItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getNotSelectedMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li.selected a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li > a", "pseudoEl" => ""];
    }

    protected function getThemeSubMenuItemDropDownSelector(): array
    {
        return ["selector" => "#mobile-navigation .main-navigation > ul:nth-of-type(2)", "pseudoEl" => ""];
        //#main-navigation > ul:nth-child(1) > li.has-sub > ul
    }
}
