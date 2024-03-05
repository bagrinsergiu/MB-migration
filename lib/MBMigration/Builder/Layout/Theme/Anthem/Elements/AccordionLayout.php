<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use MBMigration\Core\Logger;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;

class AccordionLayout extends Element
{

    /**
     * @var VariableCache
     */
    protected $cache;
    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    /**
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->AccordionLayout($elementData);
    }

    /**
     * @throws DOMException
     */
    protected function AccordionLayout(array $sectionData) {
        Logger::instance()->info('Create bloc');

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['accordion-layout'];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objList = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objList->newItem($decoded['list']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $this->textCreation($headItem, $objBlock);
            }
        }
        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $this->textCreation($headItem, $objBlock);
            }
        }

        foreach ($sectionData['items'] as $section) {

            $objItem->newItem($decoded['item']);

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'accordion_title') {
                        $objItem->setting('labelText', preg_replace('/<[^>]*>/', '', $item['content']));
                    }
                }
            }

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'accordion_body') {
                        $this->textCreation($item, $objItem);
                    }
                }
            }
            $objList->item(0)->addItem($objItem->get());
        }
        $objList->item(0)->setting('bgColorHex', $sectionData['style']['background-color']);

        if(!empty($sectionData['style']['accordion'])){
            foreach ( $sectionData['style']['accordion'] as $key => $value){
                $objList->item(0)->setting($key, $value);
            }
        }


        $objBlock->item(0)->addItem($objList->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
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
                            $objBlock->item(0)->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                case 'Wrapper':
                    $objBlock->item(0)->addItem($textItem);
                    break;
            }
        }
    }
}