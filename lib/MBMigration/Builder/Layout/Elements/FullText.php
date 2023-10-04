<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class FullText extends Element
{
    /**
     * @var VariableCache
     */
    protected $cache;
    private $jsonDecode;

    /**
     * @var array
     */
    protected $sectionData;

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
        $this->sectionData = $elementData;
        return $this->FullText($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function FullText(array $sectionData)
    {
        Utils::log('Create bloc', 1, "full_text");

        $options = [];

        $objBlock = new ItemBuilder();
        $objLine = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-text'];

        $objBlock->newItem($decoded['main']);
        $objLine->newItem($decoded['line']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $objBlock->item(0)->item(0)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {

                    $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                    $objBlock->item(0)->addItem($this->itemWrapperRichText($richText));

//                    $objBlock->item(0)->item(0)->item(0)->setText($richText);

                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'body' && $this->showBody($sectionData)) {

                    $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);

                    $objBlock->item(0)->addItem($this->itemWrapperRichText($richText));

//                    $objBlock->item(0)->item(1)->item(0)->setText($richText);
                }
            }
        }
        return json_encode($this->replaceIdWithRandom($objBlock->get()));
    }
}