<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;

class GalleryLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    public function transformToItem(ElementDataInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $elementContext = $data->instanceWithBrizyComponent($brizySection);
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $slideJson = json_decode($this->brizyKit['slide'], true);

        $brizySectionItems = [];
        foreach ($data->getMbSection()['items'] as $mbItem) {
            $brizySectionItem = new BrizyComponent($slideJson);
            $brizyComponentValue = $brizySectionItem->getItemValueWithDepth(0,0);
            $brizyComponentValue
                ->set_marginTop(0)
                ->set_marginBottom(0)
                ->set_imageSrc($mbItem['content'])
                ->set_imageFileName($mbItem['imageFileName'])
                ->set_imageExtension($mbItem['settings']['slide']['extension']);

                if(isset($mbItem['settings']['slide']['slide_width'])) {
                    $brizyComponentValue->set_width($mbItem['settings']['slide']['slide_width']);
                    $brizyComponentValue->set_widthSuffix('px');
                }
                if(isset($mbItem['settings']['slide']['slide_height'])) {
                    $brizyComponentValue->set_height($mbItem['settings']['slide']['slide_height']);
                    $brizyComponentValue->set_heightSuffix('px');
                }

            $brizySectionItems[] = $brizySectionItem;
        }

        $brizySection->getValue()->set_items($brizySectionItems);
        return $brizySection;
    }

}