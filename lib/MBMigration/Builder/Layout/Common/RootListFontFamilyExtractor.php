<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorUtility;
use MBMigration\Builder\Utils\FontUtils;
use MBMigration\Core\Logger;

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
        Logger::instance()->info('Starting font family extraction', [
            'url' => $this->browserPage->getCurrentUrl()
        ]);

        $allFontFamilies = $this->browserPage->evaluateScript('brizy.dom.extractAllFontFamilies', []);

        if (isset($allFontFamilies['error'])) {
            Logger::instance()->error('Font family extraction failed', [
                'error' => $allFontFamilies['error'],
                'url' => $this->browserPage->getCurrentUrl()
            ]);
            $allFontFamilies = [];
        }

        if (empty($allFontFamilies)) {
            Logger::instance()->warning('No fonts detected on page', [
                'url' => $this->browserPage->getCurrentUrl()
            ]);
            $allFontFamilies = [];
        } else {
            Logger::instance()->info('Font families extracted successfully', [
                'fontCount' => count($allFontFamilies),
                'url' => $this->browserPage->getCurrentUrl()
            ]);
        }

        $this->allFontFamilies = $allFontFamilies;
    }

    public function getAllFontName(): array
    {
        $map = [];
        foreach ($this->allFontFamilies as $fontFamily) {

            $fontsMap = explode(',', $fontFamily[1]);
            foreach ($fontsMap as $font) {
                $font = trim($font);
                $converted = FontUtils::convertFontFamily(str_replace(' ', '_', $font));
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
//            $fontFamily = str_replace(['_sans-serif', '_serif', 'sans-serif', 'serif'], '', $fontFamily);
            $fontsMap = explode(',', $fontFamily[1]);
            foreach ($fontsMap as $font) {
                $font = trim($font);
                $converted = FontUtils::convertFontFamily(str_replace(' ', '_', $font));

                if ($name === $converted) {
                    return $fontFamily[0];
                }
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
