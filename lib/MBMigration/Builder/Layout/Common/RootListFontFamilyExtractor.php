<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;

class RootListFontFamilyExtractor
{
    private BrowserPageInterface $browserPage;

    public function __construct(BrowserPageInterface $browserPage)
    {
        $this->browserPage = $browserPage;
    }

    public function getListFontFamily(): array
    {
        $elementStyles = $this->browserPage->evaluateScript('brizy.dom.extractAllFontFamilies', []);

        if (isset($elementStyles['error'])) {
            return [];
        }

        if (empty($elementStyles)) {
            return [];
        }

        return $elementStyles;
    }
}
