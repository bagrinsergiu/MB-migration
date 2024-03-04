<?php

namespace MBMigration\Builder\Layout\Theme\Solstice\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Element;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class ThreeTopMedia extends Element
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
        return $this->three_top_media($elementData);
    }

    /**
     * @throws \DOMException
     */
    protected function three_top_media(array $sectionData)
    {
        \MBMigration\Core\Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['three-top-media'];

        $options = [];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objRowImage = new ItemBuilder();
        $objRowText = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);
        $objRowImage->newItem($decoded['row']);
        $objRowText->newItem($decoded['row']);

        $objBlock->item(0)->setting('bgAttachment', 'none');
        $objBlock->item(0)->setting('bgColorPalette', '');

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($decoded, $options);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if(isset($item['content']) && isset($item['imageFileName'])) {
                if ($item['category'] === 'photo') {
                    $objItem->item()->item()->setting('imageSrc', $item['content']);
                    $objItem->item()->item()->setting('imageFileName', $item['imageFileName']);
                    $objRowImage->addItem($objItem->get());
                }
            }
        }
        $objBlock->item()->addItem($objRowImage->get());

        foreach ($sectionData['items'] as $item) {
            if ($item['item_type'] === 'title') {

                $this->textCreation($item, $objRowText);

//                $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                $objBlock->item(0)->item(1)->item(0)->item(0)->item(0)->setText($richText);
                $this->items(0,0,1,0)->setting();
            }
        }

        foreach ($sectionData['items'] as $item)
        {
            if ($item['item_type'] === 'body') {

                $this->textCreation($item, $objRowText);

//                $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
//                $objBlock->item(0)->item(1)->item(0)->item(2)->item(0)->setText($richText);
            }
        }

        $objBlock->item()->addItem($objRowText->get());

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws \Exception
     */
    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(is_array($embedCode)){
                            $objBlock->addItem($this->embedCode($embedCode[$i]));
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
                    $objBlock->addItem($textItem);
                    break;
                case 'Wrapper':
                    $objBlock->addItem($textItem);
                    break;
            }
        }
    }

}