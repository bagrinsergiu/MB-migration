<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;

class SideMedia extends PhotoTextElement
{
    private $imagePosition = 'left';

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        if ($this->imagePosition == 'right') {
            return $brizySection->getItemWithDepth(0, 0, 1, 0, 0);
        }
        return $brizySection->getItemWithDepth(0, 0, 0, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        if ($this->imagePosition == 'right') {
            return $brizySection->getItemWithDepth(0, 0, 0);
        }
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();

        $text = new BrizyComponent(json_decode($this->brizyKit['textCol'], true));
        $image = new BrizyComponent(json_decode($this->brizyKit['imageCol'], true));
        $main = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $main->getItemWithDepth(0, 0)->getValue()->set_items([$image, $text]);

        if (isset($mbSection['settings']['sections']['text']['photo_position'])) {
            $this->imagePosition = 'right';
            $main->getItemWithDepth(0, 0)->getValue()->set_items([$text, $image]);
        }

        $this->brizyKit['main'] = json_encode($main);

        $brizySection = parent::internalTransformToItem($data);

        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
