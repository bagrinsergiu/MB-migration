<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;

class GridLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use MbSectionUtils;

    public function transformToItem(ElementDataInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0, 0, 0));
        $this->handleRichTextHead($elementContext, $this->browserPage);


        $rowJson = json_decode($this->brizyKit['row'], true);
        $itemJson = json_decode($this->brizyKit['item'], true);


        $itemsChunks = array_chunk($mbSection['items'], 3);
        foreach ($itemsChunks as $row) {
            $brizySectionRow = new BrizyComponent($rowJson);
            foreach ($row as $item) {
                $brizySectionItem = new BrizyComponent($itemJson);

                $elementContext = $data->instanceWithMBSection($item);
                $styles = $this->obtainSectionStyles($elementContext, $this->browserPage);

                $brizySectionItem->getValue()
                    ->set_paddingTop((int)$styles['margin-top'])
                    ->set_paddingBottom((int)$styles['margin-bottom'])
                    ->set_paddingRight((int)$styles['margin-right'])
                    ->set_paddingLeft((int)$styles['margin-left']);

                foreach ($item['item'] as $mbItem) {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $brizySectionItem->getItemWithDepth(0)
                    );
                    $this->handleRichTextItem($elementContext, $this->browserPage);

                    switch ($mbItem['category']) {
                        case 'photo':
                            $brizySectionItem->getItemValueWithDepth(0,0)
                                ->set_widthSuffix('%')
                                ->set_heightSuffix('%')
                                ->set_width(100)
                                ->set_height(100);
                            break;
                    }
                }

                $brizySectionRow->getValue()->add_items([$brizySectionItem]);
            }


            $brizySection->getItemValueWithDepth(0)->add_items([$brizySectionRow]);
        }


        return $brizySection;
    }
}