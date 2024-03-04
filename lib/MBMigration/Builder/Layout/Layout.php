<?php

namespace MBMigration\Builder\Layout;

use MBMigration\Core\Logger;
use DOMException;
use Exception;
use MBMigration\Builder\ItemBuilder;

class Layout extends LayoutUtils
{


    /**
     * @throws Exception
     */
    protected function full_text(array $sectionData)
    {
        $jsonDecode = $this->initData();
        Logger::instance()->info('Create bloc');

        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $jsonDecode['blocks']['full-text'];
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

    /**
     * @throws Exception
     */
    protected function full_media(array $sectionData)
    {
        $jsonGlobal = $this->initData();
        Logger::instance()->info('Create full media');

        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $jsonGlobal['blocks']['full-media']['main'];

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

    /**
     * @throws DOMException
     * @throws Exception
     */
    protected function top_media(array $sectionData) {

        $jsonGlobal = $this->initData();

        Logger::instance()->info('Create full media');

        $objBlock = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->$jsonGlobal['blocks']['top_media']['main'];

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
        } else {
            $defaultPalette = $this->cache->get('subpalette', 'parameter');
            $blockBg = $defaultPalette['subpalette1']['bg'];
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

    /**
     * @throws Exception
     */
    protected function left_media(array $sectionData)
    {
        $jsonDecode = $this->initData();
        Logger::instance()->info('Create bloc');

        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $jsonDecode['blocks']['left-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorOpacity', 1);
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

    /**
     * @throws Exception
     */
    protected function right_media(array $sectionData)
    {
        $jsonDecode = $this->initData();
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);

        $objBlock = new ItemBuilder();

        $decoded =  $jsonDecode['blocks']['right-media']['main'];

        $objBlock->newItem($decoded);

        $objBlock->item(0)->setting('bgColorOpacity', 1);
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
                    $objBlock->item(0)->item(0)->item(0)->item(1)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-right'));
                }
            }
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws Exception
     */
    protected function two_horizontal_text($sectionData)
    {
        $jsonDecode = $this->initData();

        Logger::instance()->info('Create full media');

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $jsonDecode['blocks']['two-horizontal-text'];
        $block = json_decode($decoded['main'], true);

        $objBlock = new ItemBuilder($decoded['main']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } else {
            $defaultPalette = $this->cache->get('subpalette', 'parameter');
            $blockBg = $defaultPalette['subpalette1']['bg'];
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


    /**
     * @throws DOMException
     */
    protected function three_horizontal_text($sectionData)
    {
        $jsonDecode = $this->initData();

        Logger::instance()->info('Create full media');

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $jsonDecode['blocks']['three-horizontal-text'];
        $block = json_decode($decoded['main'], true);

        $objBlock = new ItemBuilder($decoded['main']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } else {
            $defaultPalette = $this->cache->get('subpalette', 'parameter');
            $blockBg = $defaultPalette['subpalette1']['bg'];
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
            if($item['group'] == 2){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(2)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(2)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws DOMException
     * @throws Exception
     */
    protected function four_horizontal_text($sectionData)
    {
        $jsonDecode = $this->initData();

        Logger::instance()->info('Create full media');

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $jsonDecode['blocks']['three-horizontal-text'];
        $block = json_decode($decoded['main'], true);

        $objBlock = new ItemBuilder($decoded['main']);

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $blockBg = $sectionData['settings']['color']['bg'];
            $objBlock->item(0)->setting('bgColorHex', $blockBg);
        } else {
            $defaultPalette = $this->cache->get('subpalette', 'parameter');
            $blockBg = $defaultPalette['subpalette1']['bg'];
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
            if($item['group'] == 2){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(2)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(2)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
            if($item['group'] == 3){
                if($item['category'] == 'text') {
                    if($item['item_type']=='title'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-heading1', 'mainPosition'=>'brz-text-lg-center', 'upperCase' => 'brz-capitalize-on']);
                        $objBlock->item(0)->item(0)->item(3)->item(0)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                    if($item['item_type']=='body'){
                        if (isset($item['settings']['used_fonts'])){
                            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                        }
                        $options = array_merge($options, ['sectionType' => 'brz-tp-lg-paragraph', 'mainPosition'=>'brz-text-lg-left']);
                        $objBlock->item(0)->item(0)->item(3)->item(2)->item(0)->setText($this->replaceString($item['content'], $options));
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    /**
     * @throws Exception
     */
    protected function gallery_layout(array $sectionData)
    {
        $jsonDecode = $this->initData();
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded['main'], true);
        $slide = json_decode($decoded['item'], true);

        $this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item) {
            $slide['value']['bgImageFileName'] = $item['imageFileName'];
            $slide['value']['bgImageSrc'] = $item['content'];

            $this->insertElementAtPosition($block, 'value/items', $slide);
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    /**
     * @throws Exception
     */
    protected function list_layout(array $sectionData){

        $jsonDecode = $this->initData();
        Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $jsonDecode['blocks']['list-layout'];

        $objBlock = new ItemBuilder();
        $objItem = new ItemBuilder();
        $objHead = new ItemBuilder();
        $objImage = new ItemBuilder();
        $objRow = new ItemBuilder();

        $objBlock->newItem($decoded['main']);
        $objHead->newItem($decoded['head']);
        $imageCircle = ['August'];
        $designName = $this->cache->get('design', 'settings');

        if(in_array($designName,$imageCircle)) {
            $loadImageItem = $decoded['image-circle'];
        } else {
            $loadImageItem = $decoded['image'];
        }

        $objBlock->item(0)->setting('bgColorPalette', '');
        $objBlock->item(0)->setting('bgColorOpacity', 1);

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

            if(!array_key_exists('item', $section)){
                continue;
            }
            $objImage->newItem($loadImageItem);
            foreach ($section['item'] as $item) {

                if ($item['category'] === 'photo' && array_key_exists('imageFileName', $item)) {
                    $objImage->item(0)->item(0)->setting('imageSrc', $item['content']);
                    $objImage->item(0)->item(0)->setting('imageFileName', $item['imageFileName']);
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
            $objRow->addItem($objImage->get());
            $objRow->addItem($objItem->get());
            $objBlock->item(0)->addItem($objRow->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

//    /**
//     * @throws Exception
//     */
//    protected function itemWrapperRichText($content, array $settings = [], $associative = false)
//    {
//        $jsonDecode = $this->initData();
//        $decoded = $jsonDecode['global']['wrapper--richText'];
//        $block = new ItemBuilder($decoded);
//        $block->item(0)->setText($content);
//        $result = $block->get();
//        if (!empty($settings)) {
//            foreach ($settings as $key => $value) {
//                $block->item(0)->setting($key, $value);
//            }
//        }
//        if (!$associative) {
//            return $result;
//        }
//        return json_decode(json_encode($result), true);
//    }

    /**
     * @throws Exception
     */
    protected function itemWrapperImage($content, $associative = false)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--image'];
        $block = new ItemBuilder($decoded);
        $result = $block->item(0)->setting('text', $content)->get();
        if (!$associative) {
            return $result;
        }
        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function itemWrapperIcon($content, $associative = false)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--icon'];
        $block = new ItemBuilder($decoded);
        $result = $block->item(0)->setting('text', $content)->get();
        if (!$associative) {
            return $result;
        }
        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function parallaxScroll(array $sectionData)
    {
        $jsonDecode = $this->initData();
        Logger::instance()->info('Create bloc');
        $objBlock = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $jsonDecode['blocks']['full-text'];
        
        if ($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Logger::instance()->info('Set background');

            $objBlock->newItem($decoded['parallax-scroll']);

            $objBlock->item(0)->setting('bgImageFileName', $sectionData['settings']['sections']['background']['filename']);
            $objBlock->item(0)->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);

            foreach ($sectionData['items'] as $item) {
                if ($item['category'] == 'text') {
                    $show_header = true;
                    $show_body = true;

                    if ($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')) {
                        $show_header = $sectionData['settings']['sections']['text']['show_header'];
                    }
                    if ($this->checkArrayPath($sectionData, 'settings/sections/text/show_header')) {
                        $show_body = $sectionData['settings']['sections']['text']['show_body'];
                    }

                    if ($item['item_type'] == 'title' && $show_header) {
                        $objBlock->item(0)->item(0)->item(0)->setText($this->replaceTitleTag($item['content'], '', 'brz-text-lg-left'));
                    }
                    if ($item['item_type'] == 'body' && $show_body) {
                        $objBlock->item(0)->item(0)->item(0)->setText($this->replaceParagraphs($item['content'], '', 'brz-text-lg-left'));
                    }
                }
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }


}