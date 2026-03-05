<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $this->applySectionLevelMarginProperties($brizySection);
        return $brizySection;
    }

    /**
     * Применяет margin-свойства из getPropertiesMainSection к Section (корню блока).
     * handleSectionStyles работает с SectionItem; Brizy ожидает mobileMargin* на Section.value.
     */
    private function applySectionLevelMarginProperties(BrizyComponent $brizySection): void
    {
        $keys = [
            'mobileMarginType', 'mobileMargin', 'mobileMarginSuffix',
            'mobileMarginTop', 'mobileMarginTopSuffix',
            'mobileMarginRight', 'mobileMarginRightSuffix',
            'mobileMarginBottom', 'mobileMarginBottomSuffix',
            'mobileMarginLeft', 'mobileMarginLeftSuffix',
        ];
        $props = $this->getPropertiesMainSection();
        $sectionValue = $brizySection->getValue();
        foreach ($keys as $key) {
            if (isset($props[$key])) {
                $method = 'set_' . $key;
                $sectionValue->$method($props[$key]);
            }
        }
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition,
        $mbItem = null
    ): BrizyComponent {
        if ($photoPosition == 'left') {
            return $brizyComponent->getItemWithDepth(0,0,1);
        } else {
            return $brizyComponent->getItemWithDepth(0,0,0);
        }
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        if ($photoPosition == 'left') {
            return $brizyComponent->getItemWithDepth(0,0,0,0,0);
        } else {
            return $brizyComponent->getItemWithDepth(0,0,1,0,0);
        }

    }

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, $params = []): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }


    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = [], BrizyComponent $brizyParent = null): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }

    protected function handleRowListItem(BrizyComponent $brizySection, $position = 'left'): void
    {
        $item = $brizySection->getItemWithDepth(0);
        $item->addPadding(0, 0, 0, 0)
            ->addMobileMargin([0, 0, 0, 0]);
    }

    protected function sectionIndentations(BrizyComponent $section){
        $section
            ->getItemWithDepth(0)
            ->addPadding($this->pageTDO->getHeadStyle()->getHeight() ?? 50, 0, 50, 0)
            ->addGroupedMargin()
            ->addMobilePadding()
            ->addTabletPadding()
            ->addTabletMargin();

        $this->pageTDO->getPageStyle()->setPreviousSectionEmpty(true);
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 21,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 21,
            "mobilePaddingLeftSuffix" => "px",

            "mobileMarginType" => "ungrouped",
            "mobileMargin" => 0,
            "mobileMarginTop" => -21,
            "mobileMarginTopSuffix" => "px",
            "mobileMarginBottom" => 0,
            "mobileMarginBottomSuffix" => "px",
            "mobileMarginLeft" => 0,
            "mobileMarginLeftSuffix" => "px",
            "mobileMarginRight" => 0,
            "mobileMarginRightSuffix" => "px",
            "mobileMarginSuffix" => "px",

            "paddingType"=> "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
