<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\HeadElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;

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
        return $brizySection->getItemWithDepth(0, 0, 0, 2, 0);
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

        $menuSectionStyles = $this->extractStyle(
            $menuSectionSelector,
            ['background-color', 'opacity', 'background-image']
        );

        $imageSectionSelector = '[data-id="' . $MbSection['sectionId'] . '"] .branding a';

        $brandingSectionStyles = $this->extractStyle(
            $imageSectionSelector,
            ['width', 'height']
        );

        $mainNavigationBorderSelector = '[data-id="' . $MbSection['sectionId'] . '"] .main-navigation ul';

        $mainNavigationBorderStyles = $this->extractStyle(
            $mainNavigationBorderSelector,
            [
                'align-items',
                'border-bottom-style',
                'border-bottom-color',
                'border-bottom-width'
            ]
        );

        $headStyle = [
            'image-width' => ColorConverter::convertColorRgbToHex($brandingSectionStyles['width']),
            'image-height' => ColorConverter::convertColorRgbToHex($brandingSectionStyles['height']),
            'bg-color'=> ColorConverter::rgba2hex($menuSectionStyles['background-color']),
            'bg-opacity' => ColorConverter::rgba2opacity($menuSectionStyles['opacity']),
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
            'sizeType' => 'original',

            'imageWidth' => $headStyle['image-width'],
            'imageHeight' => $headStyle['image-height'],

            'height' => 100,
            'width' => 300,
            'widthSuffix' => 'px',
            'heightSuffix' => '%',

            'size' => 40,
            'sizeSuffix' => '%',

            'mobileSize' => 52,
            'mobileSizeSuffix' => '%',
            'mobileWidthSuffix' => '%',
            'mobileHeightSuffix' => '%',
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
            'mobileMarginLeft' => 0,
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
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",
        ];

        $mainNavigationBorderOptions = [
            'borderStyle' => $mainNavigationBorderStyles['border-bottom-style'],
            'borderColorHex' => ColorConverter::convertColorRgbToHex($mainNavigationBorderStyles['border-bottom-color']),
            'borderColorOpacity' => 1,
            'borderColorPalette' => '',
            'borderWidthType' => 'grouped',
            'borderWidth' => (int) $mainNavigationBorderStyles['border-bottom-width']
        ];

        foreach ($mainNavigationBorderOptions as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 1, 0)
                ->getValue()
                ->$nameOption($value);

            $brizySection->getItemWithDepth(0, 0, 0, 3, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($sectionlogoOptions as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($imageLogoOptions as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 0, 0)
                ->getValue()
                ->$nameOption($value);
        }

        foreach ($mobileIconButtonOptions as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 1)
                ->getValue()
                ->$nameOption($value);
        }


        foreach ($this->getPropertiesIconMenuItem() as $logoOption => $value) {
            $nameOption = 'set_'.$logoOption;
            $brizySection->getItemWithDepth(0, 0, 0, 1, 0)
                ->getValue()
                ->$nameOption($value);
        }

        return $brizySection;
    }

    private function extractStyle($selector, array $styleProperties)
    {
        try {
            $styles = $this->browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => $selector,
                    'styleProperties' => $styleProperties,
                    'families' => [],
                    'defaultFamily' => '',
                ]
            );

            if(!empty( $styles['data'])){

                return $styles['data'];
            } else {
                Logger::instance()->info('Error extract style: ' . json_encode($styles));

                return [];
            }
        } catch (\Exception $e) {
            Logger::instance()->info('Error extract style: ' . $e->getMessage());
            return [];
        }
    }

    public function isBgHoverItemMenu(): bool
    {
        return true;
    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {

    }

    public function getThemeMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeMenuItemActiveSelector(): array
    {
        return ["selector" => "#main-navigation>ul>li.selected a", "pseudoEl" => ""];
    }

    public function getThemeParentMenuItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li", "pseudoEl" => ""];
    }

    public function getThemeSubMenuNotSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li:not(.selected) a", "pseudoEl" => ""];
    }

    public function getThemeSubMenuItemClassSelected(): array
    {
        return ["selector" => "#main-navigation > ul > li.selected.has-sub > ul > li.selected > a", "className" => "selected"];
    }

    public function getThemeSubMenuItemBGSelector(): array
    {
        return $this->getThemeSubMenuNotSelectedItemSelector();
    }

    public function getThemeMobileNavSelector(): array
    {
        return ["selector" => "#mobile-navigation", "pseudoEl" => ""];
    }

    public function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
    }

    public function getThemeMenuItemPaddingSelector(): array
    {
        return ["selector" => '#main-navigation > ul > li.landing.row-0.first > a > span', "pseudoEl" => ""];
    }

    public function getThemeMobileBtnSelector(): array
    {
        return $this->getThemeMenuItemMobileSelector();
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
        ];
    }

    public function getThemeSubMenuSelectedItemSelector(): array
    {
        return ["selector" => "#main-navigation ul li.has-sub ul.sub-navigation li.selected a", "pseudoEl" => ""];
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
        return $this->getThemeMenuItemSelector();
    }

    public function getNotSelectedMenuItemBgSelector(): array
    {
        return $this->getThemeMenuItemSelector();
    }
}
