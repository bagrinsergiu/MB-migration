<?php

namespace Brizy\Builder\Layout\Hope;

use Brizy\Builder\VariableCache;
use Brizy\core\Utils;
use Brizy\Builder\ItemSetter;
use DOMDocument;

class Hope
{
    private mixed $jsonDecode;
    private DOMDocument $dom;
    private VariableCache $cache;

    private array $textPosition;
    /**
     * @var array|string[]
     */
    private array $textDefaultPosition;

    public function __construct(VariableCache $cache)
    {
        $this->dom   = new DOMDocument();
        $this->cache = $cache;
        $this->textPosition = ['center' => ' brz-text-lg-center', 'left' => ' brz-text-lg-left', 'right' => ' brz-text-lg-right'];

        Utils::log('Connected!', 4, 'Hope Builder');
        $file = __DIR__.'\blocksKit.json';

        if (file_exists($file))
        {
            $fileContent = file_get_contents($file);
            $this->jsonDecode = json_decode($fileContent, true);
            if(empty($fileContent))
            {
                Utils::log('File empty', 2, "Hope] [__construct");
                exit;
            }
            Utils::log('File exist: ' .$file , 1, "Hope] [__construct");
        }
        else
        {
            Utils::log('File does not exist', 2, "Hope] [__construct");
            exit;
        }

        $menuList = $this->cache->get('menuList');

        if($menuList['create'] == false) {
            if ($this->createMenu($menuList)) {
                Utils::log('Success create MENU', 1, "Hope] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, "Hope] [__construct");
            }
        }
        $this->createFooter($menuList);
    }

    private function createMenu($menuList)
    {
        Utils::log('Create block menu', 1, "Hope] [createMenu");
        $decoded = $this->jsonDecode['blocks']['menu'];
        $block = json_decode($decoded['main'], true);
        $lgoItem = $this->cache->get('mainSection')['header']['items'];
        foreach ($lgoItem as $item)
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

        $block['value']['items'][0]['value']['bgColorHex'] = $menuList['color'];
        $block['value']['items'][0]['value']['bgColorType'] = 'solid';

        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'] = $itemsMenu;


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
            $blockMenu['value']['items'] = $this->creatingMenuTree($item['childs'], $blockMenu);
            if($item['landing'] == false){
                $blockMenu['value']['url'] = $blockMenu['value']['items'][0]['value']['url'];
            }

            $encodeItem = json_encode($blockMenu);

            $blockMenu['value']['id'] = $this->getNameHash($encodeItem);

            $treeMenu[] = $blockMenu;
        }
        return $treeMenu;
    }

    private function left_media(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [left_media");
        $decoded = $this->jsonDecode['blocks']['left-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $encoded['color'];

        foreach ($encoded['items'] as $item){
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
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content'], 'brz-text-lg-left');
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }
    private function right_media(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [right_media");

        $decoded = $this->jsonDecode['blocks']['right-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $encoded['color'];

        foreach ($encoded['items'] as $item){
            if($item['category'] == 'photo'){
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

    private function full_media($encode): bool|string
    {
        Utils::log('Create full media', 1, "Hope] [full_media");
        $decoded = $this->jsonDecode['blocks']['full-media'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $encode['color'];

        foreach ($encode['items'] as $item){
            if($item['category'] == 'photo'){
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
        if($encode['category'] == 'donation')
        {
            $button =  json_decode($this->jsonDecode['blocks']['donation'], true);
            $button['value']['items'][0]['value']['text'] = $encode['settings']['layout']['donations']['text'];
            $button['value']['items'][0]['value']['linkExternal'] = $encode['settings']['sections']['donations']['url'];
            $button['value']['items'][0]['value']['hoverBgColorHex'] = $encode['settings']['layout']['color'];
            $block['value']['items'][0]['value']['items'][] = $button;
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function full_text(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [full_text");
        $decoded = $this->jsonDecode['blocks']['full-text'];
        if($this->checkArrayPath($encoded, 'settings/sections/background/photoOption'))
        {
            if( $encoded['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $encoded['settings']['sections']['background']['photoOption'] === 'parallax-fixed')
            {
                return $this->parallaxScroll($encoded);
            }
        }
        if (!$this->checkArrayPath($encoded, 'settings/sections/background/filename')) {
            $block = json_decode($decoded['main'], true);

            $block['value']['items'][0]['value']['bgColorPalette'] = '';
            $block['value']['items'][0]['value']['bgColorHex'] = $encoded['color'];

            foreach ($encoded['items'] as $item) {
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
            Utils::log('Set background', 1, "Hope] [full_text");
            $block = json_decode($decoded['background'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $encoded['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc'] = $encoded['settings']['sections']['background']['photo'];

            foreach ($encoded['items'] as $item) {
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

    private function parallaxScroll(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [full_text (parallaxScroll)");
        $decoded = $this->jsonDecode['blocks']['full-text'];

        if(!empty($encoded['settings']['sections']['background'])) {
            $block = json_decode($decoded['parallax-scroll'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $encoded['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc']      = $encoded['settings']['sections']['background']['photo'];

        } else {
            Utils::log('Set background', 1, "Hope] [full_text (parallaxScroll)");
            $block = json_decode($decoded['background'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $encoded['settings']['sections']['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc']      = $encoded['settings']['sections']['background']['photo'];

            foreach ($encoded['items'] as $item){
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

    private function right_media_circle(array $encoded): bool|string
    {
        return '';
    }

    private function left_media_circle(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [left_media_circle");
        $decoded = $this->jsonDecode['blocks']['left-media-circle'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $encoded['color'];

        foreach ($encoded['items'] as $item){
            if($item['category'] == 'photo'){
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

    private function top_media_diamond(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [top_media_diamond");

        $decoded = $this->jsonDecode['blocks']['top-media-diamond'];

        $decode = json_decode($decoded['main'], true);

        $decode['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($encoded[0]['content']);
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($encoded[1]['content']);

        return json_encode($decode);
    }

    private function grid_layout(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [grid_layout");
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $objItem = new ItemSetter($decoded['item']);

        $block = json_decode($decoded['main'], true);
        $item  = json_decode($decoded['item'], true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $encoded['color'];

        $path = Utils::findKeyPath($block, '_id');

        foreach ($encoded['items'] as $section)
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

                        $objItem->addItem($this->itemWrapper($section['content']));

                        $item = $this->itemWrapper($this->replaceTitleTag($section['content']));
                    }
                    if ($section['item_type'] == 'body') {
                        $objItem->addItem($this->itemWrapper($section['content']));
                    }
                }
            }
            $resultRemove[] = $item;
        }
        $block['value']['items'][0]['value']['items'][0]['value']['items'] = $resultRemove;

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function list_layout(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [grid_layout");
        $decoded = $this->jsonDecode['blocks']['list-layout'];

        $block = json_decode($decoded['main'], true);
        $item  = json_decode($decoded['item'], true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $encoded['color'];

        foreach ($encoded['items'] as $section)
        {
            switch ($section['category']) {
                case 'text':
                    if($item['item_type'] == 'title')
                    {
                        break;
                    }
                    if($item['item_type'] == 'body')
                    {
                        break;
                    }
                case 'list':
                    foreach ($section['item'] as $sectionItem) {
                        if($sectionItem['category'] == 'photo') {
                            $item['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $sectionItem['content'];
                            $item['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $sectionItem['imageFileName'];
                            if($sectionItem['link'] != '') {
                                $item['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['linkType'] = "external";
                                $item['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['linkExternal'] = '/' . $sectionItem['link'];
                            }
                        }
                        if($sectionItem['category'] == 'text') {
                            if($sectionItem['item_type']=='title') {
                                $item['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($sectionItem['content']);
                            }
                            if($sectionItem['item_type']=='body') {
                                $item['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($sectionItem['content']);
                            }
                        }
                    }
                    break;
            }
            $resultRemove[] = $item;
        }
        $block['value']['items'][0]['value']['items'][0]['value']['items'] = $resultRemove;

        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function gallery_layout(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Hope] [gallery_layout");

        $encoded['items'] = $this->sortByOrderBy($encoded['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded, true);
        $slide = $block['value']['items'][0];
        $block['value']['items'] = [];

        foreach ($encoded['items'] as $item){
            $slide['value']['bgImageFileName'] = $item['imageFileName'];
            $slide['value']['bgImageSrc']      = $item['content'];
            $block['value']['items'][] = $slide;
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }

    private function empty_layout(array $encoded)
    {
        $decoded = $this->jsonDecode['blocks']['empty-layout'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';
        $block['value']['items'][0]['value']['bgColorHex'] = $encoded['color'];
        return json_encode($block);
    }

    private function create_Default_Page()
    {
        Utils::log('Create structure default page', 1, "Hope] [top_media_diamond");

        //$decoded = $this->jsonDecode['blocks']['defaultBlocks'];

    }

    private function createFooter(): void
    {
        Utils::log('Create Footer', 1, "Hope] [createFooter");
        $encoded = $this->cache->get('mainSection')['footer'];
        $decoded = $this->jsonDecode['blocks']['footer'];
        $block = json_decode($decoded, true);

        $block['value']['bgColorPalette'] = '';
        $block['value']['bgColorHex'] = $encoded['settings']['color']['subpalette'];
        foreach ($encoded['items'] as $item) {
            if ($item['category'] == 'text') {
                $block['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
            }
        }
        $this->cache->set('footerBlock', json_encode($block));
    }

    private function itemWrapper($content, $associative = false ){
        $decoded = $this->jsonDecode['global']['wrapper'];
        $block = new ItemSetter($decoded);
        $result = $block->item(0)->setting('text', $content)->get();
        if(!$associative){
            return $result;
        }
        return json_decode($result, true);
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
        Utils::log('Replace Title Tag: '. $html, 1, "Hope] [replaceTitleTag");
        if(empty($html))
            return '';
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $paragraphs = $doc->getElementsByTagName('p');
        if ($paragraphs->length > 0) {
            foreach ($paragraphs as $paragraph) {
                $styleValue = 'opacity: 1; ';
                $style = '';
                $class = 'brz-cp-color6';
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

    private function replaceParagraphs($html, $type = ''): string {
        Utils::log('Replace Paragraph: '. $html, 1, "Hope] [replaceParagraphs");
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
            $class = 'brz-cp-color6';

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
                    $class .= $this->textPosition[$value];
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

    private function rgbToHex($rgb): bool|string
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

    private function generateCharID($length = 32): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function callMethod($methodName, $params = null)
    {
        $verifiedMethodName = $this->replaceInName($methodName);
        if (method_exists($this, $verifiedMethodName)) {
            if(!isset($params)){
                $params = $this->jsonDecode;
            }
            Utils::log('Call method ' . $verifiedMethodName , 1, "Hope] [callDynamicMethod");
            return call_user_func_array(array($this, $verifiedMethodName), [$params]);
        }
        Utils::log('Method ' . $verifiedMethodName . ' does not exist', 2, "Hope] [callDynamicMethod");
        return false;
    }

}