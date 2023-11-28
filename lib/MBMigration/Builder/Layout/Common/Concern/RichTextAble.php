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

        // sort items
        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ((array)$mbSectionItem['items'] as $mbItem) {
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
        $brizySection = $data->getBrizySection();

        switch ($mbSectionItem['category']) {
            case 'text':
                $brizySection = $this->handleTextItem(
                    $mbSectionItem,
                    $brizySection,
                    $browserPage,
                    $families,
                    $default_fonts
                );
                break;
            case 'photo':
                $imageTarget = $brizySection;
                if ($brizySection->getType() != 'Wrapper') {
                    $imageTarget = new BrizyWrapperComponent('wrapper-image');
                    $brizySection->getValue()->add_items([$imageTarget]);
                }

                $this->handlePhotoItem(
                    $mbSectionItem['sectionId'] ?? $mbSectionItem['id'],
                    $mbSectionItem,
                    $imageTarget,
                    $browserPage,
                    $families,
                    $default_fonts
                );

                break;
        }

        return $brizySection;
    }

    private function handleTextItem(
        $mbSectionItem,
        BrizyComponent $brizySection,
        BrowserPage $browserPage,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $richTextBrowserData = $browserPage->evaluateScript('Text.js', [
            'SELECTOR' => '[data-id="'.($mbSectionItem['sectionId'] ?? $mbSectionItem['id']).'"]',
            'FAMILIES' => $families,
            'DEFAULT_FAMILY' => $defaultFont,
        ]);

        if (isset($richTextBrowserData['error'])) {
            throw new BrowserScriptException($richTextBrowserData['error']);
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
        BrizyComponent $brizySection,
        BrowserPage $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {

        $brizyImage = new BrizyImageComponent();

        if (!empty($mbSectionItem['content'])) {
            $brizyImage->getValue()
                ->set_imageFileName($mbSectionItem['imageFileName'])
                ->set_imageSrc($mbSectionItem['content'])
                ->set_width($mbSectionItem['settings']['image']['width'])
                ->set_height($mbSectionItem['settings']['image']['height'])
                ->set_imageWidth($mbSectionItem['settings']['image']['width'])
                ->set_imageHeight($mbSectionItem['settings']['image']['height'])
                ->set_widthSuffix('px')
                ->set_heightSuffix('px');
        }

        return $brizySection->getValue()->add_items([$brizyImage]);
    }

    private function findEmbeddedElements($html): array
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($html);

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