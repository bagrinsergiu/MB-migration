<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\TextTools;

class AccordionLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\AccordionLayoutElement
{

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizyAccordionItems = [];

        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();
        $families = $data->getFontFamilies();

        $sectionSelector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';
        $backgroundColorStyles = ColorConverter::convertColorRgbToHex(
            $this->getDomElementStyles($sectionSelector, ['background-color'], $this->browserPage));

        $accordionElementStyles = $this->getAccordionElementStyles($sectionSelector, $this->browserPage, $families);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());
        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionHeaderComponent($brizySection));
        $this->handleRichTextHead($elementContext, $this->browserPage);

        $itemJson = json_decode($this->brizyKit['item'], true);

        $brizyAccordionComponent = $this->getAccordionParentComponent($brizySection)->getValue();

        $brizySection->getItemWithDepth(0,1,0,0)->addMobilePadding([0, 10, 0, 10]);

        $additionalOptionsAccordionStyles = [
            "titlePaddingType" => "ungrouped",
            "titlePadding" => 10,
            "titlePaddingSuffix" => "px",
            "titlePaddingTop" => 10,
            "titlePaddingTopSuffix" => "px",
            "titlePaddingRight" => 15,
            "titlePaddingRightSuffix" => "px",
            "titlePaddingBottom" => 10,
            "titlePaddingBottomSuffix" => "px",
            "titlePaddingLeft" => 15,
            "titlePaddingLeftSuffix" => "px",
        ];

        $accordionStyles = array_merge($accordionElementStyles, $additionalOptionsAccordionStyles);

        foreach ($accordionStyles as $key => $value) {
            $propertiesName = 'set_' . $key;
            $brizyAccordionComponent->$propertiesName($value);
        }

        foreach ($mbSection['items'] as $mbSectionItem) {
            $brizyAccordionItemComponent = new BrizyComponent($itemJson);

            $lableText = TextTools::transformTextBool(strip_tags($mbSectionItem['items'][0]['content']),
                $accordionElementStyles['uppercase']);

            $brizyAccordionItemComponent->getValue()->set_labelText($lableText);

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
                "mobileMarginTop" => 10,
                "mobileMarginTopSuffix" => "px",
                "mobileMarginRight" => 11,
                "mobileMarginRightSuffix" => "px",
                "mobileMarginBottom" => 10,
                "mobileMarginBottomSuffix" => "px",
                "mobileMarginLeft" => 11,
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
                $method = "set_" . $key;
                $brizyAccordionItem->getValue()
                    ->$method($value);
            }

            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem['items'][1],
                $brizyAccordionItem
            );
            $this->handleRichTextItem($elementContext, $this->browserPage);

            $elementWrapper = $brizyAccordionItem->getItemWithDepth(0);

            if (!empty($elementWrapper)) {
                foreach ($accordionWrapperElementStyle as $key => $value) {
                    $method = "set_" . $key;
                    $elementWrapper->getValue()
                        ->$method($value);
                }
            }

            $brizyAccordionItems[] = $brizyAccordionItemComponent;
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
        return 110;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 95;
    }

    protected function getPropertiesMainSection(): array
    {
        return array(
            "mobilePaddingType" => "ungrouped",
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
            "paddingTop" => 10,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 10,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",

            "marginType" => "ungrouped",
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => 10,
            "marginBottomSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",
        );
    }
}
