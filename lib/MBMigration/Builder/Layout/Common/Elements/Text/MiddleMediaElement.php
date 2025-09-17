<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Utils\ColorConverter;

abstract class MiddleMediaElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;

    protected function getSectionName(): string
    {
        return "Full Text";
    }

    /**
     * @throws BadJsonProvided
     * @throws \Exception
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $mbSectionItem = $data->getMbSection();

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);
        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $sectionForItemsGroup0 = $brizySection->getItemWithDepth(0,0,0);
        $itemsGroup0 = $this->getItemsByGroup($mbSectionItem, 0);

        foreach ($itemsGroup0 as $item) {
            $elementContextGroup0 = $data->instanceWithBrizyComponentAndMBSection(
                $item,
                $sectionForItemsGroup0
            );
            $this->handleRichTextItem($elementContextGroup0, $this->browserPage);
        }

        $sectionForItemsGroup1 = $brizySection->getItemWithDepth(0,0,1);
        $itemsGroup1 = $this->getItemsByGroup($mbSectionItem, 1);
        $this->handleGroupStyle($mbSectionItem, $sectionForItemsGroup1, 1);

        foreach ($itemsGroup1 as $item) {
            $elementContextGroup1 = $data->instanceWithBrizyComponentAndMBSection(
                $item,
                $sectionForItemsGroup1
            );
            $this->handleRichTextItem($elementContextGroup1, $this->browserPage);
        }

        return $brizySection;
    }

    abstract protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent;

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
        ];
    }

    private function handleGroupStyle($section, BrizyComponent $brizySection, int $group = 0)
    {
        $selector = '[data-id="' . $section['sectionId']. '"]'. " div.group.group-$group > div";
        $styleList = $this->getDomElementStyles(
            $selector,
            ['background-color'],
            $this->browserPage
        );

        $brizySection->getValue()
            ->set_bgColorHex(ColorConverter::rgba2hex($styleList['background-color']))
            ->set_bgColorPalette('')
            ->set_bgColorType('solid')
            ->set_bgColorOpacity($styleList['opacity'] ?? 1);

    }
}
