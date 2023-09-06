<?php

namespace MBMigration\Builder\Layout\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class TopMedia extends Element
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
        return $this->TopMedia($elementData);
    }

    /**
     * @throws DOMException
     */
    protected function TopMedia(array $sectionData)
    {
        Utils::log('Create full media', 1, "top_media");

        $objBlock = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['top_media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');
        $objBlock->item(0)->setting('bgAttachment','none');
        $objBlock->item(0)->setting('bgColorOpacity', 1);

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_header = $sectionData['settings']['sections']['text']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_body = $sectionData['settings']['sections']['text']['show_body'];
                }

                if($item['item_type']=='title' && $show_header) {
                    $this->setOptionsForUsedFonts($item, $options);
                    $this->defaultTextPosition($item, $options);

                    $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if($item['item_type']=='body' && $show_body) {

                    $this->setOptionsForUsedFonts($item, $options);
                    $this->defaultTextPosition($item, $options);

                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item()->item()->item()->item(0)->item()->setting('imageSrc', $item['content']);
                $objBlock->item()->item()->item()->item(0)->item()->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}