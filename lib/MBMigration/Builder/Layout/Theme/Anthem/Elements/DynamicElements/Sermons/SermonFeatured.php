<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Sermons;

use DOMException;
use Exception;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\DynamicElement;

class SermonFeatured extends DynamicElement
{

    /**
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->SermonFeatured($elementData);
    }

    /**
     * @throws DOMException
     * @throws Exception
     */
    private function SermonFeatured(array $sectionData)
    {
        $options = [];
        $elementName = 'SermonFeatured';

        $currentPageSlug = $this->cache->get('tookPage')['slug'];

        $objBlock = new ItemBuilder();
        $objHead  = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['dynamic'][$elementName];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->backgroundColor($objBlock, $sectionData);

        $blockHead = false;

        foreach ($sectionData['head'] as $headItem)
        {
            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objHead);
                $objHead->item()->addItem($this->wrapperLine(
                    [
                        'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                    ]
                ));
            }
        }

        foreach ($sectionData['head'] as $headItem)
        {
            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objHead);
            }
        }

        foreach ($sectionData['items'] as $headItem)
        {
            if ($headItem['item_type'] === 'title' && $this->showHeader($sectionData)) {
                $this->textCreation($headItem, $objHead);
                $blockHead = true;
                $objHead->item()->addItem($this->wrapperLine(
                    [
                        'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                    ]
                ));
            }
        }

        foreach ($sectionData['items'] as $headItem)
        {
            if ($headItem['item_type'] === 'body' && $this->showBody($sectionData)) {
                $blockHead = true;
                $this->textCreation($headItem, $objHead);
            }
        }

        $objBlock->item(0)->addItem($objHead->get(), 0);

        $objBlock->item()->item(1)->item()->setting('sermonSlug', $this->createSlug($sectionData['settings']['containTitle']));
        $objBlock->item()->item(2)->item()->setting('sermonSlug', $this->createSlug($sectionData['settings']['containTitle']));


        $objBlock->item()->item(1)->item()->setting('titleColorHex', $sectionData['settings']['palette']['link'] ?? "#1e1eb7");
        $objBlock->item()->item(1)->item()->setting('titleColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('titleColorPalette', "");

        $objBlock->item()->item(1)->item()->setting('hoverTitleColorHex', $sectionData['settings']['palette']['link'] ?? "#1e1eb7");
        $objBlock->item()->item(1)->item()->setting('hoverTitleColorOpacity', 0.7);
        $objBlock->item()->item(1)->item()->setting('hoverTitleColorPalette', "");

        $objBlock->item()->item(1)->item()->setting('metaLinksColorHex', $sectionData['settings']['palette']['link'] ?? "#3d79ff");
        $objBlock->item()->item(1)->item()->setting('metaLinksColorOpacity', 1);
        $objBlock->item()->item(1)->item()->setting('metaLinksColorPalette', "");

        $objBlock->item()->item(1)->item()->setting('hoverMetaLinksColorHex', $sectionData['settings']['palette']['link'] ?? "#3d79ff");
        $objBlock->item()->item(1)->item()->setting('hoverMetaLinksColorOpacity', 0.7);
        $objBlock->item()->item(1)->item()->setting('hoverMetaLinksColorPalette', "");

        $objBlock->item()->item(2)->item()->setting('colorHex', $sectionData['style']['sermon']['text'] ?? "#ebeff2");
        $objBlock->item()->item(2)->item()->setting('colorOpacity', 1);
        $objBlock->item()->item(2)->item()->setting('colorPalette', "");

        $objBlock->item()->item(2)->item()->setting('titleColorHex', $sectionData['style']['sermon']['text'] ?? "#ebeff2");
        $objBlock->item()->item(2)->item()->setting('titleColorOpacity', 1);
        $objBlock->item()->item(2)->item()->setting('titleColorPalette', "");

        $objBlock->item()->item(2)->item()->setting('hoverTitleColorHex', $sectionData['style']['sermon']['text'] ?? "#ebeff2");
        $objBlock->item()->item(2)->item()->setting('hoverTitleColorOpacity', 1);
        $objBlock->item()->item(2)->item()->setting('hoverTitleColorPalette', "");

        $objBlock->item()->item(2)->item()->setting('previewColorHex', $sectionData['style']['sermon']['text'] ?? "#ebeff2");
        $objBlock->item()->item(2)->item()->setting('previewColorOpacity', 1);
        $objBlock->item()->item(2)->item()->setting('previewColorPalette', "");

        $objBlock->item()->item(2)->item()->setting('parentBgColorHex', $sectionData['style']['sermon']['bg'] ?? '#505050');
        $objBlock->item()->item(2)->item()->setting('parentBgColorOpacity', 1);
        $objBlock->item()->item(2)->item()->setting('parentBgColorPalette', "");

        $block = $this->replaceIdWithRandom($objBlock->get());

        return json_encode($block);
    }


    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(!empty($embedCode)){
                            $objBlock->item()->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                    $textItem['value']['mobileHorizontalAlign'] = 'center';
                    foreach ($textItem['value']['items'] as &$iconItem) {
                        if ($iconItem['type'] == 'Button') {
                            $iconItem['value']['borderStyle'] = "none";
                        }

                    }
                    $objBlock->item()->addItem($textItem);
                    break;
                case 'Wrapper':
                    $objBlock->item()->addItem($textItem);
                    break;
            }
        }
    }
}
