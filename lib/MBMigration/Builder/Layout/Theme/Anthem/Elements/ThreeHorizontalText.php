<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class ThreeHorizontalText extends Element
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
        return $this->ThreeHorizontalText($elementData);
    }


    /**
     * @throws \DOMException
     */
    protected function ThreeHorizontalText($sectionData)
    {
        Utils::log('Create full media', 1, "three-horizontal-text");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['three-horizontal-text'];

        $objBlock = new ItemBuilder($decoded['main']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);


        foreach ($sectionData['items'] as $item) {

            if($item['group'] == 0){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($richText);
                    }
                    if($item['item_type']=='body'){
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($richText);
                    }
                }
            }
            if($item['group'] == 1){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($richText);
                    }
                    if($item['item_type']=='body'){
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($richText);
                    }
                }
            }
            if($item['group'] == 2){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(2)->item(0)->item(0)->setText($richText);
                    }
                    if($item['item_type']=='body'){
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objBlock->item(0)->item(0)->item(2)->item(2)->item(0)->setText($richText);
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}