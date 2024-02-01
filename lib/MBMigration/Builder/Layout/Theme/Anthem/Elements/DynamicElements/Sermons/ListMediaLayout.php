<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Sermons;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;
use MBMigration\Builder\VariableCache;

class ListMediaLayout extends DynamicElement
{

    /**
     * @throws \Exception
     */
    public function getElement(array $elementData = [])
    {
        $this->cache = VariableCache::getInstance();
        return $this->listMedia($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function listMedia(array $sectionData) {

        $slug = 'sermon-list';
        $title = 'Sermon List';
        $elementName = 'ListMediaLayout';

        $currentPageSlug = $this->cache->get('tookPage')['slug'];

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

        $objBlock->item()->item()->item()->setting('detailPage', '{{ brizy_dc_url_post id="' . $collectionItemsForDetailPage . '" }} "');
        $objBlock->item()->item()->item()->setting('defaultCategory', $currentPageSlug);

        $block = $this->replaceIdWithRandom($objBlock->get());

        $this->createDetailPage($collectionItemsForDetailPage, $slug, $elementName);
        return json_encode($block);
    }

    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(is_array($embedCode)){
                            $objBlock->item()->item(1)->item()->addItem($this->embedCode($embedCode[$i]));
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
                    $objBlock->item()->item(1)->item()->addItem($textItem);
                    break;
                case 'Wrapper':
                    $objBlock->item()->item(1)->item()->addItem($textItem);
                    break;
            }
        }
    }
}