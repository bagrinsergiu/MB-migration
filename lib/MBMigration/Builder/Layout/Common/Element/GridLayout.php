<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

abstract class GridLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    private array $globalBrizyKit;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $this->globalBrizyKit = $data->getThemeContext()->getBrizyKit()['global'];

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $sectionItemComponent->getValue()
            ->set_paddingTop($this->getTopPaddingOfTheFirstElement());

        $elementContext = $data->instanceWithBrizyComponent($this->getHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);
        $this->handleRichTextHeadFromItems($elementContext, $this->browserPage);

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

                $dataIdSelector = '[data-id="'.($item['sectionId'] ?? $item['id']).'"]';

                $resultColorStyles = $this->getDomElementStyles(
                    $dataIdSelector,
                    ['border-bottom-color'],
                    $this->browserPage);

                $resultColorStyles['border-bottom-color'] = ColorConverter::convertColorRgbToHex($resultColorStyles['border-bottom-color']);

                $brizySectionItem = new BrizyComponent($itemJson);

                $elementContext = $data->instanceWithMBSection($item);
                $styles = $this->obtainSectionStyles($elementContext, $this->browserPage);

                $brizySectionItem->getValue()
                    ->set_borderColorHex($resultColorStyles['border-bottom-color'])
                    ->set_borderColorPalette('')
                    ->set_borderColorOpacity(1)
                    ->set_borderWidth(3)

                    ->set_width($itemWidth)
                    ->set_paddingTop((int)$styles['margin-top'])
                    ->set_paddingBottom((int)$styles['margin-bottom'])
                    ->set_paddingRight((int)$styles['margin-right'])
                    ->set_paddingLeft((int)$styles['margin-left']);

                foreach ($item['items'] as $mbItem) {
                    switch ($mbItem['category']) {
                        case 'photo':
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $brizySectionItem
                            );

                            $this->handleBgPhotoItems($elementContext, $this->browserPage);
//                            $this->getItemImageComponent($brizySectionItem)
//                                ->getValue()
//                                ->set_widthSuffix('%')
//                                ->set_heightSuffix('%')
//                                ->set_width(100)
//                                ->set_height(100);
                            break;
                        default:
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $brizySectionItem
                            );
                            $this->handleRichTextItem($elementContext, $this->browserPage, null, ['setEmptyText' => true]);
                            break;
                    }
                }
                $brizySectionRow->getValue()->add_items([$brizySectionItem]);
            }
            $brizySection->getItemValueWithDepth(0)->add_items([$brizySectionRow]);
        }


        return $brizySection;
    }

    private function handleBgPhotoItems($data)
    {
        $mbSectionItem = $data->getMbSection();
        $brizyComponent = $data->getBrizySection();

        $brizyComponent->getValue()
            ->set_verticalAlign('bottom')
            ->set_bgImageFileName($mbSectionItem['imageFileName'])
            ->set_bgImageSrc($mbSectionItem['content']);

        $this->handleLink($mbSectionItem, $brizyComponent);

        $aa= 1-3;
    }

    abstract protected function getItemsPerRow(): int;

    abstract protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent;

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
