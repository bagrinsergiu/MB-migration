<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class AccordionLayout extends Element
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
        return $this->AccordionLayout($elementData);
    }

    /**
     * @throws \DOMException
     */
    protected function AccordionLayout(array $sectionData) {
        Utils::log('Create bloc', 1, "Accordion_layout");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['accordion-layout'];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objList = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objList->newItem($decoded['list']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $section) {

            $objItem->newItem($decoded['item']);

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    //$objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    //$objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    //$objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'accordion_title') {

                        $this->setOptionsForUsedFonts($item, $options);
                        $this->defaultTextPosition($item, $options);

                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-left']);
                        $objItem->setting('labelText', $this->replaceString($item['content'], $options)['text']);
                    }

                    if ($item['item_type'] === 'accordion_body') {

                        $this->setOptionsForUsedFonts($item, $options);
                        $this->defaultTextPosition($item, $options);

                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }

            }
            $objList->item(0)->addItem($objItem->get());
        }
        $objBlock->item(0)->addItem($objList->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}