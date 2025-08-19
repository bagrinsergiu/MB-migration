<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;
use MBMigration\Builder\Utils\ColorConverter;

class LeftMedia extends PhotoTextElement
{
    use LineAble;
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        $brizySection->getItemWithDepth(0,0,0,0)->addMobileHorizontalContentAlign();
        return $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
    }

    public function targetImageSize(BrizyComponent $imageTarget, int $width, int $height){
        $imageTarget
            ->getValue()
            ->set_width($width)
            ->set_height($height)
            ->set_mobileSize(100)
            ->set_mobileSizeSuffix('%')
            ->set_heightSuffix((strpos($height,'%')===true)?'%':'px')
            ->set_widthSuffix((strpos($width,'%')===true)?'%':'px');
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

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();
        $show = $this->canShowHeader($mbSectionItem);
        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if($show) {
            $titleMb = $this->getItemByType($mbSectionItem, 'body');
            $image = $brizySection->getItemWithDepth(0,0,1);
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $titleMb,
                $image
            );

            $this->handleLineMediaSection($elementContext, $this->browserPage, $titleMb['id'], null, [], 1);
        }

        return $brizySection;
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
        ];
    }
}
