<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use DOMElement;
use DOMException;
use Exception;
use MBMigration\Builder\Media\MediaController;
use MBMigration\Core\Logger;
use DOMDocument;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyEmbedCodeComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

trait RichTextAble
{
    use TextsExtractorAware;
    use CssPropertyExtractorAware;
    use GeneratorID;

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextHead(ElementContextInterface $data, BrowserPageInterface $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        $mbSectionItem['head'] = $this->sortItems($mbSectionItem['head']);

        foreach ((array)$mbSectionItem['head'] as $mbSectionItem) {

            if ($mbSectionItem['item_type'] == 'title' && !$showHeader) {
                continue;
            }
            if ($mbSectionItem['item_type'] == 'body' && !$showBody) {
                continue;
            }

            $elementContext = $data->instanceWithMBSection($mbSectionItem);
            try {
                $this->handleRichTextItem(
                    $elementContext,
                    $browserPage
                );
            } catch (Exception $e) {
                Logger::instance()->info($e->getMessage());
            }
        }

        return $brizySection;
    }

    protected function handleRichTextHeadFromItems(
        ElementContextInterface $data,
        BrowserPageInterface $browserPage,
        callable $acllback = null
    ): BrizyComponent {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ((array)$mbSectionItem['items'] as $mbSectionItem) {

            if ($mbSectionItem['item_type'] == 'title' && !$showHeader) {
                continue;
            }
            if ($mbSectionItem['item_type'] == 'body' && !$showBody) {
                continue;
            }

            $elementContext = $data->instanceWithMBSection($mbSectionItem);

            try {
                if (!is_null($acllback)) {
                    $acllback($elementContext);
                } else {
                    $this->handleRichTextItem(
                        $elementContext,
                        $browserPage
                    );
                }
            } catch (Exception $e) {
                Logger::instance()->info($e->getMessage());
            }


        }

        return $brizySection;
    }

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextItems(ElementContextInterface $data, BrowserPageInterface $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $sectionCategory = $mbSectionItem['category'];
        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        // sort items
        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ((array)$mbSectionItem['items'] as $mbItem) {

            if ($mbItem['category'] == $sectionCategory && isset($mbItem['item_type'])) {
                if ($mbItem['item_type'] == 'title' && !$showHeader) {
                    continue;
                }
                if ($mbItem['item_type'] == 'body' && !$showBody) {
                    continue;
                }

            }

            $elementContext = $data->instanceWithMBSection($mbItem);
            try {
                $this->handleRichTextItem(
                    $elementContext,
                    $browserPage
                );
            } catch (Exception $e) {
                Logger::instance()->info($e->getMessage());
            }
        }

        return $brizySection;
    }

    /**
     * Process single rich text item and place it the brizy section
     */
    protected function handleRichTextItem(
        ElementContextInterface $data,
        BrowserPageInterface $browserPage,
        $selector = null,
        $settings = []
    ) {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $default_fonts = $data->getDefaultFontFamily();
        $brizyComponent = $data->getBrizySection();

        switch ($mbSectionItem['category']) {
            case 'text':
                $brizyComponent = $this->handleTextItem(
                    $mbSectionItem,
                    $brizyComponent,
                    $browserPage,
                    $families,
                    $default_fonts,
                    $data->getThemeContext()->getUrlMap(),
                    $selector,
                    $settings
                );
                break;
            case 'photo':
//                $imageTarget = $brizyComponent;
//                if ($brizyComponent->getType() != 'Wrapper') {
//                    $imageTarget = new BrizyWrapperComponent('wrapper-image');
//                    $brizyComponent->getValue()->add_items([$imageTarget]);
//                }

                $this->handlePhotoItem(
                    $mbSectionItem['sectionId'] ?? $mbSectionItem['id'],
                    $mbSectionItem,
                    $brizyComponent,
                    $browserPage,
                    $families,
                    $default_fonts
                );

                break;
        }

        return $brizyComponent;
    }

    private function handleTextItem(
        $mbSectionItem,
        BrizyComponent $brizySection,
        BrowserPageInterface $browserPage,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
        $urlMap = [],
        $selector = null,
        $settings = []
    ) {
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];
        $richTextBrowserData = $this->extractTexts($selector ?? '[data-id="'.$sectionId.'"]', $browserPage, $families, $defaultFont, $urlMap);
        $styles = $this->getDomElementStyles(
            $selector ?? '[data-id="'.$sectionId.'"]',
            ['text-align', 'font-family'],
            $browserPage,
            $families,
            $defaultFont
        );
        $embeddedElements = $this->findEmbeddedElements($mbSectionItem['content']);
        $embeddIndex = 0;
        foreach ($richTextBrowserData as $i => $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    //wrapper
                    if(!isset($embeddedElements[$embeddIndex]))  break;
                    $brizyEmbedCodeComponent = new BrizyEmbedCodeComponent($embeddedElements[$embeddIndex++]);
                    $cssClass = 'custom-align-'.random_int(0, 10000);
                    $brizyEmbedCodeComponent->getValue()->set_customClassName($cssClass);
                    $brizyEmbedCodeComponent->getItemValueWithDepth(0)->set_customCSS(
                        ".{$cssClass} { text-align: {$styles['text-align']}; font-family: {$styles['font-family']}; }"
                    );
                    $brizySection->getValue()->add_items([$brizyEmbedCodeComponent]);
                    break;
                case 'Cloneable':
                case 'Wrapper':
                    //wrapper--richText
                    if(empty($settings['setEmptyText']) || $settings['setEmptyText'] === false) {
                            $brizySection->getValue()->add_items([new BrizyComponent($textItem)]);
                    } elseif ($settings['setEmptyText'] === true) {
                        if ($this->hasAnyTagsInsidePTag($textItem['value']['items'][0]['value']['text'])) {
                            $brizySection->getValue()->add_items([new BrizyComponent($textItem)]);
                        }
                    }
                    break;
            }
        }

        return $brizySection;
    }

    private function handlePhotoItem(
        $mbSectionItemId,
        $mbSectionItem,
        BrizyComponent $brizyComponent,
        BrowserPageInterface $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {

        if (!empty($mbSectionItem['content'])) {

            $selectorImageSizes = '[data-id="'.$mbSectionItemId.'"] .photo-container img';
            $sizes = $this->handleSizeToInt($this->getDomElementSizes($selectorImageSizes, $browserPage, $families, $default_fonts));
            $sizeUnit = 'px';

            $brizyComponent->getValue()
                ->set_imageFileName($mbSectionItem['imageFileName'])
                ->set_imageSrc($mbSectionItem['content']);

            if(!empty($sizes['width']) && !empty($sizes['height'])) {
                if (strpos($sizes['width'], '%') !== false) {
                    $selectorImageSizes = '[data-id="'.$mbSectionItemId.'"] .photo-container';
                    $sizes = $this->getDomElementSizes($selectorImageSizes, $browserPage, $families, $default_fonts);
                }

                $brizyComponent->getValue()
                    ->set_width((int)$sizes['width'])
                    ->set_tabletWidth((int)$sizes['width'])
                    ->set_mobileWidth((int)$sizes['width'])
                    ->set_height((int)$sizes['height'])
                    ->set_tabletHeight((int)$sizes['height'])
                    ->set_mobileHeight((int)$sizes['height'])
                    ->set_imageWidth($mbSectionItem['settings']['image']['width'])
                    ->set_imageHeight($mbSectionItem['settings']['image']['height'])
                    ->set_widthSuffix($sizeUnit)
                    ->set_heightSuffix($sizeUnit)
                    ->set_tabletHeightSuffix($sizeUnit)
                    ->set_mobileWidthSuffix($sizeUnit)
                    ->set_mobileHeightSuffix($sizeUnit);
            }

            $this->handleLink($mbSectionItem, $brizyComponent);
        }

        return $brizyComponent;
    }

    private function handleLink($mbSectionItem, $brizyComponent)
    {
        if ($mbSectionItem['new_window']) {
            $mbSectionItem['new_window'] = 'on';
        } else {
            $mbSectionItem['new_window'] = 'off';
        }

        if ($mbSectionItem['link'] != '') {
            if ($this->findTag($mbSectionItem['link'], 'iframe')) {
                $popupFromKit = $this->globalBrizyKit['popup']['popup--embedCode'];
                $popupSection = new BrizyComponent(json_decode($popupFromKit, true));

                $popupUid = $this->generateUniqueId(12);

                $attribute = [
                    'style' => "position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                ];

                $remove = [
                    'width', 'height'
                ];

                $iframe = $this->setAttributeInToElement($mbSectionItem['link'], 'iframe', $attribute, $remove);

                $attribute = [
                    'style' => "position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden;"
                ];

                $iframeUpdated = $this->putInsideTheBlock('div', $iframe, $attribute);

                $popupSection->getValue()
                    ->set_popupId($popupUid);
                $popupSection->getItemWithDepth(0, 0, 0, 0)->getValue()
                    ->set_code($iframeUpdated);

                $external = [
                    'linkType' => 'popup',
                    'linkPopup' => $popupUid,
                    'linkExternalBlank' => $mbSectionItem['new_window'],
                    'popups' => [$popupSection],
                ];

            } else {
                switch ($this->detectLinkType($mbSectionItem['link'])) {
                    case 'mail':
                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => 'mailto:'.$mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                        break;
                    case 'phone':
                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => 'tel:'.$mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                        break;
                    case 'string':
                    case 'link':
                        if(MediaController::is_doc($mbSectionItem['link'])) {
                            $mbSectionItem['link'] = MediaController::getURLDoc($mbSectionItem['link']);
                        }

                        $slash = $this->processURL($mbSectionItem['link']);

                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => $slash . $mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                        break;
                    default:
                        $slash = $this->processURL($mbSectionItem['link']);

                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => $slash . $mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                }
            }

            foreach ($external as $key => $value) {
                $method = 'set_' . $key;
                $brizyComponent->getValue()
                    ->$method($value);
            }
        }
    }

    private function detectLinkType($link): string
    {
        if (filter_var($link, FILTER_VALIDATE_EMAIL)) {
            return 'mail';
        }
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            return 'link';
        }
        if ($this->checkPhoneNumber($link)) {
            return 'phone';
        }

        return 'string';
    }

    private function processURL($url): string
    {
        $urlComponents = parse_url($url);

        $hasHost = isset($urlComponents['host']);
        $isValidScheme = isset($urlComponents['scheme']) && ($urlComponents['scheme'] === 'https' || $urlComponents['scheme'] === 'http');

        if ($hasHost || $isValidScheme) {
            return '';
        } else {
            return '/';
        }
    }

    public function checkPhoneNumber($str): bool
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

    private function findEmbeddedElements($html): array
    {
        $dom = new DOMDocument();
        $dom->loadHTML(
            "<!DOCTYPE html><html><head><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"></head><body>{$html}</body></html>",
            LIBXML_NOWARNING | LIBXML_BIGLINES | LIBXML_NOBLANKS | LIBXML_NONET | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_PARSEHUGE
        );

        $iframes = $dom->getElementsByTagName('iframe');

        foreach ($iframes as $iframe) {
            $width = $iframe->getAttribute('width');
            $height = $iframe->getAttribute('height');

            if (!empty($width) && !empty($height)) {
                $iframe->setAttribute('style', "max-width: {$width}px; max-height: {$height}px; width: 100%;");
            }
        }
        $result = [];
        $divs = $dom->getElementsByTagName('div');
        foreach ($divs as $div) {
            if ($div->hasAttribute('class') && $div->getAttribute('class') === 'embedded-paste') {
                $dataSrc = $div->getAttribute('data-src');
                $escapedDataSrc = str_replace('"', '\\"', $dataSrc);
                $div->setAttribute('data-src', $escapedDataSrc);

                $result[] = $dom->saveHTML($div);
            }
        }

        return $result;
    }

    private function findTag($html, $name): bool
    {
        $pattern = '/<\s*' . preg_quote($name, '/') . '\b[^>]*>(.*?)<\s*\/\s*' . preg_quote($name, '/') . '\s*>/i';

        if (preg_match($pattern, $html)) {
            return true;
        }

        return false;
    }

    private function setAttributeInToElement($html, $elementName, array $attributes, array $attributesToRemove = [])
    {
        $dom = new DOMDocument();
        $dom->loadHTML(
            $html,
            LIBXML_NOWARNING | LIBXML_BIGLINES | LIBXML_NOBLANKS | LIBXML_NONET | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_PARSEHUGE
        );

        $elements = $dom->getElementsByTagName($elementName);

        foreach ($elements as $element) {
            $this->setAttribute($element, $attributes);

            foreach ($attributesToRemove as $name) {
                if ($element->hasAttribute($name)) {
                    $element->removeAttribute($name);
                }
            }
        }

        return $dom->saveHTML();
    }

    private function putInsideTheBlock(string $blocName, string $inputHtml, array $attributes = [])
    {
        try {
            $dom = new DOMDocument();

            $div = $dom->createElement($blocName);

            $this->setAttribute($div, $attributes);

            $fragment = $dom->createDocumentFragment();
            $fragment->appendXML($inputHtml);
            $div->appendChild($fragment);

            $dom->appendChild($div);

            return $dom->saveHTML();
        } catch (DOMException $e) {

            return $inputHtml;
        }
    }

    public function hasAnyTagsInsidePTag($html): bool
    {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);

        $pTags = $dom->getElementsByTagName('p');

        $ignoreTags = ['br'];

        foreach ($pTags as $pTag) {
            if ($pTag->hasChildNodes()) {
                foreach ($pTag->childNodes as $childNode) {

                    if ($childNode->nodeType === XML_ELEMENT_NODE && !in_array(
                            strtolower($childNode->nodeName),
                            $ignoreTags
                        )) {
                        return true;
                    }

                    if ($childNode->nodeType === XML_TEXT_NODE && trim($childNode->nodeValue) !== '') {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function setAttribute(DOMElement $fragment, array $attributes){
        foreach ($attributes as $name => $value) {
            $fragment->setAttribute($name, $value);
        }
    }

    private function extractInteger($string): string
    {
        if (strpos($string, "px") !== false) {
            $cleanedString = str_replace("px", "", $string);
            return (int) $cleanedString;
        }

        return $string;
    }

    private function handleSizeToInt(array $size): array
    {
        $result = [];
        foreach ($size as $key => $size) {
            $result[$key] = $this->extractInteger($size);
        }
        return $result;
    }

}
