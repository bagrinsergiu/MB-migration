<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyImageComponent;
use MBMigration\Builder\BrizyComponent\BrizyWrapperComponent;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait RichTextAble
{

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextHead(ElementDataInterface $data, BrowserPage $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $showHeader = $mbSectionItem['settings']['sections']['text']['show_header'] ?? true;
        $showBody = $mbSectionItem['settings']['sections']['text']['show_body'] ?? true;

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

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextItems(ElementDataInterface $data, BrowserPage $browserPage): BrizyComponent
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
        ElementDataInterface $data,
        BrowserPage $browserPage
    ) {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $default_fonts = $data->getDefaultFontFamily();
        $brizySection = $data->getBrizySection();

        switch ($mbSectionItem['category']) {
            case 'text':
                $brizySection = $this->handleTextItem(
                    $mbSectionItem['sectionId'] ?? $mbSectionItem['id'],
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
        $mbSectionItemId,
        BrizyComponent $brizySection,
        BrowserPage $browserPage,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $richTextBrowserData = $browserPage->evaluateScript('Text.js', [
            'SELECTOR' => '[data-id="'.$mbSectionItemId.'"]',
            'FAMILIES' => $families,
            'DEFAULT_FAMILY' => $defaultFont,
        ]);

        if (isset($richTextBrowserData['error'])) {
            throw new BrowserScriptException($richTextBrowserData['error']);
        }

        foreach ($richTextBrowserData['data'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    //wrapper
                    $brizySection->getValue()->add_items([new BrizyComponent($textItem)]);
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
}