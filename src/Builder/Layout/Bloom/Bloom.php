<?php

namespace Brizy\Builder\Layout\Bloom;

use Brizy\Builder\VariableCache;
use Brizy\core\Utils;
use DOMDocument;

class Bloom
{
    private mixed $jsonDecode;
    private DOMDocument $dom;
    private VariableCache $cache;

    public function __construct(VariableCache $cache)
    {
        $this->dom   = new DOMDocument();
        $this->cache = $cache;
        Utils::log('Connected!', 4, 'Bloom Builder');
        $file = __DIR__.'\blocksKit.json';

        if (file_exists($file))
        {
            $fileContent = file_get_contents($file);
            $this->jsonDecode = json_decode($fileContent, true);
            if(empty($fileContent))
            {
                Utils::log('File empty', 2, "Bloom] [__construct");
                exit;
            }
            Utils::log('File exist: ' .$file , 1, "Bloom] [__construct");
        }
        else
        {
            Utils::log('File does not exist', 2, "Bloom] [__construct");
            exit;
        }

//        $menuList = $this->cache->get('menuList');
//
//        if($menuList['create'] == false) {
//            if ($this->createMenu($menuList)) {
//                Utils::log('Success create MENU', 1, "Bloom] [__construct");
//                $menuList['create'] = true;
//                $this->cache->set('menuList', $menuList);
//            } else {
//                Utils::log("Failed create MENU", 2, "Bloom] [__construct");
//            }
//        }
//        $this->createFooter($menuList);
    }

    private function createMenu($menuList)
    {
        Utils::log('Create menu', 1, "Bloom] [createMenu");
        $decoded = $this->jsonDecode['blocks']['menu'];
        $block = json_decode($decoded['main'], true);
        $blockMenuItems = json_decode($decoded['item'], true);
        //$block['value']['items'][0]['value']['items'][0]['value']['items'][0] //logo
        //$block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'] //menu items
        //$block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0] = $menuItems;

//        $itemMenu = $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0];
//        $itemsMenu = [];
//        foreach ($menuList['list'] as $item)
//        {
//            $itemMenu['value']['itemId'] = $item['collection'];
//            $itemMenu['value']['title'] = $item['name'];
//            if($item['slug'] == 'home') {
//                $itemMenu['value']['url'] = '/';
//            } else {
//                $itemMenu['value']['url'] = $item['slug'];
//            }
//            $encodeItem = json_encode($itemMenu);
//
//            $itemMenu['value']['id'] = $this->getNameHash($encodeItem);
//
//            $itemsMenu[] = $itemMenu;
//        }
//
//        $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'] = $itemsMenu;
//
        $this->cache->set('menuBlock', json_encode($block));

        return true;
    }

    private function left_media(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Bloom] [left_media");
        $decoded = $this->jsonDecode['blocks']['left-media'];
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

        return json_encode($block);
    }
    private function right_media(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Bloom] [right_media");

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
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                }
                if($item['item_type']=='body'){
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                }
            }
        }
        return json_encode($block);
    }

    private function full_media($encode): bool|string
    {
        Utils::log('Create full media', 1, "Bloom] [full_media");
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
        return json_encode($block);
    }

    private function full_text(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Bloom] [full_text");
        $decoded = $this->jsonDecode['blocks']['full-text'];

        if(empty($encoded['background'])) {
            $block = json_decode($decoded['main'], true);

            $block['value']['items'][0]['value']['bgColorPalette'] = '';
            $block['value']['items'][0]['value']['bgColorHex']     = $encoded['color'];

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
        } else {
            Utils::log('Set background', 1, "Bloom] [full_text");
            $block = json_decode($decoded['background'], true);

            $block['value']['items'][0]['value']['bgImageFileName'] = $encoded['background']['filename'];
            $block['value']['items'][0]['value']['bgImageSrc']      = $encoded['background']['photo'];

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
        return json_encode($block);
    }

    private function right_media_circle(array $encoded): bool|string
    {
        return '';
    }

    private function left_media_circle(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Bloom] [left_media_circle");
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

        return json_encode($block);
    }

    private function top_media_diamond(array $encoded) {
        Utils::log('Create bloc', 1, "Bloom] [top_media_diamond");

        $decoded = $this->jsonDecode['blocks']['top-media-diamond'];

        $decode = json_decode($decoded['main'], true);

        $decode['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($encoded[0]['content']);
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($encoded[1]['content']);

        return json_encode($decode);
    }

    private function grid_layout(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Bloom] [grid_layout");
        $decoded = $this->jsonDecode['blocks']['grid-layout'];

        $decodeBlock    = json_decode($decoded['main'], true);
        $decodeRow      = json_decode($decoded['row'], true);

        $resultRemove = $this->removeItemsFromArray($decodeBlock['items'][0]['value']['items'][0]['value']['items'], 2);

        foreach ($encoded as $item)
        {
            switch ($item['category']) {
                case 'text':
                    if($item['item_type'] == 'title')
                    {
                        $replaceHeadTitle = $this->replaceTitleTag($item['content']);
                        $resultRemove[0]['value']['items'][0]['value']['text'] = $replaceHeadTitle;
                        break;
                    }
                    if($item['item_type'] == 'body')
                    {
                        $newBlock = $decodeBlock['items'][0]['value']['items'][0]['value']['items'][3]['value']['items'][1]['value']['items'][2];
                        $replaceBody = $this->replaceParagraphs($item['content']);
                        $newBlock['value']['items'][0]['value']['text'] = $replaceBody;
                        $resultRemove = $this->insertItemInArray($resultRemove, $newBlock, 1);
                        break;
                    }
                case 'list':
                    $replaceTitle = $this->replaceTitleTag($item['children'][1]['content'], 'brz-tp-lg-heading4');
                    $replaceBody  = $this->replaceParagraphs($item['children'][2]['content']);

                    //$decodeRow['value']['items'][0]['value']['items'][0]; //image
                    $decodeRow['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text']  = $replaceTitle;
                    $decodeRow['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = '';
                    $decodeRow['value']['items'][1]['value']['items'][2]['value']['items'][0]['value']['text'] = $replaceBody;
                    $resultRemove[] = $decodeRow;
                    break;
            }
        }
        $decodeBlock['items'][0]['value']['items'][0]['value']['items'] = $resultRemove;
        return json_encode($decodeBlock);
    }

    private function list_layout(array $encoded): bool|string
    {
        $result = $this->full_text($encoded);
        return $result;
    }

    private function gallery_layout(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "Bloom] [gallery_layout");

        $encoded['items'] = $this->sortByOrderBy($encoded['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];
        $block = json_decode($decoded, true);
        $slide = $block['value']['items'][0];
        $block['value']['items'] = [];

        foreach ($encoded['items'] as $item){
            $slide = $this->replaceIdWithRandom($slide);
            $slide['value']['bgImageFileName'] = $item['imageFileName'];
            $slide['value']['bgImageSrc']      = $item['content'];
            $block['value']['items'][] = $slide;
        }
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
        Utils::log('Create structure default page', 1, "Bloom] [top_media_diamond");

        //$decoded = $this->jsonDecode['blocks']['defaultBlocks'];

    }

    private function createFooter(): void
    {
        Utils::log('Create Footer', 1, "Bloom] [createFooter");
        $decoded = $this->jsonDecode['blocks']['footer'];
        $block = json_decode($decoded, true);

        $this->cache->set('footerBlock', json_encode($block));
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

    private function replaceTitleTag($html, $class = 'brz-tp-lg-heading1 brz-text-lg-center'): string
    {
        if(empty($html))
            return '';
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $paragraphs = $doc->getElementsByTagName('p');

        foreach ($paragraphs as $paragraph) {
            $paragraph->removeAttribute('style');
            $paragraph->setAttribute('class', $class);

            $span = $doc->createElement('span');
            $span->setAttribute('style', 'opacity: 1;');
            $span->setAttribute('class', 'brz-cp-color6');

            while ($paragraph->firstChild) {
                $span->appendChild($paragraph->firstChild);
            }
            $paragraph->appendChild($span);
        }
        return $this->clearHtmlTag($doc->saveHTML());
    }

    private function replaceParagraphs($html): string {
        if(empty($html)){
            return '';
        }

        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $paragraphs = $doc->getElementsByTagName('p');

        foreach ($paragraphs as $paragraph) {
            $getTagAInPatragraph = $paragraph->getElementsByTagName('a');
            if($getTagAInPatragraph->length > 0 ){

                $this->createUrl($getTagAInPatragraph->item(0));
            }
            $paragraph->removeAttribute('style');
            $paragraph->setAttribute('class', 'brz-text-lg-center brz-tp-lg-paragraph');

            $span = $doc->createElement('span');
            $span->setAttribute('style', 'opacity: 1;');
            $span->setAttribute('class', 'brz-cp-color6');

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

    private function sortByOrderBy($array) {
        usort($array, function($a, $b) {
            return $a['order_by'] - $b['order_by'];
        });
        return $array;
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
            Utils::log('Call method ' . $verifiedMethodName , 1, "Bloom] [callDynamicMethod");
            return call_user_func_array(array($this, $verifiedMethodName), [$params]);
        }
        Utils::log('Method ' . $verifiedMethodName . ' does not exist', 2, "Bloom] [callDynamicMethod");
        return false;
    }

}