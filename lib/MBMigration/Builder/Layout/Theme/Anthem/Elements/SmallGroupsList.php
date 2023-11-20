<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class SmallGroupsList extends Element
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
        return $this->SmallGroupsList($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function SmallGroupsList(array $sectionData)
    {
        Utils::log('Create bloc', 1, "small-groups-list");

        $options = [];

        $objBlock = new ItemBuilder();
        $objLine = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['small-groups-list'];

        $objBlock->newItem($decoded['main']);
        $objLine->newItem($decoded['line']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $this->backgroundVideo($objBlock, $sectionData);

        $this->setOptionsForTextColor($sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {
                    $this->textCreation($item['id'], $item['content'], $options, $objBlock);
                    $objBlock->item(0)->addItem($this->wrapperLine(['borderColorHex' => $options['border']['border-bottom-color'] ?? '']));
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'body' && $this->showBody($sectionData)) {
                    $this->textCreation($item['id'], $item['content'], $options, $objBlock);
                }
            }
        }

        if ($sectionData['category'] == 'donation' && $this->checkArrayPath($sectionData, 'settings/sections/donations')) {

            $buttonOptions = [
                'linkExternal'=> $sectionData['settings']['sections']['donations']['url'],
                'text'=>  $sectionData['settings']['sections']['donations']['text']
            ];
            $position = $sectionData['settings']['sections']['donations']['alignment'];

            $objBlock->item(0)->addItem($this->button($buttonOptions, $position));
        }

        return json_encode($this->replaceIdWithRandom($objBlock->get()));
    }

    /**
     * @throws \Exception
     */
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