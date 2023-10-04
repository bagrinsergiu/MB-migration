<?php

namespace MBMigration\Builder\Fonts;

use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\Utils\builderUtils;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;

class FontsController extends builderUtils
{
    private $BrizyApi;
    private $fontsMap;
    /**
     * @var mixed
     */
    private $projectId;

    protected $layoutName;

    /**
     * @var VariableCache
     */
    private $cache;

    /**
     * @throws \Exception
     */
    public function __construct($projectId){
        $this->BrizyApi = new BrizyAPI();
        $this->getFontsMap();
        $this->projectId = $projectId;
        $this->cache = VariableCache::getInstance();
        $this->layoutName = 'FontsController';
    }

    /**
     * @throws \Exception
     * @throws GuzzleException
     */
    public function upLoadFonts($fontName): string
    {
        Utils::log("Create FontName $fontName", 1, "upLoadFonts");
        $KitFonts = $this->getPathFont($fontName);
        if($KitFonts){
            $responseDataAddedNewFont = $this->BrizyApi->createFonts($fontName, $this->projectId, $KitFonts['fontsFile'], $KitFonts['displayName']);
            $this->cache->add('responseDataAddedNewFont', [$fontName => $responseDataAddedNewFont]);
            return $this->BrizyApi->addFontAndUpdateProject($responseDataAddedNewFont);
        }
        return 'lato';
    }

    /**
     * @throws \Exception
     */
    public function getFontsMap(): void
    {
        $this->layoutName = 'FontsController';
        Utils::log("Download fonts map", 1, "downloadMapFontsFromUrl");
        if(Config::$urlJsonKits) {
            $createUrlForFileFontsMap = Config::$urlJsonKits . '/fonts/fonts.json';
            $this->fontsMap = $this->loadJsonFromUrl($createUrlForFileFontsMap);
        } else {
            $file = __DIR__ . '\fonts.json';
            $fileContent = file_get_contents($file);
            $this->fontsMap = json_decode($fileContent, true);
        }
    }

    private function getPathFont($name)
    {
        $fontPack = [];
        foreach ($this->fontsMap as $key=>$font) {
            if($key === $name) {
                foreach ($font['fonts'] as $fontWeight => $fontStyle) {
                    $fontPack[$fontWeight] = $fontStyle['normal'];
                }
                $this->cache->add('fonsUpload',  [$name => $fontPack]);
                return [
                    'displayName' => $font['settings']['display_name'],
                    'fontsFile' => $fontPack
                ];
            }
        }
        return false;
    }

    protected function checkArrayPath($array, $path, $check = ''): bool
    {
        $keys = explode('/', $path);
        $current = $array;

        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                return false;
            }
            $current = $current[$key];
        }

        if($check != '')
        {
            if(is_array($check)){
                foreach ($check as $look){
                    if ($current === $look) {
                        return true;
                    }
                }
            } else {
                if ($current === $check) {
                    return true;
                }
            }
        }
        return true;
    }
}