<?php

namespace Brizy\Builder\Fonts;

use Brizy\layer\Brizy\BrizyAPI;

class FontsController
{
    private BrizyAPI $BrizyApi;

    public function __construct(){
        $this->BrizyApi = new BrizyAPI();
    }

    public function upLoadFonts($fontName, $projectID): bool|array
    {
        $path = $this->getPathToFonts($fontName);
        return $this->BrizyApi->createFonts($fontName, $projectID, $path);
    }

    private function getPathToFonts($fontName): string
    {
        $fonts = $this->kitFonts();
        $path = false;
        if(array_key_exists($fontName, $fonts)){
            $path = __DIR__ . $fonts[$fontName];
        }
        return $path;
    }

    private function kitFonts(): array
    {
        return [
            'Arial' => '/FontSet/Arial.wft'
        ];
    }
}