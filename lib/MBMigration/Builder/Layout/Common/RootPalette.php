<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;

class RootPalette implements RootPaletteInterface
{
    private array $rootPalette = [];

    /**
     * @throws BrowserScriptException
     */
    public function __construct(BrowserPageInterface $browserPage)
    {
        if(empty($this->rootPalette)) {
            $this->rootPalette = ColorUtility::parseSubpalettes(
                $this->evaluate($browserPage)
            );
        }

        return $this;
    }

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

    public function getSubPaletteByName($name): array
    {
        if (array_key_exists($name, $this->rootPalette)) {
            return $this->rootPalette[$name];
        }
        return $this->rootPalette['subpalette1'] ?? [];
    }

}
