<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class ListMediaLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        // reset the left column of the brizy block
        $brizySection->getItemValueWithDepth(0, 0, 0)->set_items([]);

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