<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Element\FooterElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class Footer extends FooterElement
{
    use CssPropertyExtractorAware;

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTopSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getBottomSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(1);
    }


    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {

            $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
            $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
            $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);
            $this->handleSectionStyles($elementContext, $this->browserPage);

            $mbSectionItem = $data->getMbSection();

            // sort items
            $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

            // top group
            $mbItem = $mbSectionItem['items'][0];
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbItem,
                $this->getTopSectionItemComponent($brizySectionItemComponent)->getItemWithDepth(0)
            );
            $this->handleRichTextItem(
                $elementContext,
                $this->browserPage
            );
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbItem,
                $this->getTopSectionItemComponent($brizySectionItemComponent)
            );
            $this->handleGroupBg(
                $elementContext,
                $mbSectionItem['sectionId'],
                0,
                $this->browserPage,
                $data->getFontFamilies(),
                $data->getDefaultFontFamily()
            );

            // bottom groups
            $mbItem = $mbSectionItem['items'][1];
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbItem,
                $this->getBottomSectionItemComponent($brizySectionItemComponent)->getItemWithDepth(0)
            );
            $this->handleRichTextItem(
                $elementContext,
                $this->browserPage
            );
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbItem,
                $this->getBottomSectionItemComponent($brizySectionItemComponent)
            );
            $this->handleGroupBg(
                $elementContext,
                $mbSectionItem['sectionId'],
                1,
                $this->browserPage,
                $data->getFontFamilies(),
                $data->getDefaultFontFamily()
            );

            return $brizySection;
        });
    }

    protected function handleGroupBg(
        ElementContextInterface $data,
        $sectionId,
        $groupIndex,
        BrowserPageInterface $browserPage,
        array $families,
        string $defaultFont
    ) {
        try {
            $selectorSectionWrapperStyles = '[data-id="'.$sectionId.'"] .group-'.$groupIndex;
            $properties = [
                'background-color',
                'opacity',
            ];

            $styles = $this->getDomElementStyles(
                $selectorSectionWrapperStyles,
                $properties,
                $browserPage,
                $families,
                $defaultFont
            );

            $backgroundColorHex = ColorConverter::rgba2hex($styles['background-color']);
            $backgroundOpacity = ColorConverter::rgba2opacity($styles['background-color']);

            $brizyComponent = $data->getBrizySection();

            $brizyComponent->getValue()
                ->set_bgColorHex($backgroundColorHex)
                ->set_bgColorOpacity($backgroundOpacity);
        } catch (Exception $e) {

        }
    }
}
