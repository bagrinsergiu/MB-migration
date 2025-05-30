<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements\Text;

use Exception;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\ThreeHorizontalTextElement;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

class ThreeHorizontalText extends ThreeHorizontalTextElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;

    /**
     * @throws BrowserScriptException
     * @throws Exception
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $colJson = json_decode($this->brizyKit['column'], true);

        $mbElements = $this->sortItemsInGroups($mbSection);

        foreach ($mbElements as $mbElement) {

            $brizySectionCol = new BrizyComponent($colJson);

            if (!$this->checkColumnContent($mbElement))
            {
                continue;
            }

            foreach ($mbElement as $mbItem) {

                // add the text on the left side of th bock
                $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                    $mbItem,
                    $brizySectionCol
                );
                $this->handleRichTextItem(
                    $elementContext,
                    $this->browserPage
                );
            }

            $brizySection->getItemWithDepth(0, 0)->getValue()->add_items([$brizySectionCol]);

        }

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;
    }

    private function checkColumnContent(array $contentColumn): bool
    {
        if (empty($contentColumn)) {
            return false;
        }

        foreach ($contentColumn as $item) {
            if ($item['category'] === 'photo' && !empty($item['content'])) {
                return true;
            }

            if ($item['category'] === 'text' && !empty($item['content'])) {
                $textContent = strip_tags($item['content']);
                $textContent = trim($textContent);
                if (!empty($textContent)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function sortItemsInGroups($mbSection): array
    {
        $sortedItems = [];

        foreach ($mbSection['items'] as $mbItem) {
            if (!$this->canShowHeader($mbSection) && $mbItem['item_type'] == 'title') {
                continue;
            }
            if (!$this->canShowBody($mbSection) && $mbItem['item_type'] == 'body') {
                continue;
            }

            $sortedItems[$mbItem['group']][] = $mbItem;
        }
        ksort($sortedItems);

        return $sortedItems;
    }

}
