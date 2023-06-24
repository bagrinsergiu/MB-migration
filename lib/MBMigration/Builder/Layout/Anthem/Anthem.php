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
     * @throws Exception
     */
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
                throw new Exception('File empty');
            }
            Utils::log('File exist: ' .$file , 1, "Anthem] [__construct");
        }
        else
        {
            Utils::log('File does not exist', 2, "Anthem] [__construct");
            throw new Exception('File does not exist');
        }

        $menuList = $this->cache->get('menuList');

        if($menuList['create'] == false) {
            if ($this->createMenu($menuList)) {
                Utils::log('Success create MENU', 1, "Anthem] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, "Anthem] [__construct");
                throw new Exception('Failed create MENU');
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

        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media'];

        $objBlock->newItem($decoded);


        $objBlock->item(0)->setting('bgColorPalette','');

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);
        }

        if($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Utils::log('Set background', 1, "Anthem] [left_media");

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

    private function right_media(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [right_media");
        $this->cache->set('currentSectionData', $sectionData);

        $objBlock = new ItemSetter();

        $decoded = $this->jsonDecode['blocks']['right-media'];
        $block = json_decode($decoded, true);

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('bgColorHex', $sectionData['settings']['color']['bg']);

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'photo' && $item['content'] !== '') {
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageSrc',$item['content']);
                $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('imageFileName',$item['imageFileName']);

                if ($item['link'] != '') {
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkType', 'external');
                    $objBlock->item(0)->item(0)->item(1)->item(0)->item(0)->setting('linkExternal', $item['link']);
                }

            }

            $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText('');
            $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('');

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

    private function full_media(array $sectionData)
    {
        Utils::log('Create full media', 1, "Anthem] [full_media");

        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media'];

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

        $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText('');
        $objBlock->item(0)->item(0)->item(0)->item(2)->item(0)->setText('');

        foreach ($sectionData['items'] as $item) {
            if($item['category'] == 'text') {

                if($item['item_type']=='title') {
                    $objBlock->item(0)->item(0)->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content']));
                }
                if($item['item_type']=='body') {
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

    private function full_text(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [full_text");

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
            Utils::log('Set background', 1, "Anthem] [full_text");

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

    private function parallaxScroll(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [full_text (parallaxScroll)");
        $objBlock = new ItemSetter();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['full-text'];

        if(!empty($sectionData['settings']['sections']['background']))  {
            Utils::log('Set background', 1, "Anthem] [full_text (parallaxScroll)");
            $block = json_decode($decoded['parallax-scroll'], true);

            $objBlock->newItem($decoded['parallax-scroll']);

            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);

            foreach ($sectionData['items'] as $item){
                if($item['category'] == 'text') {
                    $show_header = true;
                    $show_body = true;

                    if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                        $show_header = $sectionData['settings']['sections']['text']['show_header'];
                    }
                    if($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')){
                        $show_body = $sectionData['settings']['sections']['text']['show_body'];
                    }

                    if($item['item_type'] == 'title' &&  $show_header) {
                        $objBlock->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
                    }
                    if($item['item_type'] == 'body' && $show_body) {
                        $objBlock->item(0)->item(0)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    private function two_horizontal_text($sectionData)
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

    private function grid_layout(array $sectionData) {
        Utils::log('Create bloc', 1, "Anthem] [grid_layout");

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

    private function list_layout(array $sectionData) {
        Utils::log('Create bloc', 1, "Anthem] [grid_layout");
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

    private function gallery_layout(array $sectionData)
    {
        Utils::log('Create bloc', 1, "Anthem] [gallery_layout");
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

    private function itemWrapperRichText($content, array $settings = [], $associative = false ){
        $decoded = $this->jsonDecode['global']['wrapper--richText'];
        $block = new ItemSetter($decoded);
        $result = $block->item(0)->setting('text', $content)->get();
        if(!empty($settings)) {
            foreach ($settings as $key => $value) {
                $result = $block->item(0)->setting($key, $value);
            }
        }
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

    private function itemWrapperIcon($content, $associative = false ){
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

    private function replaceTitleTag($html, $type = '', $position = ''): string
    {
        Utils::log('Replace Title Tag ', 1, "Anthem] [replaceTitleTag");
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

                if($position !== ''){
                    $textPosition  = ' ' . $position;
                }

                if($type !== ''){
                    $textPosition  .= ' ' . $type;
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

    private function replaceParagraphs($html, $type = '', $position = ''): string
    {
        Utils::log('Replace Paragraph', 1, "Anthem] [replaceParagraphs");
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

            if($position !== ''){
                $textPosition  = ' ' . $position;
            }

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
                    $style .= ' font-size:' . $this->convertFontSize($value) . ';';
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