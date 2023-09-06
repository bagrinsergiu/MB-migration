<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class ThreeTopMediaCircle extends Element
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
    public function getElement(array $elementData)
    {
        return $this->three_top_media_circle($elementData);
    }

    /**
     * @throws \DOMException
     */
    protected function three_top_media_circle(array $sectionData)
    {
        Utils::log('Create bloc', 1, "three_top_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['three-top-media-circle'];

        $options = [];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objSpacer = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);
        $objSpacer->newItem($decoded['spacer']);

        $objBlock->item(0)->setting('bgAttachment', 'none');
        $objBlock->item(0)->setting('bgColorPalette', '');

        $this->defaultOptionsForElement($sectionData, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);


        foreach ($sectionData['items'] as $item)
        {
            if ($item['category'] === 'photo') {
                $objItem->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objItem->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                $objBlock->item(0)->item(0)->addItem($objItem->get());
            }

            if ($item['item_type'] === 'title') {
                $this->setOptionsForUsedFonts($item, $options);
                $this->defaultTextPosition($item, $options);

                $objBlock->item(0)->item(1)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options ));
            }

            if ($item['item_type'] === 'body') {
                $this->setOptionsForUsedFonts($item, $options);
                $this->defaultTextPosition($item, $options);

                $objBlock->item(0)->item(1)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
            }
        }
        $objBlock->item(0)->item(0)->addItem($objSpacer->get());

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

}