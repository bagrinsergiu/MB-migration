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
     * @throws \Exception
     */
    protected function ThreeHorizontalText($sectionData)
    {
        \MBMigration\Core\Logger::instance()->info('Create full media');

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

            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $this->textCreation($item, $objBlock);
//                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($richText);
                    }
                    if ($item['item_type'] == 'body') {

                        $this->textCreation($item, $objBlock);
//                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($richText);
                    }
                }
            }
            if ($item['group'] == 1) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $this->textCreation($item, $objBlock, 1);
//                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($richText);
                    }
                    if ($item['item_type'] == 'body') {
                        $this->textCreation($item, $objBlock, 1);
//                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($richText);
                    }
                }
            }
            if ($item['group'] == 2) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $this->textCreation($item, $objBlock, 2);
//                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objBlock->item(0)->item(0)->item(2)->item(0)->item(0)->setText($richText);
                    }
                    if ($item['item_type'] == 'body') {
                        $this->textCreation($item, $objBlock, 2);
//                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objBlock->item(0)->item(0)->item(2)->item(2)->item(0)->setText($richText);
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
    private function textCreation($sectionData, $objBlock, $level = 0)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if (!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if (is_array($embedCode)) {
                            $objBlock->item(0)->item(0)->item($level)->addItem($this->embedCode($embedCode[$i]));
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
                    $objBlock->item(0)->item(0)->item($level)->addItem($textItem);
                    break;
                case 'Wrapper':
                    $objBlock->item(0)->item(0)->item($level)->addItem($textItem);
                    break;
            }
        }
    }
}