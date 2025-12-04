<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class LeftMedia extends PhotoTextElement
{
    use ShadowAble;
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 0,0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();
        $itemsKit = $data->getThemeContext()->getBrizyKit();

        $wrapperLine = new BrizyComponent(json_decode($itemsKit['global']['wrapper--line'], true));

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);
        $titleMb = $this->getItemByType($mbSectionItem, 'title');

        $menuSectionSelector = '[data-id="' . $titleMb['id'] . '"]';
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

        $brizySection->getItemWithDepth(0, 0, 1)
            ->getValue()
            ->add_items([$wrapperLine], 1);

        $this->handleShadow($brizySection);

        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType" => "ungrouped",
            "paddingTop" => 90,
            "paddingBottom" => 90,

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 70,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 70,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 100;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 100;
    }


}
