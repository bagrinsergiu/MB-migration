<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Element\HeadElement;
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

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected)>a", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:has(.sub-navigation)", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:has(.sub-navigation) .sub-navigation>li>a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:has(.sub-navigation) .sub-navigation", "pseudoEl" => ""];
    }

    public function getThemeMenuItemPaddingSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        //set the default body bg as there are sections that are transparent

        $menuSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => 'body',
                'styleProperties' => ['background-color', 'background-image'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );
        if (!isset($menuSectionStyles['data'])) {
            return;
        }

        $menuSectionStyles = $menuSectionStyles['data'];
        $backgroundColorHex = ColorConverter::rgba2hex($menuSectionStyles['background-color']);
        $opacity = ColorConverter::rgba2opacity($menuSectionStyles['background-color']);

        $brizySection->getItemWithDepth(0)
            ->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorOpacity($opacity)
            ->set_mobileBgColorType('solid')
            ->set_mobileBgColorHex($backgroundColorHex)
            ->set_mobileBgColorPalette('')
            ->set_mobileBgColorOpacity($opacity);

        $imageLogoOptions = [
            'mobileSize' => 85,
            'mobileSizeSuffix' => '%'
        ];

        $sectionlogoOptions = [
            'mobileMarginType' => 'grouped',
            'mobileMargin' => -20,
            'mobileMarginSuffix' => 'px',
            'mobileMarginTop' => -20,
            'mobileMarginTopSuffix' => 'px',
            'mobileMarginRight' => -20,
            'mobileMarginRightSuffix' => 'px',
            'mobileMarginBottom' => -20,
            'mobileMarginBottomSuffix' => 'px',
            'mobileMarginLeft' => -20,
            'mobileMarginLeftSuffix' => 'px',
        ];


        foreach ($imageLogoOptions as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($sectionlogoOptions as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($this->getPropertiesIconMenuItem() as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 1, 0)
                ->getValue()
                ->$nameOption($value);
        }
    }

    protected function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
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
}
