<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait CssPropertyExtractorAware
{
    protected function evaluate(
        $scriptName,
        $selectorSectionStyles,
        $styles,
        BrowserPageInterface  $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $elementStyles = $browserPage->evaluateScript(
            $scriptName,
            [
                'selector' => $selectorSectionStyles,
                'styleProperties' => $styles,
                'families' => $families,
                'defaultFamily' => $default_fonts,
            ]
        );

        if (isset($elementStyles['error'])) {
            throw new BrowserScriptException($elementStyles['error']);
        }

        if (empty($elementStyles)) {
            throw new BrowserScriptException(
                "The element with selector {$selectorSectionStyles} was not found in page."
            );
        }

        return $elementStyles['data'];
    }

    protected function getDomElementStyles(
        $selectorSectionStyles,
        $styles,
        $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {

        return $this->evaluate(
            'brizy.getStyles',
            $selectorSectionStyles,
            $styles,
            $browserPage,
            $families,
            $default_fonts
        );
    }

    protected function getDomElementSizes(
        $selectorSectionStyles,
        $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $styles = [
            'margin-top',
            'margin-bottom',
            'margin-right',
            'margin-left',
            'padding-top',
            'padding-bottom',
            'padding-right',
            'padding-left',
            'margin-top',
            'margin-bottom',
            'margin-left',
            'margin-right',
            'width',
            'height',
        ];

        return $this->getDomElementStyles($selectorSectionStyles, $styles, $browserPage, $families, $default_fonts);
    }

}