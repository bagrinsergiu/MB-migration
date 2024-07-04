<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use Exception;
use MBMigration\Core\Logger;
use DOMDocument;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;

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
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->TabsLayout($elementData);
    }

    /**
     * @throws DOMException
     * @throws Exception
     */
    protected function TabsLayout(array $sectionData) {
        Logger::instance()->info('Create bloc');

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
                    if(!empty($sectionData['style']['border']['border-bottom-color']))
                    {
                        $objBlock->item()->addItem(
                            $this->wrapperLine(['borderColorHex' => $sectionData['style']['border']['border-bottom-color']])
                        );
                    }
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
                        $tab_title = $this->extractTextFromHtml($item['content']);
                        $objItem->setting('labelText', $tab_title);
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
                                            if($iconItem['value']['hoverColorOpacity'] == 1){
                                                $iconItem['value']['hoverColorOpacity'] = 0.9;
                                            }
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
        $objRow->item(0)->setting('contentBgColorHex', $sectionData['style']['background-color'] ?? '#FFFFFF');
        $objRow->item(0)->setting('bgColorHex', $sectionData['style']['background-color'] ?? "#FFFFFF");
        $objBlock->item(0)->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    private function extractTextFromHtml($html): string
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);

        $dom->loadHTML($html);

        $text = '';

        $paragraphs = $dom->getElementsByTagName('p');

        foreach ($paragraphs as $paragraph) {
            $filteredText = $this->replaceLetters($paragraph->nodeValue);
            $filteredText = preg_replace('/[^\p{L}\p{N}\s]/u', '', $filteredText);
            $text .= $filteredText . ' ';
        }

        libxml_use_internal_errors(false);

        return trim($text);
    }

    private function replaceLetters($string): string
    {
        $pattern = [
            '/(à|á|â|ã|ä|å|À|Á|Â|Ã|Ä|Å|æ|Æ)/u',
            '/(è|é|é|ê|ë|È|É|Ê|Ë)/u',
            '/(ì|í|î|ï|Ì|Í|Î|Ï)/u',
            '/(ò|ó|ô|õ|ö|ø|Ò|Ó|Ô|Õ|Ö|Ø|œ|Œ)/u',
            '/(ù|ú|û|ü|Ù|Ú|Û|Ü)/u',
            '/(ñ|Ñ)/u',
            '/(ý|ÿ|Ý|Ÿ)/u',
            '/(ç|Ç)/u',
            '/(ð|Ð)/u',
            '/(ß)/u'
        ];

        $replacement = [
            'a',
            'e',
            'i',
            'o',
            'u',
            'n',
            'y',
            'c',
            'd',
            's'
        ];

        return preg_replace($pattern, $replacement, $string);
    }

}
