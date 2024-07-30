<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;

class RootPalettesExtractor implements RootPalettesExtractorInterface
{
    private array $rootPalette = [];

    private BrowserPageInterface $browserPage;

    /**
     * @throws BrowserScriptException
     */
    public function __construct(BrowserPageInterface $browserPage)
    {
        $this->browserPage = $browserPage;
        return $this;
    }

    /**
     * @throws BrowserScriptException
     */
    public function ExtractRootPalettes(): RootPalettesExtractor
    {
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

        return $this;
    }

    public function getRootPalettes(): array
    {
        return $this->rootPalette ?? [];
    }

}
