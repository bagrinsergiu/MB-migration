<?php

namespace MBMigration\Builder\Layout\Theme\August;

use MBMigration\Core\Logger;
use DOMDocument;
use Exception;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\VariableCache;

class August extends Layout
{
    protected $layoutName;
    
    /**
     * @throws Exception
     */
    public function __construct(VariableCache $cache)
    {
        $this->dom = new DOMDocument();

        $this->layoutName = 'August';

        $this->cache = $cache;
        $this->textPosition = ['center' => ' brz-text-lg-center', 'left' => ' brz-text-lg-left', 'right' => ' brz-text-lg-right'];

        Logger::instance()->info('Connected!');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        if ($menuList['create'] == false) {
            if ($this->createMenu($menuList)) {
                Logger::instance()->info('Success create MENU');
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Logger::instance()->warning("Failed create MENU");
                throw new Exception('Failed create MENU');
            }
        }
        $this->createFooter($menuList);
    }

    protected function createMenu($menuList): bool
    {
        Logger::instance()->info('Create block menu');

        $this->cache->set('currentSectionData', $menuList);
        $lgoItem = $this->cache->get('header', 'mainSection');
        $decoded = $this->jsonDecode['blocks']['menu'];

        $objBlock = new ItemBuilder();

        $objBlock->newItem($decoded['main']);

        foreach ($lgoItem['items'] as $item) {
            if ($item['category'] = 'photo') {
                $logo['imageSrc'] = $item['content'];
                $logo['imageFileName'] = $item['imageFileName'];
            }
        }

        $itemMenu = json_decode($decoded['item'], true);

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $logo['imageSrc']);
        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageFileName', $logo['imageFileName']);
        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('menuSelected', $menuList['uid']);

        $itemsMenu = $this->creatingMenuTree($menuList['list'], $itemMenu);

        if ($this->checkArrayPath($lgoItem, 'settings/color/subpalette')) {

            $objBlock->item(0)->setting('bgColorPalette', '');
            $objBlock->item(0)->setting('bgColorHex', $lgoItem['color']);
            $objBlock->item(0)->setting('bgColorType', 'solid');

            $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);
        } else {
            $color = $this->cache->get('subpalette1', 'subpalette');

            $objBlock->item(0)->setting('bgColorPalette', '');
            $objBlock->item(0)->setting('bgColorHex', $color['bg']);
            $objBlock->item(0)->setting('bgColorOpacity', 1);
            $objBlock->item(0)->setting('tempBgColorOpacity', 1);
            $objBlock->item(0)->setting('bgColorType', 'ungrouped');

            $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('subMenuBgColorHex', $color['bg']);
            $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('subMenuColorHex', $color['text']);
            $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('colorHex', $color['text']);

            $this->cache->set('flags', ['createdFirstSection' => false, 'bgColorOpacity' => true]);
        }

        $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->addItem($itemsMenu);

        $block = $this->replaceIdWithRandom($objBlock->get());
        $this->cache->set('menuBlock', json_encode($block));

        return true;
    }

    protected function creatingMenuTree($menuList, $blockMenu): array
    {
        $treeMenu = [];
        foreach ($menuList as $item) {
            $blockMenu['value']['itemId'] = $item['collection'];
            $blockMenu['value']['title'] = $item['name'];
            if ($item['slug'] == 'home') {
                $blockMenu['value']['url'] = '/';
            } else {
                $blockMenu['value']['url'] = $item['slug'];
            }
            $blockMenu['value']['items'] = $this->creatingMenuTree($item['child'], $blockMenu);
            if ($item['landing'] == false) {
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
        Logger::instance()->info('Create bloc');

        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media']['main'];

        $objBlock->newItem($decoded);


        $objBlock->item(0)->setting('bgColorPalette', '');

        if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        if ($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Logger::instance()->info('Set background');

            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
            $objBlock->item(0)->setting('bgColorType', 'none');
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'photo' && $item['content'] != '') {
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);

                if ($this->checkArrayPath($item, 'settings/image')) {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageWidth', $item['settings']['image']['width']);
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('imageHeight', $item['settings']['image']['height']);
                }

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title') {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
                }
                if ($item['item_type'] == 'body') {
                    $objBlock->item(0)->item(0)->item(1)->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function right_media(array $sectionData)
    {
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);

        $objBlock = new ItemBuilder();

        $decoded = $this->jsonDecode['blocks']['right-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);

        foreach ($sectionData['items'] as $item) {
//
//            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText('');
//            $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('');

            if ($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }
            }

            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-right'));
                }
                if ($item['item_type'] == 'body') {
                    $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-right'));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function full_media(array $sectionData)
    {
        Logger::instance()->info('Create full media');

        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette','');
        $objBlock->item(0)->setting('bgColorOpacity', 1);
        $objBlock->item(0)->setting('bgImageSrc', '');
        $objBlock->item(0)->setting('bgImageFileName', '');

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
            $objBlock->item(0)->setting('bgColorOpacity', 0);
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
                    $objBlock->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content']));
                }
                if($item['item_type']=='body' && $show_body) {
                    $objBlock->item(0)->item(1)->item(0)->setText($this->replaceParagraphs($item['content']));
                }
            }
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(3)->item(0)->setting('imageSrc', $item['content']);
                $objBlock->item(0)->item(3)->item(0)->setting('imageFileName', $item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(3)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(3)->item(0)->setting('linkExternal', $item['link']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    protected function full_text(array $sectionData)
    {
        Logger::instance()->info('Create bloc');

        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-text'];
        if ($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if ($sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                return $this->parallaxScroll($sectionData);
            }
        }

        if (!$this->checkArrayPath($sectionData, 'settings/sections/background/filename')) {
            $block = json_decode($decoded['main'], true);

            $objBlock->newItem($decoded['main']);

            $objBlock->item(0)->setting('bgColorPalette', '');

            if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
                $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
            } else {
                $objBlock->item(0)->setting('bgColorHex', $this->cache->get('subpalette1', 'subpalette')['bg']);
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
            Logger::instance()->info('Set background');

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
        Logger::instance()->info('Create full media');
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['two-horizontal-text'];
        $block = json_decode($decoded, true);

        if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['bg']);
        }

        foreach ($sectionData['items'] as $item) {

            if ($item['group'] == 0) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    }
                    if ($item['item_type'] == 'body') {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-left');
                    }
                }
            }
            if ($item['group'] == 1) {
                if ($item['category'] == 'text') {
                    if ($item['item_type'] == 'title') {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                    }
                    if ($item['item_type'] == 'body') {
                        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-left');
                    }
                }

            }

        }

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function left_media_circle(array $sectionData)
    {
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media-circle'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';

        if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['bg']);
        }
        //$this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'photo' && $item['content'] !== '') {
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
            }
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title') {
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                }
                if ($item['item_type'] == 'body') {
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function top_media_diamond(array $sectionData)
    {
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['top-media-diamond'];

        $decode = json_decode($decoded['main'], true);

        $decode['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($sectionData[0]['content']);
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($sectionData[1]['content']);

        return json_encode($decode);
    }

    protected function grid_layout(array $sectionData)
    {
        Logger::instance()->info('Create bloc');

        $objItem = new ItemBuilder();
        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);

        $objBlock->item(0)->setting('bgColorPalette', '');

        if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        if (!empty($sectionData['settings']['sections']['background'])) {
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']));
        }
        $resultRemove = [];
        foreach ($sectionData['items'] as $section) {
            if (isset($section['item'])) {
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

//    protected function list_layout(array $sectionData)
//    {
//        Utils::log('Create bloc', 1, $this->layoutName . "] [grid_layout");
//        $this->cache->set('currentSectionData', $sectionData);
//        $decoded = $this->jsonDecode['blocks']['list-layout'];
//
//        $objBlock = new ItemSetter();
//        $objItem = new ItemSetter();
//        $objHead = new ItemSetter();
//        $objImage = new ItemSetter();
//        $objRow = new ItemSetter();
//
//        $objBlock->newItem($decoded['main']);
//        $objHead->newItem($decoded['head']);
//        $objImage->newItem($decoded['image']);
//
//
//        $objBlock->item(0)->setting('bgColorPalette', '');
//        if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
//            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
//        }
//
//        if ($this->checkArrayPath($sectionData, 'settings/sections/background')) {
//            $background = $this->getKeyRecursive('background', 'sections', $sectionData);
//
//            if (isset($background['photo']) && isset($background['filename'])) {
//                $objBlock->item(0)->setting('bgImageSrc', $background['photo']);
//                $objBlock->item(0)->setting('bgImageFileName', $background['filename']);
//            }
//            if (isset($background['opacity'])) {
//                $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($background['opacity']));
//                $objBlock->item(0)->setting('tempBgColorOpacity', $this->colorOpacity($background['opacity']));
//            }
//        }
//
//        $blockHead = false;
//        foreach ($sectionData['head'] as $headItem) {
//            if ($headItem['category'] !== 'text') {
//                continue;
//            }
//
//            $show_header = true;
//            $show_body = true;
//
//            if ($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')) {
//                $show_header = $sectionData['settings']['sections']['list']['show_header'];
//            }
//            if ($this->checkArrayPath($sectionData, 'settings/sections/list/show_header')) {
//                $show_body = $sectionData['settings']['sections']['list']['show_body'];
//            }
//
//            if ($headItem['item_type'] === 'title' && $show_header) {
//                $blockHead = true;
//                $objHead->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($headItem['content'], '', 'brz-text-lg-left'));
//            }
//
//            if ($headItem['item_type'] === 'body' && $show_body) {
//                $blockHead = true;
//                $objHead->item(0)->item(2)->item(0)->setText($this->replaceParagraphs($headItem['content'], '', 'brz-text-lg-left'));
//            }
//        }
//
//        if ($blockHead) {
//            $objBlock->item(0)->addItem($objHead->get());
//        }
//
//        foreach ($sectionData['items'] as $section) {
//            $objRow->newItem($decoded['row']);
//            $objItem->newItem($decoded['item']);
//            foreach ($section['item'] as $item) {
//                if ($item['category'] === 'photo') {
//                    $objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
//                    $objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
//                    $objRow->addItem($objImage->get());
//                }
//                if ($item['category'] === 'text') {
//                    if ($item['item_type'] === 'title') {
//                        $objItem->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
//                    }
//
//                    if ($item['item_type'] === 'body') {
//                        $objItem->item(2)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
//                    }
//                }
//            }
//            $objRow->addItem($objItem->get());
//            $objBlock->item(0)->addItem($objRow->get());
//        }
//        $block = $this->replaceIdWithRandom($objBlock->get());
//        return json_encode($block);
//    }

    protected function gallery_layout(array $sectionData)
    {
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded['main'], true);
        $slide = json_decode($decoded['item'], true);

        foreach ($sectionData['items'] as $item) {
            $slide['value']['bgImageFileName'] = $item['imageFileName'];
            $slide['value']['bgImageSrc'] = $item['content'];

            $this->insertElementAtPosition($block, 'value/items', $slide);
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    protected function three_top_media_circle(array $sectionData)
    {
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['three-top-media-circle'];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objSpacer = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);
        $objSpacer->newItem($decoded['spacer']);

        $objBlock->item(0)->setting('bgAttachment', 'none');
        $objBlock->item(0)->setting('bgColorPalette', '');

        if ($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Logger::instance()->info('Set background');

            $background = $this->getKeyRecursive('background', 'sections', $sectionData);

            if (isset($background['photo']) && isset($background['filename'])) {
                $objBlock->item(0)->setting('bgImageSrc', $background['photo']);
                $objBlock->item(0)->setting('bgImageFileName', $background['filename']);
            }
            if (isset($background['opacity'])) {
                $objBlock->item(0)->setting('bgColorOpacity', $this->colorOpacity($background['opacity']));
                $objBlock->item(0)->setting('tempBgColorOpacity', $this->colorOpacity($background['opacity']));
            }
            if (isset($background['photoOption'])) {
                $objBlock->item(0)->setting('bgAttachment', 'fixed');
            }
        }

        if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        foreach ($sectionData['items'] as $item) {
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
        if ($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = $sectionData['settings']['color']['bg'];
        }
        return json_encode($block);
    }

    protected function create_Default_Page()
    {
        Logger::instance()->info('Create structure default page');

        //$decoded = $this->jsonDecode['blocks']['defaultBlocks'];

    }

    protected function createFooter()
    {
        Logger::instance()->info('Create Footer');
        $sectionData = $this->cache->get('mainSection')['footer'];
        $decoded = $this->jsonDecode['blocks']['footer'];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objItem->newItem($decoded['item']);

        $objBlock->item(0)->setting('bgColorPalette', '');

        if ($this->checkArrayPath($sectionData, 'settings/color/subpalette/bg')) {
            $objBlock->item(0)->setting('bgColorHex', strtolower($sectionData['settings']['color']['subpalette']['bg']));
        } else {
            $objBlock->item(0)->setting('bgColorHex', $this->cache->get('subpalette', 'parameter')['subpalette1']['bg']);
        }
        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                $itemsIcon = $this->getDataIconValue($item['content']);
                if (!empty($itemsIcon)) {
                    foreach ($itemsIcon as $itemIcon) {
                        $blockIcon['value']['linkExternal'] = $itemIcon['href'];
                        $blockIcon['value']['name'] = $this->getIcon($itemIcon['icon']);

                        $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][] = $blockIcon;
                    }
                }

                $objBlock->item(0)->item(0)->item(0)->setText($this->replaceParagraphs($item['content']));
                //$block['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
            }
        }
        $this->cache->set('footerBlock', json_encode($objBlock->get()));
    }

    public function callMethod($methodName, $params = [], $marker = ''): bool
    {
        $verifiedMethodName = $this->replaceInName($methodName);
        if (method_exists($this, $verifiedMethodName)) {
            if (!isset($params)) {
                $params = $this->jsonDecode;
            }
            Logger::instance()->info('Call method ' . $verifiedMethodName);
            $result = call_user_func_array(array($this, $verifiedMethodName), [$params]);
            $this->cache->set('callMethodResult', $result);
            return $result;
        }
        Logger::instance()->warning('Method ' . $verifiedMethodName . ' does not exist. Page: ' . $marker);
        return false;
    }

}