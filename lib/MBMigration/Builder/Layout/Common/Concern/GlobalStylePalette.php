<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;

trait GlobalStylePalette
{
    private array $rootPalette = [];

    /**
     * @throws BrowserScriptException
     */
    protected function evaluate(BrowserPageInterface $browserPage) :array
    {
        $elementStyles = $browserPage->evaluateScript('brizy.dom.getRootPropertyStyles', []);

        if (isset($elementStyles['error'])) {
            throw new BrowserScriptException($elementStyles['error']);
        }

        if (empty($elementStyles)) {
            throw new BrowserScriptException(
                "The element was not found in page."
            );
        }

        return $elementStyles['data'];
    }

    /**
     * @throws BrowserScriptException
     */
    protected function getRootPalette($browserPage): array
    {
        if(empty($this->rootPalette)) {
            $this->rootPalette = ColorUtility::parseSubpalettes(
                $this->evaluate($browserPage)
            );
        }

        return $this->rootPalette ?? [];
    }
}
