<?php

namespace MBMigration\Builder\Layout;

use DOMDocument;
use Exception;
use InvalidArgumentException;
use MBMigration\Builder\Utils\builderUtils;
use MBMigration\Builder\Utils\HtmlHandler;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;

class LayoutUtils extends builderUtils
{
    public function colorOpacity($value): float
    {
        return 1 - (float) $value;
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
            $option['mainFonts'] = $this->getFonts('main_text');
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
        if (array_key_exists('upperCase', $option)) {
            $upperCase = $option['upperCase'];
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

    /**
     * @param $option
     * @return int
     */
    public function getFontSize($option): int
    {
        if (array_key_exists('mainSize', $option)) {
            $fontSize = $option['mainSize'];
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
        if (array_key_exists('letterSpacing', $option)) {
            $letterSpacing = str_replace('.', '_', $option['letterSpacing']);
        } else {
            $letterSpacing = '0_8';
        }
        return $letterSpacing;
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

    protected function convertFontSize_($fontSize, $unit = 'px'): float
    {
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

    function convertFontSize($fontSize): string
    {
        preg_match('/(\d+(\.\d+)?)\s*([a-z]{2})/', $fontSize, $matches);

        if (count($matches) === 4) {
            $size = (float) $matches[1];
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
            $result = round($size);
        } else {
            $result = (float) $matches[1];
        }
        return $result;
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
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        return "rgb($red, $green, $blue)";
    }

    /**
     * @throws \DOMException
     */
    protected function replaceString($htmlString, $option = []): array
    {
        $mainFonts = $this->mainFonts($option);

        $hOptions = [
            'sectionType'   => $this->getSectionType($option),
            'fontType'      => $mainFonts['fontType'],
            'fontFamily'    => $mainFonts['fontFamily'],
            'letterSpacing' => $this->getLetterSpacing($option),
            'position'      => $this->getPosition($option),
            'fontSize'      => $this->getFontSize($option),
            'mainColor'     => $this->getMainColor($option),
            'textColor'     => $this->getMainColor($option),
            'fontWeight'    => $this->getWeight($option),
            'upperCase'     => $this->getUpperCase($option),
            'fontHeaders'   => $this->getFonts('sub_headers'),
            'fontMain'      => $this->getFonts('main_text'),
        ];

        $processedHTML = new HtmlHandler($htmlString, $hOptions);

        return [
            'text' => $processedHTML->getNewHtml()
        ];
    }

    /**
     * @throws \DOMException
     */
    private function replaceDivWithParagraph($html) {
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $divs = $dom->getElementsByTagName('div');

        foreach ($divs as $div) {
            $p = $dom->createElement('p');
            foreach ($div->attributes as $attribute) {
                $p->setAttribute($attribute->name, $attribute->value);
            }

            while ($div->firstChild) {
                $child = $div->firstChild;
                $div->removeChild($child);
                $p->appendChild($child);
            }

            $div->parentNode->replaceChild($p, $div);
        }
        return $dom->saveHTML();
    }

    protected function removeSemicolon($string) {
        if (substr($string, -1) === ';') {
            $string = substr($string, 0, -1);
        }
        return $string;
    }


    /**
     * @throws \DOMException
     */
    protected function replaceTitleTag($html, $options = [], $type = '', $position = ''): array
    {
        Utils::log('Replace Title Tag ', 1, $this->layoutName . "] [replaceTitleTag");
        if(empty($html))
            return [
                'text' => ''
            ];

        $mainFonts = $this->getFonts('headers');
        $size = $this->convertFontSize($mainFonts['font_size']);
        $fontWeight = 400;
        if(isset($options['bg'])){
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
                            $textPosition .= $this->textPosition[$value];
                        }
                        if($key == 'color'){
                            $style .= 'color:' . $value . ';';
                        }
                        if($key == 'font-size'){
                            $style .= ' font-size:' . $value . ';';
                        }
                        if($key == 'font-weight'){
                            $fontWeight = $value;
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

                                    $style .= 'color:' . $this->hexToRgb($textColor) . ';';

                                if ($key == 'font-size') {
                                    $style .= ' font-size:' . $value . ';';
                                }
                            }
                        }
                    }
                }
                $textPosition .= " brz-tp-lg-empty brz-ff-" . $mainFonts['uuid'] . " brz-ft-upload brz-fs-lg-$size brz-fss-lg-px brz-fw-lg-$fontWeight brz-ls-lg-0 brz-lh-lg-1_9 syler";

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
            'FontColor' => $textColor
        ];
    }

    /**
     * @throws \DOMException
     */
    protected function replaceParagraphs($html, $type = '', $position = '', $options = []): array
    {
        Utils::log('Replace Paragraph', 1, $this->layoutName . "] [replaceParagraphs");
        if(empty($html)){
            return [ 'text' => '' ];
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
            $class = '';

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
        return [
            'text' => $this->clearHtmlTag($doc->saveHTML())
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

    protected function marginAndPaddingOffset(&$block, $settings =[])
    {
        $imageOffset = ['Bloom'];
        $designName = $this->cache->get('design', 'settings');


        if(in_array($designName,$imageOffset)) {
            $flags = $this->cache->get('createdFirstSection', 'flags');
            if (!$flags) {
                $block['value']['marginTop'] = -200;
                $block['value']['marginTopSuffix'] = "px";
                $block['value']['tempMarginTop'] = -200;
                $block['value']['tempMarginTopSuffix'] = "px";
                $block['value']['marginType'] = "ungrouped";

                $block['value']['paddingTop'] = 250;
                $block['value']['paddingTopSuffix'] = "px";
                $block['value']['tempPaddingTop'] = 250;
                $block['value']['tempPaddingTopSuffix'] = "px";
            }
            $this->cache->update('createdFirstSection', true, 'flags');
        }
    }

    /**
     * @throws Exception
     */
    protected function loadKit($layoutName = '', $fileName = ''){

        if(Config::$urlJsonKits && !Config::$devMode) {
            Utils::log('Download json BlocksKit', 1, $this->layoutName . "] [loadKit");
            $createUrl = Config::$urlJsonKits . '/Layout';

            if($fileName === '' && $layoutName === '' ) {
                $createUrl .= '/globalBlocksKit.json';
            } else {
                if ($layoutName !== '') {
                    $createUrl .= '/' . $layoutName;
                }
                if ($fileName !== '') {
                    $createUrl .= '/' . $fileName;
                } else {
                    $createUrl .= '/blocksKit.json';
                }
            }
            $url = $this->validateAndFixURL($createUrl);
            if(!$url) {
                Utils::log('Bad Url: ' . $createUrl, 3, $this->layoutName . "] [loadKit");
                throw new Exception("Bad Url: loadKit");
            }
            return $this->loadJsonFromUrl($url);

        } else {
            Utils::log('Open file json BlocksKit', 1, $this->layoutName . "] [loadKit");
            $file = __DIR__;
            if($fileName === '' && $layoutName === '' ) {
                $file .= '/globalBlocksKit.json';
            } else {
                if ($layoutName !== '') {
                    $file .= '/' . $layoutName;
                }
                if ($fileName !== '') {
                    $file .= '/' . $fileName;
                } else {
                    $file .= '/blocksKit.json';
                }
            }
            if (file_exists($file)) {
                $fileContent = file_get_contents($file);

                if (empty($fileContent)) {
                    Utils::log('File ' . $file . ' empty', 2, $this->layoutName . "] [loadKit");
                    throw new Exception('File ' . $file . ' empty');
                }
                Utils::log('File exist: ' . $file, 1, $this->layoutName . "] [loadKit");
                return json_decode($fileContent, true);

            } else {
                Utils::log('File does not exist. Path: ' . $file, 2, $this->layoutName . "] [loadKit");
                throw new Exception('File does not exist. Path: ' . $file);
            }
        }
    }

    protected function validateAndFixURL($url) {
        if (!parse_url($url, PHP_URL_SCHEME)) {
            $url = 'https://' . $url;
        }
        if (!parse_url($url, PHP_URL_HOST)) {
            return false;
        }
        if (preg_match('/[^A-Za-z0-9-._~:\/?#\[\]@!$&\'()*+,;=]/', $url)) {
            return false;
        }
        return str_replace(' ', '%20', $url);
    }

    private function getFonts($fontsType) {
        $fonts = $this->cache->get('fonts', 'settings');
        foreach ($fonts as $font) {
            if ($font['name'] === $fontsType) {
                return $font;
            }
        }
        return false;
    }

}