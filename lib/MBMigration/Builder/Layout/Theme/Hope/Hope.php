<?php

namespace MBMigration\Builder\Layout\Theme\Hope;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\Layout\Common\AbstractTheme;

class Hope extends AbstractTheme
{
    public function getThemeIconSelector(): string
    {
        return "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
    }

    public function getThemeButtonSelector(): string
    {
        return ".sites-button:not(.nav-menu-button)";
    }

    public function beforeTransformBlocks(BrizyPage $page, array $mbPageSections): BrizyPage
    {
        return $page;
    }

    static public function getStyles($selector, $properties, BrowserPageInterface $browserPage, $pseudoElement = null): array
    {
        $styles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'pseudoElement' => $pseudoElement,
                'styleProperties' => $properties,
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        if (isset($styles['data'])) {
            return $styles['data'];
        }

        return [];
    }

}
