<?php

namespace MBMigration\Builder\Layout\Theme\Aurora;

use MBMigration\Builder\Layout\Common\AbstractTheme;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\Gradient;

class Aurora extends AbstractTheme
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
                'selector' => 'body',
                'styleProperties' => ['background-image', 'background-color'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        if(isset($sectionStyles['error'])){
            return ['bg-gradient'=> '#ffffff'];
        }

        $bgColor = $sectionStyles['data']['background-color'];
        $bgColorGradient = $sectionStyles['data']['background-image'];
        try{
            $gradient = new Gradient($bgColorGradient);

            return ['bg-gradient'=> [
                'type' => $gradient->getType(),
                'angleOrPosition' => $gradient->getAngleOrPosition(),
                'colors' => $gradient->getColors()
                ]
            ];
        } catch (\Exception $e){
            return ['bg-gradient' => ColorConverter::convertColorRgbToHex($bgColor)];
        }
    }

}
