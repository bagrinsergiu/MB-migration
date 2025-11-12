<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Sermons;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class MediaLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Sermons\MediaLayoutElement
{
    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
        $brizySection->getValue()->set('fullHeight', 'auto');
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $items = $brizySection->getItemWithDepth(0)->getValue()->get('items');
        // create two separate sections for text and media
        // extract text part components
        $sectionValueProperties = $brizySection->getItemValueWithDepth(0);

        $mainSection = new BrizyComponent(json_decode($this->brizyKit['GridMediaLayout']['section'], true));

        $aComp = $mainSection->getItemValueWithDepth(0,0);
        foreach ($sectionValueProperties as $key=>$value) {
            if ($key === 'items' || strpos($key, '_') === 0 ||
            strpos($key, 'paddingBottom') !== false  ||
             strpos($key, 'paddingLeft') !== false ||
             strpos($key, 'paddingRight') !== false ||
             strpos($key, 'width') !== false ||
             strpos($key, 'widthSuffix') !== false
             ) {
                continue;
            }
            $str = "set_$key";
            $aComp->$str($value);
        }

        $mainSection->getItemValueWithDepth(0,0,0,0,0)->set('items', array_slice($items, 0, -1));

        $lastKey = array_key_last($items);
        $mdElement = $items[$lastKey];
        $mdElement->getValue()->set('mobilePaddingType', 'grouped');
        $mdElement->getValue()->set('paddingType', 'grouped');
        $mdElement->getValue()->set('padding', 0);
        $mdElement->getValue()->set('mobilePadding', 0);
        $mainSection->getItemValueWithDepth(0,1,0,0,0)->set('items', [$mdElement]);

        return $mainSection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "paddingTop" => 90,
            "paddingBottom" => 90,
            "mobilePaddingTop" => 60,
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
