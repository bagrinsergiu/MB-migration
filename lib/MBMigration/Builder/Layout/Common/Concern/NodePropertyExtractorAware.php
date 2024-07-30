<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait NodePropertyExtractorAware
{
    protected function fetchElementStyles(
        $scriptName,
        BrowserPageInterface  $browserPage,
        $selectorSectionStyles,
        array $properties = []
    ) {

        $params = [
            'selector' => $selectorSectionStyles
        ];

        if(isset($properties)){
            $params = array_merge($params, $properties);
        }

        $elementStyles = $browserPage->evaluateScript(
            $scriptName,
            $params
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

    /**
     * @throws BrowserScriptException
     */
    protected function getDomSubPalette(
        $selectorSectionStyles,
        $browserPage
    ) {

        return $this->fetchElementStyles(
            'brizy.dom.detectSubpalette',
            $browserPage,
            $selectorSectionStyles
        );
    }

}
