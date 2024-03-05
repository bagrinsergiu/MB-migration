<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class GridLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $rowJson = json_decode($this->brizyKit['row'], true);
        $itemJson = json_decode($this->brizyKit['item'], true);


        $accordionItems = $this->getItemsByCategory($mbSection,'list');
        $accordionItems = $this->sortItems($accordionItems);
        $itemsChunks = array_chunk($accordionItems, $this->getItemsPerRow());
        foreach ($itemsChunks as $row) {
            $brizySectionRow = new BrizyComponent($rowJson);
            $itemCount = count($row);
            $itemWidth = (int)(100/$itemCount);
            $rowWidth = (int)( (100/$this->getItemsPerRow()) * $itemCount );
            $brizySectionRow->getValue()->set_size($rowWidth);

            foreach ($row as $item) {
                $brizySectionItem = new BrizyComponent($itemJson);

                $elementContext = $data->instanceWithMBSection($item);
                $styles = $this->obtainSectionStyles($elementContext, $this->browserPage);

                $brizySectionItem->getValue()
                    ->set_width($itemWidth)
                    ->set_paddingTop((int)$styles['margin-top'])
                    ->set_paddingBottom((int)$styles['margin-bottom'])
                    ->set_paddingRight((int)$styles['margin-right'])
                    ->set_paddingLeft((int)$styles['margin-left']);

                foreach ($item['item'] as $mbItem) {
                    switch ($mbItem['category']) {
                        case 'photo':
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $this->getItemImageComponent($brizySectionItem)
                            );
                            $this->handleRichTextItem($elementContext, $this->browserPage);
                            $this->getItemImageComponent($brizySectionItem)
                                ->getValue()
                                ->set_widthSuffix('%')
                                ->set_heightSuffix('%')
                                ->set_width(100)
                                ->set_height(100);
                            break;
                        default:
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $this->getItemTextContainerComponent($brizySectionItem)
                            );
                            $this->handleRichTextItem($elementContext, $this->browserPage);
                            break;
                    }
                }
                $brizySectionRow->getValue()->add_items([$brizySectionItem]);
            }
            $brizySection->getItemValueWithDepth(0)->add_items([$brizySectionRow]);
        }


        return $brizySection;
    }

    abstract protected function getItemsPerRow(): int;

    abstract protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent;
}