<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Events;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;

class EventGridLayout extends DynamicElement
{


    public function getElement(array $elementData)
    {
        return $this->Grid($elementData);
    }

    private function Grid(array $sectionData)
    {

        $slug = 'event-grid';
        $title = 'Event grid';
        $elementName = 'EventGridLayout';

        $objBlock = new ItemBuilder();
        $objHead  = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['dynamic'][$elementName];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);

        if($this->checkArrayPath($sectionData, 'settings/sections/color/bg')) {
            $blockBg = $sectionData['settings']['sections']['color']['bg'];
            $objBlock->item(0)->setting('bgColorPalette','');
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        }

        $blockHead = false;
        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            $show_header = true;
            $show_body = true;

            if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                $show_header = $sectionData['settings']['sections']['list']['show_header'];
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                $show_body = $sectionData['settings']['sections']['list']['show_body'];
            }

            if ($headItem['item_type'] === 'title' && $show_header) {
                $blockHead = true;
                $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], [ 'sectionType' => 'brz-tp-lg-heading1', 'bgColor' => $blockBg])), 0);
            }

            if ($headItem['item_type'] === 'body' && $show_body) {
                $blockHead = true;
                $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], [ 'sectionType' => 'brz-tp-lg-heading1', 'bgColor' => $blockBg])));
            }
        }

        $mainCollectionType = $this->cache->get('mainCollectionType');

        if($blockHead) {
            $objBlock->item(0)->addItem($objHead->get(), 0);
            $objBlock->item()->item(1)->item()->setting('source', $mainCollectionType);
        } else {
            $objBlock->item()->item()->item()->setting('source', $mainCollectionType);
        }

        $collectionItemsForDetailPage = $this->createCollectionItems($mainCollectionType, $slug, $title);

        $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $collectionItemsForDetailPage . '" }}"');
        $objBlock->item()->item()->item()->setting('detailPage', "{{placeholder content='$placeholder'}}");


        $block = $this->replaceIdWithRandom($objBlock->get());

        $this->createDetailPage($collectionItemsForDetailPage, $slug, $elementName);
        return json_encode($block);
    }
}