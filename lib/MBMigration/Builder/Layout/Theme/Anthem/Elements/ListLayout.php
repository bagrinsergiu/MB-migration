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
     */
    protected function ListLayout(array $sectionData) {
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

//        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
//            Utils::log('Set background', 1, "] [list_layout");
//
//            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
//                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
//                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
//                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
//            }
//            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
//                if ($this->checkArrayPath($sectionData, 'settings/sections/background/fadeMode')) {
//                    if ($sectionData['settings']['sections']['background']['fadeMode'] !== 'none'){
//                        $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
//                        if ($opacity <= 0.3) {
//                            $options = array_merge($options, ['textColor' => '#000000']);
//                        }
//                        $objBlock->item(0)->setting('bgColorOpacity', $opacity);
//                    } else {
//                        $objBlock->item(0)->setting('bgColorOpacity', 1);
//                    }
//                }
//                $objBlock->item(0)->setting('bgColorType', 'none');
//            }
//        }

        $blockHead = false;
        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $blockHead = true;

                $richText = JS::RichText($headItem['id'], $options['currentPageURL'], $options['fontsFamily']);

                $objHead->item(0)->item(0)->item(0)->setText($richText);
            }

            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $blockHead = true;

                $richText = JS::RichText($headItem['id'], $options['currentPageURL'], $options['fontsFamily']);

                $objHead->item(0)->item(2)->item(0)->setText($richText);
            }
        }

        if($blockHead) {
            $objBlock->item(0)->addItem($objHead->get());
        }

        foreach ($sectionData['items'] as $section) {
            $objRow->newItem($decoded['row']);
            $objItem->newItem($decoded['item']);
            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    $objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    $objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    $objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objItem->item(0)->item(0)->setText($richText);
                    }

                    if ($item['item_type'] === 'body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objItem->item(2)->item(0)->setText($richText);
                    }
                }
            }
            $objRow->addItem($objItem->get());
            $objBlock->item(0)->addItem($objRow->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}