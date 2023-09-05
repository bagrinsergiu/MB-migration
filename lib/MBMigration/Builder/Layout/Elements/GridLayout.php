<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class GridLayout extends Element
{
    /**
     * @var VariableCache
     */
    private $cache;
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

        $this->defaultOptionsForElement($sectionData, $options);
        $this->backgroundColor($objBlock, $sectionData, $options);
        $this->setOptionsForTextColor($sectionData, $options);

        $objBlock->item(0)->setting('bgColorPalette', '');
        foreach ( $sectionData['head'] as $head){
            if ($head['category'] == 'text') {

                if ($head['item_type'] === 'title' && $this->showHeader($head)) {

                    $this->defaultOptionsForElement($head, $options);
                    $this->defaultTextPosition($head, $options);
                    $this->textType($head, $options);

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center']);
                    $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($head['content'], $options)));

                }

                if ($head['item_type'] === 'body' && $this->showBody($head)) {

                    $this->defaultOptionsForElement($head, $options);
                    $this->defaultTextPosition($head, $options);
                    $this->textType($head, $options);

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                    $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($head['content'], $options)));
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

                                    $this->defaultOptionsForElement($section, $options);
                                    $this->defaultTextPosition($section, $options);
                                    $this->textType($section, $options, 'title');

                                    $objItem->item(1)->item(0)->setText($this->replaceString($sectionItem['content'], $options));
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

                        $this->setOptionsForUsedFonts($section, $options);
                        $this->defaultTextPosition($section, $options);
                        $this->textType($section, $options);

                        $objItem->addItem($this->itemWrapperRichText($this->replaceString($section['content'], $options)));
                    }
                    if ($section['item_type'] == 'body' && $this->showBody($section)) {

                        $this->setOptionsForUsedFonts($section, $options);
                        $this->defaultTextPosition($section, $options);
                        $this->textType($section, $options);

                        $objItem->addItem($this->itemWrapperRichText($this->replaceString($section['content'], $options)));
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