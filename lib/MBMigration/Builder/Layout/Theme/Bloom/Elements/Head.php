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
            ->set_bgColorOpacity($opacity);
    }

    protected function getThemeMenuItemMobileSelector(): array
    {
        return ["selector" => "#mobile-nav-button-container", "pseudoEl" => ""];
    }
}
