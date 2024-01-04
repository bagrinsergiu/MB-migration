<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMDocument;
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
            if ($headItem['category'] === 'text') {
                if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                    $blockHead = true;
                    foreach ($headItem['brzElement'] as $item) {
                        $objHead->item()->addItem($item);
                    }
                    $objHead->item()->addItem(
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
                        $objHead->item()->addItem($item);
                    }
                }
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

                    $objImage->item(0)->setting('mobileHorizontalAlign', 'center');

                    if (empty($options['photoPosition']) || $options['photoPosition'] === 'left') {
                        $objRow->addItem($objImage->get());
                    }
                }
            }

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'title') {
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
                                            if($iconItem['value']['hoverColorOpacity'] == 1){
                                                $iconItem['value']['hoverColorOpacity'] = 0.9;
                                            }
                                        }

                                        if ($iconItem['type'] == 'Button') {
                                            $iconItem['value']['borderStyle'] = "none";
                                        }
                                    }
                                    $objItem->addItem($element);
                                    break;
                                case 'Wrapper':
                                    $element['value']['items'][0]['value']['mobileContentHorizontalAlign'] = 'center';
                                    $element['value']['items'][0]['value']['text'] = $this->addClassToTags($element['value']['items'][0]['value']['text'], 'brz-text-xs-center');
                                    $objItem->addItem($element);
//                                    $objBlock->item(0)->addItem($element);
                                    break;

                            }
                        }
                        $objItem->addItem(
                            $this->wrapperLine([
                                'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? '',
                            ])
                        );
                    }
                }
            }

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'body') {
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
                                            if($iconItem['value']['hoverColorOpacity'] == 1){
                                                $iconItem['value']['hoverColorOpacity'] = 0.9;
                                            }
                                        }

                                        if ($iconItem['type'] == 'Button') {
                                            $iconItem['value']['borderStyle'] = "none";
                                        }
                                    }
                                    $objItem->addItem($element);
                                    break;
                                case 'Wrapper':
                                    $element['value']['items'][0]['value']['mobileContentHorizontalAlign'] = 'center';
                                    $objItem->addItem($element);
//                                    $objBlock->item(0)->addItem($element);
                                    break;
                            }
                        }
                    }
                }
            }

            $objRow->addItem($objItem->get());
            if (!empty($options['photoPosition']) && $options['photoPosition'] === 'right') {
                $objRow->addItem($objImage->get());
            }
            $objBlock->item(0)->addItem($objRow->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());

        return json_encode($block);
    }

    private function addClassToTags($htmlString, $className) {
        $dom = new DOMDocument;

        $dom->loadHTML($htmlString, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $tags = $dom->getElementsByTagName('*');
        foreach ($tags as $tag) {

            $currentClasses = $tag->getAttribute('class');

            $currentClassesArray = explode(' ', $currentClasses);

            if (!in_array($className, $currentClassesArray)) {
                $currentClassesArray[] = $className;
                $newClasses = implode(' ', $currentClassesArray);

                $tag->setAttribute('class', $newClasses);
            }
        }

        $updatedHtmlString = $dom->saveHTML();

        return str_replace(['<!DOCTYPE html>', '<html>', '</html>'], '', $updatedHtmlString);
    }


}