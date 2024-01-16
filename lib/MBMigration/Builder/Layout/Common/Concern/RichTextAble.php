<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyEmbedCodeComponent;
use MBMigration\Builder\BrizyComponent\BrizyImageComponent;
use MBMigration\Builder\BrizyComponent\BrizyWrapperComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait RichTextAble
{
    use DomeElementSizeExtractor;

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextHead(ElementContextInterface $data, BrowserPage $browserPage): BrizyComponent
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
            $this->handleRichTextItem(
                $elementContext,
                $browserPage
            );
        }

        return $brizySection;
    }

    protected function handleRichTextHeadFromItems(
        ElementContextInterface $data,
        BrowserPage $browserPage,
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

            if (!is_null($acllback)) {
                $acllback($elementContext);
            } else {
                $this->handleRichTextItem(
                    $elementContext,
                    $browserPage
                );
            }
        }

        return $brizySection;
    }

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextItems(ElementContextInterface $data, BrowserPage $browserPage): BrizyComponent
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
            $this->handleRichTextItem(
                $elementContext,
                $browserPage
            );
        }

        return $brizySection;
    }

    /**
     * Process single rich text item and place it the brizy section
     */
    protected function handleRichTextItem(
        ElementContextInterface $data,
        BrowserPage $browserPage
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
                    $default_fonts
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
        BrowserPage $browserPage,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];
        $richTextBrowserData = $browserPage->evaluateScript('Text.js', [

            'SELECTOR' => '[data-id="'.$sectionId.'"]',
            'FAMILIES' => $families,
            'DEFAULT_FAMILY' => $defaultFont,
        ]);

        if (isset($richTextBrowserData['error'])) {
            throw new BrowserScriptException($richTextBrowserData['error']);
        }

        if (!isset($richTextBrowserData['data'])) {
            throw new BrowserScriptException("Probably the section id was not found in page. SectionId:".$sectionId);
        }
        $embeddedElements = $this->findEmbeddedElements($mbSectionItem['content']);
        $embeddIndex = 0;
        foreach ($richTextBrowserData['data'] as $i => $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    //wrapper
                    $brizySection->getValue()->add_items(
                        [new BrizyEmbedCodeComponent($embeddedElements[$embeddIndex++])]
                    );
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
        BrowserPage $browserPage,
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
                ->set_height((int)$sizes['height'])
                ->set_imageWidth($mbSectionItem['settings']['image']['width'])
                ->set_imageHeight($mbSectionItem['settings']['image']['height'])
                ->set_widthSuffix($sizeUnit)
                ->set_heightSuffix($sizeUnit);
        }

        return $brizyComponent;
    }

    private function findEmbeddedElements($html): array
    {
        $dom = new \DOMDocument();
        $dom->loadHTML(
            "<!DOCTYPE html><html><head><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"></head><body>{$html}</body></html>"
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

}