<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

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

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['tabs-layout'];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objRow = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objRow->newItem($decoded['row']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $blockHead = false;

        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            $show_header = true;
            $show_body = true;

            if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                $show_header = $sectionData['settings']['sections']['text']['show_header'];
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                $show_body = $sectionData['settings']['sections']['text']['show_body'];
            }

            if ($headItem['item_type'] === 'title' && $show_header) {
                $blockHead = true;

                $this->setOptionsForUsedFonts($sectionData, $options);
                $this->defaultTextPosition($sectionData, $options);

                $objBlock->item(0)->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], $options)));
            }

            if ($headItem['item_type'] === 'body' && $show_body) {
                $blockHead = true;

                $this->setOptionsForUsedFonts($sectionData, $options);
                $this->defaultTextPosition($sectionData, $options);

                $objBlock->item(0)->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], $options)));
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
                        $this->setOptionsForUsedFonts($sectionData, $options);
                        $this->defaultTextPosition($sectionData, $options);

                        $objItem->setting('labelText', $this->replaceString($item['content'], $options)['text']);
                    }

                    if ($item['item_type'] === 'tab_body') {

                        $this->setOptionsForUsedFonts($sectionData, $options);
                        $this->defaultTextPosition($sectionData, $options);

                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }

            }

            $objRow->item(0)->addItem($objItem->get());
//            $objRow->item(0)->setting('contentBgColorHex', $blockBg);
        }
        $objBlock->item(0)->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}