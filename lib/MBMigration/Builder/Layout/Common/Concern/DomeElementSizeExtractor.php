<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait DomeElementSizeExtractor
{
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

        return $this->getDomElementStyle($selectorSectionStyles, $styles, $browserPage, $families, $default_fonts);
    }

    protected function getDomElementStyle(
        $selectorSectionStyles,
        $styles,
        $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {
        $elementStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => $selectorSectionStyles,
                'STYLE_PROPERTIES' => $styles,
                'FAMILIES' => $families,
                'DEFAULT_FAMILY' => $default_fonts,
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
}