<?php

namespace MBMigration\Builder\Layout\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class LeftMedia extends Element
{

    private $cache;
    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    public function getElement(array $elementData = [])
    {
        return $this->LeftMedia($elementData);
    }

    /**
     * @throws DOMException
     */
    protected function LeftMedia(array $sectionData)
    {
        Utils::log('Create bloc', 1, "left_media");
        $options = [];
        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['left-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');

        $this->defaultOptionsForElement($sectionData, $options);
        $this->backgroundColor($objBlock, $sectionData, $options);
        $this->setOptionsForTextColor($sectionData, $options);
        $this->backgroundColor($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);

                if($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageWidth', $item['settings']['image']['width']);
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageHeight', $item['settings']['image']['height']);
                }

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title' && $this->showHeader($sectionData)) {

                    $this->setOptionsForUsedFonts($item, $options);
                    $this->defaultTextPosition($item, $options);
                    $this->textType($item, $options);

                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if($item['item_type']=='body' && $this->showBody($sectionData)) {

                    $this->setOptionsForUsedFonts($item, $options);
                    $this->defaultTextPosition($item, $options);
                    $this->textType($item, $options);

                    $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}