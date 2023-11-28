<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

class GalleryLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    /**
     * @throws BrowserScriptException
     * @throws \Exception
     */
    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $elementContext = $data->instanceWithBrizyComponent($brizySection);
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $slideJson = json_decode($this->brizyKit['item'], true);

        $arrows = $mbSection['settings']['sections']['gallery']['arrows'] ?? true;
        $markers = $mbSection['settings']['sections']['gallery']['markers'] ?? true;
        $autoplay = $mbSection['settings']['sections']['gallery']['autoplay'] ?? true;

        $brizySection->getValue()->set_sliderDots($markers ? "circle" : "none");
        $brizySection->getValue()->set_sliderArrows($arrows ? "thin" : "none");
        $brizySection->getValue()->set_sliderAutoPlay($autoplay ? "on" : "off");

        $brizySectionItems = [];
        foreach ($data->getMbSection()['items'] as $mbItem) {
            $brizySectionItem = new BrizyComponent($slideJson);
            $brizySectionItem->getValue()->set_marginTop(0);
            $brizySectionItem->getValue()->set_marginBottom(0);
            $brizySectionItem->getValue()->set_bgImageSrc($mbItem['content']);
            $brizySectionItem->getValue()->set_bgImageFileName($mbItem['imageFileName']);
            $brizySectionItem->getValue()->set_imageExtension($mbItem['settings']['slide']['extension']);

//            if (isset($mbItem['settings']['slide']['slide_width'])) {
//                $brizyComponentValue->set_width($mbItem['settings']['slide']['slide_width']);
//                $brizyComponentValue->set_widthSuffix('px');
//            }
//            if (isset($mbItem['settings']['slide']['slide_height'])) {
//                $brizyComponentValue->set_height($mbItem['settings']['slide']['slide_height']);
//                $brizyComponentValue->set_heightSuffix('px');
//            }

            $brizySectionItems[] = $brizySectionItem;
        }

        $brizySection->getValue()->set_items($brizySectionItems);

        return $brizySection;
    }

}