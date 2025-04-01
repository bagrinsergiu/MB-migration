<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\ButtonAble;
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
    use ButtonAble;

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
        $buttons = $this->sortItems(array_filter($mbSection['items'], function ($item) {
            return $item['category'] == 'button';
        }));

        $columnJson = json_decode($this->brizyKit['column'], true);

        $columns = [];
        foreach ($titles as $i => $mbItem) {
            $brizyColumn = new BrizyComponent($columnJson);

            $brizyColumn->addMobileMargin(20);
            $tmpElementContext = $data->instanceWithBrizyComponentAndMBSection($mbItem, $brizyColumn);
            $this->handleRichTextItem($tmpElementContext, $this->browserPage);
            $tmpElementContext = $data->instanceWithBrizyComponentAndMBSection($bodies[$i], $brizyColumn);
            $this->handleRichTextItem($tmpElementContext, $this->browserPage);
            if($this->canShowButton($mbSection)){

                $buttonSelector = $mbSection['sectionId'];
                $selector = "[data-id='$buttonSelector']";
                $selectorButton = $selector ." .group-$i > a > button";

                if($this->hasNode($selectorButton, $this->browserPage)){
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $buttons[$i],
                        $brizyColumn
                    );

                    $this->handleButton($elementContext, $this->browserPage, $this->brizyKit, $selectorButton, $mbSection['sectionId']);
                }

                $tmpElementContext = $data->instanceWithBrizyComponentAndMBSection($buttons[$i], $brizyColumn);
                $this->handleRichTextItem($tmpElementContext, $this->browserPage, "$selector . ' .group-$i");
            }

            $columns[] = $brizyColumn;
        }

        $brizySection->getItemValueWithDepth(0,0)->add_items($columns);

        return $brizySection;
    }

    protected function getMobileTopMarginOfTheFirstElement(): int
    {
        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();

        return (int) $dtoPageStyle['headerHeight'];
    }


    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
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
            "paddingTop" => 50,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 50,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
