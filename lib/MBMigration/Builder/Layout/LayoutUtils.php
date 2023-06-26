<?php

namespace MBMigration\Builder\Layout;

use DOMDocument;
use InvalidArgumentException;
use MBMigration\Core\Utils;

class LayoutUtils
{
    public function colorOpacity($value): int
    {
        return 1 - (int) $value;
    }

    protected function replaceIdWithRandom($data) {
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

    protected function generateCharID(int $length = 32): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    protected function convertFontSize($fontSize, $unit = 'px') {
        $value = (float)$fontSize;
        $originalUnit = strtolower(trim($unit));

        $unitFactors = [
            'px' => 1,
            'em' => 16,
            'rem' => 16,
            'pt' => 1.33,
            'percent' => 0.16
        ];

        if (!isset($unitFactors[$originalUnit])) {
            return $fontSize;
        }

        return $value * $unitFactors[$originalUnit];
    }

    protected function getDataIconValue($html): array
    {
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

    protected function clearHtmlTag($str): string
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

    protected function checkArrayPath($array, $path, $check = ''): bool
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

    protected function getIcon($iconName)
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

    protected function getKeyRecursive($key, $section, $array) {
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

    function mergeArrayAtPath(array &$array, string $path, array $mergeArray): void
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

    protected function replaceValue($data, $keyToReplace, $newValue) {
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

    protected function insertElementAtPosition(array &$array, string $path, array $element, $position = null): void
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

    protected function getNameHash($data = ''): string
    {
        $to_hash = $this->generateUniqueID() . $data;
        $newHash = hash('sha256', $to_hash);
        return substr($newHash, 0, 32);
    }

    protected function generateUniqueID(): string
    {
        $microtime = microtime();
        $microtime = str_replace('.', '', $microtime);
        $microtime = substr($microtime, 0, 10);
        $random_number = rand(1000, 9999);
        return $microtime . $random_number;
    }

    protected function sortByOrderBy(array $array): array
    {
        usort($array, function($a, $b) {
            return $a['order_by'] - $b['order_by'];
        });
        return $array;
    }

    protected function parseStyle(string $styleString): array
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

    protected function rgbToHex($rgb)
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

    protected function replaceInName($str): string
    {
        if(empty($str))
        {
            return false;
        }
        return str_replace("-", "_", $str);
    }

    protected function createUrl(object $href)
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

    protected function replaceTitleTag($html, $type = '', $position = '', $options = []): string
    {
        Utils::log('Replace Title Tag ', 1, $this->layoutName . "] [replaceTitleTag");
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

    protected function replaceParagraphs($html, $type = '', $position = '', $options = []): string
    {
        Utils::log('Replace Paragraph', 1, $this->layoutName . "] [replaceParagraphs");
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

    protected function removeItemsFromArray(array $array, $index): array
    {
        if ($index >= 0 && $index < count($array)) {
            $result = array_slice($array, 0, $index + 1);
        } else {
            $result = $array;
        }
        return $result;
    }

    protected function integrationOfTheWrapperItem(array &$block, array $section, string $path): void
    {
        if ($section['item_type'] === 'title') {
            $content = $this->replaceTitleTag($section['content'], 'brz-text-lg-center');
            $position = 0;
        } else {
            $content = $this->replaceParagraphs($section['content'], 'brz-text-lg-center');
            $position = null;
        }
        $wrapper = $this->itemWrapperRichText($content,  [], true);
        $this->insertElementAtPosition($block, $path, $wrapper, $position);
    }

    protected function marginAndPaddingOffset(&$block)
    {
        $flags = $this->cache->get('createdFirstSection', 'flags');
        if (!$flags) {
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
        $this->cache->update('createdFirstSection', true, 'flags');
    }



}