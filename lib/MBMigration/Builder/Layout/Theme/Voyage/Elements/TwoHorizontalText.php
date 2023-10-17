<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class TwoHorizontalText extends Element
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
        return $this->TwoHorizontalText($elementData);
    }

    /**
     * @throws DOMException
     */
    protected function TwoHorizontalText($sectionData)
    {
        Utils::log('Create full media', 1, "] [two-horizontal-text");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['two-horizontal-text'];

        $objBlock = new ItemBuilder($decoded['main']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if($item['group'] == 0) {
                if($item['category'] == 'text') {
                    if ($item['category'] == 'text') {
                        if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {
                            $this->textCreation($item['id'], $item['content'], $options, $objBlock);
                        }
                    }

                    if($item['item_type']=='title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($richText);
                    }
                    if($item['item_type']=='body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($richText);
                    }
                }
            }

            if($item['group'] == 1) {
                if($item['category'] == 'text') {
                    if($item['item_type']=='title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($richText);
                    }
                    if($item['item_type']=='body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($richText);
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    private function textCreation($itemID, $content, $options, $objBlock)
    {
        $richText = JS::RichText($itemID, $options['currentPageURL'], $options['fontsFamily']);
        if(!is_array($richText)) {
            $objBlock->item(0)->addItem($this->itemWrapperRichText($richText));
        } else {
            if(!empty($richText['text'])) {
                $objBlock->item(0)->addItem($this->itemWrapperRichText($richText['text']));
            }

            if(!empty($richText['embeds']['persist'])) {
                $result = $this->findEmbeddedPasteDivs($content);
                foreach ($result as $item) {
                    $objBlock->item(0)->addItem($this->embedCode($item));
                }
            }
        }
    }

}