<?php

namespace MBMigration\Builder\Layout\Theme\Serene\Elements;

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

    protected function getLogoFloatComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(1, 0, 0, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTargetMenuComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1, 0, 1,0,0);
    }

    protected function getTargetFloatMenuComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(1, 0, 1, 0, 0);
    }

    protected function getStickyTargetMenuComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(1, 0, 1, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return BrizyComponent
     */
    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getSectionFloatItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(1);
    }

    protected function beforeTransformToItem(ElementContextInterface $data): void
    {

    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $MbSection = $data->getMbSection();

        $this->browserPage->triggerEvent('click', '#mobile-nav-button');

        $headStyles = $this->extractBlockBrowserData(
            $data->getMbSection()['sectionId'],
            $data->getFontFamilies(),
            $data->getDefaultFontFamily(),
            $data
        );

        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        // reset color palette
        $sectionItem = $this->getSectionItemComponent($brizySection);
        $sectionFloatItem = $this->getSectionFloatItemComponent($brizySection);

        $logoImageComponent = $this->getLogoComponent($brizySection);
        $logoImageFloatComponent = $this->getLogoFloatComponent($brizySection);

        $menuTargetComponent = $this->getTargetMenuComponent($brizySection);
        $menuTargetFloatComponent = $this->getTargetFloatMenuComponent($brizySection);

        $this->setImageLogo($logoImageComponent, $data->getMbSection());
        $this->setImageLogo($logoImageFloatComponent, $data->getMbSection());
        // build menu items and set the menu uid
        $this->buildMenuItemsAndSetTheMenuUid($data, $menuTargetComponent, $headStyles);
        $this->buildMenuItemsAndSetTheMenuUid($data, $menuTargetFloatComponent, $headStyles);

        $elementContext = $data->instanceWithBrizyComponent($sectionItem);
        $elementContextF = $data->instanceWithBrizyComponent($sectionFloatItem);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);
        $this->handleSectionStyles($elementContextF, $this->browserPage, $additionalOptions);

        $this->getThemeMenuHeaderStyle($headStyles, $brizySection);

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
            'bg-color' => ColorConverter::rgba2hex($menuSectionStyles['data']['background-color']),
            'bg-opacity' => ColorConverter::rgba2opacity($menuSectionStyles['data']['opacity']),
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


        $itemStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '#mobile-navigation li.landing:not(.selected) a span',
                'styleProperties' => ['color', 'opacity'],

                'families' => [],
                'defaultFamily' => '',
            ]
        );
        $bgSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '#mobile-navigation nav',
                'styleProperties' => ['background-color', 'opacity'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $itemCurrentStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '#mobile-navigation .main-navigation  ul:not(.pinned-navigation) .current a span',
                'styleProperties' => ['color', 'opacity'],

                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $itemHoveStyles = ['data' => []];

        if ($this->browserPage->triggerEvent('hover', '#mobile-navigation .main-navigation ul:not(.pinned-navigation) li:not(.selected) a span')) {
            $itemHoveStyles = $this->browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => '#mobile-navigation .main-navigation ul:not(.pinned-navigation) li:not(.selected) a span',
                    'styleProperties' => ['color', 'opacity'],

                    'families' => [],
                    'defaultFamily' => '',
                ]
            );
        }

        $convertColorRgbToHex = ColorConverter::convertColorRgbToHex($itemStyles['data']['color']);
        $convertColorRgbToHexHover = ColorConverter::convertColorRgbToHex($itemHoveStyles['data']['color']);

        $activeItemMenuOptions = [
            'mMenuBgColorHex' => (ColorConverter::convertColorRgbToHex($bgSectionStyles['data']['background-color'])),
            'mobileMMenuBgColorHex' => (ColorConverter::convertColorRgbToHex($bgSectionStyles['data']['background-color'])),
            'tabletMMenuBgColorHex' => (ColorConverter::convertColorRgbToHex($bgSectionStyles['data']['background-color'])),

            'mMenuBgColorOpacity' => ColorConverter::rgba2opacity($bgSectionStyles['data']['background-color']),
            'mobileMMenuBgColorOpacity' => ColorConverter::rgba2opacity($bgSectionStyles['data']['background-color']),
            'tabletMMenuBgColorOpacity' => ColorConverter::rgba2opacity($bgSectionStyles['data']['background-color']),

            'mMenuColorHex' => ($convertColorRgbToHex),
            'mobileMMenuColorHex' => ($convertColorRgbToHex),
            'tabletMMenuColorHex' => ($convertColorRgbToHex),
            'hoverMMenuColorHex' => ($convertColorRgbToHexHover),
            'hoverMMenuColorOpacity' => (1),
            'mMenuColorOpacity' => ($itemHoveStyles['data']['opacity']),
            'mobileMMenuColorOpacity' => ($itemHoveStyles['data']['opacity']),
            'tabletMMenuColorOpacity' => ($itemHoveStyles['data']['opacity']),
            //'activeColorHex' => (ColorConverter::convertColorRgbToHex($itemCurrentStyles['data']['color'])),
            'activeMMenuColorHex' => (ColorConverter::convertColorRgbToHex($itemCurrentStyles['data']['color'])),
            'activeMMenuColorOpacity' => (1),
            'activeColorPalette' => (''),
            'subMenuHoverColorPalette' => (''),


            'activeMenuBorderStyle' => 'none',
            'activeMenuBorderColorHex' => '#000000',
            'activeMenuBorderColorOpacity' => 0.02,
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

        $menuValue = $this->getTargetMenuComponent($brizySection)->getValue();
        foreach ($this->getPropertiesIconMenuItem() as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $menuValue->$nameOption($value);
        }

        foreach ($activeItemMenuOptions as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $menuValue->$nameOption($value);
        }

        $menuValue = $this->getStickyTargetMenuComponent($brizySection)->getValue();
        foreach ($this->getPropertiesIconMenuItem() as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $menuValue->$nameOption($value);
        }

        foreach ($activeItemMenuOptions as $logoOption => $value) {
            $nameOption = 'set_' . $logoOption;
            $menuValue->$nameOption($value);
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
        return ["selector" => "#mobile-navigation > nav > ul > li:not(.selected) > a", "pseudoEl" => ""];

    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#mobile-navigation .main-navigation ul:nth-of-type(2) li", "className" => "selected"];
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#mobile-navigation>nav", "className" => "selected"];
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
        return ["selector" => "#mobile-navigation > nav > ul > li.selected> a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li > a", "pseudoEl" => ""];
    }

    protected function getThemeSubMenuItemDropDownSelector(): array
    {
        return ["selector" => "#mobile-navigation .main-navigation", "pseudoEl" => ""];
        //#main-navigation > ul:nth-child(1) > li.has-sub > ul
    }


    protected function getNormalSubMenuStyle($families, $defaultFamilies): array
    {
        $this->browserPage->triggerEvent('hover', 'body');

        $this->browserPage->triggerEvent('click', '#mobile-nav-button');

        $themeSubMenuNotSelectedItemSelector = $this->getThemeSubMenuNotSelectedItemSelector();
        $themeSubMenuItemBGSelector = $this->getThemeSubMenuItemBGSelector();
        $getSubMenuItemParams = [
            'itemSelector' => $themeSubMenuNotSelectedItemSelector,
            'itemBgSelector' => $themeSubMenuItemBGSelector,
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
            'hover' => false,
        ];

        $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $getSubMenuItemParams);

        $menuSubItemDropdownStylesOptions = [
            'nodeSelector' => $this->getThemeSubMenuItemDropDownSelector(),
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
        ];

        $menuSubItemDropdownStyles = $this->browserPage->evaluateScript('brizy.getSubMenuDropdown', $menuSubItemDropdownStylesOptions );

        $menuSubItemStyles['data'] = array_merge($menuSubItemStyles['data'], $menuSubItemDropdownStyles['data']);

        if (isset($menuSubItemStyles['error'])) {
            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            //$this->browserPage->getPageScreen('subNormal_1');

            $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $getSubMenuItemParams);

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);
        }

        return $menuSubItemStyles['data'] ?? [];

        $data['subMenuBgColorOpacity'] = ColorConverter::rgba2opacity(1);


        $menuSubItemDropdownStyles = $this->browserPage->evaluateScript('brizy.getStyles', [
            'selector' => "#mobile-navigation .main-navigation  li:not(.selected):nth-of-type(1)",
            'styleProperties' => ['font-size'],
            'families' => [],
            'defaultFamily' => '',
        ]);
        if (isset($menuSubItemDropdownStyles['data']['font-size'])) {
            $data['subMenuFontSize'] = $menuSubItemDropdownStyles['data']['font-size'];
            $data['mobileSubMenuFontSize'] = $menuSubItemDropdownStyles['data']['font-size'];
            $data['tabletSubMenuFontSize'] = $menuSubItemDropdownStyles['data']['font-size'];
        }

        return $data;
    }

    protected function getHoverSubMenuStyle(): array
    {
        if ($this->browserPage->triggerEvent('click', '#mobile-nav-button')) {
            $selector1 = $this->getThemeSubMenuNotSelectedItemSelector()['selector'];
            if ($this->browserPage->triggerEvent('hover', $selector1)) {

                $entrySubMenu = [
                    'itemSelector' => $this->getThemeSubMenuNotSelectedItemSelector(),
                    'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                    'families' => '',
                    'defaultFamily' => [],
                    'hover' => true,
                ];

                $hoverMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $entrySubMenu);
            }

            sleep(1);

            $themeSubMenuSelectedItemSelector = $this->getThemeSubMenuSelectedItemSelector();
            $activeMenuSubItemStyles = $this->scrapeStyle($themeSubMenuSelectedItemSelector['selector'], ['color']);

            if (isset($activeMenuSubItemStyles['color'])) {
                $hoverMenuSubItemStyles['data']['activeSubMenuColorHex'] = ColorConverter::rgba2hex($activeMenuSubItemStyles['color']);
                $hoverMenuSubItemStyles['data']['hoverSubMenuColorHex'] = ColorConverter::rgba2hex($activeMenuSubItemStyles['color']);
                $hoverMenuSubItemStyles['data']['activeSubMenuColorOpacity'] = 1;
                $hoverMenuSubItemStyles['data']['hoverSubMenuColorOpacity'] = 1;
            }

            $menuSubItemDropdownStyles = $this->browserPage->evaluateScript('brizy.getStyles', [
                'selector' => '#mobile-navigation .main-navigation',
                'styleProperties' => ['background-color', 'opacity'],
                'families' => [],
                'defaultFamily' => '',
            ]);

            if (isset($menuSubItemDropdownStyles['data']['background-color'])) {
                $hoverMenuSubItemStyles['data']['hoverSubMenuBgColorHex'] = ColorConverter::convertColorRgbToHex($menuSubItemDropdownStyles['data']['background-color']);
                $hoverMenuSubItemStyles['data']['hoverSubMenuBgColorOpacity'] = ColorConverter::rgba2opacity(1);
            }
        }

        return $hoverMenuSubItemStyles['data'] ?? [];
    }

}
