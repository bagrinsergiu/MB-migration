<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    private $borderColor = '';

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 1 : 0);
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0,0);
    }

    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $this->transformItems($data, $brizySection, $params);
    }

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $this->transformItems($data, $brizySection, $params);
    }

    protected function transformItems(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $itemsKit = $data->getThemeContext()->getBrizyKit();

        $wrapperLine = new BrizyComponent(json_decode($itemsKit['global']['wrapper--line'], true));
        if(!isset($mbSectionItem['item_type']) || $mbSectionItem['item_type'] !== 'title'){
            $titleMb = $this->getByType($mbSectionItem['head'], 'title');
        } else {
            $titleMb['id'] =  $mbSectionItem['id'];
        }

        $menuSectionSelector = '[data-id="' . $titleMb['id']. '"]';
        $wrapperLineStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $menuSectionSelector,
                'styleProperties' => ['border-bottom-color',],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        $headStyle = [
            'line-color' => ColorConverter::convertColorRgbToHex($wrapperLineStyles['data']['border-bottom-color']),
        ];

        $wrapperLine->getItemWithDepth(0)
            ->getValue()
            ->set_borderColorHex($headStyle['line-color']);

        $brizySection->getValue()->add_items([$wrapperLine]);

        $sectionSubPalette = $this->getNodeSubPalette($menuSectionSelector, $this->browserPage);
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        $this->borderColor = !empty($headStyle['line-color']) ? $headStyle['line-color'] : $sectionPalette['text'];

        return $brizySection;
    }

    protected function handleRowListItem(BrizyComponent $brizySection, $position = null): void
    {
        $brizySection->getValue()
            ->set_mobileBorderColorPalette('')
            ->set_mobileBorderColorHex($this->borderColor)
            ->set_mobileBorderTopColor($this->borderColor)
            ->set_mobileBorderRightColor($this->borderColor)
            ->set_mobileBorderBottomColor($this->borderColor)
            ->set_mobileBorderLeftColor($this->borderColor)
            ->set_mobileBorderColorOpacity(1);

        $brizySection
            ->addMargin(20,0,20,0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 50,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 50,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
