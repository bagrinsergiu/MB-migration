<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;

class RootListFontFamilyExtractor
{
    private static $storedPalette;

    private BrowserPageInterface $browserPage;

    public function __construct(BrowserPageInterface $browserPage)
    {
        $this->browserPage = $browserPage;
    }

    public function getListFontFamily(): array
    {
        if (self::$storedPalette !== null) {
            return self::$storedPalette;
        }

        $elementStyles = $this->browserPage->evaluateScript('brizy.dom.extractAllFontFamilies', []);

        if (isset($elementStyles['error'])) {
            throw new BrowserScriptException($elementStyles['error']);
        }

        if (empty($elementStyles)) {
            throw new BrowserScriptException(
                "The element was not found in page."
            );
        }

        return $elementStyles;
    }



}
