<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\Button;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\ImageStylesAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class GridLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\GridLayoutElement
{
    use RichTextAble;
    use SectionStylesAble;
    use ImageStylesAble;
    use DonationsAble;
    use LineAble;
    use Button;

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

        $titleMb = $this->getByType($mbSection['head'], 'title');
        $this->handleLine(
            $elementContext,
            $this->browserPage,
            $titleMb['id'],
            null,
            [
                "borderWidth" => 1,
                "width" => 100,
                "widthSuffix" => "%"
            ],
            1,
            ''
        );

        $rowJson = json_decode($this->brizyKit['row'], true);
        $itemJson = json_decode($this->brizyKit['item'], true);

        $brizySection->getItemWithDepth(0)->addMargin(0, 30, 0, 0, '', '%');

        $accordionItems = $this->getItemsByCategory($mbSection, 'list');
        $accordionItems = $this->sortItems($accordionItems);
        $itemsChunks = array_chunk($accordionItems, $this->getItemsPerRow());
        foreach ($itemsChunks as $row) {
            $brizySectionRow = new BrizyComponent($rowJson);
            $itemCount = count($row);
            $itemWidth = (int)(100 / $this->getItemsPerRow());
            $rowWidth = (int)((100 / $this->getItemsPerRow()) * $itemCount);

            $this->handleItemRowComponent($brizySectionRow);

            $this->handleBeforeMainForeachRow($brizySectionRow, $row);

            foreach ($row as $item) {

                $dataIdSelector = '[data-id="' . ($item['sectionId'] ?? $item['id']) . '"]';

                $resultColorStyles = $this->getDomElementStyles(
                    $dataIdSelector,
                    ['border-bottom-color'],
                    $this->browserPage);

                $resultColorStyles['border-bottom-color'] = ColorConverter::convertColorRgbToHex($resultColorStyles['border-bottom-color']);

                $brizySectionItem = new BrizyComponent($itemJson);

                $brizySectionItem
                    ->addPadding(15, 5, 15, 5)
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
                            if ($this->canShowButton($mbSection)) {

                                $buttonSelector = $item['id'];
                                $selector = "[data-id='$buttonSelector']";

                                if ($this->hasNode($selector, $this->browserPage)) {
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

                            $dataIdSelector = '[data-id="' . $mbItem['id'] . '"]';

                            $displayItem = $this->getDomElementStyles(
                                $dataIdSelector,
                                ['display'],
                                $this->browserPage);

                            if (trim($displayItem['display']) === 'none') {
                                continue 2;
                            }

                            $this->handleRichTextItem($elementContext, $this->browserPage, null, ['setEmptyText' => true]);
                            $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());
                            break;
                    }
                }

                $context = $data->instanceWithBrizyComponentAndMBSection($item, $brizySectionItem);

                $this->handleColumItemComponent($context);
                $brizySectionRow->getValue()->add_items([$brizySectionItem]);
            }

            // Добавляем пустые колонки если чанк неполный
            $emptyItemsCount = $this->getItemsPerRow() - $itemCount;
            if ($emptyItemsCount > 0) {
                for ($i = 0; $i < $emptyItemsCount; $i++) {
                    $emptyBrizySectionItem = new BrizyComponent($itemJson);
                    $emptyBrizySectionItem
                        ->addPadding(15, 5, 15, 5)
                        ->addMobilePadding(10)
                        ->addMobileMargin();

                    $emptyBrizySectionItem
                        ->addVerticalContentAlign()
                        ->getValue()
                        ->set_borderWidth(0)
                        ->set_width($itemWidth)
                        ->set_mobileWidth(100);

                    $brizySectionRow->getValue()->add_items([$emptyBrizySectionItem]);
                }
            }

            $this->getInsideItemComponent($brizySection)
                ->getValue()
                ->add_items([$brizySectionRow]);
        }

        return $brizySection;
    }

    protected function getSelectorForButton(): array
    {
        return [
            'a.sites-button',
        ];
    }

    protected function getPropertiesItemPhoto(): array
    {
        return [
            "borderStyle" => "solid",
            "borderColorHex" => "#66738d",
            "borderColorOpacity" => 1,
            "borderColorPalette" => "",
            "borderWidthType" => "grouped",
            "borderWidth" => 1,
            "borderTopWidth" => 1,
            "borderRightWidth" => 1,
            "borderBottomWidth" => 1,
            "borderLeftWidth" => 1,
            "tabsState" => "normal"
        ];
    }

    protected function getItemsPerRow(): int
    {
        return 4;
    }

    protected function getTypeItemImageComponent(): string
    {
        return 'image';
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    protected function getItemTextContainerComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemImageComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
