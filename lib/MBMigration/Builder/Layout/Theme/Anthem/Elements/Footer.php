<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
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
     * @throws \Exception
     */
    protected function Footer(): bool
    {
        Utils::log('Create Footer', 1, "] [createFooter");

        $sectionData = $this->cache->get('mainSection')['footer'];

        $options = [];

        $imageAdd = false;

        $objBlock = new ItemBuilder();
        $objText = new ItemBuilder();
        $objImage = new ItemBuilder();
        $objColum = new ItemBuilder();
        $objIcon = new ItemBuilder();

        $decoded = $this->jsonDecode['blocks']['footer'];

        $objBlock->newItem($decoded['main']);
        $objText->newItem($decoded['item-text']);
        $objImage->newItem($decoded['item-image']);
        $objColum->newItem($decoded['item-empty']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $color = $this->cache->get('nav-subpalette', 'subpalette');
        $style = JS::StylesColorExtractor($options['sectionID'], $options['currentPageURL']);
        $objBlock->setting('bgColorHex', $style['background-color']);

        $options = array_merge($options, ['textColor' => $color['sub-text']]);

        if ($this->checkArrayPath($sectionData, 'settings/background/photo')) {
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
            if ($item['category'] == 'text') {
                $this->setOptionsForUsedFonts($item, $options);
                $this->defaultTextPosition($item, $options);

                $richText = JS::RichText($item['sectionId'], $options['currentPageURL'], $options['fontsFamily']);

                if (!is_array($richText)) {
                    $objBlock->item(0)->addItem(
                        $this->wrapperColumn($this->itemWrapperRichText($richText))
                    );
                } else {
                    if (!empty($richText['icons'])) {

                        $TopWrapperIcon = [];
                        $BottomWrapperIcon = [];

                        foreach ($richText['icons'] as $itemIcon) {
                            if ($itemIcon['position'] === 'top') {
                                $TopWrapperIcon[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                            }

                            if ($itemIcon['position'] === 'bottom') {
                                $BottomWrapperIcon[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                            }
                        }

                        if (!empty($TopWrapperIcon)) {
                            foreach ($TopWrapperIcon as $topItem) {
                                $objBlock->item(0)->addItem($this->wrapperColumn($topItem));
                            }
                        }
                        if (!empty($richText['text'])) {
                            $objBlock->item(0)->addItem(
                                $this->wrapperColumn($this->itemWrapperRichText($richText['text']))
                            );
                        }
                        if (!empty($BottomWrapperIcon)) {

                            foreach ($BottomWrapperIcon as $bottomItem) {
                                $objBlock->item(0)->addItem($this->wrapperColumn($bottomItem));
                            }
                        }
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        $this->cache->set('footerBlock', json_encode($block));

        return true;
    }

    private function iconColumnCreation($IconItems, $objIcon, $objColum, $decoded)
    {
        foreach ($IconItems as $item) {
            $iconName = $this->getDataIconValue($item['content']);
            $objIcon->newItem($decoded['item']);
            $objIcon->setting('linkExternal', $item['href']);
            $objIcon->setting('name', $this->getIcon($iconName['icon']));
            $objColum->item()->addItem($objIcon->get());
        }
    }

}