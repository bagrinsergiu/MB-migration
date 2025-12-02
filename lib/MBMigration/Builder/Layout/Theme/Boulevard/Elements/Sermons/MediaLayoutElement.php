<?php
namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Sermons;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class MediaLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Sermons\MediaLayoutElement
{

    protected function internalTransformToItem(ElementContextInterface $data ): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $brizySection->getItemWithDepth(0)->addMargin(0, 15, 0, 15,  '', '%');
        return $brizySection;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
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
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
