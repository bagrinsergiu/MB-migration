<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Sermons;

use DOMException;
use Exception;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;

class GridMediaLayout extends DynamicElement
{

    /**
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->GridMediaLayout($elementData);
    }

    /**
     * @throws DOMException
     * @throws Exception
     */
    private function GridMediaLayout(array $sectionData)
    {
        $options = [];
        $slug = 'sermon-grid';
        $title = 'Sermon Grid';
        $elementName = 'GridMediaLayout';

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
                $this->textCreation($headItem, $objHead);
                $blockHead = true;
                $objHead->item()->addItem($this->wrapperLine(
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

        $collectionItemsForDetailPage = $this->cache->get('sermonsDetailPage');

        if(!$collectionItemsForDetailPage) {
            $collectionItemsForDetailPage = $this->createCollectionItems($mainCollectionType, $slug, $title);
            $this->cache->set('sermonsDetailPage', $collectionItemsForDetailPage);
        }

        $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $collectionItemsForDetailPage . '" }}"');
        $objBlock->item()->item(1)->item()->setting('detailPage', "{{placeholder content='$placeholder'}}");

        $objBlock->item()->item(1)->item()->setting('defaultCategory', $currentPageSlug);

        $objBlock->item()->item(1)->item()->setting('parentCategory', $currentPageSlug);

        $objBlock->item()->item(1)->item()->setting('showCategoryFilter', "off");

        $objBlock->item()->item(1)->item()->setting('titleColorHex', $sectionData['style']['sermon']['text'] ?? "#1e1eb7");
        $objBlock->item()->item(1)->item()->setting('titleColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('titleColorPalette', "");

        $objBlock->item()->item(1)->item()->setting('hoverTitleColorHex', $sectionData['style']['sermon']['text'] ?? "#1e1eb7");
        $objBlock->item()->item(1)->item()->setting('hoverTitleColorOpacity', 0.7);
        $objBlock->item()->item(1)->item()->setting('hoverTitleColorPalette', "");

        $objBlock->item()->item(1)->item()->setting('metaLinksColorHex', $sectionData['settings']['palette']['link'] ?? "#3d79ff");
        $objBlock->item()->item(1)->item()->setting('metaLinksColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('metaLinksColorPalette', "");

        $objBlock->item()->item(1)->item()->setting('hoverMetaLinksColorHex', $sectionData['settings']['palette']['link'] ?? "#3d79ff");
        $objBlock->item()->item(1)->item()->setting('hoverMetaLinksColorOpacity', 0.7);
        $objBlock->item()->item(1)->item()->setting('hoverMetaLinksColorPalette', "");

        $objBlock->item()->item(1)->item()->setting('filterBgColorHex', $sectionData['style']['sermon']['bg'] ?? '#616161');
        $objBlock->item()->item(1)->item()->setting('filterBgColorOpacity', floatval($sectionData['style']['sermon']['bg-opacity'] ?? 1) );
        $objBlock->item()->item(1)->item()->setting('filterBgColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('paginationColorHex', $sectionData['style']['sermon']['pagination-normal'] ?? '#707070');
        $objBlock->item()->item(1)->item()->setting('paginationColorOpacity', floatval($sectionData['style']['sermon']['opacity-pagination-normal'] ?? 1));
        $objBlock->item()->item(1)->item()->setting('paginationColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('activePaginationColorHex', $sectionData['style']['sermon']['pagination-active'] ?? "#131313" );
        $objBlock->item()->item(1)->item()->setting('activePaginationColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('activePaginationColorPalette', '');

        $objBlock->item()->item(1)->item()->setting('hoverPaginationColorHex', $sectionData['style']['sermon']['pagination-normal'] ?? "#707070");
        $objBlock->item()->item(1)->item()->setting('hoverPaginationColorOpacity', 0.75);
        $objBlock->item()->item(1)->item()->setting('hoverPaginationColorPalette', '');


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
                        if(!empty($embedCode)){
                            $objBlock->item()->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                    $textItem['value']['mobileHorizontalAlign'] = 'center';
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
