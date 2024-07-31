<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;

class RootPalettesExtractor implements RootPalettesExtractorInterface
{
    private array $rootPalette = [];

    private static ?array $storedPalette = null;

    private BrowserPageInterface $browserPage;

    /**
     * @throws BrowserScriptException
     */
    public function __construct(BrowserPageInterface $browserPage)
    {
        $this->browserPage = $browserPage;
        if (self::$storedPalette !== null) {
            $this->rootPalette = self::$storedPalette;
        }
    }

    /**
     * @throws BrowserScriptException
     */
    public function ExtractRootPalettes(): RootPalettesExtractor
    {
        if (self::$storedPalette !== null) {
            return $this;
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

        $this->rootPalette = ColorUtility::parseSubpalettes(
            $elementStyles['data']
        );

        self::$storedPalette = $this->rootPalette;

        return $this;
    }

    public function getRootPalettes(): array
    {
        return $this->rootPalette ?? [];
    }

}
