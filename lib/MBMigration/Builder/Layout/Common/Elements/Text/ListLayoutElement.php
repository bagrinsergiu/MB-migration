<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class ListLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $showHeader = $this->canShowHeader($mbSection);

        $photoPosition = $mbSection['settings']['sections']['list']['photo_position'] ?? 'left';

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($this->getHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        if ($showHeader) {
            $this->afterTransformItem($elementContext, $this->getHeaderComponent($brizySection));
        }

        $itemJson = json_decode($this->brizyKit['item-'.$photoPosition], true);
        $brizyComponentValue = $this->getSectionItemComponent($brizySection)->getValue();
        foreach ($mbSection['items'] as $item) {
            $brizySectionItem = new BrizyComponent($itemJson);

            $elementContext = $data->instanceWithMBSection($item);
            $styles = $this->obtainSectionStyles($elementContext, $this->browserPage);

            $brizySectionItem->getValue()
                ->set_paddingTop((int)$styles['margin-top'])
                ->set_paddingBottom((int)$styles['margin-bottom'])
                ->set_paddingRight((int)$styles['margin-right'])
                ->set_paddingLeft((int)$styles['margin-left']);

            foreach ($item['items'] as $mbItem) {
                if ($mbItem['item_type'] == 'title' || $mbItem['item_type'] == 'body') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $this->getItemTextContainerComponent($brizySectionItem, $photoPosition)
                    );
                }
                if ($mbItem['category'] == 'photo') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $this->getItemImageComponent($brizySectionItem, $photoPosition)
                    );
                }
                $this->handleRichTextItem($elementContext, $this->browserPage);

                if ($mbItem['item_type'] == 'title') {
                    $this->afterTransformItem($elementContext,
                        $this->getItemTextContainerComponent($brizySectionItem, $photoPosition));
                }
            }

            $brizyComponentValue->add_items([$brizySectionItem]);
        }

        return $brizySection;
    }

    abstract protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent;

    abstract protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent;

    abstract protected function afterTransformItem(ElementContextInterface $data, BrizyComponent $brizySection): BrizyComponent;

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
