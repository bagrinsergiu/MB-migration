<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use MBMigration\Builder\Media\MediaController;
use MBMigration\Core\Logger;
use Exception;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;

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
     * @throws DOMException
     */
    public function getElement(array $elementData)
    {
        return $this->three_top_media_circle($elementData);
    }

    /**
     * @throws DOMException
     */
    protected function three_top_media_circle(array $sectionData)
    {
        Logger::instance()->info('Create bloc');
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

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if (isset($item['content']) && isset($item['imageFileName'])) {
                if ($item['category'] === 'photo') {
                    $objItem->item(0)->item(0)->setting('imageSrc', $item['content']);
                    $objItem->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);

                    if ($item['link'] != '') {
                        $this->link($objItem, $item);
                    }

                    $objBlock->item(0)->item(0)->addItem($objItem->get());
                }
            } else {
                $objItem->item(0)->item(0)->setting('imageSrc', '');
                $objItem->item(0)->item(0)->setting('imageFileName', '');
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['item_type'] === 'title') {

                $this->textCreation($item, $objBlock);

//                $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                $objBlock->item(0)->item(1)->item(0)->item(0)->item(0)->setText($richText);
            }
        }


        foreach ($sectionData['items'] as $item) {
            if ($item['item_type'] === 'body') {

                $this->textCreation($item, $objBlock);

//                $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                $objBlock->item(0)->item(1)->item(0)->item(2)->item(0)->setText($richText);
            }
        }


//        $objBlock->item(0)->item(0)->addItem($objSpacer->get());

        $block = $this->replaceIdWithRandom($objBlock->get());

        return json_encode($block);
    }

    /**
     * @throws Exception
     */
    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if (!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if (is_array($embedCode)) {
                            $objBlock->item()->item(1)->item()->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                    foreach ($textItem['value']['items'] as &$iconItem) {
                        if ($iconItem['type'] == 'Button') {
                            $iconItem['value']['borderStyle'] = "none";
                        }
                    }
                    $objBlock->item()->item(1)->item()->addItem($textItem);
                    break;
                case 'Wrapper':
                    $objBlock->item()->item(1)->item()->addItem($textItem);
                    break;
            }
        }
    }

}