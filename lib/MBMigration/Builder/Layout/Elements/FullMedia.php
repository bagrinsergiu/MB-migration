<?php

namespace MBMigration\Builder\Layout\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class FullMedia extends Element
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
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->FullMedia($elementData);
    }

    /**
     * @throws DOMException
     */
    protected function FullMedia(array $sectionData)
    {
        Utils::log('Create full media', 1, "full_media");

        $objBlock = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media']['main'];

        $objBlock->newItem($decoded);

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {

                if($item['item_type']=='title' && $this->showHeader($sectionData)) {

                    $this->setOptionsForUsedFonts($item, $options);
                    $this->defaultTextPosition($item, $options);

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if($item['item_type']=='body' && $this->showBody($sectionData)) {

                    $this->setOptionsForUsedFonts($item, $options);
                    $this->defaultTextPosition($item, $options);

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item()->item()->item()->item(3)->item()->setting('imageSrc', $item['content']);
                $objBlock->item()->item()->item()->item(3)->item()->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }
}