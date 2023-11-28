<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;


use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class ListLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use MbSectionUtils;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $photoPosition = $mbSection['settings']['sections']['list']['photo_position'] ?? 'left';

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0, 0, 0));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item-'.$photoPosition], true);

        foreach ($mbSection['items'] as $item) {
            $brizySectionItem = new BrizyComponent($itemJson);

            $elementContext = $data->instanceWithMBSection($item);
            $styles = $this->obtainSectionStyles($elementContext, $this->browserPage);

            $brizySectionItem->getValue()
                ->set_paddingTop((int)$styles['margin-top'])
                ->set_paddingBottom((int)$styles['margin-bottom'])
                ->set_paddingRight((int)$styles['margin-right'])
                ->set_paddingLeft((int)$styles['margin-left']);

            foreach ($item['item'] as $mbItem) {
                $photoPositionIndex = $photoPosition == 'left' ? 0 : 1;
                if ($mbItem['item_type'] == 'title' || $mbItem['item_type'] == 'body') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $brizySectionItem->getItemWithDepth($photoPosition == 'left' ? 1 : 0)
                    );
                }
                if ($mbItem['category'] == 'photo') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $brizySectionItem->getItemWithDepth($photoPositionIndex, 0)
                    );
                }

                $this->handleRichTextItem($elementContext, $this->browserPage);

//                if ($mbItem['category'] == 'photo') {
//                    $brizySectionItem->getItemValueWithDepth($photoPositionIndex, 0, 0)
//                        ->set_widthSuffix('%')
//                        ->set_heightSuffix('%')
//                        ->set_width(100)
//                        ->set_height(80);
//                }
            }

            $brizySection->getItemValueWithDepth(0)->add_items([$brizySectionItem]);
        }

        return $brizySection;
    }
}