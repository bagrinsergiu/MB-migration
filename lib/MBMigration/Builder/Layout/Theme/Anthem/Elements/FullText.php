<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

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

        $this->backgroundColor($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData);

        $this->backgroundVideo($objBlock, $sectionData);

        $this->setOptionsForTextColor($sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {
                    $this->textCreation($item, $objBlock, $sectionData['style']);
                    $objBlock->item(0)->addItem($this->wrapperLine(
                        [
                            'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                        ]
                    ));
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'body' && $this->showBody($sectionData)) {
                    $this->textCreation($item, $objBlock, $sectionData['style']);
                }
            }
        }

        if ($sectionData['category'] == 'donation' && $this->checkArrayPath($sectionData, 'settings/sections/donations')) {

            if (isset($sectionData['settings']['sections']['donations']['url']) &&
                isset($sectionData['settings']['sections']['donations']['text'])) {

                $buttonOptions = [
                    'linkExternal' => $sectionData['settings']['sections']['donations']['url'],
                    'text' => $sectionData['settings']['sections']['donations']['text']
                ];
                $position = $sectionData['settings']['sections']['donations']['alignment'];

                $objBlock->item()->addItem($this->button($buttonOptions, $position));
            }
        }

        return json_encode($this->replaceIdWithRandom($objBlock->get()));
    }

    /**
     * @throws \Exception
     */
    private function textCreation($sectionData, $objBlock, $style)
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