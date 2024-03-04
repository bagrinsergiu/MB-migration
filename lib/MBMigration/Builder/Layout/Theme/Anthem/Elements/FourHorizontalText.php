<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

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
     * @throws \Exception
     */
    protected function FourHorizontalText($sectionData)
    {
        \MBMigration\Core\Logger::instance()->info('Create four horizontal text');

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['four-horizontal-text'];

        $objBlock = new ItemBuilder($decoded['main']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $this->richTextCreator($objBlock, $item, $options['currentPageURL'], $options['fontsFamily']);
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'body') {
                        $this->richTextCreator(
                            $objBlock,
                            $item,
                            $options['currentPageURL'],
                            $options['fontsFamily'],
                            [0, 2]
                        );
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 1) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $this->richTextCreator(
                            $objBlock,
                            $item,
                            $options['currentPageURL'],
                            $options['fontsFamily'],
                            [1, 0]
                        );
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 1) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'body') {
                        $this->richTextCreator(
                            $objBlock,
                            $item,
                            $options['currentPageURL'],
                            $options['fontsFamily'],
                            [1, 2]
                        );
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 2) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $this->richTextCreator(
                            $objBlock,
                            $item,
                            $options['currentPageURL'],
                            $options['fontsFamily'],
                            [2, 0]
                        );
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 2) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'body') {
                        $this->richTextCreator(
                            $objBlock,
                            $item,
                            $options['currentPageURL'],
                            $options['fontsFamily'],
                            [2, 2]
                        );
                    }
                }
            }
        }


        foreach ($sectionData['items'] as $item) {
            if ($item['group'] == 3) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $this->richTextCreator(
                            $objBlock,
                            $item,
                            $options['currentPageURL'],
                            $options['fontsFamily'],
                            [3, 0]
                        );
                    }
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if($item['group'] == 3){
                if($item['category'] == 'text') {
                    if ($item['item_type'] == 'body') {
                        $this->richTextCreator(
                            $objBlock,
                            $item,
                            $options['currentPageURL'],
                            $options['fontsFamily'],
                            [3, 2]
                        );
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
    private function richTextCreator($objBlock, $item, $currentPageURL, $fontsFamily, $itemLevel = [0, 0])
    {
        $multiElement = [];

        $richText = JS::RichText($item['id'], $currentPageURL, $fontsFamily);

        if (!is_array($richText)) {
            $objBlock->item()->item()->item(1)->addItem($this->itemWrapperRichText($richText));
        } else {
            if (!empty($richText['icons'])) {
                foreach ($richText['icons'] as $itemIcon) {
                    if ($itemIcon['position'] === 'top') {
                        $multiElement[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                    }
                }
            }

            if(!empty($richText['button'])) {
                foreach ($richText['button'] as $itemButton) {
                    if ($itemButton['position'] === 'bottom') {
                        $multiElement[] = $this->button($itemButton['items'], $itemButton['align']);
                    }
                }
            }

            if (!empty($richText['text'])) {
                $multiElement[] = $this->itemWrapperRichText($richText['text']);
            }

            if (!empty($richText['embeds'])) {
                $multiElement[] = $this->embedCode($item['content']);
            }

            if (!empty($richText['icons'])) {
                foreach ($richText['icons'] as $itemIcon) {
                    if ($itemIcon['position'] === 'bottom') {
                        $multiElement[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                    }
                }
            }

            if(!empty($richText['button'])) {
                foreach ($richText['button'] as $itemButton) {
                    if ($itemButton['position'] === 'bottom') {
                        $multiElement[] = $this->button($itemButton['items'], $itemButton['align']);
                    }
                }
            }

            $objBlock->item()->item()->item($itemLevel[0])->item($itemLevel[1])->item()->addItem($this->wrapperColumn($multiElement, true));
        }
    }


}