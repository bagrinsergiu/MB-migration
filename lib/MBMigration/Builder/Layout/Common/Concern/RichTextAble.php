<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use DOMElement;
use DOMException;
use Exception;
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
        $selector = null
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
                    $selector
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
        $selector = null
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
                    $brizySection->getValue()->add_items([new BrizyComponent($textItem)]);
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
            $sizes = $this->getDomElementSizes($selectorImageSizes, $browserPage, $families, $default_fonts);
            $sizeUnit = 'px';
            if (strpos($sizes['width'], '%') !== false) {
                $selectorImageSizes = '[data-id="'.$mbSectionItemId.'"] .photo-container';
                $sizes = $this->getDomElementSizes($selectorImageSizes, $browserPage, $families, $default_fonts);
            }

            $brizyComponent->getValue()
                ->set_imageFileName($mbSectionItem['imageFileName'])
                ->set_imageSrc($mbSectionItem['content'])
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

            if ($mbSectionItem['link'] != '') {
                if($this->findTag($mbSectionItem['link'], 'iframe')) {
                    $popupFromKit = $this->globalBrizyKit['popup']['popup--embedCode'];
                    $popupSection = new BrizyComponent(json_decode($popupFromKit, true));

                    $popupUid = $this->generateUniqueId(12);

                    $attribute = [
                        'style' => "width : 100%; height : 100%;"
                    ];
                    $remove = [

                    ];

                    $iframe = $this->setAttributeInToElement($mbSectionItem['link'], 'iframe', $attribute, $remove);

                    $attribute = [
                        'style' => "aspect-ratio: 16 / 9; width : 100%;"
                    ];

                    $this->putInsideTheBlock('div', $iframe, $attribute);

                    $popupSection->getValue()
                        ->get_popupId($popupUid);
                    $popupSection->getItemWithDepth(0,0,0,0)->getValue()
                        ->get_code($popupUid);

                    $external = [
                        'linkType' => 'popup',
                        'linkPopup' => $popupUid,
                        'linkExternalBlank' => $mbSectionItem['new_window']
                    ];

                } else {
                    $urlComponents = parse_url($mbSectionItem['link']);

                    if (!empty($urlComponents['host'])) {
                        $slash = '';
                    } else {
                        $slash = '/';
                    }
                    if ($mbSectionItem['new_window']) {
                        $mbSectionItem['new_window'] = 'on';
                    } else {
                        $mbSectionItem['new_window'] = 'off';
                    }

                    $external = [
                        'linkType' => 'external',
                        'linkExternal' => $slash.$mbSectionItem['link'],
                        'linkExternalBlank' => $mbSectionItem['new_window']
                    ];
                }

                foreach ($external as $key => $value) {
                    $method = 'set_'.$key;
                    $brizyComponent->getValue()
                        ->$method('external');
                }
            }

        }

        return $brizyComponent;
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

    private function setAttribute(DOMElement $fragment, array $attributes){
        foreach ($attributes as $name => $value) {
            $fragment->setAttribute($name, $value);
        }
    }

}
