<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait RichTextAble
{
    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextItems(ElementDataInterface $data, BrowserPage $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        foreach ((array)$mbSectionItem['items'] as $mbSectionItem) {
            $elementContext = $data->instanceWithMBSection($mbSectionItem);
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
                $brizySection = $this->handlePhotoItem(
                    $mbSectionItem['sectionId'] ?? $mbSectionItem['id'],
                    $brizySection,
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

        if(isset($richTextBrowserData['error'])) {
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
        $mbSectionItem,
        BrizyComponent $brizySection,
        BrowserPage $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        return $brizySection;
    }
}