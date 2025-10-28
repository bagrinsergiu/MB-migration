<?php

namespace MBMigration\Builder\Layout\Theme\Tradition\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullTextElement;
use MBMigration\Builder\Utils\ColorConverter;

class HorizontalTexts extends FullTextElement
{
    private $count = 0;

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, $this->count++);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $mbSection = $data->getMbSection();
        $showButton = isset($mbSection['settings']['sections']['text']['show_buttons']) && $mbSection['settings']['sections']['text']['show_buttons'] == true;

        $filteredItems = [];
        foreach ($mbSection['items'] as $item) {
            if (!$showButton && isset($item['category']) && $item['category'] === 'button') {
                continue;
            }
            $filteredItems[] = $item;
        }

        $items = $this->sortItems($filteredItems);
        $groups = $this->groupItems($items);

        foreach ($groups as $i => $group) {
            $textComponent = $this->getTextContainerComponent($brizySection);
            $mbSection['items'] = $group;
            $elementContext = $data->instanceWithBrizyComponentAndMBSection($mbSection, $textComponent);

            $styles = $this->browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => '[data-id="' . $mbSection['sectionId'] . '"] .group-' . $i,
                    'styleProperties' => ['background-color']
                ]);

            $color = ColorConverter::convertColorRgbToHex($styles['data']['background-color']);
            $textComponent->setBgColorStyle($color, 1);
            $textComponent->setMobileBgColorStyle($color, 1);
            $this->handleRichTextItems($elementContext, $this->browserPage);
        }

        $sectionItemComponent->getValue()->set_paddingType('grouped');
        $sectionItemComponent->getValue()->set_padding(0);

        $brizySection->getValue()->set_sectionHeightStyle('auto');
        $brizySection->getValue()->set_sectionHeight(0);
        $brizySection->getValue()->set_paddingType('grouped');
        $brizySection->getValue()->set_padding(0);
        return $brizySection;
    }
}
