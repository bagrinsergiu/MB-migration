<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class ListLayout extends Element
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
        return $this->ListLayout($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function ListLayout(array $sectionData)
    {
        Utils::log('Create bloc', 1, "list_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['list-layout'];

        $options = [];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objHead = new ItemBuilder();
        $objImage = new ItemBuilder();
        $objRow = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);
        $objImage->newItem($decoded['image']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $blockHead = false;
        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['category'] !== 'text') {
                continue;
            }

            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $blockHead = true;
                $richText = JS::RichText($headItem['id'], $options['currentPageURL'], $options['fontsFamily']);

                $objBlock->item(0)->addItem($this->itemWrapperRichText($richText));
                $objBlock->item(0)->addItem($this->wrapperLine(['borderColorHex' => $options['borderColorHex']]));

//                $objHead->item(0)->item(0)->item(0)->setText($richText);
            }

            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $blockHead = true;
                $richText = JS::RichText($headItem['id'], $options['currentPageURL'], $options['fontsFamily']);
                $objHead->item()->item()->addItem($this->itemWrapperRichText($richText));
            }
        }

        if ($blockHead) {
            $objBlock->item(0)->addItem($objHead->get());
        }

        if ($this->checkArrayPath($sectionData, 'settings/sections/list/photo_position')) {
            $options['photoPosition'] = $sectionData['settings']['sections']['list']['photo_position'];
        }

        foreach ($sectionData['items'] as $section) {
            $objRow->newItem($decoded['row']);
            $objItem->newItem($decoded['item']);

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    $objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    $objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);

                    if ($item['link'] != '') {
                        $objImage->item(0)->item(0)->setting('linkType', 'external');
                        $objImage->item(0)->item(0)->setting('linkExternal', $item['link']);
                    }

                    if (empty($options['photoPosition'])) {
                        $objRow->addItem($objImage->get());
                    }
                }
            }

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objItem->item(0)->item(0)->setText($richText);
//                        $objItem->addItem($this->itemWrapperRichText($richText));

                        $WrapperText = [];
                        $TopWrapperIcon = [];
                        $BottomWrapperIcon = [];
                        $TopWrapperButton = [];
                        $BottomWrapperButton = [];

                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        if(!is_array($richText)) {
                            $objItem->addItem($this->itemWrapperRichText($richText));
                        } else {
                            if(!empty($richText['text'])) {
                                $WrapperText[] = $this->itemWrapperRichText($richText['text']);
                            }
                            if(!empty($richText['embeds'])) {
                                $WrapperText[] = $this->embedCode($item['content']);
                            }
                            if(!empty($richText['icons'])) {
                                foreach ($richText['icons'] as $itemIcon) {
                                    if ($itemIcon['position'] === 'top') {
                                        $TopWrapperIcon[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                                    }
                                    if ($itemIcon['position'] === 'bottom') {
                                        $BottomWrapperIcon[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                                    }
                                }
                            }
                        }

                        if (!empty($TopWrapperIcon)) {
                            foreach ($TopWrapperIcon as $topItem) {
                                $objItem->addItem($this->wrapperColumn($topItem));
                            }
                        }

                        if (!empty($WrapperText)) {
                            foreach ($WrapperText as $text) {
                                $objItem->addItem($this->wrapperColumn($text));
                            }
                        }

                        if (!empty($BottomWrapperIcon)) {
                            foreach ($BottomWrapperIcon as $bottomItem) {
                                $objItem->addItem($this->wrapperColumn($bottomItem));
                            }
                        }

                        $objItem->addItem($this->wrapperLine(['borderColorHex' => $options['borderColorHex']]));

                    }
                }
            }

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'body') {

                        $WrapperText = [];
                        $TopWrapperIcon = [];
                        $BottomWrapperIcon = [];
                        $TopWrapperButton = [];
                        $BottomWrapperButton = [];

                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        if(!is_array($richText)) {
                            $objItem->addItem($this->itemWrapperRichText($richText));
                        } else {
                            if(!empty($richText['text'])) {
                                $WrapperText[] = $this->itemWrapperRichText($richText['text']);
                            }
                            if(!empty($richText['embeds'])) {
                                $WrapperText[] = $this->embedCode($item['content']);
                            }
                            if(!empty($richText['icons'])) {
                                foreach ($richText['icons'] as $itemIcon) {
                                    if ($itemIcon['position'] === 'top') {
                                        $TopWrapperIcon[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                                    }
                                    if ($itemIcon['position'] === 'bottom') {
                                        $BottomWrapperIcon[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                                    }
                                }
                            }
                        }

                        if (!empty($TopWrapperIcon)) {
                            foreach ($TopWrapperIcon as $topItem) {
                                $objItem->addItem($this->wrapperColumn($topItem));
                            }
                        }

                        if (!empty($WrapperText)) {
                            foreach ($WrapperText as $topItem) {
                                $objItem->addItem($topItem);
                            }
                        }

                        if (!empty($BottomWrapperIcon)) {
                            foreach ($BottomWrapperIcon as $bottomItem) {
                                $objItem->addItem($this->wrapperColumn($bottomItem));
                            }
                        }
                    }
                }
            }

            $objRow->addItem($objItem->get());
            if (!empty($options['photoPosition'])) {
                $objRow->addItem($objImage->get());
            }
            $objBlock->item(0)->addItem($objRow->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());

        return json_encode($block);
    }
}