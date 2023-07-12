<?php

namespace MBMigration\Builder\Utils;

use DOMDocument;

class HtmlHandler
{
    /**
     * @var mixed
     */
    private $htmlString;
    /**
     * @var mixed
     */
    private $option;

    public function __construct($htmlString, $option)
    {
        $this->htmlString = $htmlString;
        $this->option = $option;
    }

    /**
     * @throws \DOMException
     */
    public function getNewHtml()
    {
        $htmlString = $this->htmlString;

        $sectionType    = $this->option['sectionType'];
        $fontType       = $this->option['fontType'];
        $fontFamily     = $this->option['fontFamily'];
        $letterSpacing  = $this->option['letterSpacing'];
        $position       = $this->option['position'];
        $fontSize       = $this->option['fontSize'];
        $mainColor      = $this->hexToRgb($this->option['mainColor']);
        $fontWeight     = $this->option['fontWeight'];
        $upperCase      = $this->option['upperCase'];
        $fontHeaders    = $this->option['fontHeaders'];
        $fontMain       = $this->option['fontMain'];
        $textColor      = $this->option['textColor'];

        //$htmlString = str_replace('&nbsp;', '', $htmlString);

        $dom = new DOMDocument();

        $htmlString = $this->replaceDivWithParagraph($htmlString);
        @$dom->loadHTML($htmlString);

        $paragraphs = $dom->getElementsByTagName('p');

        foreach ($paragraphs as $paragraph) {

            $p_style = [];
            $fontSize       = $this->option['fontSize'];
            $position       = $this->option['position'];
            $fontWeight     = $this->option['fontWeight'];

            $getTagAInParagraph = $paragraph->getElementsByTagName('a');
            if ($getTagAInParagraph->length > 0) {
                $this->createUrl($getTagAInParagraph->item(0));
            }

            $paragraphStyle = $paragraph->getAttribute('style');
            if (!empty($paragraphStyle)) {
                $paragraphStyle = explode('; ', $paragraphStyle);
                foreach ($paragraphStyle as $value) {
                    $value = explode(': ', $value);
                    $value[1] = $this->removeSemicolon($value[1]);
                    $p_style[$value[0]] = $value[1];
                }

                if (array_key_exists('font-size', $p_style)) {
                    $fontSize = $this->convertFontSize($p_style['font-size']);
                }

                if (array_key_exists('color', $p_style)) {
                    $fontColor = $p_style['color'];
                }

                if (array_key_exists('font-weight', $p_style)) {
                    $fontWeight = $p_style['font-weight'];
                }

                if (array_key_exists('text-align', $p_style)) {
                    $controlPosition = ['center' => ' brz-text-lg-center', 'left' => ' brz-text-lg-left', 'right' => ' brz-text-lg-right'];
                    if (array_key_exists($p_style['text-align'], $controlPosition)) {
                        $position = $controlPosition[$p_style['text-align']];
                    }
                }

                if (isset($fontColor)) {
                    $span = $dom->createElement('span');
                    $span->setAttribute('style', "color: $fontColor; opacity: 1;");

                    while ($paragraph->childNodes->length > 0) {
                        $child = $paragraph->childNodes->item(0);
                        $span->appendChild($child);
                    }
                    $paragraph->appendChild($span);
                }
                unset($fontColor);
            }

            if ($sectionType === 'brz-tp-lg-paragraph') {
                $newClass = "$sectionType $position brz-tp-lg-empty brz-ff-$fontFamily brz-ft-$fontType brz-fs-lg-$fontSize brz-fss-lg-px brz-fw-lg-$fontWeight brz-ls-lg-$letterSpacing";
            } else {
                $titleFontSize = $this->convertFontSize($fontHeaders['font_size']);

                $newClass = "$sectionType $position brz-tp-lg-empty brz-ff-$fontFamily brz-ft-$fontType brz-fs-lg-$titleFontSize brz-fw-lg-$fontWeight brz-ls-lg-$letterSpacing ";
            }

            $paragraph->setAttribute('class', $newClass);

            $paragraph->removeAttribute('style');

            $spans = $paragraph->getElementsByTagName('span');

            foreach ($spans as $span) {

                $span->removeAttribute('class');

                $style = [];
                $styleValue = $span->getAttribute('style');

                if (!empty($styleValue)) {
                    $styleValue = explode('; ', $styleValue);
                    foreach ($styleValue as $value) {
                        $value = explode(': ', $value);
                        $value[1] = $this->removeSemicolon($value[1]);
                        $style[$value[0]] = $value[1];
                    }

                    if (!array_key_exists('color', $style)) {
                        $style['color'] = $mainColor;
                    }

                    if (array_key_exists('text-align', $style)) {
                        $controlPosition = ['center' => 'brz-text-lg-center', 'left' => 'brz-text-lg-left', 'right' => 'brz-text-lg-right'];
                        if (array_key_exists($style['text-align'], $controlPosition)) {
                            $position = $controlPosition[$style['text-align']];
                        }
                    }

                    if (array_key_exists('font-size', $style)) {
                        $fontSize = $this->convertFontSize($style['font-size']);
                    }

                    if (array_key_exists('font-weight', $style)) {
                        $fontWeight = $style['font-weight'];
                    }

                    $styleColor = 'color: ' . $style['color'] . ';';
                    $span->setAttribute('style', $styleColor . ' opacity: 1;');
                } else {
                    $styleColor = 'color: ' . $textColor . ';';
                    $span->setAttribute('style', $styleColor . ' opacity: 1;');
                }

                if (array_key_exists('color', $p_style)) {
                    $color = $p_style['color'];
                    $span->setAttribute('style', "color: $color; opacity: 1;");
                }

                if (isset($upperCase) && $sectionType !== 'brz-tp-lg-paragraph') {
                    $span->setAttribute('class', $upperCase);
                }
            }



//            $span = $dom->createElement('span');
//            if (isset($p_mainColor)) {
//                $span->setAttribute('style', "color: $p_mainColor; opacity: 1;");
//            }
//            if (isset($upperCase) && $sectionType !== 'brz-tp-lg-paragraph') {
//                $span->setAttribute('class', $upperCase);
//            }

//            while ($paragraph->childNodes->length > 0) {
//                $child = $paragraph->childNodes->item(0);
//                $span->appendChild($child);
//            }

//            if ($sectionType === 'brz-tp-lg-paragraph') {
//                $paragraph->appendChild($span);
//            }

//            $content = $paragraph->nodeValue;
//
//            $content = str_replace('&nbsp;', '', $content);
//
//            $paragraph->nodeValue = $content;


        }

        $result = preg_replace('/<(\/?)html>|<(\/?)body>|<!.*?>/i', '', $dom->saveHTML());
        return $result;
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

    private function createUrl(object $href)
    {
        $valueAttributeHref = $href->getAttribute('href');
        $aHref = json_decode('{"type":"external","anchor":"","external":"","externalBlank":"off","externalRel":"off","externalType":"external","population":"","popup":"","upload":"","linkToSlide":1}', true);
        $aHref['external'] = $valueAttributeHref;
        $aHref = json_encode($aHref);
        $dataHref = urlencode($aHref);
        $href->removeAttribute('calls');
        $href->removeAttribute('href');
        $href->setAttribute('data-href', $dataHref);
        $href->setAttribute('class', 'link--external');
    }

    private function removeSemicolon($string) {
        if (substr($string, -1) === ';') {
            $string = substr($string, 0, -1);
        }
        return $string;
    }

    private function convertFontSize($fontSize): string
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

}