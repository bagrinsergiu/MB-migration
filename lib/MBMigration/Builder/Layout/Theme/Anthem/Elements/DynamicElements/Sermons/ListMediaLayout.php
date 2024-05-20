<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Sermons;

use Exception;
use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;
use MBMigration\Builder\VariableCache;

class ListMediaLayout extends DynamicElement
{

    /**
     * @throws Exception
     */
    public function getElement(array $elementData = [])
    {
        $this->cache = VariableCache::getInstance();
        return $this->listMedia($elementData);
    }

    /**
     * @throws DOMException
     * @throws Exception
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

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->backgroundColor($objBlock, $sectionData);

        $blockHead = false;

        foreach ($sectionData['head'] as $headItem)
        {
            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objBlock);
                $objBlock->item()->addItem($this->wrapperLine(
                    [
                        'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                    ]
                ));
            }
        }

        foreach ($sectionData['head'] as $headItem)
        {
            if ($headItem['item_type'] === 'body' && $this->showHeader($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objBlock);
            }
        }

        foreach ($sectionData['items'] as $headItem)
        {
            if ($headItem['item_type'] === 'title' && $this->showBody($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objBlock);
                $objBlock->item()->addItem($this->wrapperLine(
                    [
                        'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                    ]
                ));
            }
        }

        foreach ($sectionData['items'] as $headItem)
        {
            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objBlock);
            }
        }

        $mainCollectionType = $this->cache->get('mainCollectionType');

        if($blockHead) {
            $objBlock->item()->addItem($objHead->get(), 0);
        }

        $collectionItemsForDetailPage = $this->createCollectionItems($mainCollectionType, $slug, $title);

        $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $collectionItemsForDetailPage . '" }}"');

        $objBlock->item()->addItem(
            $this->wrapperSermon(
                [
                    'source' => $mainCollectionType,
                    'detailPage' => "{{placeholder content='$placeholder'}}",
                    'defaultCategory' => $currentPageSlug
                ])
        );

        if($sectionData['settings']['mediaGridContainer']) {
            $objBlock->item()->setting('searchParam', $sectionData['settings']['containTitle'] ?? '');
        }

        $block = $this->replaceIdWithRandom($objBlock->get());

        $this->createDetailPage($collectionItemsForDetailPage, $slug, $elementName, $sectionData['settings']['palette'] ?? []);
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
