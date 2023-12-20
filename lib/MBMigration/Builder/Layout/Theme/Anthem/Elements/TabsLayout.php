<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

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

        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['category'] === 'text') {
                if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                    $blockHead = true;
                    foreach ($headItem['brzElement'] as $item) {
                        $objBlock->item()->addItem($item);
                    }
                    $objBlock->item()->addItem(
                        $this->wrapperLine(['borderColorHex' => $sectionData['style']['border']['border-bottom-color']])
                    );
                }
            }
        }

        foreach ($sectionData['head'] as $headItem) {
            if ($headItem['category'] === 'text') {
                if ($headItem['item_type'] === 'body' && $this->showHeader($sectionData)) {
                    $blockHead = true;
                    foreach ($headItem['brzElement'] as $item) {
                        $objBlock->item()->addItem($item);
                    }
                }
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

                        $objItem->setting('labelText', "test");
                    }

                    if ($item['item_type'] === 'tab_body') {
                        foreach ($item['brzElement'] as $element) {
                            switch ($element['type']) {
                                case 'EmbedCode':
                                    if (!empty($sectionData['content'])) {
                                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                                        if (is_array($embedCode)) {
                                            $objBlock->item(0)->addItem($this->embedCode($embedCode[$i]));
                                        }
                                        $i++;
                                    }
                                    break;
                                case 'Cloneable':
                                    $element['value']['mobileHorizontalAlign'] = 'center';

                                    foreach ($element['value']['items'] as &$iconItem) {
                                        if ($iconItem['type'] == 'Icon') {
                                            $iconItem['value']['hoverBgColorType'] = "solid";
                                        }


                                        if ($iconItem['type'] == 'Button') {
                                            $iconItem['value']['borderStyle'] = "none";
                                        }
                                    }
                                    $objItem->item(0)->addItem($element);
                                    break;
                                case 'Wrapper':
                                    $objItem->item(0)->addItem($element);
//                                    $objBlock->item(0)->addItem($element);
                                    break;

                            }
                        }

                    }
//                    if ($item['item_type'] === 'tab_body') {
//                        $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                        $objItem->item(0)->item(0)->setText($richText);
//                    }
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