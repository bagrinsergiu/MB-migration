<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements;

use MBMigration\Core\Logger;
use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Parser\JS;

class Footer extends Element
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
    public function getElement(array $elementData = []): bool
    {
        return $this->Footer();
    }

    /**
     * @throws DOMException
     */
    protected function Footer(): bool
    {
        Logger::instance()->info('Create Footer');

        $sectionData = $this->cache->get('mainSection')['footer'];

        $options = [];

        $imageAdd = false;

        $objBlock = new ItemBuilder();
        $objText  = new ItemBuilder();
        $objImage = new ItemBuilder();
        $objColum = new ItemBuilder();
        $objIcon  = new ItemBuilder();

        $decoded = $this->jsonDecode['blocks']['footer'];

        $objBlock->newItem($decoded['main']);
        $objText->newItem($decoded['item-text']);
        $objImage->newItem($decoded['item-image']);
        $objColum->newItem($decoded['item-empty']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $color = $this->cache->get('nav-subpalette','subpalette');

        $objBlock->setting('bgColorHex', JS::StylesColorExtractor($options['sectionID'], $options['currentPageURL']));

        $options = array_merge($options, ['textColor' => $color['sub-text']]);

        if($this->checkArrayPath($sectionData, 'settings/background/photo')) {
            $imageAdd = true;
            $objImage->item()->item()->setting('imageSrc', $sectionData['settings']['background']['photo']);
            $objImage->item()->item()->setting('imageFileName', $sectionData['settings']['background']['filename']);
            $objImage->item()->item()->setting('sizeType', 'custom');
            $objImage->item()->item()->setting('size', 100);
            $objImage->item()->item()->setting('width', 80);
            $objImage->item()->item()->setting('widthSuffix', "%");
            $objImage->item()->item()->setting('height', 100);
            $objImage->item()->item()->setting('heightSuffix', "%");
        }

        foreach ($sectionData['items'] as $item) {
            $setText = false;

            if ($item['category'] == 'text') {
                $setText = true;
                $this->setOptionsForUsedFonts($item, $options);
                $this->defaultTextPosition($item, $options);

                $richText = JS::RichText($item['sectionId'], $options['currentPageURL'], $options['fontsFamily']);

                $objText->item()->item()->setText($richText);

                if(!$imageAdd){
                    $objText->setting('width', 100);
                    $objBlock->item()->addItem($objText->get());
                } else {
                    $objBlock->item()->addItem($objText->get());
                    $objBlock->item()->addItem($objImage->get());
                }
            }

            $itemsIcon = $this->getDataIconValue($item['content']);

            if(!empty($itemsIcon)){
                foreach ($itemsIcon as $itemIcon){
                    $objIcon->newItem($decoded['item']);
                    $objIcon->setting('linkExternal', $itemIcon['href']);
                    $objIcon->setting('name', $this->getIcon($itemIcon['icon']));
                    $objColum->item()->addItem($objIcon->get());
                }

                if($setText){
                    $objBlock->item(1)->item()->addItem($objColum->get());
                } else {
                    $objBlock->item()->item()->addItem($objColum->get());
                }
            }
        }



        $block = $this->replaceIdWithRandom($objBlock->get());
        $this->cache->set('footerBlock', json_encode($block));

        return true;
    }

}