<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;

class FourHorizontalText extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $mbSection = $data->getMbSection();

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));

        $this->handleSectionStyles($elementContext, $this->browserPage);

        $titles = $this->sortItems(array_filter($mbSection['items'], function ($item) {
            return $item['item_type'] == 'title' && $item['category'] == 'text';
        }));
        $bodies = $this->sortItems(array_filter($mbSection['items'], function ($item) {
            return $item['item_type'] == 'body' && $item['category'] == 'text';
        }));

        $columnJson = json_decode($this->brizyKit['column'], true);

        $columns = [];
        foreach ($titles as $i => $mbItem) {
            $brizyColumn = new BrizyComponent($columnJson);
            $tmpElementContext = $data->instanceWithBrizyComponentAndMBSection($mbItem, $brizyColumn);
            $this->handleRichTextItem($tmpElementContext, $this->browserPage);
            $tmpElementContext = $data->instanceWithBrizyComponentAndMBSection($bodies[$i], $brizyColumn);
            $this->handleRichTextItem($tmpElementContext, $this->browserPage);
            $buttonSelector = $mbSection['sectionId'];
            $this->handleRichTextItem($tmpElementContext, $this->browserPage, "[data-id='$buttonSelector'] .group-$i");
            $columns[] = $brizyColumn;
        }
        $brizySection->getItemValueWithDepth(0,0)->add_items($columns);

        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

        return $brizySection;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 95;
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

            "paddingType" => "ungrouped",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
