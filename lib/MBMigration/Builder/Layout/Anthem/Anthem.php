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

        if($menuList['create'] == false) {
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

        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media']['main'];

        $objBlock->newItem($decoded);


        $objBlock->item(0)->setting('bgColorPalette','');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [left_media");

            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
            $objBlock->item(0)->setting('bgColorType', 'none');
        }

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content']!= '') {
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
                if($item['item_type']=='title'){
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
                }
                if($item['item_type']=='body'){
                    $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
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

        $objBlock = new ItemSetter();

        $decoded = $this->jsonDecode['blocks']['right-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);

        foreach ($sectionData['items'] as $item) {
//
//            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText('');
//            $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('');

            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageSrc',$item['content']);
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageFileName',$item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }

            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '','brz-text-lg-right'));
                }
                if($item['item_type']=='body'){
                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-right'));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function full_media(array $sectionData)
    {
        Utils::log('Create full media', 1, $this->layoutName . "] [full_media");

        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');
        $objBlock->item(0)->setting('bgAttachment','none');
        $objBlock->item(0)->setting('bgColorOpacity', 0);

        if($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if( $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item(0)->setting('bgAttachment','fixed');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', strtolower($sectionData['settings']['color']['bg']));
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
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content']));
                }
                if($item['item_type']=='body' && $show_body) {
                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($item['content']));
                }
            }
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(3)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function full_text(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [full_text");

        $objBlock = new ItemSetter();
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

            $objBlock->newItem($decoded['main']);

            $objBlock->item(0)->setting('bgColorPalette', '');

            if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
                $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
            } else {
                $objBlock->item(0)->setting('bgColorHex', $this->cache->get('subpalette1','subpalette')['bg']);
            }

            $objBlock->item(0)->item(0)->item(0)->setText('');
            $objBlock->item(0)->item(2)->item(0)->setText('');

            foreach ($sectionData['items'] as $item) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $objBlock->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
                    }
                    if ($item['item_type'] == 'body') {
                        $objBlock->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
                    }
                }
            }
        } else {
            Utils::log('Set background', 1, $this->layoutName . "] [full_text");

            $objBlock->newItem($decoded['background']);

            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);

            $objBlock->item(0)->item(0)->item(0)->setText('');
            $objBlock->item(0)->item(2)->item(0)->setText('');

            foreach ($sectionData['items'] as $item) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $objBlock->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));

                    }
                    if ($item['item_type'] == 'body') {
                        $objBlock->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function two_horizontal_text($sectionData)
    {
        Utils::log('Create full media', 1, $this->layoutName . "] [two-horizontal-text");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['two-horizontal-text'];
        $block = json_decode($decoded['main'], true);

        $objBlock = new ItemSetter($decoded['main']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);

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

    protected function right_media_circle(array $sectionData){
        Utils::log('Create bloc', 1, $this->layoutName . "] [right_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
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

    protected function grid_layout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");

        $objItem = new ItemSetter();
        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);

        $objBlock->item(0)->setting('bgColorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->setting('bgColorHex',$sectionData['settings']['color']['bg']);
        }

        if(!empty($sectionData['settings']['sections']['background'])) {
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }
        $resultRemove = [];
        foreach ($sectionData['items'] as $section)
        {
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
                                     $objItem->item(1)->item(0)->setText($this->replaceParagraphs($sectionItem['content'], 'brz-tp-lg-empty brz-ff-palanquin brz-ft-google brz-fs-lg-27 brz-fss-lg-px brz-fw-lg-700 brz-ls-lg-0 brz-lh-lg-1_9'));
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
                    if ($section['item_type'] == 'title') {
                        $objItem->addItem($this->itemWrapperRichText($this->replaceTitleTag($section['content'])));
                    }
                    if ($section['item_type'] == 'body') {
                        $objItem->addItem($this->itemWrapperRichText($this->replaceParagraphs($section['content'])));
                    }
                }
            }
            $resultRemove[] = $objItem->get();
        }

        $objBlock->item(0)->item(1)->addItem($resultRemove);

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function list_layout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['list-layout'];

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
                $objHead->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($headItem['content'], '', 'brz-text-lg-left'));
            }

            if ($headItem['item_type'] === 'body' && $show_body) {
                $blockHead = true;
                $objHead->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($headItem['content'], '', 'brz-text-lg-left'));
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
                        $objItem->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
                    }

                    if ($item['item_type'] === 'body') {
                        $objItem->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
                    }
                }
            }
            $objRow->addItem($objItem->get());
            $objBlock->item(0)->addItem($objRow->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function accordion_layout(array $sectionData) {
        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['accordion-layout'];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();

        $objBlock->newItem($decoded['main']);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('colorPalette', '');

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
                        $objItem->setting($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
                    }

                    if ($item['item_type'] === 'accordion_body') {
                        $objItem->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
                    }
                }
            }
            $objRow->addItem($objItem->get());
            $objBlock->item(0)->addItem($objRow->get());
        }
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

    protected function three_top_media_circle(array $sectionData)
    {
        Utils::log('Create bloc', 1, $this->layoutName . "] [three_top_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['three-top-media-circle'];

        $objBlock = new ItemSetter();
        $objItem = new ItemSetter();
        $objSpacer = new ItemSetter();

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);
        $objSpacer->newItem($decoded['spacer']);

        $objBlock->item(0)->setting('bgAttachment', 'none');
        $objBlock->item(0)->setting('bgColorPalette', '');

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, $this->layoutName . "] [three_top_media_circle");

            $background = $this->getKeyRecursive('background', 'sections', $sectionData);

            if(isset($background['photo']) && isset($background['filename'])) {
                $objBlock->item(0)->setting('bgImageSrc', $background['photo']);
                $objBlock->item(0)->setting('bgImageFileName', $background['filename']);
            }
            if(isset($background['opacity'])) {
                $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($background['opacity']));
                $objBlock->item(0)->setting('tempBgColorOpacity', $this->colorOpacity($background['opacity']));
            }
            if(isset($background['photoOption'])){
                $objBlock->item(0)->setting('bgAttachment', 'fixed');
            }
        }

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        foreach ($sectionData['items'] as $item)
        {
            if ($item['category'] === 'photo') {
                $objItem->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objItem->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
                $objBlock->item(0)->item(0)->addItem($objItem->get());
            }

            if ($item['item_type'] === 'title') {
                $objBlock->item(0)->item(1)->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
            }

            if ($item['item_type'] === 'body') {
                $objBlock->item(0)->item(1)->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
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

    protected function createFooter()
    {
        Utils::log('Create Footer', 1, $this->layoutName . "] [createFooter");
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