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
    protected function GridLayout(array $sectionData)
    {
        Utils::log('Create bloc', 1, "grid_layout");

        $objItem = new ItemBuilder();
        $objBlock = new ItemBuilder();
        $objHead = new ItemBuilder();
        $objRow = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];
        $global = $this->jsonDecode['global'];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);


        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);
        $this->backgroundColor($objBlock, $sectionData, $options);
        $this->backgroundImages($objBlock, $sectionData, $options);
        $this->setOptionsForTextColor($sectionData, $options);

        $objBlock->item(0)->setting('bgColorPalette', '');

        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['category'] === 'text') {
                if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                    $blockHead = true;
                    foreach ($headItem['brzElement'] as $item) {
                        $objHead->item(0)->addItem($item);
                    }
                    $objHead->item(0)->addItem(
                        $this->wrapperLine(
                            [
                                'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? '',
                            ]
                        )
                    );
                }
            }
        }

        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['category'] === 'text') {
                if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                    $blockHead = true;
                    foreach ($headItem['brzElement'] as $item) {
                        $objHead->item()->addItem($item);
                    }
                }
            }
        }

        if ($blockHead) {
            $objBlock->item(0)->addItem($objHead->get());
        }

//        if(count($sectionData['items']) <= 2){
//            $objItem->newItem($decoded['item']);
//            $objItem->setting('borderColorOpacity', 0);
//            $objRow->addItem($objItem->get());
//        }

        $itemsChunks = array_chunk($sectionData['items'], 4);
        foreach ($itemsChunks as $row) {
            $objRow->newItem($decoded['row']);

            $countItems = count($row);
            foreach ($row as $section) {
                $objItem->newItem($decoded['item']);
                $width = 100 / $countItems;
                $width = number_format($width, 2, '.', '');

                $objItem->setting('width', $width);
                if (isset($section['item'])) {
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

                                    $imageOptions['imageSrc'] = $sectionItem['content'];
                                    $imageOptions['imageFileName'] = $sectionItem['imageFileName'];


                                    //$objItem->setting('bgImageSrc', $sectionItem['content']);
                                    //$objItem->setting('bgImageFileName', $sectionItem['imageFileName']);

                                    if ($sectionItem['link'] != '') {

                                        $urlComponents = parse_url($sectionItem['link']);

                                        if (!empty($urlComponents['host'])) {
                                            $slash = '';
                                        } else {
                                            $slash = '/';
                                        }
                                        if ($sectionItem['new_window']) {
                                            $sectionItem['new_window'] = 'on';
                                        } else {
                                            $sectionItem['new_window'] = 'off';
                                        }

                                        $imageOptions['linkType'] = 'external';
                                        $imageOptions['linkExternal'] = $slash.$sectionItem['link'];
                                        $imageOptions['linkExternalBlank'] = $sectionItem['new_window'];

                                        //                                    $objItem->setting('linkType', 'external');
                                        //                                    $objItem->setting('linkExternal', $slash . $sectionItem['link']);
                                        //                                    $objItem->setting('linkExternalBlank', $sectionItem['new_window']);
                                    }

                                    $image = $this->wrapperImage($imageOptions, $global['wrapper--image']);
                                    $objItem->item(1)->addItem($image);
                                }
                            }
                            foreach ($section['item'] as $sectionItem) {
                                if ($sectionItem['category'] == 'text' ) {
                                    if ($sectionItem['item_type'] == 'title' && $this->showBody($section)) {
                                        foreach ($sectionItem['brzElement'] as $item) {
                                            $objItem->item(1)->addItem($item);
                                        }
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
                            $objItem->item(0)->item(0)->setting('linkExternal', '/'.$section['link']);
                        }
                    }
                    if ($section['category'] == 'text') {
                        if ($section['item_type'] == 'title' && $this->showHeader($section)) {
                            if ($section['item_type'] == 'title') {
                                foreach ($section['brzElement'] as $item) {
                                    $objItem->addItem($item);
                                }
                            }
                        }
                        if ($section['item_type'] == 'body' && $this->showBody($section)) {
                            if ($section['item_type'] == 'body') {
                                foreach ($section['brzElement'] as $item) {
                                    $objItem->addItem($item);
                                }
                            }
                        }
                    }
                }

                $objRow->addItem($objItem->get());
            }
            $objBlock->item()->addItem($objRow->get());
        }
//        if(count($sectionData['items']) <= 3){
//            $objItem->newItem($decoded['item']);
//            $objItem->setting('borderColorOpacity', 0);
//            $objItem->setting('showOnMobile', "off");
//            $objRow->addItem($objItem->get());
//        }
//        if(count($sectionData['items']) <= 2){
//            $objItem->newItem($decoded['item']);
//            $objItem->setting('borderColorOpacity', 0);
//            $objItem->setting('showOnMobile', "off");
//            $objRow->addItem($objItem->get());
//        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        $result = json_encode($block);
        return json_encode($block);
    }

}