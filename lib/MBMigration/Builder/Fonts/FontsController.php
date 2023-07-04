<?php

namespace MBMigration\Builder\Fonts;

use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;
use function MongoDB\Driver\Monitoring\removeSubscriber;

class FontsController
{
    private $BrizyApi;
    private $fontsMap;
    /**
     * @var mixed
     */
    private $projectId;

    public function __construct($projectId){
        $this->BrizyApi = new BrizyAPI();
        $this->kitFonts();
        $this->projectId = $projectId;
    }

    /**
     * @throws \Exception
     * @throws GuzzleException
     */
    public function upLoadFonts($fontName, $fontWeight = 400)
    {
        Utils::log("Create FontName $fontName", 1, "upLoadFonts");
        $path = $this->getPathFont($fontName, $fontWeight);
        if($path){
            $responce =  $this->BrizyApi->createFonts($fontName, $this->projectId, __DIR__ . $path, $fontWeight);
        }
        return $responce;
    }

    private function getPathFont($name, $weight): string
    {
        foreach ($this->fontsMap as $key=>$font) {
            if($key === $name) {
                if($weight !== 400){

                } else {
                    if($this->checkArrayPath($font['fonts'], 'normal/normal/0')) {
                        return [
                            'path' => $font['fonts']['normal']['normal'][0]['path'],
                            'fontWeight' =>
                            ];
                    }
                    if($this->checkArrayPath($font['fonts'], '400/normal/0')) {
                        return $font['fonts']['400']['normal'][0]['path'];
                    }
                    if($this->checkArrayPath($font['fonts'], '300/normal/0')) {
                        return $font['fonts']['300']['normal'][0]['path'];
                    }
                    if($this->checkArrayPath($font['fonts'], '200/normal/0')) {
                        return $font['fonts']['200']['normal'][0]['path'];
                    }
                    if($this->checkArrayPath($font['fonts'], '500/normal/0')) {
                        return $font['fonts']['500']['normal'][0]['path'];
                    }
                    if($this->checkArrayPath($font['fonts'], '600/normal/0')) {
                        return $font['fonts']['600']['normal'][0]['path'];
                    }
                    if($this->checkArrayPath($font['fonts'], '700/normal/0')) {
                        return $font['fonts']['700']['normal'][0]['path'];
                    }
                }
            }
        }
        return false;
    }

    private function kitFonts():void
    {
        $file = __DIR__ . '\fonts.json';
        $fileContent = file_get_contents($file);
        $this->fontsMap = json_decode($fileContent, true);
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