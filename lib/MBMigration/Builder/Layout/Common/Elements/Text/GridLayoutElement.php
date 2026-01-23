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

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($this->getHeaderComponent($brizySection));


        $this->handleTextSection($elementContext);


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

            $this->handleBeforeMainForeachRow($brizySectionRow, $row);

            foreach ($row as $item) {

                $dataIdSelector = '[data-id="'.($item['sectionId'] ?? $item['id']).'"]';

                $resultColorStyles = $this->getDomElementStyles(
                    $dataIdSelector,
                    ['border-bottom-color'],
                    $this->browserPage);

                $resultColorStyles['border-bottom-color'] = ColorConverter::convertColorRgbToHex($resultColorStyles['border-bottom-color'] ?? '#000000');

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
                $itemContext = $data->instanceWithBrizyComponent($brizySectionItem);

                foreach ($item['items'] as $mbItem) {
                    // #region agent log
                    file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
                        'sessionId' => 'debug-session',
                        'runId' => 'run1',
                        'hypothesisId' => 'B',
                        'location' => 'GridLayoutElement.php:90',
                        'message' => 'Processing item in list',
                        'data' => [
                            'itemId' => $mbItem['id'] ?? null,
                            'category' => $mbItem['category'] ?? null,
                            'item_type' => $mbItem['item_type'] ?? null,
                            'content' => substr($mbItem['content'] ?? '', 0, 100)
                        ],
                        'timestamp' => time() * 1000
                    ]) . "\n", FILE_APPEND);
                    // #endregion
                    
                    switch ($mbItem['category']) {
                        case 'photo':

                            $imageSize = $this->obtainItemImageStyles($mbItem['id'], $this->browserPage);

                            $additionalOptions = [
                                'mobileWidth' => 100,
                                'mobileHeightStyle' => 'custom',
                                'mobileHeight' => (int)ColorConverter::removePx($imageSize['height'] ?? '0px'),
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
                            // #region agent log
                            file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
                                'sessionId' => 'debug-session',
                                'runId' => 'run1',
                                'hypothesisId' => 'B',
                                'location' => 'GridLayoutElement.php:130',
                                'message' => 'Entering default case for text item',
                                'data' => [
                                    'itemId' => $mbItem['id'] ?? null,
                                    'category' => $mbItem['category'] ?? null
                                ],
                                'timestamp' => time() * 1000
                            ]) . "\n", FILE_APPEND);
                            // #endregion
                            
                            $textContainerComponent = $this->getItemTextContainerComponent($brizySectionItem);
                            
                            // #region agent log
                            file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
                                'sessionId' => 'debug-session',
                                'runId' => 'run1',
                                'hypothesisId' => 'A',
                                'location' => 'GridLayoutElement.php:133',
                                'message' => 'Got text container component',
                                'data' => [
                                    'componentType' => $textContainerComponent->getType(),
                                    'itemId' => $mbItem['id'] ?? null
                                ],
                                'timestamp' => time() * 1000
                            ]) . "\n", FILE_APPEND);
                            // #endregion
                            
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $textContainerComponent
                            );

                            $dataIdSelector = '[data-id="'.$mbItem['id'].'"]';

                            // #region agent log
                            file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
                                'sessionId' => 'debug-session',
                                'runId' => 'run1',
                                'hypothesisId' => 'D',
                                'location' => 'GridLayoutElement.php:136',
                                'message' => 'Checking selector for text item',
                                'data' => [
                                    'selector' => $dataIdSelector,
                                    'itemId' => $mbItem['id'] ?? null
                                ],
                                'timestamp' => time() * 1000
                            ]) . "\n", FILE_APPEND);
                            // #endregion

                            $displayItem = $this->getDomElementStyles(
                                $dataIdSelector,
                                ['display'],
                                $this->browserPage);

                            // #region agent log
                            file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
                                'sessionId' => 'debug-session',
                                'runId' => 'run1',
                                'hypothesisId' => 'D',
                                'location' => 'GridLayoutElement.php:138',
                                'message' => 'Display check result',
                                'data' => [
                                    'display' => $displayItem['display'] ?? null,
                                    'willSkip' => isset($displayItem['display']) && trim($displayItem['display']) === 'none'
                                ],
                                'timestamp' => time() * 1000
                            ]) . "\n", FILE_APPEND);
                            // #endregion

                            if(isset($displayItem['display']) && trim($displayItem['display']) === 'none'){
                                continue 2;
                            }

                            // #region agent log
                            file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
                                'sessionId' => 'debug-session',
                                'runId' => 'run1',
                                'hypothesisId' => 'B',
                                'location' => 'GridLayoutElement.php:147',
                                'message' => 'Before handleRichTextItem call',
                                'data' => [
                                    'itemId' => $mbItem['id'] ?? null,
                                    'contentLength' => strlen($mbItem['content'] ?? '')
                                ],
                                'timestamp' => time() * 1000
                            ]) . "\n", FILE_APPEND);
                            // #endregion

                            $this->handleRichTextItem($elementContext, $this->browserPage, null, ['setEmptyText' => true]);
                            
                            // #region agent log
                            file_put_contents('/home/sg/projects/MB-migration/.cursor/debug.log', json_encode([
                                'sessionId' => 'debug-session',
                                'runId' => 'run1',
                                'hypothesisId' => 'C',
                                'location' => 'GridLayoutElement.php:147',
                                'message' => 'After handleRichTextItem call',
                                'data' => [
                                    'itemId' => $mbItem['id'] ?? null,
                                    'componentItemsCount' => count($textContainerComponent->getValue()->get_items() ?? [])
                                ],
                                'timestamp' => time() * 1000
                            ]) . "\n", FILE_APPEND);
                            // #endregion
                            
                            $this->handleItemTextAfter($elementContext, $itemContext);
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

    protected function handleBgPhotoItems(
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

    protected function handleTextSection(ElementContextInterface $context) {
         $this->handleRichTextHead($context, $this->browserPage);
    }
    abstract protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent;

    protected function getTypeItemImageComponent(): string
    {
       return 'bg';
    }

    protected function handleBeforeMainForeachRow(BrizyComponent $brizyComponent, $row): BrizyComponent
    {
       return $brizyComponent;
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
