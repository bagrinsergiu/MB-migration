<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class TabsLayout extends Element
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
        return $this->TabsLayout($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function TabsLayout(array $sectionData) {
        Utils::log('Create bloc', 1, "tabs_layout");

        $options = ['elementType' => 'tabs_layout'];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['tabs-layout'];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objRow = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objRow->newItem($decoded['row']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $blockHead = false;
        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $richText = JS::RichText($headItem['id'], $options['currentPageURL'], $options['fontsFamily']);
                $objBlock->item(0)->addItem($this->itemWrapperRichText($richText));
            }
        }
        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $richText = JS::RichText($headItem['id'], $options['currentPageURL'], $options['fontsFamily']);
                $objBlock->item(0)->addItem($this->itemWrapperRichText($richText));
            }
        }

        foreach ($sectionData['items'] as $section) {

            $objItem->newItem($decoded['item']);

            foreach ($section['item'] as $item) {

                if ($item['category'] === 'photo') {
                    //$objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    //$objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    //$objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'tab_title') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objItem->setting('labelText', $richText);
                    }

                    if ($item['item_type'] === 'tab_body') {
                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objItem->item(0)->item(0)->setText($richText);
                    }
                }

            }

            $objRow->item(0)->addItem($objItem->get());
        }
        $objRow->item(0)->setting('contentBgColorHex', $options['bgColor']);
        $objRow->item(0)->setting('bgColorHex', $options['bgColor']);
        $objBlock->item(0)->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}