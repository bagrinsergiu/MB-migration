<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\Button;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\ImageStylesAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Utils\ColorConverter;

abstract class GridLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use ImageStylesAble;
    use DonationsAble;
    use Button;

    public array $globalBrizyKit;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $this->globalBrizyKit = $data->getThemeContext()->getBrizyKit()['global'];

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

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
            $brizySectionRow->getValue()
                ->set_size($rowWidth)
                ->set_mobileSize(100);

            $this->handleItemRowComponent($brizySectionRow);

            foreach ($row as $item) {

                $dataIdSelector = '[data-id="'.($item['sectionId'] ?? $item['id']).'"]';

                $resultColorStyles = $this->getDomElementStyles(
                    $dataIdSelector,
                    ['border-bottom-color'],
                    $this->browserPage);

                $resultColorStyles['border-bottom-color'] = ColorConverter::convertColorRgbToHex($resultColorStyles['border-bottom-color']);

                $brizySectionItem = new BrizyComponent($itemJson);

                $brizySectionItem
                    ->addPadding(15,15,15,15)
                    ->addMobilePadding(10)
                    ->addMobileMargin();

                $brizySectionItem
                    ->addVerticalContentAlign()
                    ->getValue()
                    ->set_borderWidth(0)
                    ->set_width($itemWidth)
                    ->set_mobileWidth(100);

                foreach ($item['items'] as $mbItem) {
                    switch ($mbItem['category']) {
                        case 'photo':

                            $imageSize = $this->obtainItemImageStyles($mbItem['id'], $this->browserPage);

                            $additionalOptions = [
                                'mobileWidth' => 100,
                                'mobileHeightStyle' => 'custom',
                                'mobileHeight' => (int)ColorConverter::removePx($imageSize['height']),
                                'mobileHeightSuffix' => 'px',
                                "mobileMarginType" => "grouped",
                                "mobileMargin" => 20,
                            ];

                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $this->getItemImageComponent($brizySectionItem)
                            );

                            $this->handleBgPhotoItems($elementContext, $additionalOptions, $this->getTypeItemImageComponent(), $this->getPropertiesItemPhoto());

                            $this->handleItemPhotoAfter($elementContext);
                            break;
                        case 'button':
                            if($this->canShowButton($mbSection)){

                                $buttonSelector = $item['id'];
                                $selector = "[data-id='$buttonSelector']";

                                if($this->hasNode($selector, $this->browserPage)){
                                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                        $mbItem,
                                        $brizySectionItem
                                    );

                                    $this->handleButton($elementContext, $this->browserPage, $this->brizyKit, null, $mbItem['id'] ?? null);
                                }
                            }
                            break;
                        default:
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $brizySectionItem
                            );

                            $dataIdSelector = '[data-id="'.$mbItem['id'].'"]';

                            $displayItem = $this->getDomElementStyles(
                                $dataIdSelector,
                                ['display'],
                                $this->browserPage);

                            if(trim($displayItem['display']) === 'none'){
                                continue 2;
                            }

                            $this->handleRichTextItem($elementContext, $this->browserPage, null, ['setEmptyText' => true]);
                            $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());
                            break;
                    }
                }

                $context = $data->instanceWithBrizyComponentAndMBSection($item,$brizySectionItem);

                $this->handleColumItemComponent($context);
                $brizySectionRow->getValue()->add_items([$brizySectionItem]);
            }
           $this->getInsideItemComponent($brizySection)
               ->getValue()
               ->add_items([$brizySectionRow]);
        }

        return $brizySection;
    }

    private function handleBgPhotoItems(
        ElementContextInterface $data,
        array $options = [],
        $elementImageType = 'bg',
        array $propertiesItemPhoto = []
    )
    {
        $mbSectionItem = $data->getMbSection();
        $brizyComponent = $data->getBrizySection();

        switch ($elementImageType){
            case 'bg':
                $brizyComponent->getValue()
                    ->set_verticalAlign('bottom')
                    ->set_bgImageFileName($mbSectionItem['imageFileName'])
                    ->set_bgImageSrc($mbSectionItem['content']);

                $itemPhoto = $brizyComponent;
                break;
            case 'image':
                $brizyComponent->addImage($mbSectionItem, $propertiesItemPhoto);

                $itemPhoto = $brizyComponent->findFirstByType('Image');

                if($itemPhoto === null)
                {
                    $itemPhoto = $brizyComponent->getItemWithDepth(0);
                }

                break;
            default:
                $itemPhoto = $brizyComponent;
        }

        foreach ($options as $key => $value) {
            $method = 'set_'.$key;
            $brizyComponent->getValue()
                ->$method($value);
        }

        $this->handleLink(
            $mbSectionItem,
            $itemPhoto,
            '[data-id="'.$mbSectionItem['id'].'"] .photo-container a',
            $this->browserPage
        );
    }

    abstract protected function getItemsPerRow(): int;

    abstract protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent;

    protected function getTypeItemImageComponent(): string
    {
       return 'bg';
    }

    protected function handleItemRowComponent(BrizyComponent $brizyComponent):void
    {
        $brizyComponent
            ->addPadding(20,0,20,0)
            ->addMobilePadding(10);
    }

    protected function handleColumItemComponent(ElementContextInterface $context):void
    {
    }

    protected function getInsideItemComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
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
            "mobilePaddingBottom" => 30,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
