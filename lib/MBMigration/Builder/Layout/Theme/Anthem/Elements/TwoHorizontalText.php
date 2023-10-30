<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

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
     * @throws \Exception
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
            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {
                        $this->textCreation($item['id'], $item['content'], $options, $objBlock);
                        $objBlock->item()->item()->item()->addItem(
                            $this->wrapperLine(['borderColorHex' => $options['borderColorHex']])
                        );
                    }
                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'body' && $this->showBody($sectionData)) {
                        $this->textCreation($item['id'], $item['content'], $options, $objBlock);
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if($item['group'] == 1) {
                if($item['category'] == 'text') {
                    if($item['item_type']=='title' && $this->showHeader($sectionData)) {
                        $this->textCreation($item['id'], $item['content'], $options, $objBlock, 1);
                        $objBlock->item()->item()->item(1)->addItem($this->wrapperLine(['borderColorHex' => $options['borderColorHex']]));
                    }
                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if($item['group'] == 1) {
                if($item['category'] == 'text') {
                    if($item['item_type']=='body' && $this->showBody($sectionData)) {
                        $this->textCreation($item['id'], $item['content'], $options, $objBlock, 1);
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws \Exception
     */
//    private function textCreation($itemID, $content, $options, $objBlock, $setId = 0)
//    {
//        $richText = JS::RichText($itemID, $options['currentPageURL'], $options['fontsFamily']);
//        if(!is_array($richText)) {
//            $objBlock->item(0)->item(0)->item($setId)->addItem($this->itemWrapperRichText($richText));
//        } else {
//            if(!empty($richText['text'])) {
//                $objBlock->item(0)->item(0)->item($setId)->addItem($this->itemWrapperRichText($richText));
//            }
//
//            if(!empty($richText['embeds']['persist'])) {
//                $result = $this->findEmbeddedPasteDivs($content);
//                foreach ($result as $item) {
//                    $objBlock->item(0)->item(0)->item($setId)->addItem($this->embedCode($item));
//                }
//            }
//        }
//    }

    /**
     * @throws \Exception
     */
    private function textCreation($itemID, $content, $options, $objBlock, $setId = 0)
    {
        $multiElement = [];

        $richText = JS::RichText($itemID, $options['currentPageURL'], $options['fontsFamily']);

        if(!is_array($richText)) {
            $objBlock->item(0)->item(0)->item($setId)->addItem($this->itemWrapperRichText($richText));
        } else {
            if(!empty($richText['icons'])) {
                foreach ($richText['icons'] as $itemIcon) {
                    if ($itemIcon['position'] === 'top') {
                        $multiElement[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                    }
                }
            }

            if(!empty($richText['buttons'])) {
                foreach ($richText['buttons'] as $itemButton) {
                    if ($itemButton['position'] === 'top') {
                        $multiElement[] = $this->button($itemButton['items'], $itemButton['align']);
                    }
                }
            }

            if(!empty($richText['text'])) {
                $multiElement[] = $this->itemWrapperRichText($richText['text']);
            }

            if(!empty($richText['embeds']['persist'])) {
                $result = $this->findEmbeddedPasteDivs($content);
                foreach ($result as $item) {
                    $multiElement[] = $this->embedCode($item);
                }
            }

            if(!empty($richText['buttons'])) {
                foreach ($richText['buttons'] as $itemButton) {
                    if ($itemButton['position'] === 'middle') {
                        foreach ($itemButton['items'] as $item) {
                            $multiElement[] = $this->button($item, $itemButton['align']);
                        }
                    }
                }
            }

            if(!empty($richText['icons'])) {
                foreach ($richText['icons'] as $itemIcon) {
                    if ($itemIcon['position'] === 'bottom') {
                        $multiElement[] = $this->wrapperColumn($multiElement, true);
                    }
                }
            }

            if(!empty($richText['buttons'])) {
                foreach ($richText['button'] as $itemButton) {
                    if ($itemButton['position'] === 'bottom') {
                        $multiElement[] = $this->button($itemButton['items'], $itemButton['align']);
                    }
                }
            }

            $objBlock->item(0)->item(0)->item($setId)->addItem($this->wrapperColumn($multiElement, true));
        }
    }

}