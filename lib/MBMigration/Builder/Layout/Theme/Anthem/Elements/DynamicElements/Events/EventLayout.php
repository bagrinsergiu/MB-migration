<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Events;


use DOMException;
use Exception;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;


class EventLayout extends DynamicElement
{
    /**
     * @throws DOMException
     */
    public function getElement(array $elementData)
    {
        return $this->Calendar($elementData);
    }

    /**
     * @throws DOMException
     * @throws Exception
     */
    private function Calendar(array $sectionData)
    {
        $slug = 'event-calendar';
        $title = 'Event Calendar';
        $elementName = 'EventLayout';

        $objBlock = new ItemBuilder();
        $objHead  = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['dynamic'][$elementName];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);

        if($this->checkArrayPath($sectionData, 'style/background-color')) {
            $blockBg = $sectionData['style']['background-color'];
            $objBlock->item(0)->setting('bgColorPalette','');
            $objBlock->item(0)->setting('mobileBgColorPalette', '');
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
            $objBlock->item(0)->setting('mobileBgColorHex', $blockBg);
        }

        $blockHead = false;

        foreach ($sectionData['head'] as $headItem)
        {
            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objHead);
                $objHead->item()->addItem($this->wrapperLine(
                    [
                        'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                    ]
                ));
            }
        }

        foreach ($sectionData['head'] as $headItem)
        {
            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objHead);
            }
        }

        foreach ($sectionData['items'] as $headItem)
        {
            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objHead);
            }
            $objHead->item()->addItem($this->wrapperLine(
                [
                    'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                ]
            ));
        }

        foreach ($sectionData['items'] as $headItem)
        {
            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objHead);
            }
        }

        $mainCollectionType = $this->cache->get('mainCollectionType');

        if($blockHead) {
            $objBlock->item(0)->addItem($objHead->get(), 0);
            $objBlock->item()->item(1)->item()->setting('source', $mainCollectionType);
        } else {
            $objBlock->item()->item()->item()->setting('source', $mainCollectionType);
        }

        $collectionItemsForDetailPage = $this->cache->get('eventDetailPage');

        if(!$collectionItemsForDetailPage) {
            $collectionItemsForDetailPage = $this->createCollectionItems($mainCollectionType, $slug, $title);
            $this->cache->set('eventDetailPage', $collectionItemsForDetailPage);
        }

        $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $collectionItemsForDetailPage . '" }}"');
        $objBlock->item()->item(1)->item()->setting('eventDetailPage', "{{placeholder content='$placeholder'}}");
        $objBlock->item()->item(1)->item()->setting('featuredViewOrder', 3);
        $objBlock->item()->item(1)->item()->setting('calendarViewOrder', 1);

        $objBlock->item()->item(1)->item()->setting('resultsHeadingColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('resultsHeadingColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('resultsHeadingColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('listPaginationArrowsColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('listPaginationArrowsColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('listPaginationArrowsColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('hoverListPaginationArrowsColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('hoverListPaginationArrowsColorOpacity', 0.75);
        $objBlock->item()->item(1)->item()->setting('hoverListPaginationArrowsColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('listPaginationColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('listPaginationColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('listPaginationColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('calendarHeadingColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('calendarHeadingColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('calendarHeadingColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('calendarDaysColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('calendarDaysColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('calendarDaysColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('eventsColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('eventsColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('eventsColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('listItemTitleColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('listItemTitleColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('listItemTitleColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('hoverListItemTitleColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('hoverListItemTitleColorOpacity', 0.75);
        $objBlock->item()->item(1)->item()->setting('hoverListItemTitleColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('listItemMetaColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('listItemMetaColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('listItemMetaColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('listItemDateColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('listItemDateColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('listItemDateColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('listTitleColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('listTitleColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('listTitleColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('groupingDateColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('groupingDateColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('groupingDateColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('titleColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('hoverTitleColorOpacity', 0.75);
        $objBlock->item()->item(1)->item()->setting('hoverTitleColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('hoverTitleColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('titleColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('titleColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('dateColorHex', $sectionData['settings']['palette']['text']);
        $objBlock->item()->item(1)->item()->setting('dateColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('dateColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('listItemDateBgColorHex', '#000000');
        $objBlock->item()->item(1)->item()->setting('listItemDateBgColorOpacity', 0);
        $objBlock->item()->item(1)->item()->setting('listItemDateBgColorType', 'none');
        $objBlock->item()->item(1)->item()->setting('listItemDateBgColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('detailButtonBgColorHex', $sectionData['settings']['palette']['btn-bg']);
        $objBlock->item()->item(1)->item()->setting('detailButtonBgColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('detailButtonBgColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('detailButtonGradientColorHex', $sectionData['settings']['palette']['btn-text']);
        $objBlock->item()->item(1)->item()->setting('detailButtonGradientColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('detailButtonGradientColorPalette', '');

        $block = $this->replaceIdWithRandom($objBlock->get());

        $this->createDetailPage($collectionItemsForDetailPage, $slug, $elementName, $sectionData['settings']['palette'] ?? []);
        return json_encode($block);
    }

    /**
     * @throws Exception
     */
    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(is_array($embedCode)){
                            $objBlock->item()->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                    foreach ($textItem['value']['items'] as &$iconItem) {
                        if ($iconItem['type'] == 'Button') {
                            $iconItem['value']['borderStyle'] = "none";
                        }
                    }
                    $objBlock->item()->addItem($textItem);
                    break;
                case 'Wrapper':
                    $objBlock->item()->addItem($textItem);
                    break;
            }
        }
    }
}
