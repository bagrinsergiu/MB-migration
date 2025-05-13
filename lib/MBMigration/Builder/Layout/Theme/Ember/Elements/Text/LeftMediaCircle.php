<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;

class LeftMediaCircle extends PhotoTextElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        $brizySection->getItemWithDepth(0, 0, 0)->addVerticalContentAlign();
        return $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    public function targetImageSize(BrizyComponent $imageTarget, int $width, int $height)
    {
        $imageTarget
            ->getValue()
            ->set_width($width)
            ->set_height($height)
            ->set_mobileWidth(286)
            ->set_mobileHeight(286)
            ->set_heightSuffix((strpos($height,'%')===true)?'%':'px')
            ->set_widthSuffix((strpos($width,'%')===true)?'%':'px');
    }

    protected function getMobileTopMarginOfTheFirstElement(): int
    {
        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();

        return (int) $dtoPageStyle['headerHeight'];
    }


    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 95;

    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 20,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 20,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
