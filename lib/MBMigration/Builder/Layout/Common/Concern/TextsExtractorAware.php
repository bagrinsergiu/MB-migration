<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait TextsExtractorAware
{
    protected function extractTexts(
        $selectorSectionStyles,
        BrowserPage $browserPage,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $richTextBrowserData = $browserPage->evaluateScript('Text.js', [
            'SELECTOR' => $selectorSectionStyles,
            'FAMILIES' => $families,
            'DEFAULT_FAMILY' => $defaultFont,
        ]);

        if (isset($richTextBrowserData['error'])) {
            throw new BrowserScriptException($richTextBrowserData['error']);
        }

        if (!isset($richTextBrowserData['data'])) {
            throw new BrowserScriptException("Probably the section id was not found in page. Selector:".$selectorSectionStyles);
        }

        return $richTextBrowserData['data'];
    }

}