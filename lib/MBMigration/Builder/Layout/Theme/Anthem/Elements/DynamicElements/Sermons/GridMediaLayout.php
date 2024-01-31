<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Sermons;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;
use MBMigration\Parser\JS;

class GridMediaLayout extends DynamicElement
{

    /**
     * @throws \DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->GridMediaLayout($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    private function GridMediaLayout(array $sectionData)
    {
        $options = [];
        $slug = 'sermon-grid';
        $title = 'Sermon Grid';
        $elementName = 'GridMediaLayout';

        $objBlock = new ItemBuilder();
        $objHead  = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['dynamic'][$elementName];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $blockHead = false;
        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem['id'], $headItem['content'], $options, $objBlock);
                $objBlock->item()->addItem($this->wrapperLine(['borderColorHex' => $options['borderColorHex']]));
            }

            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
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

        $block = $this->replaceIdWithRandom($objBlock->get());

        $this->createDetailPage($collectionItemsForDetailPage, $slug, $elementName);
        return json_encode($block);
    }


    private function textCreation($sectionData, $objBlock, $setId = 0)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(!empty($embedCode)){
                            $objBlock->item(0)->item(0)->item($setId)->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                    $textItem['value']['mobileHorizontalAlign'] = 'center';

                    foreach ($textItem['value']['items'] as &$iconItem) {
//                        if ($iconItem['type'] == 'Icon') {
//                            $iconItem['value']['hoverColorHex'] = $sectionData['style']['hover']['icon'] ?? '';
//                        }

                        if ($iconItem['type'] == 'Button') {
                            $iconItem['value']['borderStyle'] = "none";
                        }

                    }
                    $objBlock->item(0)->item(0)->item($setId)->addItem($textItem);
                    break;
                case 'Wrapper':
                    $objBlock->item(0)->item(0)->item($setId)->addItem($textItem);
                    break;
            }
        }
    }

}