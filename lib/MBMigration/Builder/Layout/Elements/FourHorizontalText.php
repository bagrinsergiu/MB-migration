<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class FourHorizontalText extends Element
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
     * @throws \DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->FourHorizontalText($elementData);
    }

    /**
     * @throws \DOMException
     */
    protected function FourHorizontalText($sectionData)
    {
        Utils::log('Create four horizontal text', 1, "four-horizontal-text");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['four-horizontal-text'];

        $objBlock = new ItemBuilder($decoded['main']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);
        
        $this->backgroundImages($objBlock, $sectionData, $options);
        
        $this->setOptionsForTextColor($sectionData,  $options);

        foreach ($sectionData['items'] as $item) {
            if($item['group'] == 0){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item()->item()->item()->setText($richText);
                    }
                    if($item['item_type']=='body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item()->item(2)->item()->setText($richText);
                    }
                }
            }
            if($item['group'] == 1){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item(1)->item()->item()->setText($richText);
                    }
                    if($item['item_type']=='body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item(1)->item(2)->item()->setText($richText);
                    }
                }
            }
            if($item['group'] == 2){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item(2)->item()->item()->setText($richText);
                    }
                    if($item['item_type']=='body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item(2)->item(2)->item()->setText($richText);
                    }
                }
            }
            if($item['group'] == 3){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item(3)->item()->item()->setText($richText);
                    }
                    if($item['item_type']=='body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item()->item()->item(3)->item(2)->item()->setText($richText);
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }


}