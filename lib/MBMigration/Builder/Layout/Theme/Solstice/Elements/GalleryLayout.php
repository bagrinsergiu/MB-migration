<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

class GalleryLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    /**
     * @throws BrowserScriptException
     * @throws \Exception
     */
    public function transformToItem(ElementDataInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $elementContext = $data->instanceWithBrizyComponent($brizySection);
        $this->handleSectionStyles($elementContext, $this->browserPage);

        //$brizySection->getValue()->set_marginType('ungrouped');

        $slideJson = json_decode($this->brizyKit['slide'], true);

        $brizySectionItems = [];
        foreach ($data->getMbSection()['items'] as $mbItem) {
            $brizySectionItem = new BrizyComponent($slideJson);
            $brizySectionItem->getValue()
                ->set_marginTop(0)
                ->set_marginBottom(0)
                ->set_imageSrc($mbItem['content'])
                ->set_imageFileName($mbItem['imageFileName'])
                ->set_imageExtension($mbItem['settings']['slide']['extension']);

//            $brizySectionItem->getValue(0)
//                    ->set_bgImageFileName($mbItem['imageFileName'])
//                    ->set_bgImageSrc($mbItem['content']);

            $brizySectionItems[] = $brizySectionItem;
        }

        $brizySection->getValue()->set_items($brizySectionItems);

        return $brizySection;
    }

}