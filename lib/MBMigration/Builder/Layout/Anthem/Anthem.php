<?php

namespace MBMigration\Builder\Layout\Anthem;

use DOMDocument;
use Exception;
use MBMigration\Core\Utils;
use InvalidArgumentException;
use MBMigration\Builder\ItemSetter;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\VariableCache;

class Anthem extends Layout
{
    /**
     * @var mixed
     */
    private $jsonDecode;

    protected $layoutName;
    /**
     * @var VariableCache
     */
    public $cache;
    /**
     * @var array|string[]
     */
    private $textPosition;
    /**
     * @var DOMDocument
     */
    private $dom;

    /**
     * @throws Exception
     */
    public function __construct(VariableCache $cache)
    {
        $this->dom   = new DOMDocument();

        $this->layoutName = 'Anthem';

        $this->cache = $cache;
        $this->textPosition = ['center' => ' brz-text-lg-center', 'left' => ' brz-text-lg-left', 'right' => ' brz-text-lg-right'];

        Utils::log('Connected!', 4, $this->layoutName . ' Builder');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        if($menuList['create'] === false) {
            if ($this->createMenu($menuList)) {
                Utils::log('Success create MENU', 1, $this->layoutName . "] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, $this->layoutName . "] [__construct");
                throw new Exception('Failed create MENU');
            }
        }
        $this->createFooter($menuList);
    }

    private function createMenu($menuList): bool
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

    protected function left_media(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [left_media");
        $options = [];
        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['left-media']['main'];

        $objBlock->newItem($decoded);
        
        $objBlock->item(0)->setting('bgColorPalette','');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 
        
        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [left_media");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') && $sectionData['settings']['sections']['background']['filename'] !== ''){
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/background/photo') && $sectionData['settings']['sections']['background']['photo'] !== '') {
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }

            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
                $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
                if ($opacity <= 0.3) {
                    $options = array_merge($options, ['textColor' => '#000000']);
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/background/fadeMode') && $sectionData['settings']['sections']['background']['fadeMode'] !== 'none') {
                    $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                }
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);

                if($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageWidth', $item['settings']['image']['width']);
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageHeight', $item['settings']['image']['height']);
                }

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title') {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-left', 'upperCase' => 'brz-capitalize-on']);

                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if($item['item_type']=='body') {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);

                    $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function right_media(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [right_media");
        $this->cache->set('currentSectionData', $sectionData);

        $options = [];

        $objBlock = new ItemSetter();

        $decoded = $this->jsonDecode['blocks']['right-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('bgColorOpacity', 1);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [right_media");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
                if ($this->checkArrayPath($sectionData, 'settings/sections/background/fadeMode')) {
                    if ($sectionData['settings']['sections']['background']['fadeMode'] !== 'none'){
                        $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
                        if ($opacity <= 0.3) {
                            $options = array_merge($options, ['textColor' => '#000000']);
                        }
                        $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                    }
                }
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageSrc',$item['content']);
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageFileName',$item['imageFileName']);

                if($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageWidth', $item['settings']['image']['width']);
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageHeight', $item['settings']['image']['height']);
                }

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }

            if($item['category'] == 'text') {
                if($item['item_type']=='title') {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-right', 'upperCase' => 'brz-capitalize-on']);

                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if($item['item_type']=='body') {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-right']);

                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws \DOMException
     */
    protected function full_media(array $sectionData)
    {
        Utils::log('Create full media', 1, $this->layoutName . "] [full_media");

        $objBlock = new ItemSetter();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');
        $objBlock->item(0)->setting('bgAttachment','none');
        $objBlock->item(0)->setting('bgColorOpacity', 1);

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photo') &&
            $this->checkArrayPath($sectionData, 'settings/sections/background/filename'))
        {
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')){
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_header = $sectionData['settings']['sections']['text']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_body = $sectionData['settings']['sections']['text']['show_body'];
                }

                if($item['item_type']=='title' && $show_header) {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], [ 'sectionType' => 'brz-tp-lg-heading1', 'bgColor' => $blockBg]));
                }
                if($item['item_type']=='body' && $show_body) {

                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], [ 'sectionType' => 'brz-tp-lg-paragraph', 'bgColor' => $blockBg]));
                }
            }
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item()->item()->item()->item(3)->item()->setting('imageSrc', $item['content']);
                $objBlock->item()->item()->item()->item(3)->item()->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws \DOMException
     */
    protected function top_media(array $sectionData)
    {
        Utils::log('Create full media', 1, $this->layoutName . "] [top_media");

        $objBlock = new ItemSetter();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['top_media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');
        $objBlock->item(0)->setting('bgAttachment','none');
        $objBlock->item(0)->setting('bgColorOpacity', 1);

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photo') &&
            $this->checkArrayPath($sectionData, 'settings/sections/background/filename'))
        {
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')){
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }

        $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_header = $sectionData['settings']['sections']['text']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_body = $sectionData['settings']['sections']['text']['show_body'];
                }

                if($item['item_type']=='title' && $show_header) {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setText($this->replaceString($item['content'], [ 'sectionType' => 'brz-tp-lg-heading1', 'bgColor' => $blockBg]));
                }
                if($item['item_type']=='body' && $show_body) {

                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], [ 'sectionType' => 'brz-tp-lg-paragraph', 'bgColor' => $blockBg]));
                }
            }
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item()->item()->item()->item(0)->item()->setting('imageSrc', $item['content']);
                $objBlock->item()->item()->item()->item(0)->item()->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function full_text(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [full_text");

        $options = [];
        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-text'];

        $objBlock->newItem($decoded['main']);

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
                $objBlock->item(0)->setting('bgColorOpacity', 0);
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [right_media");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
                $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
                if ($opacity <= 0.3) {
                    $options = array_merge($options, ['textColor' => '#000000']);
                }
                $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
            $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {

            Utils::log('Set background', 1, $this->layoutName . "] [full_text");

            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
        }

        $objBlock->item(0)->item(0)->item(0)->setText('<p></p>');
        $objBlock->item(0)->item(2)->item(0)->setText('<p></p>');

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_header = $sectionData['settings']['sections']['text']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                    $show_body = $sectionData['settings']['sections']['text']['show_body'];
                }

                if ($item['item_type'] == 'title' && $show_header) {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-left', 'upperCase' => 'brz-capitalize-on']);
                    $objBlock->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                }
                if ($item['item_type'] == 'body' && $show_body) {
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                    $objBlock->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws \DOMException
     */
    protected function two_horizontal_text($sectionData)
    {
        Utils::log('Create full media', 1, $this->layoutName . "] [two-horizontal-text");
        $options = [];
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['two-horizontal-text'];
        $block = json_decode($decoded['main'], true);

        $objBlock = new ItemSetter($decoded['main']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')){
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        foreach ($sectionData['items'] as $item) {

            if($item['group'] == 0){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceString($item['content'], $options);
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceString($item['content'], $options);
                    }
                }
            }
            if($item['group'] == 1){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }

            }

        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function right_media_circle(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [right_media_circle");
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
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                }
                if($item['item_type']=='body'){
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function left_media_circle(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [left_media_circle");
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
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                }
                if($item['item_type']=='body'){
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
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
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($sectionData[1]['content']);

        return json_encode($decode);
    }

    /**
     * @throws \DOMException
     * @throws Exception
     */
    protected function grid_layout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");

        $objItem    = new ItemSetter();
        $objBlock   = new ItemSetter();
        $objHead    = new ItemSetter();
        $objRow     = new ItemSetter();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);
        $objRow->newItem($decoded['row']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
            $options = array_merge($options, ['bgColor' => $blockBg]);
        }

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];
            $options = array_merge($options, ['textColor' => $textColor]);
        }

        $objBlock->item(0)->setting('bgColorPalette', '');
        foreach ( $sectionData['head'] as $head){
            if ($head['category'] == 'text') {

                $show_header = true;
                $show_body = true;

                if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                    $show_header = $sectionData['settings']['sections']['list']['show_header'];
                }
                if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                    $show_body = $sectionData['settings']['sections']['list']['show_body'];
                }

                if ($head['item_type'] === 'title' && $show_header) {

                    if (isset($item['settings']['used_fonts'])) {
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center']);
                    $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($head['content'], $options)));

                }

                if ($head['item_type'] === 'body' && $show_body) {

                    if (isset($item['settings']['used_fonts'])) {
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }

                    $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                    $objHead->item()->addItem($this->itemWrapperRichText($this->replaceString($head['content'], $options)));
                }
            }
        }
        $objBlock->item()->addItem($objHead->get());

        if(!empty($sectionData['settings']['sections']['background'])) {
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }

        foreach ($sectionData['items'] as $section)
        {
            $objItem->newItem($decoded['item']);

            if(isset($section['item'])) {
                switch ($section['category']) {
                    case 'text':
                        if ($section['item_type'] == 'title') {
                            break;
                        }
                        if ($section['item_type'] == 'body') {
                            break;
                        }
                    case 'list':
                        foreach ($section['item'] as $sectionItem) {
                            if ($sectionItem['category'] == 'photo') {
                                $objItem->setting('bgImageSrc', $sectionItem['content']);
                                $objItem->setting('bgImageFileName', $sectionItem['imageFileName']);

                                if ($sectionItem['link'] != '') {
                                    $objItem->setting('linkType', 'external');
                                    $objItem->setting('linkExternal', '/' . $sectionItem['link']);
                                }
                            }
                            if ($sectionItem['category'] == 'text') {
                                if ($sectionItem['item_type'] == 'title') {
                                    if (isset($item['settings']['used_fonts'])){
                                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                                    }
                                    $objItem->item(1)->item(0)->setText($this->replaceString($sectionItem['content'], [ 'sectionType' => 'brz-tp-lg-paragraph', 'bgColor' => $blockBg]));
                                    $objItem->item(1)->item(0)->setting('typographyFontSize', 27);
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
                        $objItem->item(0)->item(0)->setting('linkExternal', '/' . $section['link']);
                    }
                }
                if ($section['category'] == 'text') {

                    $show_header = true;
                    $show_body = true;

                    if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                        $show_header = $sectionData['settings']['sections']['list']['show_header'];
                    }
                    if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                        $show_body = $sectionData['settings']['sections']['list']['show_body'];
                    }

                    if ($section['item_type'] == 'title' && $show_header) {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $objItem->addItem($this->itemWrapperRichText($this->replaceString($section['content'], [ 'sectionType' => 'brz-tp-lg-heading1', 'bgColor' => $blockBg])));
                    }
                    if ($section['item_type'] == 'body' && $show_body) {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $objItem->addItem($this->itemWrapperRichText($this->replaceString($section['content'], [ 'sectionType' => 'brz-tp-lg-paragraph', 'bgColor' => $blockBg])));
                    }
                }
            }
            $objRow->addItem($objItem->get());
        }
        $objBlock->item()->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function list_layout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [list_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['list-layout'];

        $options = [];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();
        $objHead = new ItemSetter();
        $objImage = new ItemSetter();
        $objRow = new ItemSetter();

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);
        $objImage->newItem($decoded['image']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
                $objBlock->item(0)->setting('bgColorOpacity', 0);
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }


        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [list_layout");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
                if ($this->checkArrayPath($sectionData, 'settings/sections/background/fadeMode')) {
                    if ($sectionData['settings']['sections']['background']['fadeMode'] !== 'none'){
                        $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
                        if ($opacity <= 0.3) {
                            $options = array_merge($options, ['textColor' => '#000000']);
                        }
                        $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                    } else {
                        $objBlock->item(0)->setting('bgColorOpacity', 1);
                    }
                }
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        $blockHead = false;
        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            $show_header = true;
            $show_body = true;

            if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                $show_header = $sectionData['settings']['sections']['list']['show_header'];
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')){
                $show_body = $sectionData['settings']['sections']['list']['show_body'];
            }

            if ($headItem['item_type'] === 'title' && $show_header) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                $objHead->item(0)->item(0)->item(0)->setText($this->replaceString($headItem['content'], $options));
            }

            if ($headItem['item_type'] === 'body' && $show_body) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                $objHead->item(0)->item(2)->item(0)->setText($this->replaceString($headItem['content'], $options));
            }
        }

        if($blockHead) {
            $objBlock->item(0)->addItem($objHead->get());
        }

        foreach ($sectionData['items'] as $section) {
            $objRow->newItem($decoded['row']);
            $objItem->newItem($decoded['item']);
            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    $objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    $objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    $objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'title') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }

                    if ($item['item_type'] === 'body') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                        $objItem->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
            $objRow->addItem($objItem->get());
            $objBlock->item(0)->addItem($objRow->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws \DOMException
     */
    protected function accordion_layout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['accordion-layout'];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();
        $objList = new ItemSetter();

        $objBlock->newItem($decoded['main']);
        $objList->newItem($decoded['list']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objList->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
            $objList->item(0)->setting('navIcon', 'filled');
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            $background = $this->getKeyRecursive('background', 'sections', $sectionData);

            if(isset($background['photo']) && isset($background['filename'])) {
                $objBlock->item(0)->setting('bgImageSrc', $background['photo']);
                $objBlock->item(0)->setting('bgImageFileName', $background['filename']);
            }
            if(isset($background['opacity']) ) {
                $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($background['opacity']));
                $objBlock->item(0)->setting('tempBgColorOpacity', $this->colorOpacity($background['opacity']));
            }
        }

        $blockHead = false;


        if($blockHead) {
            //$objBlock->item(0)->addItem($objHead->get());
        }

        foreach ($sectionData['items'] as $section) {

            $objItem->newItem($decoded['item']);

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    //$objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    //$objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    //$objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'accordion_title') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-left', 'upperCase' => 'brz-capitalize-on']);
                        $objItem->setting('labelText', $this->replaceString($item['content'], $options)['text']);
                    }

                    if ($item['item_type'] === 'accordion_body') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }

                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }

            }
            $objList->item(0)->addItem($objItem->get());
        }
        $objBlock->item(0)->addItem($objList->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws \DOMException
     * @throws Exception
     */
    protected function tabs_layout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [tabs_layout");

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['tabs-layout'];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();
        $objRow = new ItemSetter();

        $objBlock->newItem($decoded['main']);
        $objRow->newItem($decoded['row']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objRow->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
            $objRow->item(0)->setting('navIcon', 'filled');
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
                $objBlock->item(0)->setting('bgColorOpacity', 0);
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            $background = $this->getKeyRecursive('background', 'sections', $sectionData);

            if(isset($background['photo']) && isset($background['filename'])) {
                $objBlock->item(0)->setting('bgImageSrc', $background['photo']);
                $objBlock->item(0)->setting('bgImageFileName', $background['filename']);
            }
            if(isset($background['opacity']) ) {
                $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($background['opacity']));
                $objBlock->item(0)->setting('tempBgColorOpacity', $this->colorOpacity($background['opacity']));
            }
        }

        $blockHead = false;

        foreach ($sectionData['head'] as $headItem)
        {
            if($headItem['category'] !== 'text') { continue; }

            $show_header = true;
            $show_body = true;

            if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                $show_header = $sectionData['settings']['sections']['text']['show_header'];
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                $show_body = $sectionData['settings']['sections']['text']['show_body'];
            }

            if ($headItem['item_type'] === 'title' && $show_header) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                $objBlock->item(0)->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], $options)));
            }

            if ($headItem['item_type'] === 'body' && $show_body) {
                $blockHead = true;
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                $objBlock->item(0)->addItem($this->itemWrapperRichText($this->replaceString($headItem['content'], $options)));
            }
        }

        foreach ($sectionData['items'] as $section) {

            $objItem->newItem($decoded['item']);

            foreach ($section['item'] as $item) {
                if ($item['category'] === 'photo') {
                    //$objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    //$objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                    //$objRow->addItem($objImage->get());
                }
                if ($item['category'] === 'text') {
                    if ($item['item_type'] === 'tab_title') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objItem->setting('labelText', $this->replaceString($item['content'], $options)['text']);
                    }

                    if ($item['item_type'] === 'tab_body') {
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                        $objItem->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }

            }

            $objRow->item(0)->addItem($objItem->get());
            $objRow->item(0)->setting('contentBgColorHex', $blockBg);
        }
        $objBlock->item(0)->addItem($objRow->get());
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function gallery_layout(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [gallery_layout");
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded['main'], true);
        $slide  = json_decode($decoded['item'], true);

        foreach ($sectionData['items'] as $item){
            $slide['value']['bgImageFileName'] = $item['imageFileName'];
            $slide['value']['bgImageSrc']      = $item['content'];

            $this->insertElementAtPosition($block, 'value/items', $slide);
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function new_gallery_layout(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [gallery_layout");
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded['main'], true);
        $slide  = json_decode($decoded['item'], true);

        foreach ($sectionData['items'] as $item){
            $slide['value']['bgImageFileName'] = $item['imageFileName'];
            $slide['value']['bgImageSrc']      = $item['content'];

            $this->insertElementAtPosition($block, 'value/items', $slide);
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    /**
     * @throws \DOMException
     */
    protected function three_top_media_circle(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [three_top_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['three-top-media-circle'];

        $options = [];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();
        $objSpacer = new ItemSetter();

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);
        $objSpacer->newItem($decoded['spacer']);

        $objBlock->item(0)->setting('bgAttachment', 'none');
        $objBlock->item(0)->setting('bgColorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
                $objBlock->item(0)->setting('bgColorOpacity', 0);
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } 

        $options = array_merge($options, ['bgColor' => $blockBg]);

        if($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];

            $objBlock->item(0)->setting('bgColorHex', $blockBg);

            $options = array_merge($options, ['textColor' => $textColor]);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [three_top_media_circle");

            if($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
                $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
                $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            }
            if($this->checkArrayPath($sectionData, 'settings/sections/background/opacity')) {
                $opacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);
//                if ($opacity <= 0.3) {
//                   // $options = array_merge($options, ['textColor' => '#000000']);
//                }
                $objBlock->item(0)->setting('bgColorOpacity', $opacity);
                $objBlock->item(0)->setting('bgColorType', 'none');
            }
        }

        foreach ($sectionData['items'] as $item)
        {
            if ($item['category'] === 'photo') {
                $objItem->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objItem->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                $objBlock->item(0)->item(0)->addItem($objItem->get());
            }

            if ($item['item_type'] === 'title') {
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }

                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                $objBlock->item(0)->item(1)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options ));
            }

            if ($item['item_type'] === 'body') {
                if (isset($item['settings']['used_fonts'])){
                    $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                }
                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                $objBlock->item(0)->item(1)->item(0)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
            }
        }
        $objBlock->item(0)->item(0)->addItem($objSpacer->get());

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

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

    /**
     * @throws \DOMException
     */
    protected function createFooter()
    {
        Utils::log('Create Footer', 1, $this->layoutName . "] [createFooter");

        $options = [];
        $objBlock = new ItemSetter();
        $objIcon = new ItemSetter();

        $sectionData = $this->cache->get('mainSection')['footer'];

        $decoded = $this->jsonDecode['blocks']['footer']['main'];
        $iconItem = $this->jsonDecode['blocks']['footer']['item'];

        $objBlock->newItem($decoded);
        $objIcon->newItem($iconItem);


        $block = json_decode($decoded, true);
        $blockIcon = json_decode($iconItem, true);

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
                        $blockIcon['value']['linkExternal'] = $itemIcon['href'];

                        $blockIcon['value']['name'] = $this->getIcon($itemIcon['icon']);
                        $objIcon->setting('linkExternal', $itemIcon['href']);
                        $objIcon->setting('name', $this->getIcon($itemIcon['icon']));
                        $objBlock->item(1)->item(0)->item(0)->item(0)->addItem($objIcon->get());
                    }
                }

                $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-center']);
                $objBlock->item(1)->item(0)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        $this->cache->set('footerBlock', json_encode($block));
    }

    public function callMethod($methodName, $params = null, $marker = '')
    {
        $verifiedMethodName = $this->replaceInName($methodName);
        if (method_exists($this, $verifiedMethodName)) {
            if(!isset($params)){
                $params = $this->jsonDecode;
            }
            Utils::log('Call method ' . $verifiedMethodName , 1, $this->layoutName . "] [callDynamicMethod");
            $result = call_user_func_array(array($this, $verifiedMethodName), [$params]);
            $this->cache->set('callMethodResult', $result);
            return $result;
        }
        Utils::log('Method ' . $verifiedMethodName . ' does not exist. Page: ' . $marker, 2, $this->layoutName . "] [callDynamicMethod");
        return false;
    }

}