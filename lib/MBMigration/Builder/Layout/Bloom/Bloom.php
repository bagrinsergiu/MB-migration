<?php

namespace MBMigration\Builder\Layout\Bloom;

use Exception;
use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\VariableCache;
use DOMDocument;
use InvalidArgumentException;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;

class Bloom extends Layout
{
    protected  $jsonDecode;
    protected  $dom;
    protected  $cache;
    protected  $textPosition;
    /**
     * @var string
     */
    protected $layoutName;

    /**
     * @throws Exception
     */
    public function __construct(VariableCache $cache)
    {
        $this->dom   = new DOMDocument();

        $this->layoutName = 'Bloom';

        $this->cache = $cache;
        $this->textPosition = ['center' => ' brz-text-lg-center', 'left' => ' brz-text-lg-left', 'right' => ' brz-text-lg-right'];

        Utils::log('Connected!', 4, $this->layoutName . ' Builder');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        if($menuList['create'] == false) {
            if ($this->createMenu($menuList)) {
                Utils::log('Success create MENU', 1, $this->layoutName . "] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, $this->layoutName . "] [__construct");
            }
        }
        $this->createFooter($menuList);
    }

    protected function createMenu($menuList)
    {
        Utils::log('Create block menu', 1, $this->layoutName . "] [createMenu");
        $this->cache->set('currentSectionData', $menuList);
        $decoded = $this->jsonDecode['blocks']['menu'];
        $block = json_decode($decoded['main'], true);
        $lgoItem = $this->cache->get('header','mainSection');
        foreach ($lgoItem['items'] as $item)
        {
            if ($item['category'] = 'photo')
            {
                $logo = $item['content'];
            }
        }
        $itemMenu = json_decode($decoded['item'], true);

        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $logo; //logo
        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['menuSelected'] = $menuList['uid']; //menu items

        $itemsMenu = $this->creatingMenuTree($menuList['list'], $itemMenu);

        if($this->checkArrayPath($lgoItem, 'settings/color/subpalette')) {
            $block['value']['items'][0]['value']['bgColorHex'] = $lgoItem['color'];
            $block['value']['items'][0]['value']['bgColorType'] = 'solid';
            $this->cache->set('flags', ['createdFirstSection'=> false, 'bgColorOpacity' => true]);
        } else {
            $block['value']['items'][0]['value']['bgColorOpacity'] = 0;
            $block['value']['items'][0]['value']['tempBgColorOpacity'] = 0;
            $block['value']['items'][0]['value']['bgColorType'] = 'ungrouped';
            $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);
        }


        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'] = $itemsMenu;


        $block = $this->replaceIdWithRandom($block);
        $this->cache->set('menuBlock', json_encode($block));

        return true;
    }

    protected function creatingMenuTree($menuList, $blockMenu): array
    {
        $treeMenu = [];
        foreach ($menuList as $item)
        {
            $blockMenu['value']['itemId'] = $item['collection'];
            $blockMenu['value']['title'] = $item['name'];
            if($item['slug'] == 'home') {
                $blockMenu['value']['url'] = '/';
            } else {
                $blockMenu['value']['url'] = $item['slug'];
            }
            $blockMenu['value']['items'] = $this->creatingMenuTree($item['child'], $blockMenu);
            if($item['landing'] == false){
                $blockMenu['value']['url'] = $blockMenu['value']['items'][0]['value']['url'];
            }

            $encodeItem = json_encode($blockMenu);

            $blockMenu['value']['id'] = $this->getNameHash($encodeItem);

            $treeMenu[] = $blockMenu;
        }
        return $treeMenu;
    }

    protected function left_media(array $sectionData) 
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [left_media");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];

        $this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item){
            if($item['category'] == 'photo' && $item['content']!= ''){
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
                if($this->checkArrayPath($item, 'settings/image')){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageWidth'] = $item['settings']['image']['width'];
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageHeight'] = $item['settings']['image']['height'];
                }
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content'], 'brz-text-lg-left');

                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-left');

                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }
    
    protected function right_media(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [right_media");
        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['right-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];

        $this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content'] !== ''){
                $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content'], 'brz-text-lg-right');

                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-right');

                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function full_media($sectionData)
    {
        Utils::log('Create full media', 1, $this->layoutName . "] [full_media");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];

        $this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item){
            if($item['category'] == 'photo' && $item['content'] !== ''){
                $block['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
                $block['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['_id'] = $this->generateCharID();
                $block = $this->replaceValue($block, "paddingBottom", 270);
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);

                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }

                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);

                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }

                }
            }
        }
        if($sectionData['category'] == 'donation')
        {
            $button =  json_decode($this->jsonDecode['blocks']['donation'], true);
            $button['value']['items'][0]['value']['text'] = $sectionData['settings']['layout']['donations']['text'];
            $button['value']['items'][0]['value']['linkExternal'] = $sectionData['settings']['sections']['donations']['url'];
            $button['value']['items'][0]['value']['hoverBgColorHex'] = $sectionData['settings']['color']['bg'];
            $block['value']['items'][0]['value']['items'][] = $button;
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function full_text(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [full_text");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-text'];
        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption'))
        {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed')
            {
                return $this->parallaxScroll($sectionData);
            }
        }

            if (!$this->checkArrayPath($sectionData, 'settings/sections/background/filename')) {
                $block = json_decode($decoded['main'], true);

                $block['value']['items'][0]['value']['bgColorPalette'] = '';
                if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
                    $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];
                }

                $this->marginAndPaddingOffset($block);

                foreach ($sectionData['items'] as $item) {
                    if ($item['category'] == 'text') {
                        if ($item['item_type'] == 'title') {
                            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content'], '', '');
                            
                            if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                            }
                        }
                        if ($item['item_type'] == 'body') {
                            $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], '', '');
                            
                            if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                                $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                            }
                        }
                    }
                }
            } else {
                Utils::log('Set background', 1, $this->layoutName . "] [full_text");
                $block = json_decode($decoded['background'], true);

                $block['value']['items'][0]['value']['bgImageFileName'] = $sectionData['settings']['sections']['background']['filename'];
                $block['value']['items'][0]['value']['bgImageSrc'] = $sectionData['settings']['sections']['background']['photo'];

                $this->marginAndPaddingOffset($block);

                foreach ($sectionData['items'] as $item) {
                    if ($item['category'] == 'text') {
                        if ($item['item_type'] == 'title') {
                            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content'], '', '');

                            if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                            }

                        }
                        if ($item['item_type'] == 'body') {
                            $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], '', '');

                            if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                                $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                            }

                        }

                    }
                }
            }

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function right_media_circle(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [right_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        return '';
    }

    protected function left_media_circle(array $sectionData){
        Utils::log('Create bloc', 1, $this->layoutName . "] [left_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media-circle'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];
        }
        $this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item){
            if($item['category'] == 'photo' && $item['content'] !== ''){
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);

                    if($this->checkArrayPath($sectionData, 'settings/color/text')) {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
                    }

                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function top_media_diamond(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [top_media_diamond");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['top-media-diamond'];

        $decode = json_decode($decoded['main'], true);

        $decode['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($sectionData[0]['content']);
        $decode['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['color'] = $sectionData['settings']['color']['text'];
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($sectionData[1]['content']);
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $sectionData['settings']['color']['text'];

        return json_encode($decode);
    }

    /**
     * @throws \Exception
     */
    protected function grid_layout(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $objBlock = new ItemSetter($decoded['main']);
        $objItem = new ItemSetter($decoded['item']);

        $block = json_decode($decoded['main'], true);
        $item  = json_decode($decoded['item'], true);

        $this->marginAndPaddingOffset($block);

        $objBlock->item(0)->setting('bgColorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        foreach ($sectionData['items'] as $section)
        {
            $objItem->newItem($decoded['item']);

            if(isset($section['item'])) {
                switch ($section['category']) {
                    case 'text':
                        if ($item['item_type'] == 'title') {
                            break;
                        }
                        if ($item['item_type'] == 'body') {
                            break;
                        }
                    case 'list':
                        foreach ($section['item'] as $sectionItem) {
                            if ($sectionItem['category'] == 'photo') {

                                $objItem->item(0)->item(0)->setting('imageSrc', $sectionItem['content']);
                                $objItem->item(0)->item(0)->setting('imageFileName', $sectionItem['content']);

                                if ($sectionItem['link'] != '') {
                                    $objItem->item(0)->item(0)->setting('linkType', "external");
                                    $objItem->item(0)->item(0)->setting('linkExternal', '/' . $sectionItem['content']);
                                }
                            }
                            if ($sectionItem['category'] == 'text') {
                                if ($sectionItem['item_type'] == 'title') {
                                    $objItem->item(0)->item(0)->setText($this->replaceTitleTag($sectionItem['content']));
                                }
                            }
                        }
                        break;
                }
            } else {
                if ($section['category'] == 'photo') {

                    $objItem->item(0)->item(0)->setting('imageSrc', $section['content']);
                    $objItem->item(0)->item(0)->setting('imageFileName', $section['imageFileName']);

                    if ($section['link'] != '') {
                        $objItem->item(0)->item(0)->setting('linkType', "external");
                        $objItem->item(0)->item(0)->setting('linkExternal', '/' . $sectionItem['content']);
                    }
                }
                if ($section['category'] == 'text') {
                    if ($section['item_type'] == 'title') {
                        $objItem->item(0)->addItem($this->itemWrapperRichText($section['content']));
                    }
                    if ($section['item_type'] == 'body') {
                        $objItem->item(0)->addItem($this->itemWrapperRichText($section['content']));
                    }
                }
            }
            $objBlock->item(0)->item(0)->addItem($objItem->get());
        }

        $objBlock->item(0)->item(0)->addItem($objItem->get());

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

//    protected function list_layout(array $sectionData)
//    {
//        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");
//        $this->cache->set('currentSectionData', $sectionData);
//        $decoded = $this->jsonDecode['blocks']['list-layout'];
//        $sb = new SectionBuilder();
//        $block = json_decode($decoded['main'], true);
//        $item  = json_decode($decoded['item'], true);
//        $image  = json_decode($decoded['image'], true);
//
//        $block['value']['items'][0]['value']['bgColorPalette'] = '';
//        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
//            $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];
//        }
//
//        //$this->marginAndPaddingOffset($block);
//
//        if($this->checkArrayPath($sectionData, 'settings/sections/background'))
//        {
//            $background = $this->getKeyRecursive('background', 'sections', $sectionData);
//
//            if(isset($background['photo']) && isset($background['filename'])) {
//                $block['value']['items'][0]['value']['bgImageSrc'] = $background['photo'];
//                $block['value']['items'][0]['value']['bgImageFileName'] = $background['filename'];
//            }
//            if(isset($background['opacity']) ){
//
//                $opacity = 1 - $background['opacity'];
//                $block['value']['items'][0]['value']['bgColorOpacity'] = $opacity;
//                $block['value']['items'][0]['value']['tempBgColorOpacity'] = $opacity;
//            }
//        }
//
//        $position = 0;
//        foreach ($sectionData['head'] as $hitem)
//        {
//            if($hitem['category'] == 'text') {
//                if ($hitem['item_type'] === 'title') {
//                    $content = $this->replaceTitleTag($hitem['content'], 'brz-text-lg-center');
//                    $position = 0;
//                } else {
//                    $content = $this->replaceParagraphs($hitem['content'], 'brz-text-lg-center');
//                    $position++;
//                }
//                $wrapper = $this->itemWrapperRichText($content, true);
//                $this->insertElementAtPosition($block, 'value/items/0/value/items', $wrapper, $position);
//            }
//        }
//
//        $p = 0;
//        foreach ($sectionData['items'] as $section)
//        {
//            switch ($section['category']) {
//                case 'text':
//
//                    $this->integrationOfTheWrapperItem(
//                        $block,
//                        $section,
//                        'value/items/0/value/items'
//                    );
//
////                    if ($section['item_type'] === 'title') {
////                        $content = $this->replaceTitleTag($section['content'], 'brz-text-lg-center');
////                        $p = 0;
////                    } else {
////                        $content = $this->replaceParagraphs($section['content'], 'brz-text-lg-center');
////                        $p++;
////                    }
////                    $wrapper = $this->itemWrapper($content, true);
////                    $this->insertElementAtPosition($block, 'value/items/0/value/items', $wrapper, $p);
//                    break;
//                case 'list':
//                    foreach ($section['item'] as $sectionItem) {
//                        if($sectionItem['category'] == 'photo' && $sectionItem['content'] != '' ) {
//
//                            $image['value']['imageSrc'] = $sectionItem['content'];
//                            $image['value']['imageFileName'] = $sectionItem['imageFileName'];
//                            if($sectionItem['link'] != '') {
//                                $image['value']['linkType'] = "external";
//                                $image['value']['linkExternal'] = '/' . $sectionItem['link'];
//                            }
//
//                            $item['value']['items'][0]['value']['value'][0]['value']['items'][0] = $image;
//                        }
//
//                        $this->integrationOfTheWrapperItem(
//                            $block,
//                            $sectionItem,
//                            'value/items/0/value/items'
//                        );
//
////                        if($sectionItem['category'] == 'text') {
////                            if($sectionItem['item_type']=='title') {
////                                $item['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($sectionItem['content'], 'brz-text-lg-left');
////                            }
////                            if($sectionItem['item_type']=='body') {
////                                $item['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($sectionItem['content'], 'brz-text-lg-left');
////                            }
////                        }
//                    }
//                    break;
//            }
//            //$resultRemove[] = $item;
//        }
//
//        //$this->mergeArrayAtPath($block, 'value/items/0/value/items', $resultRemove);
//        //$block['value']['items'][0]['value']['items'][0]['value']['items'] = $resultRemove;
//
//        $block = $this->replaceIdWithRandom($block);
//        return json_encode($block);
//    }

//    protected function gallery_layout(array $sectionData)
//    {
//        Utils::log('Create bloc', 1, $this->layoutName . "] [gallery_layout");
//        $this->cache->set('currentSectionData', $sectionData);
//
//        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);
//
//        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
//        $block = json_decode($decoded['main'], true);
//        $slide  = json_decode($decoded['item'], true);
//
//        $this->marginAndPaddingOffset($block);
//
//        foreach ($sectionData['items'] as $item){
//            $slide['value']['bgImageFileName'] = $item['imageFileName'];
//            $slide['value']['bgImageSrc']      = $item['content'];
//
//            $this->insertElementAtPosition($block, 'value/items', $slide);
//        }
//        $block = $this->replaceIdWithRandom($block);
//        return json_encode($block);
//    }

    protected function empty_layout(array $sectionData)
    {
        $this->cache->set('curentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['empty-layout'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];
        }
        return json_encode($block);
    }
    
    protected function createFooter(): void
    {
        Utils::log('Create Footer', 1, $this->layoutName . "] [createFooter");
        $sectionData = $this->cache->get('mainSection')['footer'];
        $decoded = $this->jsonDecode['blocks']['footer']['main'];
        $iconItem = $this->jsonDecode['blocks']['footer']['item'];
        $block = json_decode($decoded, true);
        $blockIcon = json_decode($iconItem, true);

        $block['value']['bgColorPalette'] = '';
        $block['value']['bgColorHex'] = $sectionData['settings']['color']['subpalette']['bg'];
        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                $itemsIcon = $this->getDataIconValue($item['content']);
                if(!empty($itemsIcon)){
                    foreach ($itemsIcon as $itemIcon){
                        $blockIcon['value']['linkExternal'] = $itemIcon['href'];
                        $blockIcon['value']['name'] = $this->getIcon($itemIcon['icon']);

                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][] = $blockIcon;
                    }
                }
                $block['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
            }
        }
        $this->cache->set('footerBlock', json_encode($block));
    }

    public function callMethod($methodName, $params = null)
    {
        $verifiedMethodName = $this->replaceInName($methodName);
        if (method_exists($this, $verifiedMethodName)) {
            if(!isset($params)){
                $params = $this->jsonDecode;
            }
            Utils::log('Call method ' . $verifiedMethodName , 1, $this->layoutName . "] [callDynamicMethod");
            return call_user_func_array(array($this, $verifiedMethodName), [$params]);
        }
        Utils::log('Method ' . $verifiedMethodName . ' does not exist', 2, $this->layoutName . "] [callDynamicMethod");
        return false;
    }

}