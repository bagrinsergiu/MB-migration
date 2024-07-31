<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;

class RootPalettesExtractor implements RootPalettesExtractorInterface
{
    private static RootPalettes $storedPalette;

    private BrowserPageInterface $browserPage;

    public function __construct(BrowserPageInterface $browserPage)
    {
        $this->browserPage = $browserPage;
    }

    /**
     * @throws BrowserScriptException
     */
    public function extractRootPalettes(): RootPalettes
    {
        if (self::$storedPalette !== null) {
            return self::$storedPalette;
        }

        $elementStyles = $this->browserPage->evaluateScript('brizy.dom.getRootPropertyStyles', []);

        if (isset($elementStyles['error'])) {
            throw new BrowserScriptException($elementStyles['error']);
        }

        if (empty($elementStyles)) {
            throw new BrowserScriptException(
                "The element was not found in page."
            );
        }

        $rootPalettes = ColorUtility::parseSubpalettes(
            $elementStyles['data']
        );

        return self::$storedPalette =  new RootPalettes($rootPalettes);
    }
}
