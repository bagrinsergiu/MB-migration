<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Forms;

use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Form extends DynamicElement
{
    /**
     * @throws \DOMException
     */
    public function getElement(array $elementData = [])
    {
        $this->sectionData = $elementData;
        return $this->formElement($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function formElement(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Form");

//        $options = [];
//
//        $objBlock = new ItemBuilder();
//        $objLine = new ItemBuilder();
//
//        $this->cache->set('currentSectionData', $sectionData);
//
//        $decoded = $this->jsonDecode['blocks']['full-text'];
//
//        $objBlock->newItem($decoded['main']);
//        $objLine->newItem($decoded['line']);
//
//        $this->generalParameters($objBlock, $options, $sectionData);
//
//        $this->backgroundParallax($objBlock, $sectionData);
//
//        $this->backgroundColor($objBlock, $sectionData);
//
//        $this->backgroundImages($objBlock, $sectionData);
//
//        $this->backgroundVideo($objBlock, $sectionData);
//
//        $this->setOptionsForTextColor($sectionData, $options);
//
//        foreach ($sectionData['items'] as $item) {
//            if ($item['category'] == 'text') {
//                if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {
//                    $this->textCreation($item, $objBlock);
//                    $objBlock->item(0)->addItem($this->wrapperLine(['borderColorHex' => $sectionData['style']['borderColorHex']]));
//                }
//            }
//        }
//
//        foreach ($sectionData['items'] as $item) {
//            if ($item['category'] == 'text') {
//                if ($item['item_type'] === 'body' && $this->showBody($sectionData)) {
//                    $this->textCreation($item, $objBlock);
//                }
//            }
//        }
//
//        if ($sectionData['category'] == 'donation' && $this->checkArrayPath($sectionData, 'settings/sections/donations')) {
//
//            $buttonOptions = [
//                'linkExternal'=> $sectionData['settings']['sections']['donations']['url'],
//                'text'=>  $sectionData['settings']['sections']['donations']['text']
//            ];
//            $position = $sectionData['settings']['sections']['donations']['alignment'];
//
//            $objBlock->item(0)->addItem($this->button($buttonOptions, $position));
//        }

        return json_encode($this->wrapperForm());
    }

    /**
     * @throws \Exception
     */
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