<?php

namespace Brizy\Builder\Layout\Zion;

use Brizy\core\Utils;
use DOMDocument;

class Zion
{

    private mixed $jsonDecode;
    private DOMDocument $dom;

    public function __construct()
    {
        $this->dom = new DOMDocument();
        Utils::log('Connected!', 4, 'ZION Builder');
        $file = __DIR__.'\blocksKit.json';

        if (file_exists($file))
        {
            $fileContent = file_get_contents($file);
            $this->jsonDecode = json_decode($fileContent, true);
            if(empty($fileContent))
            {
                Utils::log('File empty', 2, "ZION] [__construct");
                exit;
            }
            Utils::log('File exist: ' .$file , 1, "ZION] [__construct");
        }
        else
        {
            Utils::log('File does not exist', 2, "ZION] [__construct");
            exit;
        }
    }

    private function left_media_diamond(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "ZION] [left_media_diamond");
        $decoded = $this->jsonDecode['blocks']['left-media-diamond'];
        $blockj = json_decode($decoded, true);

        $replaceTitle = $this->replaceTitleTag($encoded[0]['content']);
        $replaceBody = $this->replaceParagraphs($encoded[1]['content']);

        $blockj['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $replaceTitle;
        $blockj['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][2]['value']['items'][0]['value']['text'] = $replaceBody;

        return json_encode($blockj);
    }
    private function right_media_diamond(array $encoded) { //
        Utils::log('Create bloc', 1, "ZION] [right-media-diamond");

        $decoded = $this->jsonDecode['blocks']['right-media-diamond'];
        $blockj = json_decode($decoded, true);

        $replaceTitle = $this->replaceTitleTag($encoded[0]['content']);
        $replaceBody = $this->replaceParagraphs($encoded[1]['content']);

        $blockj['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $replaceTitle;
        $blockj['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $replaceBody;

        return json_encode($blockj);
    }

    private function full_text(array $encoded)
    {
        Utils::log('Create bloc', 1, "ZION] [full_text");
        $decoded = $this->jsonDecode['blocks']['full-text'];

        $decode = json_decode($decoded, true);

        $replaceTitle = $this->replaceTitleTag($encoded[0]['content']);
        $replaceBody = $this->replaceParagraphs($encoded[1]['content']);

        $this->dom->loadHTML($encoded[1]['content']);

        $decode['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $replaceTitle;
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $replaceBody;
        //$decode['items'][0]['value']['items'][1]['value']['items'][0]['value']['imageSrc'] = $encoded[2]['content'];

        return json_encode($decode);
    }

    private function grid_layout(array $encoded): bool|string
    {
        Utils::log('Create bloc', 1, "ZION] [grid_layout");
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
        $result = $this->grid_layout($encoded);
        return $result;
    }

    private function top_media_diamond(array $encoded) {
        Utils::log('Create bloc', 1, "ZION] [top_media_diamond");

        $decoded = $this->jsonDecode['blocks']['top-media-diamond'];

        $decode = json_decode($decoded['main'], true);

        $decode['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($encoded[0]['content']);
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($encoded[1]['content']);

        $result = json_encode($decode);
        return $result;
    }

    private function createPopup()
    {
//        ToDo
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

    private function replaceTitleTag_($content, $search = 'p', $replace = 'h1', array $attribute = [])
    {
        $this->dom->loadHTML($content);
        $titleParagraphs = $this->dom->getElementsByTagName($search);
        $changedTags = [];
        foreach ($titleParagraphs as $paragraph)
        {
            if(empty($paragraph->nodeValue)){continue;}
            $newTag = $this->dom->createElement($replace, $paragraph->nodeValue);

            if(!empty($attribute)){
                foreach ($attribute as $nameAttribute => $value) {
                    $newTag->setAttribute($nameAttribute, $value);
                }
            }
           // $paragraph->parentNode->replaceChild($newTag, $paragraph);
            $changedTags[] = $this->dom->saveXML($newTag);
        }

        return $changedTags;
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
        if(empty($html)){return '';}
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

    private function replaceInName($str): string
    {
        return str_replace("-", "_", $str);
    }

    public function callMethod($methodName, $params = null)
    {
        $verifiedMethodName = $this->replaceInName($methodName);
        if (method_exists($this, $verifiedMethodName)) {
            if(!isset($params)){
                $params = $this->jsonDecode;
            }
            Utils::log('Call method ' . $verifiedMethodName , 1, "ZION] [callDynamicMethod");
            return call_user_func_array(array($this, $verifiedMethodName), [$params]);
        }
        Utils::log('Method ' . $verifiedMethodName . ' does not exist', 2, "ZION] [callDynamicMethod");
        return false;
    }
}