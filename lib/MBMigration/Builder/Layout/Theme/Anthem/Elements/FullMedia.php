<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class FullMedia extends Element
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
        return $this->FullMedia($elementData);
    }

    /**
     * @throws DOMException
     * @throws \Exception
     */
    protected function FullMedia(array $sectionData)
    {
        Utils::log('Create full media', 1, "full_media");

        $objBlock = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media']['main'];
        $general = $this->jsonDecode['blocks']['full-media'];
        $blockImage = $this->jsonDecode['blocks']['full-media']['image'];

        $objBlock->newItem($decoded);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($general, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title' && $this->showHeader($sectionData)) {

                    $this->richTextCreator($objBlock, $item, $options['currentPageURL'], $options['fontsFamily']);

                    $objBlock->item()->item()->item()->addItem($this->wrapperLine(['borderColorHex' => $options['borderColorHex']]));
                }
            }
        }
        
        foreach ($sectionData['items'] as $item) {
            if($item['item_type']=='body' && $this->showBody($sectionData)) {

                $this->richTextCreator($objBlock, $item, $options['currentPageURL'], $options['fontsFamily']);
            }
        }
            
        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'photo' && !empty($item['content'])) {
                $imageOptions = [
                    'imageSrc' => $item['content'],
                    'imageFileName' => $item['imageFileName']
                ];

                if (!empty($item['link'])) {
                    $imageOptions = [
                        'linkType' => 'external',
                        'linkExternal' => $item['link']
                    ];
                }
                $objBlock->item()->item()->item()->addItem($this->wrapperImage($imageOptions, $blockImage));
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