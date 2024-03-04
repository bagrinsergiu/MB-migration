<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class LeftMediaOverlap extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DanationsAble;


    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        foreach ((array)$mbSection['items'] as $mbSectionItem) {
            switch ($mbSectionItem['category']) {
                case 'photo':
                    // add the photo items on the right side of the block
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbSectionItem,
                        $brizySection->getItemWithDepth(0, 0, 0, 0) //
                    );
                    $this->handleRichTextItem(
                        $elementContext,
                        $this->browserPage
                    );

                    $brizySection->getItemWithDepth(0, 0, 0, 0, 0)->getValue()
                        ->set_width(100)
                        ->set_height(100)
                        ->set_heightSuffix('%')
                        ->set_widthSuffix('%');
                    break;
                case 'text':
                    // add the text on the left side of th bock
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbSectionItem,
                        $brizySection->getItemWithDepth(0, 0, 1)
                    );
                    $this->handleRichTextItem(
                        $elementContext,
                        $this->browserPage
                    );


                    break;
            }
        }

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0, 0, 1));
        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;

    }
}