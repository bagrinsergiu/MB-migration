<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait TextsExtractorAware
{
    protected function extractTexts(
        $selectorSectionStyles,
        BrowserPageInterface $browserPage,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
        $urlMap = []
    ) {
        $richTextBrowserData = $browserPage->evaluateScript('brizy.getText', [
            'selector' => $selectorSectionStyles,
            'families' => $families,
            'defaultFamily' => $defaultFont,
            'urlMap'=>$urlMap
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