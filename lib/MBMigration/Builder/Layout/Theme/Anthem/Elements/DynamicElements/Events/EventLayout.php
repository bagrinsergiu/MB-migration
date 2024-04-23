<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Events;


use DOMException;
use Exception;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;


class EventLayout extends DynamicElement
{
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
        $elementName = 'EventGalleryLayout'; //'EventLayout';

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
        $objBlock->item()->item(1)->item()->setting('detailPage', "{{placeholder content='$placeholder'}}");
        $objBlock->item()->item(1)->item()->setting('featuredViewOrder', 3);
        $objBlock->item()->item(1)->item()->setting('calendarViewOrder', 1);

        $block = $this->replaceIdWithRandom($objBlock->get());

        $this->createDetailPage($collectionItemsForDetailPage, $slug, $elementName);
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