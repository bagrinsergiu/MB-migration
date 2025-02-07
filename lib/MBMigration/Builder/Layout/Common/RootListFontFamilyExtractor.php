<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;
use MBMigration\Builder\Utils\FontUtils;

class RootListFontFamilyExtractor
{
    private BrowserPageInterface $browserPage;
    private array $allFontFamilies;

    public function __construct(BrowserPageInterface $browserPage)
    {
        $this->browserPage = $browserPage;
        $this->allFontFamilies = [];

        $this->getListFontFamily();
    }

    private function getListFontFamily(): void
    {
        $allFontFamilies = $this->browserPage->evaluateScript('brizy.dom.extractAllFontFamilies', []);

        if (isset($allFontFamilies['error'])) {
            $allFontFamilies = [];
        }

        if (empty($allFontFamilies)) {
            $allFontFamilies = [];
        }

        $this->allFontFamilies = $allFontFamilies;
    }

    public function getAllFontName(): array
    {
        $map = [];
        foreach ($this->allFontFamilies as $fontFamily) {

            $fontsMap = explode(',', $fontFamily[1]);
            foreach ($fontsMap as $font) {
                $converted = FontUtils::convertFontFamily(str_replace(' ', '', $font));
                if(!in_array($converted, $map)){
                    $map[] = $converted;
                }
            }

        }

        return $map;
    }

    public function getFontIdByName($name)
    {
        foreach ($this->allFontFamilies as $fontFamily) {
            $fontFamily = str_replace(['_sans-serif', '_serif', 'sans-serif', 'serif'], '', $fontFamily);
            $fontsMap = explode(',', $fontFamily[1]);
            $converted = FontUtils::convertFontFamily(str_replace(' ', '', $fontsMap[0]));

            if($name === $converted) {
                return $fontFamily[0];
            }
        }
    }

    public function getFontFamilyByName($name): array
    {
        $map = [];
        foreach ($this->allFontFamilies as $fontFamily) {

            $fontsMap = explode(',', $fontFamily[1]);
            foreach ($fontsMap as $font) {
                $converted = FontUtils::convertFontFamily(str_replace(' ', '', $font));
                if(!in_array($converted, $map)){
                    $map[] = $converted;
                }
            }

        }

        return $map;
    }




    public function getFontFamily(): array
    {
        $map = [];
        foreach ($this->allFontFamilies as $fontFamily) {
            if(!in_array($fontFamily[1], $map)){
                $map[] = $fontFamily[1];
            }
        }

        return $map;
    }

    public function getFontFamilyID(): array
    {
        $map = [];
        foreach ($this->allFontFamilies as $fontFamily) {
            if(!in_array($fontFamily[0], $map)){
                $map[] = $fontFamily[0];
            }
        }

        return $map;
    }



}
