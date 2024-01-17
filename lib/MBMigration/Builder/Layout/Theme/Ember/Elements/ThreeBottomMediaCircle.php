<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Element\FullTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class ThreeBottomMediaCircle extends FullTextElement
{
    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mainBrizySection = parent::transformToItem($data);

        $circleRow = new BrizyComponent(json_decode($this->brizyKit['media-circles'], true));
        $spacer = new BrizyComponent(json_decode($this->brizyKit['spacer'], true));
        $brizySection = $this->getSectionItemComponent($mainBrizySection);
        $mbSection = $data->getMbSection();

        $items = $this->sortItems($mbSection['items']);

        $i = 0;
        foreach ($items as $mbItem) {
            if ($mbItem['category'] == 'photo') {
                $this->handleMediaCircle($mbItem, $circleRow->getItemWithDepth($i++, 0, 0));
            }
        }

        $brizySection->getValue()->add_items([$spacer,$circleRow]);

        return $mainBrizySection;
    }

    private function handleMediaCircle($mbSectionItem, BrizyComponent $brizyComponent)
    {
        $brizyComponent->getValue()
            ->set_imageFileName($mbSectionItem['imageFileName'])
            ->set_imageSrc($mbSectionItem['content']);
    }
}