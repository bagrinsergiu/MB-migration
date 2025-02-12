<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;

class ThreeBottomMediaCircle extends FullTextElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mainBrizySection = parent::internalTransformToItem($data);

        $circleRow = new BrizyComponent(json_decode($this->brizyKit['media-circles'], true));
        $spacer = new BrizyComponent(json_decode($this->brizyKit['spacer'], true));
        $brizySection = $this->getSectionItemComponent($mainBrizySection);
        $mbSection = $data->getMbSection();

        $items = $this->sortItems($mbSection['items']);

        $this->setTopPaddingOfTheFirstElement(
            $data,
            $brizySection,
            [],
            50,
            $this->pageTDO->getPageStyle()->getPreviousSectionEmpty()
        );

        $i = 0;
        foreach ($items as $mbItem) {
            if ($mbItem['category'] == 'photo') {
                $this->handleMediaCircle($mbItem, $circleRow->getItemWithDepth($i++, 0, 0));
            }
        }

        $brizySection->getValue()->add_items([$spacer, $circleRow]);

        return $mainBrizySection;
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    private function handleMediaCircle($mbSectionItem, BrizyComponent $brizyComponent)
    {
        $brizyComponent->getValue()
            ->set_imageFileName($mbSectionItem['imageFileName'])
            ->set_imageSrc($mbSectionItem['content']);
    }

    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 120;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 95;
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
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "marginType" => "ungrouped",
            "marginTop" => 0,
            "marginTopSuffix" => "px",
            "marginBottom" => 0,
            "marginBottomSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 70,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 20,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 50,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
