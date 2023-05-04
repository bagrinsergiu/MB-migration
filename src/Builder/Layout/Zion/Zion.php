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
        if (file_exists($file)) {
            $fileContent = file_get_contents($file);
            $this->jsonDecode = json_decode($fileContent, true);
            if(empty($fileContent))
            {
                Utils::log('File empty', 2, "ZION] [__construct");
                exit;
            }
            Utils::log('File exist: ' .$file , 2, "ZION] [__construct");
        }
        else
        {
            Utils::log('File does not exist', 2, "ZION] [__construct");
            exit;
        }
    }

    function left_media_diamond(array $encoded) {
        Utils::log('Create bloc', 1, "ZION] [left_media_diamond");
        $decoded = $this->jsonDecode['blocks']['left-media-diamond'];

//        $decoded['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['imageSrc'] = $newImageSrc;
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'];

        return $decoded;

       //var_dump($encoded['blocks']['left-media-diamond']['data']);
    }
    function right_media_diamond(array $encoded) {
        Utils::log('Create bloc', 1, "ZION] [right-media-diamond");

        $decoded = $this->jsonDecode['blocks']['right-media-diamond'];
//        $result = [];
//        $decoded['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['imageSrc'] = $encoded[2]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $encoded[0]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $encoded[1]['content'];

        $result = $decoded;
        return $result;

        //var_dump($encoded['blocks']['left-media-diamond']['data']);
    }

    function full_text(array $encoded)
    {
        Utils::log('Create bloc', 1, "ZION] [full_text");
        $decoded = $this->jsonDecode['blocks']['full-text'];

        $decode = json_decode($decoded, true);

        $decode['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $encoded[0]['content'];
        $decode['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $encoded[1]['content'];
        //$decode['items'][0]['value']['items'][1]['value']['items'][0]['value']['imageSrc'] = $encoded[2]['content'];

        $result = json_encode($decode);

        //$result = $decode;

        //print_r($decode);
        return $result;
    }

    function grid_layout(array $encoded) {
        Utils::log('Create bloc', 1, "ZION] [grid_layout");

        $decoded = $this->jsonDecode['blocks']['grid-layout'];
//        $result = [];
//        $decoded['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['imageSrc'] = $encoded[2]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $encoded[0]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $encoded[1]['content'];

        $result = $decoded;
        return $result;

        //var_dump($encoded['blocks']['left-media-diamond']['data']);
    }
    function list_layout(array $encoded) {
        
        Utils::log('Create bloc', 1, "ZION] [list_layout");

        $decoded = $this->jsonDecode['blocks']['list-layout'];
//        $result = [];
//        $decoded['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['imageSrc'] = $encoded[2]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $encoded[0]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $encoded[1]['content'];

        $result = $decoded;
        return $result;

        //var_dump($encoded['blocks']['left-media-diamond']['data']);
    }
    function top_media_diamond(array $encoded) {
        Utils::log('Create bloc', 1, "ZION] [top_media_diamond");

        $decoded = $this->jsonDecode['blocks']['top-media-diamond'];
//        $result = [];
//        $decoded['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['imageSrc'] = $encoded[2]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $encoded[0]['content'];
//        $decoded['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $encoded[1]['content'];

        $result = $decoded;
        return $result;

        //var_dump($encoded['blocks']['left-media-diamond']['data']);
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






