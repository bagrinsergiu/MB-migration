<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Parser\JS;

class LeftMediaCircle extends Element
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
        return $this->LeftMediaCircle($elementData);
    }

    /**
     * @throws DOMException
     * @throws \Exception
     */
    protected function LeftMediaCircle(array $sectionData)
    {
        Utils::log('Create bloc', 1, "LeftMediaCircle");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media-circle'];
        $block = json_decode($decoded, true);

        $objBlock = new ItemBuilder();
        $objBlock->newItem($decoded);

        $this->generalParameters($objBlock, $options, $sectionData);
        $this->backgroundColor($objBlock, $sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting(
                    'imageFileName',
                    $item['imageFileName']
                );
            }
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title' && $this->showHeader($sectionData)) {
                    $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                    $objBlock->item()->item()->item(1)->item()->item()->setText($richText);

                    $objBlock->item()->item()->item(1)->item()->addItem($this->itemWrapperRichText($richText));
                    $objBlock->item()->item()->item(1)->item()->addItem(
                        $this->wrapperLine(['borderColorHex' => $options['border']['border-bottom-color'] ?? ''])
                    );

                }
                if ($item['item_type'] == 'body' && $this->showHeader($sectionData)) {
                    $this->textCreation($item, $objBlock);
                    $richText = JS::RichText($item['id'], $options['currentPageURL'], $options['fontsFamily']);
                    $objBlock->item()->item()->item(1)->item(1)->item()->setText($richText);
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);

        return json_encode($block);
    }

    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if (!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if (is_array($embedCode)) {
                            $objBlock->item(0)->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                case 'Wrapper':
                    $objBlock->item(0)->addItem($textItem);
                    break;
            }
        }
    }
}