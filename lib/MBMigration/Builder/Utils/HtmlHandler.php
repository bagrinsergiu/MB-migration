<?php

namespace MBMigration\Builder\Utils;

use DOMDocument;

class HtmlHandler
{

    /**
     * @throws \DOMException
     */
    protected function replaceString($htmlString, $option = []): array
    {
        $dom = new DOMDocument();

        $htmlString = $this->replaceDivWithParagraph($htmlString);
        @$dom->loadHTML($htmlString);

        $paragraphs = $dom->getElementsByTagName('p');

        foreach ($paragraphs as $paragraph) {
            $p_style = [];

            $getTagAInParagraph = $paragraph->getElementsByTagName('a');
            if ($getTagAInParagraph->length > 0) {
                $this->createUrl($getTagAInParagraph->item(0));
            }

//            $span = $dom->createElement('span');
//            $span->setAttribute('style', "color: $mainColor; opacity: 1;");
//            while ($paragraph->childNodes->length > 0) {
//                $child = $paragraph->childNodes->item(0);
//                $span->appendChild($child);
//            }
//            $paragraph->appendChild($span);

            $paragraphStyle = $paragraph->getAttribute('style');
            if (!empty($paragraphStyle)) {
                $paragraphStyle = explode('; ', $paragraphStyle);
                foreach ($paragraphStyle as $value) {
                    $value = explode(': ', $value);
                    $value[1] = $this->removeSemicolon($value[1]);
                    $p_style[$value[0]] = $value[1];
                }
                if (isset($p_style['color'])) {
                    $p_mainColor = $p_style['color'];
                }
                $fontWeight = $p_style['font-weight'];
                $fontSize = $this->convertFontSize($p_style['font-size']);
                if (array_key_exists('text-align', $p_style)) {
                    $controlPosition = ['center' => ' brz-text-lg-center', 'left' => ' brz-text-lg-left', 'right' => ' brz-text-lg-right'];
                    if (array_key_exists($p_style['text-align'], $controlPosition)) {
                        $position = $controlPosition[$p_style['text-align']];
                    }
                }
            }

            if ($sectionType === 'brz-tp-lg-paragraph') {
                $newClass = "$sectionType $position brz-tp-lg-empty brz-ff-$fontFamily brz-ft-$fontType brz-fs-lg-$fontSize brz-fss-lg-px brz-fw-lg-$fontWeight brz-ls-lg-$letterSpacing";
            } else {

                $fontSize = $this->getFonts('sub_headers');
                $titleFontSize = $this->convertFontSize($fontSize['font_size']);

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
                    $styleColor = 'color: ' . $option['textColor'] . ';';
                    $span->setAttribute('style', $styleColor . ' opacity: 1;');
                }

                if (isset($p_mainColor)) {
                    $span->setAttribute('style', "color: $p_mainColor; opacity: 1;");
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

            $content = $paragraph->nodeValue;

            $content = str_replace('&nbsp;', ' ', $content);

            $paragraph->nodeValue = $content;


        }

        $processedHTML = preg_replace('/<(\/?)html>|<(\/?)body>|<!.*?>/i', '', $dom->saveHTML());
        //$processedHTML = '';

        return [
            'text' => $processedHTML
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

    protected function createUrl(object $href)
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

    protected function removeSemicolon($string) {
        if (substr($string, -1) === ';') {
            $string = substr($string, 0, -1);
        }
        return $string;
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


}