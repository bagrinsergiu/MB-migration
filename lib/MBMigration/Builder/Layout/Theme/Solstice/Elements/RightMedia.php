<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class RightMedia extends Element
{
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
        return $this->RightMedia($elementData);
    }

    /**
     * @throws DOMException
     * @throws \Exception
     */
    protected function RightMedia(array $sectionData)
    {
        Utils::log('Create bloc', 1, "right_media");
        $this->cache->set('currentSectionData', $sectionData);

        $options = [];

        $objBlock = new ItemBuilder();

        $decoded = $this->jsonDecode['blocks']['right-media']['main'];
        $general = $this->jsonDecode['blocks']['right-media'];

        $objBlock->newItem($decoded);

        $objBlock->item()->setting('bgColorPalette', '');
        $objBlock->item()->setting('bgColorOpacity', 1);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($general, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item()->item()->item(1)->item()->item()->setting('imageSrc', $item['content']);
                $objBlock->item()->item()->item(1)->item()->item()->setting(
                    'imageFileName',
                    $item['imageFileName']
                );
                $objBlock->item()->item()->item(1)->item()->item()->setting('mobileSize', 100);
                $objBlock->item()->item()->item(1)->item()->item()->setting('mobileSizeSuffix', '%');


                if ($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item()->item()->item(1)->item()->item()->setting(
                        'imageWidth',
                        $item['settings']['image']['width']
                    );
                    $objBlock->item()->item()->item(1)->item()->item()->setting(
                        'imageHeight',
                        $item['settings']['image']['height']
                    );
                }

                if ($item['link'] != '') {

                    $urlComponents = parse_url($item['link']);

                    if (!empty($urlComponents['host'])) {
                        $slash = '';
                    } else {
                        $slash = '/';
                    }
                    if ($item['new_window']) {
                        $sectionItem['new_window'] = 'on';
                    } else {
                        $sectionItem['new_window'] = 'off';
                    }
                    $objBlock->item()->item()->item(1)->item()->item()->setting('linkType', 'external');
                    $objBlock->item()->item()->item(1)->item()->item()->setting(
                        'linkExternal',
                        $slash.$item['link']
                    );
                    $objBlock->item()->item()->item(1)->item()->item()->setting(
                        'linkExternalBlank',
                        $sectionItem['new_window']
                    );
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title' && $this->showHeader($sectionData)) {
                    $this->textCreation($item, $objBlock);
                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'body' && $this->showBody($sectionData)) {
                    $this->textCreation($item, $objBlock);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());

        return json_encode($block);
    }

    private function textCreation($sectionData, $objBlock)
    {

        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(is_array($embedCode)){
                            $objBlock->item()->item()->item()->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                case 'Wrapper':
                    $objBlock->item()->item()->item()->addItem($textItem);
                    break;
            }
        }
    }

}