<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullMediaElementElement;
use MBMigration\Builder\Utils\ColorConverter;

class FullMediaElement extends FullMediaElementElement
{
    use LineAble;
    use ShadowAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();
        $showHeader = $this->canShowHeader($mbSectionItem);
        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if ($showHeader) {
            $titleMb = $this->getItemByType($mbSectionItem, 'title');
            $image = $brizySection->getItemWithDepth(0, 0, 0);
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $titleMb,
                $image
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 1, null);
        }
        $this->handleShadow($brizySection);

        return $brizySection;
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 100;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 100;
    }

    protected function customSettings(): array
    {
        return [
//            'customCSS' => $this->getCustomCss(),
//            'maskShape' => 'custom'
        ];
    }

    private function getCustomCss(): string
    {
        return "element:has(.brz-ed-image__wrapper) .brz-ed-image__wrapper{
  mask-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0ibTEwMCAxMCA5MCA5MC05MCA5MC05MC05MHoiLz48L3N2Zz4=') !important;
}

element:not(:has(.brz-ed-image__wrapper)) picture{
  mask-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0ibTEwMCAxMCA5MCA5MC05MCA5MC05MC05MHoiLz48L3N2Zz4=') !important;
}";
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType" => "ungrouped",
            "paddingTop" => 90,
            "paddingBottom" => 90,

            "mobilePaddingType" => "ungrouped",
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

}
