<?php

namespace MBMigration\Builder\Layout\Anthem;

use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\VariableCache;
use DOMDocument;
use InvalidArgumentException;
use MBMigration\Core\Utils;

class Anthem
{
    private $jsonDecode;
    private $dom;
    private $cache;
    private  $textPosition;

    public function __construct(VariableCache $cache)
    {
        $this->dom   = new DOMDocument();
        $this->cache = $cache;
        $this->textPosition = ['center' => ' brz-text-lg-center', 'left' => ' brz-text-lg-left', 'right' => ' brz-text-lg-right'];

        Utils::log('Connected!', 4, 'Anthem Builder');
        $file = __DIR__.'\blocksKit.json';

        if (file_exists($file))
        {
            $fileContent = file_get_contents($file);
            $this->jsonDecode = json_decode($fileContent, true);
            if(empty($fileContent))
            {
                Utils::log('File empty', 2, "Anthem] [__construct");
                exit;
            }
            Utils::log('File exist: ' .$file , 1, "Anthem] [__construct");
        }
        else
        {
            Utils::log('File does not exist', 2, "Anthem] [__construct");
            exit;
        }

        $menuList = $this->cache->get('menuList');

        if($menuList['create'] == false) {
            if ($this->createMenu($menuList)) {
                Utils::log('Success create MENU', 1, "Anthem] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, "Anthem] [__construct");
            }
        }
        $this->createFooter($menuList);
    }

    private function createMenu($menuList): bool
    {
        Utils::log('Create block menu', 1, "Anthem] [createMenu");
        $this->cache->set('currentSectionData', $menuList);
        $decoded = $this->jsonDecode['blocks']['menu'];
        $block = json_decode($decoded['main'], true);
        $lgoItem = $this->cache->get('header','mainSection');
        foreach ($lgoItem['items'] as $item)
        {
            if ($item['category'] = 'photo')
            {
                $logo['imageSrc'] = $item['content'];
                $logo['imageFileName'] = $item['imageFileName'];
            }
        }
        $itemMenu = json_decode($decoded['item'], true);

        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $logo['imageSrc'];
        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $logo['imageFileName'];
        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['menuSelected'] = $menuList['uid'];

        $itemsMenu = $this->creatingMenuTree($menuList['list'], $itemMenu);

        if($this->checkArrayPath($lgoItem, 'settings/color/subpalette')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($lgoItem['color']);
            $block['value']['items'][0]['value']['bgColorType'] = 'solid';
            $this->cache->set('flags', ['createdFirstSection'=> false, 'bgColorOpacity' => true]);
        } else {
            $color = $this->cache->get('subpalette1','subpalette');
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($color['bg']);
            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['subMenuBgColorHex'] = strtolower($color['bg']);
            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['subMenuColorHex'] = strtolower($color['text']);
            $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['colorHex'] = strtolower($color['text']);
            $block['value']['items'][0]['value']['bgColorOpacity'] = 1;
            $block['value']['items'][0]['value']['tempBgColorOpacity'] = 0;
            $block['value']['items'][0]['value']['bgColorType'] = 'ungrouped';
            $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);
        }


        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'] = $itemsMenu;


        $block = $this->replaceIdWithRandom($block);
        $this->cache->set('menuBlock', json_encode($block));

        return true;
    }

    private function creatingMenuTree($menuList, $blockMenu): array
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

    private function left_media(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [left_media");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['bg']);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, "Anthem] [left_media");

            $block['value']['items'][0]['value']['bgImageFileName'] = $sectionData['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc'] = $sectionData['settings']['sections']['background']['photo'];
            $block['value']['items'][0]['value']['bgColorOpacity'] = 0;
            $block['value']['items'][0]['value']['bgColorType'] = "none";
        }

        //$this->marginAndPaddingOffset($block);

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
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-left');
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }
    private function right_media(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [right_media");
        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['right-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];

        //$this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item){
            if($item['category'] == 'photo' && $item['content'] !== ''){
                $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content'], 'brz-text-lg-right');
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-right');
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function full_media($sectionData)
    {
        Utils::log('Create full media', 1, "Anthem] [full_media");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['bg']);
        }

        //$this->marginAndPaddingOffset($block);

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
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
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

    private function full_text(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [full_text");
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
            } else {
                $block['value']['items'][0]['value']['bgColorHex'] = $this->cache->get('subpalette1','subpalette')['bg'];
            }


            //$this->marginAndPaddingOffset($block);

            foreach ($sectionData['items'] as $item) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    }
                    if ($item['item_type'] == 'body') {
                        $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                    }
                }
            }
        } else {
            Utils::log('Set background', 1, "Anthem] [full_text");
            $block = json_decode($decoded['background'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $sectionData['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc'] = $sectionData['settings']['sections']['background']['photo'];

            //$this->marginAndPaddingOffset($block);

            foreach ($sectionData['items'] as $item) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    }
                    if ($item['item_type'] == 'body') {
                        $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function parallaxScroll(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [full_text (parallaxScroll)");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-text'];

        if(!empty($sectionData['settings']['sections']['background'])) {
            $block = json_decode($decoded['parallax-scroll'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $sectionData['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc']      = $sectionData['settings']['sections']['background']['photo'];

        } else {
            Utils::log('Set background', 1, "Anthem] [full_text (parallaxScroll)");
            $block = json_decode($decoded['background'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $sectionData['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc']      = $sectionData['settings']['sections']['background']['photo'];

            foreach ($sectionData['items'] as $item){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    }
                    if($item['item_type']=='body'){
                        $block['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }


    public function two_horizontal_text($sectionData)
    {
        Utils::log('Create full media', 1, "Anthem] [full_media");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['two-horizontal-text'];
        $block = json_decode($decoded, true);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['bg']);
        }

        foreach ($sectionData['items'] as $item) {

            if($item['group'] == 0){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    }
                    if($item['item_type']=='body'){
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-left');
                    }
                }
            }
            if($item['group'] == 1){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    }
                    if($item['item_type']=='body'){
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-left');
                    }
                }

            }

        }

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function right_media_circle(array $sectionData){
        Utils::log('Create bloc', 1, "Anthem] [right_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        return '';
    }

    private function left_media_circle(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [left_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media-circle'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['bg']);
        }
        //$this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item){
            if($item['category'] == 'photo' && $item['content'] !== ''){
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function top_media_diamond(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [top_media_diamond");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['top-media-diamond'];

        $decode = json_decode($decoded['main'], true);

        $decode['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($sectionData[0]['content']);
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($sectionData[1]['content']);

        return json_encode($decode);
    }

    private function grid_layout(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [grid_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        //$objItem = new ItemSetter($decoded['item']);

        $block = json_decode($decoded['main'], true);
        $item  = json_decode($decoded['item'], true);

//        $this->marginAndPaddingOffset($block);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];
        }
        if(!empty($sectionData['settings']['sections']['background'])) {
            $block = json_decode($decoded['parallax-scroll'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $sectionData['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc']      = $sectionData['settings']['sections']['background']['photo'];
        }

        $path = Utils::findKeyPath($block, '_id');

        foreach ($sectionData['items'] as $section)
        {
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
                                $item['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $sectionItem['content'];
                                $item['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $sectionItem['imageFileName'];
                                if ($sectionItem['link'] != '') {
                                    $item['value']['items'][0]['value']['items'][0]['value']['linkType'] = "external";
                                    $item['value']['items'][0]['value']['items'][0]['value']['linkExternal'] = '/' . $sectionItem['link'];
                                }
                            }
                            if ($sectionItem['category'] == 'text') {
                                if ($sectionItem['item_type'] == 'title') {
                                    $item['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($sectionItem['content']);
                                }
                            }
                        }
                        break;
                }
            } else {
                if ($section['category'] == 'photo') {
                    $item['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $section['content'];
                    $item['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $section['imageFileName'];
                    if ($section['link'] != '') {
                        $item['value']['items'][0]['value']['items'][0]['value']['linkType'] = "external";
                        $item['value']['items'][0]['value']['items'][0]['value']['linkExternal'] = '/' . $section['link'];
                    }
                }
                if ($section['category'] == 'text') {
                    if ($section['item_type'] == 'title') {

                        $objItem->addItem($this->itemWrapperRichText($section['content']));

                        $item = $this->itemWrapperRichText($this->replaceTitleTag($section['content']));
                    }
                    if ($section['item_type'] == 'body') {
                        $objItem->addItem($this->itemWrapperRichText($section['content']));
                    }
                }
            }
            $resultRemove[] = $item;
        }
        $block['value']['items'][0]['value']['items'][0]['value']['items'] = $resultRemove;

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function list_layout(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [grid_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['list-layout'];
        $sb = new SectionBuilder();
        $block = json_decode($decoded['main'], true);
        $item  = json_decode($decoded['item'], true);
        $image  = json_decode($decoded['image'], true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];
        }

        //$this->marginAndPaddingOffset($block);

        if($this->checkArrayPath($sectionData, 'settings/sections/background'))
        {
            $background = $this->getKeyRecursive('background', 'sections', $sectionData);

            if(isset($background['photo']) && isset($background['filename'])) {
                $block['value']['items'][0]['value']['bgImageSrc'] = $background['photo'];
                $block['value']['items'][0]['value']['bgImageFileName'] = $background['filename'];
            }
            if(isset($background['opacity']) ){

                $opacity = 1 - $background['opacity'];
                $block['value']['items'][0]['value']['bgColorOpacity'] = $opacity;
                $block['value']['items'][0]['value']['tempBgColorOpacity'] = $opacity;
            }
        }

        $position = 0;
        foreach ($sectionData['head'] as $hitem)
        {
            if($hitem['category'] == 'text') {
                if ($hitem['item_type'] === 'title') {
                    $content = $this->replaceTitleTag($hitem['content'], 'brz-text-lg-center');
                    $position = 0;
                } else {
                    $content = $this->replaceParagraphs($hitem['content'], 'brz-text-lg-center');
                    $position++;
                }
                $wrapper = $this->itemWrapperRichText($content, true);
                $this->insertElementAtPosition($block, 'value/items/0/value/items', $wrapper, $position);
            }
        }

        $p = 0;
        foreach ($sectionData['items'] as $section)
        {
            switch ($section['category']) {
                case 'text':

                    $this->integrationOfTheWrapperItem(
                        $block,
                        $section,
                        'value/items/0/value/items'
                    );

//                    if ($section['item_type'] === 'title') {
//                        $content = $this->replaceTitleTag($section['content'], 'brz-text-lg-center');
//                        $p = 0;
//                    } else {
//                        $content = $this->replaceParagraphs($section['content'], 'brz-text-lg-center');
//                        $p++;
//                    }
//                    $wrapper = $this->itemWrapper($content, true);
//                    $this->insertElementAtPosition($block, 'value/items/0/value/items', $wrapper, $p);
                    break;
                case 'list':
                    foreach ($section['item'] as $sectionItem) {
                        if($sectionItem['category'] == 'photo' && $sectionItem['content'] != '' ) {

                            $image['value']['imageSrc'] = $sectionItem['content'];
                            $image['value']['imageFileName'] = $sectionItem['imageFileName'];
                            if($sectionItem['link'] != '') {
                                $image['value']['linkType'] = "external";
                                $image['value']['linkExternal'] = '/' . $sectionItem['link'];
                            }

                            $item['value']['items'][0]['value']['value'][0]['value']['items'][0] = $image;
                        }

                        $this->integrationOfTheWrapperItem(
                            $block,
                            $sectionItem,
                            'value/items/0/value/items'
                        );

//                        if($sectionItem['category'] == 'text') {
//                            if($sectionItem['item_type']=='title') {
//                                $item['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($sectionItem['content'], 'brz-text-lg-left');
//                            }
//                            if($sectionItem['item_type']=='body') {
//                                $item['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($sectionItem['content'], 'brz-text-lg-left');
//                            }
//                        }
                    }
                    break;
            }
            //$resultRemove[] = $item;
        }

        //$this->mergeArrayAtPath($block, 'value/items/0/value/items', $resultRemove);
        //$block['value']['items'][0]['value']['items'][0]['value']['items'] = $resultRemove;

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function gallery_layout(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [gallery_layout");
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded['main'], true);
        $slide  = json_decode($decoded['item'], true);

        //$this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item){
            $slide['value']['bgImageFileName'] = $item['imageFileName'];
            $slide['value']['bgImageSrc']      = $item['content'];

            $this->insertElementAtPosition($block, 'value/items', $slide);
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function empty_layout(array $sectionData)
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

    private function create_Default_Page()
    {
        Utils::log('Create structure default page', 1, "Anthem] [top_media_diamond");

        //$decoded = $this->jsonDecode['blocks']['defaultBlocks'];

    }

    private function createFooter()
    {
        Utils::log('Create Footer', 1, "Anthem] [createFooter");
        $sectionData = $this->cache->get('mainSection')['footer'];
        $decoded = $this->jsonDecode['blocks']['footer']['main'];
        $iconItem = $this->jsonDecode['blocks']['footer']['item'];
        $block = json_decode($decoded, true);
        $blockIcon = json_decode($iconItem, true);

        $block['value']['bgColorPalette'] = '';
        $block['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['subpalette']['bg']);
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


    private function marginAndPaddingOffset(&$block)
    {
        $flags = $this->cache->get('createdFirstSection','flags');
        if(!$flags){
            $block['value']['marginTop'] = -200;
            $block['value']['marginTopSuffix'] = "px";
            $block['value']['tempMarginTop'] = -200;
            $block['value']['tempMarginTopSuffix'] = "px";
            $block['value']['marginType'] = "ungrouped";
            $block['value']['items'][0]['value']['paddingTop'] = 250;
            $block['value']['items'][0]['value']['paddingTopSuffix'] = "px";
            $block['value']['items'][0]['value']['tempPaddingTop'] = 250;
            $block['value']['items'][0]['value']['tempPaddingTopSuffix'] = "px";
        }
        $this->cache->update('createdFirstSection',true, 'flags');
    }

    private function integrationOfTheWrapperItem(array &$block, array $section, string $path): void
    {
        if ($section['item_type'] === 'title') {
            $content = $this->replaceTitleTag($section['content'], 'brz-text-lg-center');
            $position = 0;
        } else {
            $content = $this->replaceParagraphs($section['content'], 'brz-text-lg-center');
            $position = null;
        }
        $wrapper = $this->itemWrapperRichText($content, true);
        $this->insertElementAtPosition($block, $path, $wrapper, $position);
    }

    private function itemWrapperRichText($content, $associative = false ){
        $decoded = $this->jsonDecode['global']['wrapper--richText'];
        $block = new ItemSetter($decoded);
        $result = $block->item(0)->setting('text', $content)->get();
        if(!$associative){
            return $result;
        }
        return json_decode(json_encode($result), true);
    }
    private function itemWrapperImage($content, $associative = false ){
        $decoded = $this->jsonDecode['global']['wrapper--image'];
        $block = new ItemSetter($decoded);
        $result = $block->item(0)->setting('text', $content)->get();
        if(!$associative){
            return $result;
        }
        return json_decode(json_encode($result), true);
    }

    private function itemWrappericon($content, $associative = false ){
        $decoded = $this->jsonDecode['global']['wrapper--icon'];
        $block = new ItemSetter($decoded);
        $result = $block->item(0)->setting('text', $content)->get();
        if(!$associative){
            return $result;
        }
        return json_decode(json_encode($result), true);
    }

    private function removeItemsFromArray(array $array, $index): array
    {
        if ($index >= 0 && $index < count($array))
        {
            $result = array_slice($array, 0, $index + 1);
        } else {
            $result = $array;
        }
        return $result;
    }

    private function insertItemInArray(array $array, array $item, $index): array
    {
        if ($index >= 0 && $index <= count($array))
        {
            $left = array_slice($array, 0, $index);
            $right = array_slice($array, $index);
            $result = array_merge($left, [$item], $right);
        }
        else
        {
            $result = array_merge($array, [$item]);
        }
        return $result;
    }

    private function createUrl(object $href)
    {
        $valueAttributeHref = $href->getAttribute('href');
        $ahref = json_decode('{"type":"external","anchor":"","external":"","externalBlank":"off","externalRel":"off","externalType":"external","population":"","popup":"","upload":"","linkToSlide":1}', true);
        $ahref['external'] = $valueAttributeHref;
        $ahref = json_encode($ahref);
        $dataHref = urlencode($ahref);
        $href->removeAttribute('calls');
        $href->removeAttribute('href');
        $href->setAttribute('data-href', $dataHref);
        $href->setAttribute('class', 'link--external');
    }
// brz-text-lg-center
// brz-text-lg-left
    private function replaceTitleTag($html, $type = ''): string
    {
        Utils::log('Replace Title Tag: '. $html, 1, "Anthem] [replaceTitleTag");
        if(empty($html))
            return '';
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $paragraphs = $doc->getElementsByTagName('p');

        if ($paragraphs->length > 0) {
            foreach ($paragraphs as $paragraph) {
                $styleValue = 'opacity: 1; ';
                $style = '';
                $class = 'brz-cp-color2';
                $textPosition = ' brz-text-lg-center';

                if($type !== ''){
                    $textPosition  = ' ' . $type;
                }

                if ($paragraph->hasAttribute('style')) {
                    $styleValueString = $paragraph->getAttribute('style');
                    // font-weight: 200; letter-spacing: -0.05em; line-height: 1.1em; text-align: left;
                    $styleValue = $this->parseStyle($styleValueString);
                    foreach ($styleValue as $key => $value)
                    {
                        if($key == 'text-align'){
                            $textPosition = $this->textPosition[$value];
                        }
                        if($key == 'color'){
                            $style .= 'color:' . $value . ';';
                        }
                        if($key == 'font-size'){
                            $style .= ' font-size:' . $value . ';';
                        }
                    }
                }

                $spans = $paragraph->getElementsByTagName('span');
                if($spans->length > 0) {
                    foreach ($spans as $span) {
                        if ($span->hasAttribute('style')) {
                            $styleValueString = $paragraph->getAttribute('style');
                            // font-weight: 200; letter-spacing: -0.05em; line-height: 1.1em; text-align: left;
                            $styleValue = $this->parseStyle($styleValueString);
                            foreach ($styleValue as $key => $value) {
                                if ($key == 'text-align') {
                                    $textPosition = $this->textPosition[$value];
                                }
                                if ($key == 'color') {
                                    $style .= 'color:' . $value . ';';
                                }
                                if ($key == 'font-size') {
                                    $style .= ' font-size:' . $value . ';';
                                }
                            }
                        }
                    }
                }
                $class .= $textPosition;
                $paragraph->removeAttribute('style');
                $htmlClass = 'brz-tp-lg-heading1 ' . $class;
                $paragraph->setAttribute('class', $htmlClass);

                $span = $doc->createElement('span');
                $span->setAttribute('style', $style);
                $span->setAttribute('class', $class);

                while ($paragraph->firstChild) {
                    $span->appendChild($paragraph->firstChild);
                }
                $paragraph->appendChild($span);
            }
        }
        return $this->clearHtmlTag($doc->saveHTML());
    }

    private function replaceParagraphs($html, $type = ''): string
    {
        Utils::log('Replace Paragraph: '. $html, 1, "Anthem] [replaceParagraphs");
        if(empty($html)){
            return '';
        }

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $paragraphs = $doc->getElementsByTagName('p');

        foreach ($paragraphs as $paragraph) {
            $getTagAInPatragraph = $paragraph->getElementsByTagName('a');
            if($getTagAInPatragraph->length > 0 ){
                $this->createUrl($getTagAInPatragraph->item(0));
            }
            $style = '';
            $class = 'brz-cp-color2';

            $textPosition = ' brz-text-lg-center';

            if($type !== ''){
                $class  .= ' ' . $type;
            }
            else{
                $class .= $textPosition;
            }

            $styleValueString = $paragraph->getAttribute('style');
            // font-weight: 200; letter-spacing: -0.05em; line-height: 1.1em; text-align: left;
            $styleValue = $this->parseStyle($styleValueString);
            foreach ($styleValue as $key => $value)
            {
                if($key == 'text-align'){
                    if(array_key_exists($value, $this->textPosition)){
                        $class .= $this->textPosition[$value];
                    } else {
                        $class .= $this->textPosition['center'];
                    }
                }
                if($key == 'color'){
                    $style .= 'color:' . $value . ';';
                }
                if($key == 'font-size'){
                    $style .= ' font-size:' . $value . ';';
                }
            }

            $paragraph->removeAttribute('style');
            $htmlClass = 'brz-tp-lg-paragraph ' . $class;
            $paragraph->setAttribute('class', $htmlClass);

            $span = $doc->createElement('span');
            $span->setAttribute('style', $style);
            $span->setAttribute('class', $class);

            while ($paragraph->firstChild) {
                $span->appendChild($paragraph->firstChild);
            }
            $paragraph->appendChild($span);
        }
        return $this->clearHtmlTag($doc->saveHTML());
    }

    function getDataIconValue($html) {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');
        $result = [];
        foreach ($links as $link) {
            $spans = $link->getElementsByTagName('span');
            foreach ($spans as $span) {
                if ($span->hasAttribute('data-icon')) {
                    $icon = $span->getAttribute('data-icon');
                    $href = $link->getAttribute('href');
                    $result[] = [ 'icon' => $icon, 'href' => $href];
                }
            }
        }
        return $result;
    }

    private function clearHtmlTag($str): string
    {
        $replase = [
            '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
            "<html>",
            "<body>",
            "</html>",
            "</body>",
            "\n"
        ];
        return str_replace($replase, '', $str);
    }

    private function getIcon($iconName)
    {
        $icon = [
            'facebook'  => 'logo-facebook',
            'instagram' => 'logo-instagram',
            'youtube'   => 'logo-youtube',
            'twitter'   => 'logo-twitter',
        ];
        if(array_key_exists($iconName, $icon)){
            return $icon[$iconName];
        }
        return false;
    }

    private function sortByOrderBy(array $array): array
    {
        usort($array, function($a, $b) {
            return $a['order_by'] - $b['order_by'];
        });
        return $array;
    }

    function parseStyle(string $styleString): array
    {
        $styles = array();
        $stylePairs = explode(';', $styleString);
        foreach ($stylePairs as $pair) {
            $parts = explode(':', $pair);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $styles[$key] = $value;
            }
        }
        return $styles;
    }

    private function rgbToHex($rgb)
    {
        $regex = '/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/';
        preg_match($regex, $rgb, $matches);

        if (count($matches) === 4) {
            $red = dechex($matches[1]);
            $green = dechex($matches[2]);
            $blue = dechex($matches[3]);

            $red = str_pad($red, 2, "0", STR_PAD_LEFT);
            $green = str_pad($green, 2, "0", STR_PAD_LEFT);
            $blue = str_pad($blue, 2, "0", STR_PAD_LEFT);

            return "#$red$green$blue";
        }

        return false;
    }

    private function checkArrayPath($array, $path, $check = ''): bool
    {
        $keys = explode('/', $path);
        $current = $array;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                return false;
            }
            $current = $current[$key];
        }

        if($check != '')
        {
            if(is_array($check)){
                foreach ($check as $look){
                    if ($current === $look) {
                        return true;
                    }
                }
            } else {
                if ($current === $check) {
                    return true;
                }
            }
        }
        return true;
    }

    private function replaceInName($str): string
    {
        if(empty($str))
        {
            return false;
        }
        return str_replace("-", "_", $str);
    }

    private function getNameHash($data = ''): string
    {
        $to_hash = $this->generateUniqueID() . $data;
        $newHash = hash('sha256', $to_hash);
        return substr($newHash, 0, 32);
    }

    private function generateUniqueID(): string
    {
        $microtime = microtime();
        $microtime = str_replace('.', '', $microtime);
        $microtime = substr($microtime, 0, 10);
        $random_number = rand(1000, 9999);
        return $microtime . $random_number;
    }

    private function replaceValue($data, $keyToReplace, $newValue) {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                if (is_array($value)) {
                    $value = $this->replaceValue($value, $keyToReplace, $newValue);
                } elseif ($key === $keyToReplace) {
                    $data[$key] = $newValue;
                }
            }
            unset($value);
        }

        return $data;
    }

    private function replaceIdWithRandom($data) {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                if (is_array($value)) {
                    $value = $this->replaceIdWithRandom($value);
                } elseif ($key === '_id') {
                    $data[$key] = $this->generateCharID();
                }
            }
            unset($value);
        }

        return $data;
    }

    private function generateCharID(int $length = 32): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    private function insertElementAtPosition(array &$array, string $path, array $element, $position = null): void
    {
        $keys = explode('/', $path);

        $current = &$array;
        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        if($position === null){
            $current[] = $element;
        } else {
            $count = count($current);
            if ($position < 0 || $position > $count) {
                throw new InvalidArgumentException("Invalid position: $position");
            }
            $current = array_merge(
                array_slice($current, 0, $position),
                [$element],
                array_slice($current, $position, $count - $position)
            );
        }
    }

    private function mergeArrayAtPath(array &$array, string $path, array $mergeArray): void
    {
        $keys = explode('/', $path);

        $current = &$array;
        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        $current = array_merge($current, $mergeArray);
    }

    private function getKeyRecursive($key, $section, $array) {
        foreach ($array as $k => $value) {
            if ($k === $section && is_array($value)) {
                if (array_key_exists($key, $value)) {
                    return $value[$key];
                }
            }
            if (is_array($value)) {
                $result = $this->getKeyRecursive($key, $section, $value);
                if ($result !== null) {
                    return $result;
                }
            }
        }
        return null;
    }

    public function callMethod($methodName, $params = null)
    {
        $verifiedMethodName = $this->replaceInName($methodName);
        if (method_exists($this, $verifiedMethodName)) {
            if(!isset($params)){
                $params = $this->jsonDecode;
            }
            Utils::log('Call method ' . $verifiedMethodName , 1, "Anthem] [callDynamicMethod");
            return call_user_func_array(array($this, $verifiedMethodName), [$params]);
        }
        Utils::log('Method ' . $verifiedMethodName . ' does not exist', 2, "Anthem] [callDynamicMethod");
        return false;
    }

}