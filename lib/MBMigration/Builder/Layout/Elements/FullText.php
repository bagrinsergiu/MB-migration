<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class FullText extends Element
{
    /**
     * @var VariableCache
     */
    private $cache;
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
     */
    protected function FullText(array $sectionData)
    {
        Utils::log('Create bloc', 1, "] [full_text");

        $options = [];
        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-text'];

        $objBlock->newItem($decoded['main']);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $objBlock->item(0)->item(0)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {

                    $this->setOptionsForUsedFonts($item, $options);

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                    $objBlock->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if ($item['item_type'] === 'body' && $this->showBody($sectionData)) {

                    $this->setOptionsForUsedFonts($item, $options);

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                    $objBlock->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
        }

        return json_encode($this->replaceIdWithRandom($objBlock->get()));
    }
}