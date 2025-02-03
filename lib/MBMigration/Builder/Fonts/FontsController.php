<?php

namespace MBMigration\Builder\Fonts;

use Exception;
use MBMigration\Builder\Utils\FontUtils;
use MBMigration\Core\Logger;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\Utils\builderUtils;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Layer\Brizy\BrizyAPI;

class FontsController extends builderUtils
{
    private BrizyAPI $BrizyApi;
    private array $fontsMap;
    private int $projectId;

    protected string $layoutName;
    private VariableCache $cache;

    private array $googeFontsMap;

    /**
     * @throws Exception
     */
    public function __construct($projectId)
    {
        $this->BrizyApi = new BrizyAPI();
        $this->getFontsMap();
        $this->projectId = $projectId;
        $this->cache = VariableCache::getInstance();
        $this->layoutName = 'FontsController';
    }

    /**
     * @throws Exception
     */
    public function getFontsMap(): void
    {
        $this->layoutName = 'FontsController';

        $file = __DIR__.'/fonts.json';
        $fileContent = file_get_contents($file);
        $this->fontsMap = json_decode($fileContent, true);

        $file = __DIR__.'/googleFonts.json';
        $fileContent = file_get_contents($file);
        $this->googeFontsMap = json_decode($fileContent, true);
    }

    /**
     * @throws Exception
     */
    public function getProjectData(): array
    {
        $containerID = $this->cache->get('projectId_Brizy');
        try{

            return json_decode(
                $this->BrizyApi->getProjectContainer($containerID, true)['data'],
                true) ?? [];
        } catch (Exception|GuzzleException $e){

            return [];
        }
    }

    public function upLoadCustomFonts(array $fontsList, $getFontFamily, $getFontFamilyId): void
    {
        $result = [];

        $allUpLoadFonts = $this->getAllUpLoadFonts();

        foreach ($fontsList as $key => $font) {
            if (!in_array($font, $allUpLoadFonts)) {
                $this->upLoadMBFonts($font, $getFontFamily, $getFontFamilyId);
                $this->upLoadGoogleFonts($font, $getFontFamily, $getFontFamilyId);
            }
        }
    }

    public function upLoadMBFonts($fontName): void
    {
        foreach ($this->fontsMap as $font){
            if($font === $fontName){
                try{
                    $this->upLoadFont($fontName);
                } catch (Exception|GuzzleException $e){
                    return;
                }
                return;
            }
        }

    }

    public function upLoadGoogleFonts($fontName): void
    {
        try{
            $this->upLoadGoogleFont($fontName);
        } catch (Exception|GuzzleException $e){
            return;
        }
    }


    /**
     * @throws Exception
     */
    public function getAllUpLoadFonts(): array
    {
        $result = [];

        $projectData = $this->getProjectData();

        foreach ($projectData['fonts']['config']['data'] as $projectFont) {
            $fontFamily = FontUtils::convertFontFamily($projectFont['family']);
            if (!in_array($fontFamily, $result)) {
                $result[] = $fontFamily;
            }
        }

        foreach ($projectData['fonts']['upload']['data']  as $projectFont) {
            $fontFamily = FontUtils::convertFontFamily($projectFont['family']);
            if (!in_array($fontFamily, $result)) {
                $result[] = $fontFamily;
            }
        }

        return $result;
    }


    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function upLoadFont($fontName): string
    {
        Logger::instance()->info("Create FontName $fontName");
        $KitFonts = $this->getPathFont($fontName);
        if ($KitFonts) {
            $responseDataAddedNewFont = $this->BrizyApi->createFonts(
                $fontName,
                $this->projectId,
                $KitFonts['fontsFile'],
                $KitFonts['displayName']
            );
            $this->cache->add('responseDataAddedNewFont', [$fontName => $responseDataAddedNewFont]);

            return $this->BrizyApi->addFontAndUpdateProject($responseDataAddedNewFont);
        }

        return 'lato';
    }

    public function upLoadGoogleFont($fontName)
    {
        Logger::instance()->info("Create FontName $fontName");

        if(!$responseDataAddedNewFont = $this->cache->get($fontName, 'responseDataAddedNewFont')) {
            $KitFonts = $this->getGoogleFontBnName($fontName);
            if ($KitFonts) {

                $this->cache->add('responseDataAddedNewFont', [$fontName => $responseDataAddedNewFont]);

//                return $this->BrizyApi->addFontAndUpdateProject($responseDataAddedNewFont, true);

                return $responseDataAddedNewFont['uid'];
            }

        }
    }

    public function addFontsToBrizyProject(array $fontStyles): array
    {
        $result = [];
        $containerID = $this->cache->get('projectId_Brizy');
        $projectFullData = $this->BrizyApi->getProjectContainer($containerID, true);
        $projectData = json_decode($projectFullData['data'], true);

        $hasNewFonts = false;
        foreach ($fontStyles as $index => $fontStyle) {
            $fontName = $fontStyle['fontName'];
            $KitFonts = $this->getPathFont($fontStyle['fontName']);
            if (!$KitFonts) {
                $result[$fontName] = 'lato'; // strange uuid to return.
                continue;
            };

            $uploaded = false;

            if(!empty($projectData['fonts']['upload']['data'])) {
                foreach ($projectData['fonts']['upload']['data'] as $projectFont) {
                    if ($projectFont['family'] == $KitFonts['displayName']) {
                        foreach ($KitFonts['fontsFile'] as $type => $font) {
                            if (!in_array($type, $projectFont['files'])) {
                                continue 2;
                            }
                        }
                        $uploaded = true;
                        $fontStyles[$index]['uuid'] = $projectFont['id'];
                        break;
                    }
                }
            }

            if($uploaded) {
                continue;
            }

            if(!$fontToAttach = $this->cache->get($fontName, 'responseDataAddedNewFont')){
                $fontToAttach = $this->BrizyApi->createFonts(
                    $fontName,
                    $this->projectId,
                    $KitFonts['fontsFile'],
                    $KitFonts['displayName']
                );
                $this->cache->add('responseDataAddedNewFont', [$fontName => $fontToAttach]);
            }

            $newData = [];
            $newData['family'] = $fontToAttach['family'];
            $newData['files'] = $fontToAttach['files'];
            $newData['weights'] = $fontToAttach['weights'];
            $newData['type'] = $fontToAttach['type'];
            $newData['id'] = $fontToAttach['uid'];
            $newData['brizyId'] = BrizyAPI::generateCharID(36);

            $projectData['fonts']['upload']['data'][] = $newData;

            $fontStyles[$index]['uuid'] = $fontToAttach['uid'];

            $hasNewFonts = true;
        }

        if ($hasNewFonts) {
            $projectFullData['data'] = json_encode($projectData);
            $this->BrizyApi->updateProject($projectFullData);
        }

        return $fontStyles;
    }

    private function getPathFont($name)
    {
        $fontPack = [];
        foreach ($this->fontsMap as $key => $font) {
            if ($key === $name) {
                foreach ($font['fonts'] as $fontWeight => $fontStyle) {
                    $fontPack[$fontWeight] = $fontStyle['normal'];
                }
                $this->cache->add('fonsUpload', [$name => $fontPack]);

                return [
                    'displayName' => $font['settings']['display_name'],
                    'fontsFile' => $fontPack,
                ];
            }
        }

        return false;
    }

    private function getGoogleFontBnName($fontName)
    {
        foreach ($this->googeFontsMap['items'] as $font) {
            if (FontUtils::convertFontFamily($font['family']) === $fontName) {
               return $font;
            }
        }
        return false;
    }

    private function getPathFontByName($name)
    {
        $fontPack = [];
        foreach ($this->fontsMap as $key => $font) {
            if ($font['settings']['name'] === $name) {
                foreach ($font['fonts'] as $fontWeight => $fontStyle) {
                    $fontPack[$fontWeight] = $fontStyle['normal'];
                }
                $this->cache->add('fonsUpload', [$key => $fontPack]);

                return [
                    'displayName' => $font['settings']['display_name'],
                    'fontsFile' => $fontPack,
                ];
            }
        }

        return false;
    }

    static public function getFontsFamily(): array
    {
        $fontFamily = [];
        $cache = VariableCache::getInstance();
        $fonts = $cache->get('fonts', 'settings');
        foreach ($fonts as $font) {
            if (isset($font['name']) && $font['name'] === 'primary') {
                $fontFamily['Default'] = $font['uuid'];
            } else {
                $fontFamily['kit'][$font['fontFamily']] = $font['uuid'];
            }
        }

        return $fontFamily;
    }

    static public function getFontsFamilyFromName($name): string
    {
        $fontFamily = 'google';
        $cache = VariableCache::getInstance();
        $fonts = $cache->get('fonts', 'settings');
        foreach ($fonts as $font) {
            if (isset($font['name']) && $font['name'] === $name) {
                return $font['uuid'];
            }
        }
        foreach ($fonts as $font) {
            if (isset($font['name']) && $font['name'] === 'main_text') {
                return $font['uuid'];
            }
        }

        return $fontFamily;
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

        if ($check != '') {
            if (is_array($check)) {
                foreach ($check as $look) {
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
