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

        $this->backgroundColor($objBlock, $sectionData);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {
                        $this->textCreation($item, $objBlock);
//                        $objBlock->item()->item()->item()->addItem(
//                            $this->wrapperLine(
//                                [
//                                    'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
//                                ]
//                            )
//                        );
                    }
                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'body' && $this->showBody($sectionData)) {
                        $this->textCreation($item, $objBlock);
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if($item['group'] == 1) {
                if($item['category'] == 'text') {
                    if($item['item_type']=='title' && $this->showHeader($sectionData)) {
                        $this->textCreation($item, $objBlock, 1);
//                        $objBlock->item()->item()->item(1)
//                            ->addItem($this->wrapperLine([
//                                $sectionData['style']['border']['border-bottom-color'] ?? ''
//                            ]));
                    }
                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if($item['group'] == 1) {
                if($item['category'] == 'text') {
                    if($item['item_type']=='body' && $this->showBody($sectionData)) {
                        $this->textCreation($item, $objBlock, 1);
                    }
                }
            }
        }

        $objBlock->item(0)->item(0)->item(1)->setting("mobileBorderWidthType", 'ungrouped');
        $objBlock->item(0)->item(0)->item(1)->setting("mobileBorderTopWidth", 1);
        $objBlock->item(0)->item(0)->item(1)->setting("mobileBorderBottomWidth", 0);
        $objBlock->item(0)->item(0)->item(1)->setting("mobileBorderTopWidth", 0);
        $objBlock->item(0)->item(0)->item(1)->setting("mobileBorderLeftWidth", 0);

        $objBlock->item(0)->item(0)->item(1)->setting("borderColorOpacity", 1);
        $objBlock->item(0)->item(0)->item(1)->setting("borderLeftWidth", 1);
        $objBlock->item(0)->item(0)->item(1)->setting("borderColorPalette",  '');

        if(isset($sectionData['style']['vertical-border']['border-color'])) {
            $objBlock->item(0)->item(0)->item(1)->setting("mobileBorderColorHex",  $sectionData['style']['vertical-border']['border-color']);
            $objBlock->item(0)->item(0)->item(1)->setting("borderColorHex",  $sectionData['style']['vertical-border']['border-color']);
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