<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\Element\HeadElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
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

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        // because of a fantastic idea to not have the option the place the icon per menu item
        $menuComponent = $brizySection->getItemWithDepth(0, 0, 1, 0, 0);
        $menuComponent->getValue()->set_iconPosition('right');
    }

    public function getThemeMenuItemSelector(): string
    {
        return "#main-navigation>ul>li:not(.selected) a";
    }

    public function getThemeParentMenuItemSelector(): string
    {
        return "#main-navigation>ul>li.has-sub>a";
        //return "#main-navigation>ul>li:has(.sub-navigation):first-child a";
    }

    public function getThemeSubMenuItemSelector(): string
    {
        //return "#main-navigation>ul>li:has(.sub-navigation):first-child .sub-navigation a:first-child";
        return "#main-navigation>ul>li.has-sub .sub-navigation>li>a";
    }
}