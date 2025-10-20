<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\Button;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;

abstract class ListLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;
    use Button;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $showHeader = $this->canShowHeader($mbSection);

        $photoPosition = $mbSection['settings']['sections']['list']['photo_position'] ?? 'left';

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($this->getHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        if ($showHeader) {
            $this->transformHeadItem($elementContext, $this->getHeaderComponent($brizySection), $styleList);
        }

        $itemJson = json_decode($this->brizyKit['item-'.$photoPosition], true);
        $brizyComponentValue = $this->getSectionItemComponent($brizySection)->getValue();

        foreach ($mbSection['items'] as $item) {
            $brizySectionItem = new BrizyComponent($itemJson);

            $elementContext = $data->instanceWithMBSection($item);
            $styles = $this->obtainSectionStyles($elementContext, $this->browserPage);

            $this->handleRowListItem($brizySectionItem, $photoPosition);

//            $brizySectionItem->getValue()
//                ->set_paddingTop((int)$styles['margin-top'])
//                ->set_paddingBottom((int)$styles['margin-bottom'])
//                ->set_paddingRight((int)$styles['margin-right'])
//                ->set_paddingLeft((int)$styles['margin-left']);

            $this->handleItemTextContainerComponent($brizySectionItem);

            foreach ($item['items'] as $mbItem) {
                if ($mbItem['item_type'] == 'title') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $this->getItemTextContainerComponent($brizySectionItem, $photoPosition)
                    );
                    $this->handleRichTextItem($elementContext, $this->browserPage);

                    $this->transformListItem($elementContext,
                        $this->getItemTextContainerComponent($brizySectionItem, $photoPosition),
                        $styleList);
                }
            }

            foreach ($item['items'] as $mbItem) {
                if ($mbItem['item_type'] == 'body') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $this->getItemTextContainerComponent($brizySectionItem, $photoPosition)
                    );
                    $this->handleRichTextItem($elementContext, $this->browserPage);
                }
            }

            foreach ($item['items'] as $mbItem) {
                if ($mbItem['category'] == 'button') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $this->getItemTextContainerComponent($brizySectionItem, $photoPosition)
                    );
                    $this->handleButton($elementContext, $this->browserPage, $this->brizyKit);
                }
            }

            foreach ($item['items'] as $mbItem) {
                if ($mbItem['category'] == 'donation') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $this->getItemTextContainerComponent($brizySectionItem, $photoPosition)
                    );
                    $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());
                }
            }

            foreach ($item['items'] as $mbItem) {
                if ($mbItem['category'] == 'photo') {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbItem,
                        $this->getItemImageComponent($brizySectionItem, $photoPosition)
                    );
                    $this->handleRichTextItem($elementContext, $this->browserPage, null, [], $this->customSettings());
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

    protected function handleItemTextContainerComponent(BrizyComponent $brizySection): void
    {

    }

    protected function customSettings(): array
    {
        return [];
    }

    protected function handleRowListItem(BrizyComponent $brizySection, $position = 'left'): void
    {
        $brizySection
            ->getItemWithDepth(0)
            ->addPadding(55,0,55,0);
    }

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
