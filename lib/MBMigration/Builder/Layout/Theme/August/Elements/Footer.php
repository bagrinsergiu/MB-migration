<?php

namespace MBMigration\Builder\Layout\Theme\August\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Core\Utils;

class Footer
{

    protected function createFooter()
    {
        \MBMigration\Core\Logger::instance()->info('Create Footer');

        $options = [];
        $imageAdd = false;

        $objBlock = new ItemBuilder();
        $objText  = new ItemBuilder();
        $objImage = new ItemBuilder();
        $objColunm = new ItemBuilder();
        $objIcon  = new ItemBuilder();

        $sectionData = $this->cache->get('mainSection')['footer'];

        $decoded = $this->jsonDecode['blocks']['footer'];

        $objBlock->newItem($decoded['main']);
        $objIcon->newItem($decoded['item']);
        $objText->newItem($decoded['item-text']);
        $objImage->newItem($decoded['item-image']);
        $objColunm->newItem($decoded['item-empty']);

        $block = json_decode($decoded, true);

        if($this->checkArrayPath($sectionData, 'settings/color/subpalette')) {

            $block['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['subpalette']['bg']);
            $objBlock->setting('bgColorPalette', '');
            $objBlock->setting('bgColorHex', $sectionData['settings']['color']['subpalette']['bg']);
            $options = array_merge($options, ['color' => $sectionData['settings']['color']['subpalette']]);
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                $itemsIcon = $this->getDataIconValue($item['content']);
                if(!empty($itemsIcon)){
                    foreach ($itemsIcon as $itemIcon){
                        $objIcon->setting('linkExternal', $itemIcon['href']);
                        $objIcon->setting('name', $this->getIcon($itemIcon['icon']));
                        $objBlock->item(1)->item(0)->item(0)->item(0)->addItem($objIcon->get());
                    }
                }

                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                $objText->item()->item()->setText($this->replaceString($item['content'], $options));
            }
        }

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

        if(!$imageAdd){
            $objText->setting('width', 100);
            $objBlock->item()->addItem($objText->get());
        } else {
            $objBlock->item()->addItem($objText->get());
            $objBlock->item()->addItem($objImage->get());
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        $this->cache->set('footerBlock', json_encode($block));
    }

}