<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class GridLayout extends Element
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
        return $this->GridLayout($elementData);
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    protected function GridLayout(array $sectionData) {
        Utils::log('Create bloc', 1, "grid_layout");

        $objItem    = new ItemBuilder();
        $objBlock   = new ItemBuilder();
        $objHead    = new ItemBuilder();
        $objRow     = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);
        $objRow->newItem($decoded['row']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);
        $this->backgroundColor($objBlock, $sectionData, $options);
        $this->backgroundImages($objBlock, $sectionData, $options);
        $this->setOptionsForTextColor($sectionData, $options);

        $objBlock->item(0)->setting('bgColorPalette', '');
        foreach ($sectionData['head'] as $head){
            if ($head['category'] == 'text') {
                if ($head['item_type'] === 'title' && $this->showHeader($sectionData)) {

                    $richText = JS::RichText($head['id'], $options['currentPageURL']);
                    $objHead->item()->addItem($this->itemWrapperRichText($richText));
                }

                if ($head['item_type'] === 'body' && $this->showBody($sectionData)) {
                    $richText = JS::RichText($head['id'], $options['currentPageURL']);
                    $objHead->item()->addItem($this->itemWrapperRichText($richText));
                }
            }
        }
        $objBlock->item()->addItem($objHead->get());

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $section)
        {
            $objItem->newItem($decoded['item']);

            if(isset($section['item'])) {
                switch ($section['category']) {
                    case 'text':
                        if ($section['item_type'] == 'title') {
                            break;
                        }
                        if ($section['item_type'] == 'body') {
                            break;
                        }
                    case 'list':
                        foreach ($section['item'] as $sectionItem) {
                            if ($sectionItem['category'] == 'photo') {
                                $objItem->setting('bgImageSrc', $sectionItem['content']);
                                $objItem->setting('bgImageFileName', $sectionItem['imageFileName']);

                                if ($sectionItem['link'] != '') {
                                    $objItem->setting('linkType', 'external');
                                    $objItem->setting('linkExternal', '/' . $sectionItem['link']);
                                }
                            }
                            if ($sectionItem['category'] == 'text') {
                                if ($sectionItem['item_type'] == 'title') {
                                    $richText = JS::RichText($sectionItem['id'], $options['currentPageURL'], $options['fontsFamily']);
                                    $objItem->item(1)->item(0)->setText($richText);
                                }
                            }
                        }
                        break;
                }
            } else {
                if ($section['category'] == 'photo') {
                    $objItem->item(0)->item(0)->setting('imageSrc', $section['content']);
                    $objItem->item(0)->item(0)->setting('imageFileName', $section['imageFileName']);

                    if ($section['link'] != '') {
                        $objItem->item(0)->item(0)->setting('linkType', "external");
                        $objItem->item(0)->item(0)->setting('linkExternal', '/' . $section['link']);
                    }
                }
                if ($section['category'] == 'text') {

                    if ($section['item_type'] == 'title' && $this->showHeader($section)) {
                        $richText = JS::RichText($section['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objItem->addItem($this->itemWrapperRichText($richText));
                    }
                    if ($section['item_type'] == 'body' && $this->showBody($section)) {
                        $richText = JS::RichText($section['id'], $options['currentPageURL'], $options['fontsFamily']);
                        $objItem->addItem($this->itemWrapperRichText($richText));
                    }
                }
            }
            $objRow->addItem($objItem->get());
        }
        $objBlock->item()->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}