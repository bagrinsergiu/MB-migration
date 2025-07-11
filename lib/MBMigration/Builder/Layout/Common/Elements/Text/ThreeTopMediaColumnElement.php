<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\Line;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;

class ThreeTopMediaColumnElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;
    use Line;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        try {
            $mbSection = $data->getMbSection();
            $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

            $brizyComponent = $this->getSectionItemComponent($brizySection);
            $elementContext = $data->instanceWithBrizyComponent($brizyComponent);
            $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

            $countCount = count($mbSection['items']);

            $width = 100 / $countCount;

            foreach ((array)$mbSection['items'] as $mbSectionItem) {

                $result = $this->getBgColumnStyles($mbSectionItem['id'], $this->browserPage);

                $brizyColumnItem = new BrizyComponent(json_decode($this->brizyKit['column'], true));

                $brizyColumnItem->getItemValueWithDepth(1, 0)
                    ->set_bgColorHex(ColorConverter::rgba2hex($result['background-color']))
                    ->set_bgColorPalette('')
                    ->set_bgColorType('solid')
                    ->set_bgColorOpacity((int)$result['opacity'])
                    ->set_mobileBgColorType('solid')
                    ->set_mobileBgColorHex(ColorConverter::rgba2hex($result['background-color']))
                    ->set_mobileBgColorOpacity((int)$result['opacity'])
                    ->set_mobileBgColorPalette('');

                foreach ($mbSectionItem['items'] as $mbItem) {
                    if ($mbItem['category'] == 'photo') {
                        $image = $brizyColumnItem->getItemWithDepth(0, 0, 0, 0);
                        $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                            $mbItem,
                            $image
                        );
                        $this->handleRichTextItem(
                            $elementContext,
                            $this->browserPage
                        );
                    }
                }

                foreach ($mbSectionItem['items'] as $mbItem) {
                    if ($mbItem['category'] == 'text') {
                        if ($mbItem['item_type'] === 'title') {
                            $image = $brizyColumnItem->getItemWithDepth(1, 0);
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $image
                            );
                            $this->handleRichTextItem(
                                $elementContext,
                                $this->browserPage
                            );

                            try {
                                $brizyLineItem = new BrizyComponent(json_decode($this->brizyKit['line'], true));
                                $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                    $mbItem,
                                    $image
                                );
                                $this->handleLine($brizyLineItem, $elementContext, $this->browserPage, $mbItem['id']. ':after');


                            } catch (Exception $e) {
                                Logger::instance()->info('line utilization is not foreseen');
                            }

                        }
                    }
                }

                foreach ($mbSectionItem['items'] as $mbItem) {
                    if ($mbItem['category'] == 'text') {
                        if ($mbItem['item_type'] === 'body') {
                            $image = $brizyColumnItem->getItemWithDepth(1, 0);
                            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                                $mbItem,
                                $image
                            );
                            $this->handleRichTextItem(
                                $elementContext,
                                $this->browserPage
                            );
                        }
                    }
                }

                foreach ($mbSectionItem['items'] as $mbItem) {
                    if ($mbItem['category'] == 'button') {
                        $image = $brizyColumnItem->getItemWithDepth(1, 0);
                        $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                            $mbItem,
                            $image
                        );

                        $this->handleButton($elementContext, $this->browserPage, $this->brizyKit, null, $mbItem['id'], $this->getCustomStylesForButton());
                    }
                }

                $brizyColumnItem->getValue()->set_width($width);

                $brizySection->getItemWithDepth(0, 0)->getValue()->add_items([$brizyColumnItem]);
            }
        } catch (Exception $e) {

        }

        return $brizySection;
    }

    protected function getCustomStylesForButton(): array
    {
        return [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => -42,
            "marginBottomSuffix" => "px",
            "marginLeft" => 30,
            "marginLeftSuffix" => "px",

            "mobileMarginType" => "ungrouped",
            "mobileMargin" => 0,
            "mobileMarginSuffix" => "px",
            "mobileMarginTop" => 10,
            "mobileMarginTopSuffix" => "px",
            "mobileMarginRight" => 0,
            "mobileMarginRightSuffix" => "px",
            "mobileMarginBottom" => 0,
            "mobileMarginBottomSuffix" => "px",
            "mobileMarginLeft" => 0,
            "mobileMarginLeftSuffix" => "px",
        ];
    }

    protected function getBgColumnStyles(
        $sectionId,
        BrowserPageInterface $browserPage,
        array $families = [],
        string $defaultFont = ''
    )
    {
        $selectorSectionWrapperStyles = '[data-id="' . $sectionId . '"]';
        $properties = [
            'background-color',
            'opacity',
        ];

        return $this->getDomElementStyles(
            $selectorSectionWrapperStyles,
            $properties,
            $browserPage,
            $families,
            $defaultFont
        );
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 50,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}

