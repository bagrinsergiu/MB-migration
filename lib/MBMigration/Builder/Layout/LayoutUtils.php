<?php

namespace MBMigration\Builder\Layout;

use MBMigration\Core\Logger;
use DOMException;
use DOMDocument;
use Exception;
use InvalidArgumentException;
use MBMigration\Builder\Utils\builderUtils;
use MBMigration\Builder\Utils\HtmlHandler;
use MBMigration\Builder\VariableCache;

class LayoutUtils extends builderUtils
{
    public function colorOpacity($value): float
    {
        return 1 - (float)$value;
    }

    public function checkPhoneNumber($str)
    {
        if (!preg_match("/^(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\$/", $str)) {

            return false;
        }

        $number = preg_replace('/[^0-9]/', '', $str);

        if (ctype_digit($number)) {

            return true;
        } else {

            return false;
        }
    }

    /**
     * @param $option
     * @return array
     */
    public function mainFonts($option): array
    {
        if (array_key_exists('mainFonts', $option)) {
            if ($option['mainFonts']['uuid'] === 'lato') {
                $fontType = 'google';
            } else {
                $fontType = 'upload';
            }
            $fontFamily = $option['mainFonts']['uuid'];
        } else {
            $option['mainFonts'] = $this->getFonts($option);
            if ($option['mainFonts']['uuid'] === 'lato') {
                $fontType = 'google';
            } else {
                $fontType = 'upload';
            }
            $fontFamily = $option['mainFonts']['uuid'];
        }

        return ['fontType' => $fontType, 'fontFamily' => $fontFamily];
    }

    /**
     * @param $option
     * @return string
     */
    public function getUpperCase($option): string
    {
        if (array_key_exists('text_transform', $option)) {
            $upperCase = $option['text_transform'];
        } else {
            $upperCase = '';
        }

        return $upperCase;
    }

    /**
     * @param $option
     * @return int
     */
    public function getWeight($option): int
    {
        if (array_key_exists('mainFontWeight', $option)) {
            $fontWeight = $option['mainFontWeight'];
        } else {
            $fontWeight = 400;
        }

        return $fontWeight;
    }

    /**
     * @param $option
     * @return string
     */
    public function getMainColor($option): string
    {
        if (array_key_exists('bgColor', $option)) {
            $hexColor = $this->getContrastingColor($option['bgColor']);
            $mainColor = $this->hexToRgb($hexColor);
        } else {
            $mainColor = 'rgb(0, 0, 0)';
        }

        if (array_key_exists('textColor', $option)) {
            $mainColor = $option['textColor'];
        }

        return $mainColor;
    }


    public function getColor($option): array
    {
        if (array_key_exists('color', $option)) {
            $color = $option['color'];
        } else {
            $color = [];
        }

        return $color;
    }

    /**
     * @param $option
     * @return int
     */
    public function getFontSize($option): int
    {
        if (array_key_exists('font_size', $option)) {
            $fontSize = $this->convertFontSize($option['font_size']);
        } else {
            $fontSize = 16;
        }

        return $fontSize;
    }

    /**
     * @param $option
     * @return string
     */
    public function getPosition($option): string
    {
        if (array_key_exists('mainPosition', $option)) {
            $position = $option['mainPosition'];
        } else {
            $position = 'brz-text-lg-center';
        }

        return $position;
    }

    /**
     * @param $option
     * @return array|string|string[]
     */
    public function getLetterSpacing($option)
    {
        if (array_key_exists('letter_spacing', $option)) {

            $trimmedString = $this->convertFontSize($option['letter_spacing'], false);
            $letterSpacing = $this->transformNumber($trimmedString);
        } else {
            $letterSpacing = '0_8';
        }

        return $letterSpacing;
    }

    public function transformNumber($number)
    {

        $parts = explode('.', $number);
        if (count($parts) > 1) {
            $parts[1] = substr($parts[1], 0, 1);
        }

        if ($parts[0] === '-') {
            $parts[0] = 'm_';
        }

        return implode('_', $parts);
    }

    /**
     * @param $option
     * @return string
     */
    public function getSectionType($option): string
    {
        if (array_key_exists('sectionType', $option)) {
            $sectionType = $option['sectionType'];
        } else {
            $sectionType = 'brz-tp-lg-paragraph';
        }

        return $sectionType;
    }

    protected function replaceIdWithRandom($data)
    {
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

    protected function convertFontSize_($fontSize, $unit = 'px'): float
    {
        $value = (float)$fontSize;
        $originalUnit = strtolower(trim($unit));

        $unitFactors = [
            'px' => 1,
            'em' => 16,
            'rem' => 16,
            'pt' => 1.33,
            'percent' => 0.16,
        ];

        if (!isset($unitFactors[$originalUnit])) {
            return $fontSize;
        }

        return $value * $unitFactors[$originalUnit];
    }

    function convertFontSize($fontSize, $round = true): string
    {
        preg_match('/(\d+(\.\d+)?)\s*([a-z]{2})/', $fontSize, $matches);

        if (count($matches) === 4) {
            $size = (float)$matches[1];
            $unit = $matches[3];

            if ($unit === 'em') {
                $size *= 16;
            } elseif ($unit === 'rem') {
                $size *= 16;
            } elseif ($unit === 'pt') {
                $size *= (4 / 3);
            } elseif ($unit === 'pc') {
                $size *= 16;
            } elseif ($unit === 'cm') {
                $size *= (96 / 2.54);
            } elseif ($unit === 'mm') {
                $size *= (96 / 25.4);
            } elseif ($unit === 'in') {
                $size *= 96;
            }
            if ($round) {
                $result = round($size);
            } else {
                $result = $size;
            }
        } else {
            $result = (float)$matches[1];
        }

        return $result;
    }

    protected function getDataIconValue(&$html): array
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');
        $result = [];
        foreach ($links as $link) {

            $href = $link->getAttribute('href');
            $hostName = $this->extractDomainName($href);
            $spans = $link->getElementsByTagName('span');
            foreach ($spans as $span) {
                if ($span->hasAttribute('data-icon')) {
                    $icon = $span->getAttribute('data-icon');
                    $iconNameBrizy = $this->checkExistIcon($icon, $hostName);
                    $result[] = ['icon' => $iconNameBrizy, 'href' => $href];
                } else {
                    if ($span->hasAttribute('data-socialicon')) {
                        $icon = $span->getAttribute('data-socialicon');
                        $iconNameBrizy = $this->checkExistIcon($icon, $hostName);
                        $result[] = ['icon' => $iconNameBrizy, 'href' => $href];
                    } else {
                        if ($span->getAttribute('class')) {
                            $class = $span->getAttribute('class');
                            if (strpos($class, 'socialIconSymbol') !== false) {
                                $icon = $span->nodeValue;
                                $iconNameBrizy = $this->checkExistIcon($icon, $hostName);
                                $result[] = ['icon' => $iconNameBrizy, 'href' => $href];
                            }
                        } else {
                            Logger::instance()->critical('Icons Attribute not found');
                        }
                    }
                }
            }
        }

        $resultHtml = $dom->saveHTML();
        $replace = [
            '&acirc;',
            '&nbsp;',
            '&amp;',
            '&quot;',
            '&#128;',
            '&#129;',
            '&#130;',
            '&#135;',
            '&#138;',
            '&icirc;',
        ];

        $html = str_replace($replace, '', $resultHtml);

        return $result;
    }

    protected function getIcoNameByUrl($url, $iconCode): string
    {
        $hostName = $this->extractDomainName($url);
        $result = $this->checkExistIcon($hostName, $iconCode, false);

        return $result;
    }

    private function recursiveRemove($string, $toRemove)
    {

        if (is_array($toRemove)) {
            foreach ($toRemove as $item) {
                $string = $this->recursiveRemove($string, $item);
            }

            return $string;
        } else {

            return str_replace($toRemove, '', $string);
        }
    }

    private function checkExistIcon($name, $iconCode, $hostName = true): string
    {
        $result = 'logo-rss';

        if ($hostName === false) {
            $icoName = $this->getIcon($name);
            if (!$icoName) {
                $icoName = $this->getIcon($iconCode);
                if (!$icoName) {
                    Logger::instance()->critical('icons were not found: '.$name);

                    return $result;
                }
            } else {
                $icoName = $this->getIcon($iconCode);
            }

            return $icoName;
        }

        if ($name === $hostName) {
            $result = $name;
        } else {
            if ($this->getIcon($name) !== false) {
                $result = $name;
            } else {
                if ($this->getIcon($hostName) !== false) {
                    $result = $hostName;
                } else {
                    Logger::instance()->critical('icons were not found');
                }
            }
        }

        return $result;
    }

    protected function extractDomainName($url)
    {

        if ($this->isEmailLink($url)) {
            return 'mail';
        }

        $urlParts = parse_url($url);

        $domain = isset($urlParts['host']) ? $urlParts['host'] : '';

        if (strpos($domain, 'www.') === 0) {
            $domain = substr($domain, 4);
        } elseif (strpos($domain, 'https://') === 0) {
            $domain = substr($domain, 8);
            if (strpos($domain, 'www.') === 0) {
                $domain = substr($domain, 4);
            }
        } elseif (strpos($domain, 'http://') === 0) {
            $domain = substr($domain, 7);
            if (strpos($domain, 'www.') === 0) {
                $domain = substr($domain, 4);
            }
        }

        $parts = explode('.', $domain);

        return $parts[count($parts) - 2];
    }

    protected function isEmailLink($url): bool
    {
        if (strpos($url, 'mailto:') === 0) {
            return true;
        }

        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (preg_match($emailPattern, $url)) {
            return true;
        }

        return false;
    }

    protected function clearHtmlTag($str): string
    {
        $replase = [
            '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
            "<html>",
            "<body>",
            "</html>",
            "</body>",
            "\n",
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

        if ($check != '') {
            if (is_array($check)) {
                foreach ($check as $look) {
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
            'facebook' => 'logo-facebook',
            'instagram' => 'logo-instagram',
            'youtube' => 'logo-youtube',
            'twitter' => 'logo-twitter',
            'vimeo' => 'logo-vimeo',
            'mail' => 'email-85',
            'apple' => 'apple',
            57380 => 'email-85',
            58624 => 'logo-instagram',
            58407 => 'logo-facebook',
        ];
        if (array_key_exists($iconName, $icon)) {
            return $icon[$iconName];
        }

        return false;
    }

    protected function getKeyRecursive($key, $section, $array)
    {
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

    protected function replaceValue($data, $keyToReplace, $newValue)
    {
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
        if ($position === null) {
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
        $to_hash = $this->generateUniqueID().$data;
        $newHash = hash('sha256', $to_hash);

        return substr($newHash, 0, 32);
    }

    protected function generateUniqueID(): string
    {
        $microtime = microtime();
        $microtime = str_replace('.', '', $microtime);
        $microtime = substr($microtime, 0, 10);
        $random_number = rand(1000, 9999);

        return $microtime.$random_number;
    }

    protected function sortByOrderBy(array $array): array
    {
        usort($array, function ($a, $b) {
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
        if (empty($str)) {
            return false;
        }

        return str_replace("-", "_", $str);
    }

    protected function createUrl(object $href)
    {
        $valueAttributeHref = $href->getAttribute('href');
        $ahref = json_decode(
            '{"type":"external","anchor":"","external":"","externalBlank":"off","externalRel":"off","externalType":"external","population":"","popup":"","upload":"","linkToSlide":1}',
            true
        );
        $ahref['external'] = $valueAttributeHref;
        $ahref = json_encode($ahref);
        $dataHref = urlencode($ahref);
        $href->removeAttribute('calls');
        $href->removeAttribute('href');
        $href->setAttribute('data-href', $dataHref);
        $href->setAttribute('class', 'link--external');
    }

    protected function getContrastingColor($color): string
    {
        $red = hexdec(substr($color, 1, 2));
        $green = hexdec(substr($color, 3, 2));
        $blue = hexdec(substr($color, 5, 2));

        $brightness = ($red * 299 + $green * 587 + $blue * 114) / 1000;

        if ($brightness > 127) {
            $contrastColor = '#000000';
        } else {
            $contrastColor = '#ffffff';
        }

        return $contrastColor;
    }

    protected function hexToRgb($hex): string
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        return "rgb($red, $green, $blue)";
    }

    /**
     * @throws DOMException
     */
    protected function replaceString($htmlString, $option = []): array
    {
        $mainFonts = $this->mainFonts($option);
        $fontMain = $this->getFonts($option);

        $hOptions = [
            'sectionType' => $this->getSectionType($option),
            'fontType' => $mainFonts['fontType'],
            'fontFamily' => $mainFonts['fontFamily'],
            'letterSpacing' => $this->getLetterSpacing($fontMain),
            'position' => $this->getPosition($option),
            'fontSize' => $this->getFontSize($fontMain),
            'mainColor' => $this->getMainColor($option),
            'textColor' => $this->getMainColor($option),
            'fontWeight' => $this->getWeight($option),
            'upperCase' => $this->getUpperCase($fontMain),
            'fontMain' => $fontMain,
            'color' => $this->getColor($option),
        ];

        $processedHTML = new HtmlHandler($htmlString, $hOptions);

        return [
            'text' => $this->removeNewlines($processedHTML->getNewHtml()),
        ];
    }

    /**
     * @throws DOMException
     */
    protected function replaceTitleTag($html, $options = [], $type = '', $position = ''): array
    {
        Logger::instance()->info('Replace Title Tag ');
        if (empty($html)) {
            return [
                'text' => '',
            ];
        }

        $mainFonts = $this->getFonts('headers');
        $size = $this->convertFontSize($mainFonts['font_size']);
        $fontWeight = 400;
        if (isset($options['bg'])) {
            $textColor = $this->getContrastingColor($options['bg']);
        } else {
            $textColor = '#000000';
        }


        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $paragraphs = $doc->getElementsByTagName('p');

        if ($paragraphs->length > 0) {
            foreach ($paragraphs as $paragraph) {
                $styleValue = 'opacity: 1; ';
                $style = '';
                $class = '';

                $textPosition = ' brz-text-lg-center';
                if ($position !== '') {
                    $textPosition = ' '.$position;
                }

                if ($type !== '') {
                    $textPosition .= ' '.$type;
                }


                if ($paragraph->hasAttribute('style')) {
                    $styleValueString = $paragraph->getAttribute('style');
                    // font-weight: 200; letter-spacing: -0.05em; line-height: 1.1em; text-align: left;
                    $styleValue = $this->parseStyle($styleValueString);
                    foreach ($styleValue as $key => $value) {
                        if ($key == 'text-align') {
                            $textPosition .= $this->textPosition[$value];
                        }
                        if ($key == 'color') {
                            $style .= 'color:'.$value.';';
                        }
                        if ($key == 'font-size') {
                            $style .= ' font-size:'.$value.';';
                        }
                        if ($key == 'font-weight') {
                            $fontWeight = $value;
                        }

                    }
                }

                $spans = $paragraph->getElementsByTagName('span');
                if ($spans->length > 0) {
                    foreach ($spans as $span) {
                        if ($span->hasAttribute('style')) {
                            $styleValueString = $paragraph->getAttribute('style');
                            // font-weight: 200; letter-spacing: -0.05em; line-height: 1.1em; text-align: left;
                            $styleValue = $this->parseStyle($styleValueString);
                            foreach ($styleValue as $key => $value) {
                                if ($key == 'text-align') {
                                    $textPosition = $this->textPosition[$value];
                                }

                                $style .= 'color:'.$this->hexToRgb($textColor).';';

                                if ($key == 'font-size') {
                                    $style .= ' font-size:'.$value.';';
                                }
                            }
                        }
                    }
                }
                $textPosition .= " brz-tp-lg-empty brz-ff-".$mainFonts['uuid']." brz-ft-upload brz-fs-lg-$size brz-fss-lg-px brz-fw-lg-$fontWeight brz-ls-lg-0 brz-lh-lg-1_9 syler";

                $class .= $textPosition;
                $paragraph->removeAttribute('style');
                $htmlClass = 'brz-tp-lg-heading1 '.$class;
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

        return [
            'text' => $this->clearHtmlTag($doc->saveHTML()),
            'FontStyle' => '',
            'FontFamily' => $mainFonts['uuid'],
            'FontFamilyType' => 'upload',
            'FontSize' => $size,
            'FontSizeSuffix' => 'px',
            'FontWeight' => $fontWeight,
            'LetterSpacing' => $mainFonts['letter_spacing'],
            'LineHeight' => 1,
            'FontColor' => $textColor,
        ];
    }

    /**
     * @throws DOMException
     */
    protected function replaceParagraphs($html, $type = '', $position = '', $options = []): array
    {
        Logger::instance()->info('Replace Paragraph');
        if (empty($html)) {
            return ['text' => ''];
        }

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $paragraphs = $doc->getElementsByTagName('p');

        foreach ($paragraphs as $paragraph) {
            $getTagAInPatragraph = $paragraph->getElementsByTagName('a');
            if ($getTagAInPatragraph->length > 0) {
                $this->createUrl($getTagAInPatragraph->item(0));
            }
            $style = '';
            $class = '';

            $textPosition = ' brz-text-lg-center';

            if ($position !== '') {
                $textPosition = ' '.$position;
            }

            if ($type !== '') {
                $class .= ' '.$type;
            } else {
                $class .= $textPosition;
            }

            $styleValueString = $paragraph->getAttribute('style');
            // font-weight: 200; letter-spacing: -0.05em; line-height: 1.1em; text-align: left;
            $styleValue = $this->parseStyle($styleValueString);
            foreach ($styleValue as $key => $value) {
                if ($key == 'text-align') {
                    if (array_key_exists($value, $this->textPosition)) {
                        $class .= $this->textPosition[$value];
                    } else {
                        $class .= $this->textPosition['center'];
                    }
                }
                if ($key == 'color') {
                    $style .= 'color:'.$value.';';
                }
                if ($key == 'font-size') {
                    $style .= ' font-size:'.$this->convertFontSize($value).';';
                }
            }

            $paragraph->removeAttribute('style');
            $htmlClass = 'brz-tp-lg-paragraph '.$class;
            $paragraph->setAttribute('class', $htmlClass);

            $span = $doc->createElement('span');
            $span->setAttribute('style', $style);
            $span->setAttribute('class', $class);

            while ($paragraph->firstChild) {
                $span->appendChild($paragraph->firstChild);
            }
            $paragraph->appendChild($span);
        }

        return [
            'text' => $this->clearHtmlTag($doc->saveHTML()),
        ];
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

    /**
     * @throws Exception
     */
    protected function loadKit($layoutName = '', $fileName = '')
    {
        Logger::instance()->info('Open file json BlocksKit');
        $file = __DIR__;
        if ($fileName === '' && $layoutName === '') {
            $file .= '/globalBlocksKit.json';
        } else {
            if ($layoutName !== '') {
                $file .= '/Theme/'.$layoutName;
            }
            if ($fileName !== '') {
                $file .= '/'.$fileName;
            } else {
                $file .= '/blocksKit.json';
            }
        }
        if (file_exists($file)) {
            $fileContent = file_get_contents($file);

            if (empty($fileContent)) {
                Logger::instance()->warning('File '.$file.' empty');
                throw new Exception('File '.$file.' empty');
            }
            Logger::instance()->info('File exist: '.$file);

            return json_decode($fileContent, true);

        } else {
            Logger::instance()->warning('File does not exist. Path: '.$file);
            throw new Exception('File does not exist. Path: '.$file);
        }
    }

    protected function getFonts($option)
    {

        if (array_key_exists('fontType', $option)) {
            $fontsType = $option['fontType'];
        } else {
            $fontsType = 'body';
        }

        $fontRoute = ['title' => 'sub_headers', 'body' => 'main_text'];
        if (array_key_exists($fontsType, $fontRoute)) {
            $fontsType = $fontRoute[$fontsType];
        } else {
            $fontsType = 'main_text';
        }

        $cache = VariableCache::getInstance();
        $fonts = $cache->get('fonts', 'settings');
        foreach ($fonts as $font) {
            if ($font['name'] === $fontsType) {
                return $font;
            }
        }

        return false;
    }

    private function removeNewlines($inputString)
    {
        $newlines = array("\n", "\r");

        return str_replace($newlines, '', $inputString);
    }

}