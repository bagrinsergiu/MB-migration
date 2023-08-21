<?php

namespace MBMigration\Builder\Layout\Elements\DynamicElement;

use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\Layout\Elements\Element;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class SermonLayoutPlaceholder extends Element
{

    /**
     * @var VariableCache
     */
    private $cache;
    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    public function getElement(array $elementData = [])
    {
        return $this->sermon_layout_placeholder($elementData);
    }

    protected function sermon_layout_placeholder(array $sectionData) {

        $objBlock = new ItemSetter();
        $objHead  = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['dynamic']['sermon_layout_placeholder'];

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

        $slug = 'sermon-list';
        $title = 'Sermon List';

        $collectionItemsForDetailPage = $this->createCollectionItems($mainCollectionType, $slug, $title);

        $block = $this->replaceIdWithRandom($objBlock->get());

        $this->createDetailPage($collectionItemsForDetailPage, $slug);
        return json_encode($block);
    }

    protected function createCollectionItems($mainCollectionType, $slug, $title)
    {
        Utils::log('Create Detail Page: ' . $title, 1, "] [createDetailPage");
        if($this->pageCheck($slug)) {
            $QueryBuilder = $this->cache->getClass('QueryBuilder');
            $createdCollectionItem = $QueryBuilder->createCollectionItem($mainCollectionType, $slug, $title);
            return $createdCollectionItem['id'];
        } else {
            $ListPages = $this->cache->get('ListPages');
            foreach ($ListPages as $listSlug => $collectionItems) {
                if ($listSlug == $slug) {
                    return $collectionItems;
                }
            }
        }
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    protected function createDetailPage($itemsID, $slug) {
        $itemsData = [];
        $jsonDecode = $this->initData();
        $QueryBuilder = $this->cache->getClass('QueryBuilder');

        $decoded = $jsonDecode['dynamic']['sermon_layout_placeholder'];

        $itemsData['items'][] = $this->cache->get('menuBlock');
        $itemsData['items'][] = json_decode($decoded['detail'], true);
        $itemsData['items'][] = $this->cache->get('footerBlock');

        $pageData = json_encode($itemsData);

        $QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);
    }


}