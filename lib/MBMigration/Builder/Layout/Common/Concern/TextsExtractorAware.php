<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait TextsExtractorAware
{
    protected function extractTexts(
        $selectorSectionStyles,
        BrowserPageInterface $browserPage,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $richTextBrowserData = $browserPage->evaluateScript('brizy.getText', [
            'selector' => $selectorSectionStyles,
            'families' => $families,
            'defaultFamily' => $defaultFont,
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