<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Utils\ColorConverter;

abstract class MiddleMediaTopElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;

    protected function getSectionName(): string
    {
        return "Middle Media Top";
    }

    /**
     * @throws BadJsonProvided
     * @throws \Exception
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['sectionTop'], true));
        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleStyle($elementContext, $this->browserPage, $additionalOptions);

        return $brizySection;
    }

    private function handleStyle(ElementContextInterface $data, BrowserPageInterface $browserPage, $additionalOptions = []): BrizyComponent
    {
        $section = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $sectionStyles = $this->getSectionListStyle($data, $browserPage);
        $selector = '[data-id="' . $section['sectionId'] . '"]' . " div.bg-eclipse";

        $styleList = $this->getDomElementStyles(
            $selector,
            [
                'background-color',
                'height'
            ],
            $this->browserPage
        );

        $brizySection->addPadding(
            (int)$sectionStyles['padding-top'] ?? 0,
            (int)$sectionStyles['padding-right'] ?? 0,
            (int)$sectionStyles['padding-bottom'] ?? 0,
            (int)$sectionStyles['padding-left'] ?? 0
        );

        $brizySection->getValue()
            ->set_bgColorHex(ColorConverter::rgba2hex($styleList['background-color']))
            ->set_bgColorPalette('')
            ->set_bgColorType('solid')
            ->set_bgColorOpacity($styleList['opacity'] ?? 1);

        return $brizySection;
    }

    protected function generalSectionBehavior(ElementContextInterface $data, ?BrizyComponent $section): void
    {
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
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    abstract protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent;
}
