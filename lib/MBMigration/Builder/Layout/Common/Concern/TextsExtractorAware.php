<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ExecutionTimer;
use MBMigration\Core\Logger;

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
            Logger::instance()->error('Error extractTexts  : '.$richTextBrowserData['error']);
            return [];
        }

        if (!isset($richTextBrowserData['data'])) {
            Logger::instance()->warning('Probably the section id was not found in page. Selector: '.$selectorSectionStyles);
            return [];
        }

        return $richTextBrowserData['data'];
    }

}
