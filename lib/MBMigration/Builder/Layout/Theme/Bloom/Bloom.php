<?php

namespace MBMigration\Builder\Layout\Theme\Bloom;

use MBMigration\Builder\Layout\Common\AbstractTheme;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\Gradient;

class Bloom extends AbstractTheme
{
    public function getThemeIconSelector(): string
    {
        return "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
    }

    public function getThemeButtonSelector(): string
    {
        return ".sites-button:not(.nav-menu-button)";
    }

    public function beforeBuildPage(): array
    {
        $browserPage = $this->themeContext->getBrowserPage();
        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '#main-content > header',
                'styleProperties' => ['height'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        if(isset($sectionStyles['error'])){

            return ['headerHeight'=> 100];
        }

        $height = $sectionStyles['data']['height'];

        return ['headerHeight' => (int) ColorConverter::convertColorRgbToHex($height)];
    }

}
