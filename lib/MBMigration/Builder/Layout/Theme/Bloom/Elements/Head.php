<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\DTO\PageDto;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;

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
        return $brizySection->getItemWithDepth(0, 0, 1, 0, 0)->addVerticalContentAlign();
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
        return ["selector" => "#main-navigation>ul>li.has-sub>a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub > .sub-navigation li > a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#selected-sub-navigation > ul > li", "className" => "selected"];
    }

    public function getThemeMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.has-sub .sub-navigation", "pseudoEl" => ""];
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
                'selector' => '#main-content header',
                'styleProperties' => ['background-color', 'height'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );
        if (!isset($menuSectionStyles['data'])) {
            return;
        }

        $bodySectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => 'body',
                'styleProperties' => ['background-color'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        if (!isset($bodySectionStyles['data'])) {
            return;
        }

        $bodySectionStyles = $bodySectionStyles['data'];
        $menuSectionStyles = $menuSectionStyles['data'];

        $this->pageTDO->getHeadStyle()->setHeight(
            NumberProcessor::convertToInt($menuSectionStyles['height'])
        );

        $backgroundColorHex = ColorConverter::rgba2hex($menuSectionStyles['background-color']);
        $opacity = ColorConverter::rgba2opacity($menuSectionStyles['background-color']);

        $bodyBackgroundColorHex = ColorConverter::rgba2hex($bodySectionStyles['background-color']);
        $bodyBackgroundOpacity = ColorConverter::rgba2opacity($bodySectionStyles['background-color']);

        $brizySection->getItemWithDepth(0)
            ->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorHexPalette('')
            ->set_bgColorOpacity($opacity)
            ->set_mobileBgColorType('solid')
            ->set_mobileBgColorHex($bodyBackgroundColorHex)
            ->set_mobileBgColorPalette('')
            ->set_mobileBgColorOpacity($bodyBackgroundOpacity ?? 1);

        $imageLogoOptions = [
            'mobileSize' => 85,
            'mobileSizeSuffix' => '%'
        ];

        $sectionlogoOptions = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => 10,
            "marginBottomSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",

            'mobileMarginType' => 'ungrouped',
            'mobileMargin' => 0,
            'mobileMarginSuffix' => 'px',
            'mobileMarginTop' => 5,
            'mobileMarginTopSuffix' => 'px',
            'mobileMarginRight' => 0,
            'mobileMarginRightSuffix' => 'px',
            'mobileMarginBottom' => 5,
            'mobileMarginBottomSuffix' => 'px',
            'mobileMarginLeft' => 0,
            'mobileMarginLeftSuffix' => 'px',
        ];

        $sectionSectionOptions = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => -10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => -200,
            "marginBottomSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 5,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 0,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 5,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 0,
            "mobilePaddingLeftSuffix" => "px",
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

        foreach ($this->getPropertiesIconMenuItem() as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 1, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($sectionSectionOptions as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0)
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

    protected function getThemeSubMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub > .sub-navigation li > a", "pseudoEl" => ""];
    }
}
