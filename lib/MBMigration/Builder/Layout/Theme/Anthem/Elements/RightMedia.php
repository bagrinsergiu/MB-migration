<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

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
        Utils::log('Create bloc', 1,  "right_media");
        $this->cache->set('currentSectionData', $sectionData);

        $options = [];

        $objBlock = new ItemBuilder();

        $decoded = $this->jsonDecode['blocks']['right-media']['main'];
        $general = $this->jsonDecode['blocks']['right-media'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('bgColorOpacity', 1);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($general, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);


//        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
//            Utils::log('Set background', 1, "] [right_media");
//
//            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
//                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
//                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
//                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
//            }
//            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
//                if ($this->checkArrayPath($sectionData, 'settings/sections/background/fadeMode')) {
//                    if ($sectionData['settings']['sections']['background']['fadeMode'] !== 'none'){
//                        $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
//                        if ($opacity <= 0.3) {
//                            $options = array_merge($options, ['textColor' => '#000000']);
//                        }
//                        $objBlock->item(0)->setting('bgColorOpacity', $opacity);
//                    }
//                }
//                $objBlock->item(0)->setting('bgColorType', 'none');
//            }
//        }

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageSrc',$item['content']);
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageFileName',$item['imageFileName']);

                if($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageWidth', $item['settings']['image']['width']);
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageHeight', $item['settings']['image']['height']);
                }

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {
                if($item['item_type']=='title' && $this->showHeader($sectionData)) {
                    $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                    $objBlock->item()->item()->item()->addItem($this->itemWrapperRichText($richText));
                    $objBlock->item()->item()->item()->addItem($this->wrapperLine());
                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {
                if($item['item_type']=='body' && $this->showBody($sectionData)) {
                    $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                    $objBlock->item(0)->item(0)->item(0)->addItem($this->itemWrapperRichText($richText));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}