<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Core\Logger;

trait CssPropertyExtractorAware
{
    protected function evaluate(
        $scriptName,
        $selectorSectionStyles,
        $styles,
        BrowserPageInterface $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
        $pseudoElement = null
    ) {

        $params = [
            'selector' => $selectorSectionStyles,
            'styleProperties' => $styles,
            'families' => $families,
            'defaultFamily' => $default_fonts,
        ];

        if (isset($pseudoElement)) {
            $params['pseudoElement'] = $pseudoElement;
        }

        $elementStyles = $browserPage->evaluateScript(
            $scriptName,
            $params
        );

        if (isset($elementStyles['error'])) {
            Logger::instance()->error($elementStyles['error']);

            return [];
        }

        if (empty($elementStyles)) {
            Logger::instance()->critical("The element with selector {$selectorSectionStyles} was not found in page.");

            return [];
        }

        return $elementStyles['data'];
    }

    protected function getDomElementStyles(
        $selectorSectionStyles,
        $styles,
        $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
        $pseudoElement = null
    )
    {

        if (!$this->hasNode($selectorSectionStyles, $browserPage)) {
            Logger::instance()->error("The element with selector {$selectorSectionStyles} was not found in page.");
            return [];
        }

        return $this->evaluate(
            'brizy.getStyles',
            $selectorSectionStyles,
            $styles,
            $browserPage,
            $families,
            $default_fonts,
            $pseudoElement
        );
    }

    protected function getDomElementStylesAtViewport(
        $selectorSectionStyles,
        $styles,
        $browserPage,
        int $viewportWidth,
        int $viewportHeight,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
        $pseudoElement = null
    ) {
        if (!method_exists($browserPage, 'setViewportSize')) {
            return $this->getDomElementStyles(
                $selectorSectionStyles, $styles, $browserPage, $families, $default_fonts, $pseudoElement
            );
        }

        $browserPage->setViewportSize($viewportWidth, $viewportHeight);
        try {
            return $this->getDomElementStyles(
                $selectorSectionStyles, $styles, $browserPage, $families, $default_fonts, $pseudoElement
            );
        } finally {
            $browserPage->setViewportSize(1920, 1480);
        }
    }

    protected function getAccordionElementStyles(
        $selectorSectionStyles,
        $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
        $pseudoElement = null
    ) {

        return $this->evaluate(
            'brizy.getAccordion',
            $selectorSectionStyles,
            [],
            $browserPage,
            $families,
            $default_fonts,
            $pseudoElement
        );
    }

    protected function getTabsElementStyles(
        $selectorSection,
        $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ) {

        return $this->evaluate(
            'brizy.getTabs',
            $selectorSection,
            [],
            $browserPage,
            $families,
            $default_fonts,
        );
    }

    protected function getNodeSubPalette(
        $selectorSectionStyles,
        $browserPage
    ) {
        return $this->evaluate(
            'brizy.dom.detectSubpalette',
            $selectorSectionStyles,
            $styles = [],
            $browserPage,
            $families = [],
            $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
            $pseudoElement = null
        );
    }

    protected function hasNode(
        $selectorSectionStyles,
        $browserPage
    ) {
        $sectionStyles = $this->evaluate(
            'brizy.dom.hasNode',
            $selectorSectionStyles,
            $styles = [],
            $browserPage,
            $families = [],
            $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
            $pseudoElement = null
        );

        if (array_key_exists('error', $sectionStyles)) {
            return false;
        }

        return $sectionStyles['hasNode'] ?? false;
    }

    protected function getNodeText(
        $selectorSectionStyles,
        $browserPage
    ) {
        $sectionStyles = $this->evaluate(
            'brizy.dom.getNodeText',
            $selectorSectionStyles,
            $styles = [],
            $browserPage,
            $families = [],
            $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
            $pseudoElement = null
        );

        if (array_key_exists('error', $sectionStyles)) {
            return false;
        }

        return $sectionStyles['contain'] ?? false;
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

    public function getNodeAttribute(BrowserPageInterface $browserPage, string $selector, string $attributes)
    {
        $result = $browserPage->evaluateScript(
            "brizy.dom.getNodeAttribute",
            [
                'selector' => $selector,
                'attributeName' => $attributes,
            ]
        );

        return $result['data'] ?? false;
    }

}
