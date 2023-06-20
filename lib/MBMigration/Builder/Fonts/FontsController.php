<?php

namespace MBMigration\Builder\Fonts;

use MBMigration\Layer\Brizy\BrizyAPI;

class FontsController
{
    private $BrizyApi;
    private $fontsMap;

    public function __construct(){
        $this->BrizyApi = new BrizyAPI();
        $this->kitFonts();
    }

    public function upLoadFonts($fontName, $projectID)
    {
        $path = $this->getPathToFonts($fontName);
        return $this->BrizyApi->createFonts($fontName, $projectID, $path);
    }

    private function getPathToFonts($fontName): string
    {
        $fonts =
        $path = false;
        if(array_key_exists($fontName, $fonts)){
            $path = __DIR__ . $fonts[$fontName];
        }
        return $path;
    }

    private function kitFonts():void
    {
        $file = __DIR__ . '\fonts.json.json';
        $fileContent = file_get_contents($file);
        $this->fontsMap = json_decode($fileContent, true);
    }
}