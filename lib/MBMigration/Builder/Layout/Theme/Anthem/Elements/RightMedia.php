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
        Utils::log('Create bloc', 1, "right_media");
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

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting(
                    'imageFileName',
                    $item['imageFileName']
                );

                if ($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting(
                        'imageWidth',
                        $item['settings']['image']['width']
                    );
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting(
                        'imageHeight',
                        $item['settings']['image']['height']
                    );
                }

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title' && $this->showHeader($sectionData)) {

                    $this->richTextCreator($objBlock, $item, $options['currentPageURL'], $options['fontsFamily']);

                    $objBlock->item()->item()->item()->addItem($this->wrapperLine());
                }
            }
        }
        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'body' && $this->showBody($sectionData)) {
                    $this->richTextCreator($objBlock, $item, $options['currentPageURL'], $options['fontsFamily']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());

        return json_encode($block);
    }

    /**
     * @throws \Exception
     */
    private function richTextCreator($objBlock, $item, $currentPageURL, $fontsFamily) {
        $multiElement = [];

        $richText = JS::RichText($item['id'], $currentPageURL, $fontsFamily);

        if (!is_array($richText)) {
            $objBlock->item()->item()->item()->addItem($this->itemWrapperRichText($richText));
        } else {
            if (!empty($richText['icons'])) {
                foreach ($richText['icons'] as $itemIcon) {
                    if ($itemIcon['position'] === 'top') {
                        $multiElement[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                    }
                }
            }

            if(!empty($richText['button'])) {
                foreach ($richText['button'] as $itemButton) {
                    if ($itemButton['position'] === 'top') {
                        $multiElement[] = $this->button($itemButton['items'], $itemButton['align']);
                    }
                }
            }

            if (!empty($richText['text'])) {
                $multiElement[] = $this->itemWrapperRichText($richText['text']);
            }

            if (!empty($richText['embeds'])) {
                $multiElement[] = $this->embedCode($item['content']);
            }

            if (!empty($richText['icons'])) {
                foreach ($richText['icons'] as $itemIcon) {
                    if ($itemIcon['position'] === 'bottom') {
                        $multiElement[] = $this->wrapperIcon($itemIcon['items'], $itemIcon['align']);
                    }
                }
            }

            if(!empty($richText['button'])) {
                foreach ($richText['button'] as $itemButton) {
                    if ($itemButton['position'] === 'bottom') {
                        $multiElement[] = $this->button($itemButton['items'], $itemButton['align']);
                    }
                }
            }

            $objBlock->item()->item()->item()->addItem($this->wrapperColumn($multiElement, true));
        }
    }

}