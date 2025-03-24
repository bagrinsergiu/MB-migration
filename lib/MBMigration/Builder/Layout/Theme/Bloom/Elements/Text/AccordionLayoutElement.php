<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\TextTools;

class AccordionLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\AccordionLayoutElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();
        $families = $data->getFontFamilies();

        $sectionSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';
        $backgroundColorStyles = ColorConverter::convertColorRgbToHex(
            $this->getDomElementStyles($sectionSelector, ['background-color'], $this->browserPage));

        $accordionElementStyles = $this->getAccordionElementStyles($sectionSelector, $this->browserPage, $families);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item'], true);
        $brizyAccordionItems = [];

        $brizyAccordionComponent = $this->getAccordionParentComponent($brizySection)->getValue();

        foreach ($accordionElementStyles as $key => $value) {
            $propertiesName = 'set_'.$key;
            $brizyAccordionComponent->$propertiesName($value);
        }

        foreach ($mbSection['items'] as $mbSectionItem) {
            $brizyAccordionItemComponent = new BrizyComponent($itemJson);

            $lableText = TextTools::transformTextBool($mbSectionItem['items'][0]['content'],
                    $accordionElementStyles['uppercase']);

            $brizyAccordionItemComponent->getValue()->set_labelText(strip_tags($lableText));

            $brizyAccordionItem = $this->getAccordionSectionComponent($brizyAccordionItemComponent);

            $accordionWrapperElementStyle = [
                "marginType" => "ungrouped",
                "marginSuffix" => "px",
                "marginTop" => 10,
                "marginTopSuffix" => "px",
                "marginRight" => 11,
                "marginRightSuffix" => "px",
                "marginBottom" => 10,
                "marginBottomSuffix" => "px",
                "marginLeft" => 11,
                "marginLeftSuffix" => "px",

                "tabletMarginType" => "ungrouped",
                "tabletMargin" => 0,
                "tabletMarginSuffix" => "px",
                "tabletMarginTop" => 10,
                "tabletMarginTopSuffix" => "px",
                "tabletMarginRight" => 11,
                "tabletMarginRightSuffix" => "px",
                "tabletMarginBottom" => 10,
                "tabletMarginBottomSuffix" => "px",
                "tabletMarginLeft" => 11,
                "tabletMarginLeftSuffix" => "px",

                "mobileMarginType" => "ungrouped",
                "mobileMargin" => 0,
                "mobileMarginSuffix" => "px",
                "mobileMarginTop" => 15,
                "mobileMarginTopSuffix" => "px",
                "mobileMarginRight" => 15,
                "mobileMarginRightSuffix" => "px",
                "mobileMarginBottom" => 15,
                "mobileMarginBottomSuffix" => "px",
                "mobileMarginLeft" => 15,
                "mobileMarginLeftSuffix" => "px",
            ];


            $accordionRowElementStyle = [
                'bgColorHex' => $backgroundColorStyles['background-color'],

                "marginType" => "ungrouped",
                "marginSuffix" => "px",
                "marginTop" => 0,
                "marginTopSuffix" => "px",
                "marginRight" => -11,
                "marginRightSuffix" => "px",
                "marginBottom" => 0,
                "marginBottomSuffix" => "px",
                "marginLeft" => -11,
                "marginLeftSuffix" => "px",

                'mobileMarginType' => 'ungrouped',
                'mobileMarginTop' => 0,
                'mobileMarginTopSuffix' => 'px',
                'mobileMarginBottom' => 0,
                'mobileMarginBottomSuffix' => 'px',
                'mobileMarginRight' => -10,
                'mobileMarginRightSuffix' => 'px',
                'mobileMarginLeft' => -10,
                'mobileMarginLeftSuffix' => 'px',

                'tabletMarginType' => 'ungrouped',
                'tabletMarginTop' => 0,
                'tabletMarginTopSuffix' => 'px',
                'tabletMarginBottom' => 0,
                'tabletMarginBottomSuffix' => 'px',
                'tabletMarginRight' => -10,
                'tabletMarginRightSuffix' => 'px',
                'tabletMarginLeft' => -10,
                'tabletMarginLeftSuffix' => 'px',

            ];

            foreach ($accordionRowElementStyle as $key => $value) {
                $method = "set_".$key;
                $brizyAccordionItem->getValue()
                    ->$method($value);
            }

            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['items'][1],
                $brizyAccordionItem
            );
            $this->handleRichTextItem($elementContext, $this->browserPage);

            $elementWrapper = $brizyAccordionItem->getItemWithDepth(0);
            foreach ($accordionWrapperElementStyle as $key => $value) {
                $method = "set_".$key;
                $elementWrapper->getValue()
                    ->$method($value);
            }

            $brizyAccordionItems[] = $brizyAccordionItemComponent;
        }

        $accordionElementStyle = [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 10,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 10,
            "mobilePaddingLeftSuffix" => "px",
        ];

        $accordionHeadTextStyle = [
            'mobileMarginType' => 'ungrouped',
            'mobileMarginTop' => 0,
            'mobileMarginTopSuffix' => 'px',
            'mobileMarginBottom' => 0,
            'mobileMarginBottomSuffix' => 'px',
            'mobileMarginRight' => 10,
            'mobileMarginRightSuffix' => 'px',
            'mobileMarginLeft' => 10,
            'mobileMarginLeftSuffix' => 'px',
        ];

        foreach ($accordionElementStyle as $key => $value) {
            $method = "set_".$key;
            $brizySection->getItemWithDepth(0,1)->getValue()
                ->$method($value);
        }

        foreach ($accordionHeadTextStyle as $key => $value) {
            $method = "set_".$key;
            $brizySection->getItemWithDepth(0,0)->getValue()
                ->$method($value);
        }

        $brizyAccordionComponent->set_items($brizyAccordionItems);

        return $brizySection;
    }

    protected function getAccordionSectionComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getSectionHeaderComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function getAccordionParentComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();

        return 50 + $dtoPageStyle['headerHeight'];
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
            "paddingTop" => 80,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 80,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
