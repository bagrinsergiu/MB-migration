<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class GridMediaLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        // reset the left column of the brizy block
        $brizySection->getItemValueWithDepth(0, 0, 0)->set_items([]);
        // reset the right column of the brizy block
        //$brizySection->getItemValueWithDepth(0, 0, 1)->set_items([]);

//        foreach ((array)$mbSection['items'] as $mbSectionItem) {
//            switch ($mbSectionItem['category']) {
//                case 'media':
//                    // add the photo items on the right side of the block
//                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
//                        $mbSectionItem,
//                        $brizySection->getItemWithDepth(0, 0, 0)
//                    );
////                    $this->handleRichTextItem(
////                        $elementContext,
////                        $this->browserPage
////                    );
//                    break;
//                case 'text':
//                    // add the text on the left side of th bock
//                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
//                        $mbSectionItem,
//                        $brizySection->getItemWithDepth(0, 0, 1)
//                    );
//
//                    $this->handleRichTextItem(
//                        $elementContext,
//                        $this->browserPage
//                    );
//                    break;
//            }
//        }

        // add title
        $mbSectionItem = $this->getItemByType($mbSection, 'title');
        $elementContext = $data->instanceWithBrizyComponentAndMBSection(
            $mbSectionItem,
            $brizySection->getItemWithDepth(0, 0, 0)
        );

        $this->handleRichTextItem(
            $elementContext,
            $this->browserPage
        );

        // add body
        $mbSectionItem = $this->getItemByType($mbSection, 'body');
        $elementContext = $data->instanceWithBrizyComponentAndMBSection(
            $mbSectionItem,
            $brizySection->getItemWithDepth(0, 0, 0)
        );

        $this->handleRichTextItem(
            $elementContext,
            $this->browserPage
        );

        // add media


        // sections styles
        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;
    }
}