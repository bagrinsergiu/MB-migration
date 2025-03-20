<?php

namespace MBMigration\Builder\Fonts;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\RootListFontFamilyExtractor;
use MBMigration\Builder\Utils\FontUtils;
use MBMigration\Builder\Utils\UrlUtils;
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
    private $projectId;

    protected string $layoutName;
    private VariableCache $cache;

    private array $googeFontsMap;

    /**
     * @throws Exception
     */
    public function __construct($projectId)
    {
        $this->BrizyApi = new BrizyAPI();
        $this->loadFontsMap();
        $this->projectId = $projectId;
        $this->cache = VariableCache::getInstance();
        $this->layoutName = 'FontsController';
    }

    /**
     * @throws Exception
     */
    public function loadFontsMap(): void
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

    public function getFontsFromProjectData(): array
    {
        $containerID = $this->cache->get('projectId_Brizy');
        try{
            return json_decode(
                $this->BrizyApi->getProjectContainer($containerID, true)['data'],
                true)['fonts'] ?? [];
        } catch (Exception|GuzzleException $e){

            return [];
        }
    }

    public static function getProject_Data()
    {
        $cache = VariableCache::getInstance();
        $containerID = $cache->get('projectId_Brizy');
        try {
            $BrizyApi = new BrizyAPI();

            return json_decode(
                $BrizyApi->getProjectContainer($containerID, true)['data'],
                true) ?? [];
        } catch (Exception|GuzzleException $e){

            return [];
        }
    }

    public function refreshFontInProject(BrowserPageInterface $browserPage): void
    {
//        $projectData = $this->getProjectData();
//        $RootListFontFamilyExtractor = new RootListFontFamilyExtractor($browserPage);
//        $this->upLoadCustomFonts($RootListFontFamilyExtractor);
    }

    /**
     * @throws Exception
     */
    public function upLoadCustomFonts(RootListFontFamilyExtractor $RootListFontFamilyExtractor): void
    {
        $allUpLoadFonts = $this->getAllUpLoadFonts();
        $fontsList = $RootListFontFamilyExtractor->getAllFontName();

        $fontListToAdd = array_unique(array_diff($fontsList, $allUpLoadFonts));

        foreach ($fontListToAdd as $font) {
                $fontId = $RootListFontFamilyExtractor->getFontIdByName($font);

                if (!$this->upLoadMBFonts($font)) {
                    $this->upLoadGoogleFonts($font, $fontId);
                }
        }
    }

    public function upLoadMBFonts($fontName): bool
    {
        foreach ($this->fontsMap as $font){
            if($font === $fontName){
                try{
                   $this->upLoadFont($fontName);
                   return true;
                } catch (Exception|GuzzleException $e){
                    return false;
                }
            }
        }
        return false;
    }

    public function upLoadGoogleFonts($fontName, $fontFamilyId): void
    {
        try{
            if(!$this->cache->get($fontName, 'responseDataAddedNewFont')) {
                $KitFonts = $this->getGoogleFontBnName($fontName);
                if ($KitFonts) {
                    Logger::instance()->info("Create FontName $fontName, type font: google");

                    $fontNameLower = FontUtils::convertFontFamily($KitFonts['family']);

                    $this->cache->add('responseDataAddedNewFont', [$fontNameLower => $KitFonts]);

                    $this->BrizyApi->addFontAndUpdateProject($KitFonts, 'google');

                    self::addFontInMigration($fontName, $fontName, $fontFamilyId, 'google');
                }
            }
        } catch (Exception|GuzzleException $e){
            return;
        }
    }

    /**
     * @throws Exception
     */
    public function getAllUpLoadFonts(): array
    {
        $projectDefaultFonts = $this->getDefaultFontsFromProject();
        $projectUploadedFonts = $this->getUploadedFontsFromProject();

        return array_unique(array_merge($projectDefaultFonts, $projectUploadedFonts));
    }

    /**
     * @throws Exception
     */
    public function getDefaultFontsFromProject(): array
    {
        $result = [];

        $projectData = $this->getProjectData();

        foreach ($projectData['fonts']['config']['data'] as $projectFont) {
            $fontFamily = FontUtils::convertFontFamily($projectFont['family']);
            if (!in_array($fontFamily, $result)) {
                $result[] = $fontFamily;
            }
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function getUploadedFontsFromProject(): array
    {
        $result = [];

        $projectData = $this->getProjectData();

        foreach ($projectData['fonts']['upload']['data'] as $projectFont) {
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
    public function upLoadFont($fontName, $fontFamily = null): string
    {
        $fontName = $fontFamily ?? $fontName;

        if(!$presentFont = $this->cache->get($fontName, 'responseDataAddedNewFont')) {
            $KitFonts = $this->getPathFont($fontName);
            if ($KitFonts) {
                Logger::instance()->info("Create FontName $fontName, type font: upload");

                $responseDataAddedNewFont = $this->BrizyApi->createFonts(
                    $fontName,
                    $this->projectId,
                    $KitFonts['fontsFile'],
                    $KitFonts['displayName']
                );

                $this->cache->add('responseDataAddedNewFont', [$fontName => $responseDataAddedNewFont]);

                $fontFamilyId = $this->BrizyApi->addFontAndUpdateProject($responseDataAddedNewFont);

                $fontFamilyConverted = FontUtils::transliterateFontFamily($fontName ?? $KitFonts['displayName']);

                self::addFontInMigration($fontName, $fontFamilyId, $fontFamilyConverted);

                return $fontFamilyId;
            }

            return 'lato';
        } else {

            return $presentFont['uid'];
        }
    }

    /**
     * @throws GuzzleException
     */
    public function addFontsToBrizyProject(array $fontStyles): array
    {
        $result = [];
        $brzFontsProjectList = [];
        $mbFontsProjectList = [];

        $containerID = $this->cache->get('projectId_Brizy');
        $projectFullData = $this->BrizyApi->getProjectContainer($containerID, true);
        $projectData = json_decode($projectFullData['data'], true);

        foreach ($fontStyles as $mbFont) {
            $mbFontsProjectList[] = $mbFont['fontName'];
        }

        foreach ($projectData['fonts']['upload']['data'] as $brzFont) {
            $brzFontsProjectList[] = FontUtils::transliterateFontFamily($brzFont['family']);
        }

        $fontListToAdd = array_unique(array_diff($mbFontsProjectList, $brzFontsProjectList));

        foreach ($fontListToAdd as $fontName) {
            if($KitFonts = $this->getPathFont($fontName)){

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

                foreach ($fontStyles as &$mbFontNeedID) {
                    if($mbFontNeedID['fontName'] === $fontName){
                        $mbFontNeedID['uuid'] = $fontToAttach['uid'];
                    }
                }
            }
        }

        $projectFullData['data'] = json_encode($projectData);
        $this->BrizyApi->updateProject($projectFullData);

        return $fontStyles;
    }

    static public function addFontInMigration($fontName, $FontFamilyId, $FontFamily, $uploadType = null)
    {
        $cache = VariableCache::getInstance();
        $settings = $cache->get('settings');

        $settings['fonts'][] = [
            'fontName' => $fontName,
            'fontFamily' => $FontFamily,
            'uploadType' => $uploadType ?? 'upload',
            'uuid' => $FontFamilyId,
        ];

        $cache->set('settings', $settings);
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

    public function getFontsForSnippet(): array
    {
        $list = [];

        $listFonts = $this->getFontsFromProjectData();

        foreach ($listFonts['config']['data'] as $font) {
            $name = FontUtils::transliterateFontFamily($font['family']);
            $list[$name] = [
                'name' => $name,
                'type' => 'google',
            ];
        }

        foreach ($listFonts['google']['data'] as $font) {
            $name = FontUtils::transliterateFontFamily($font['family']);
            $list[$name] = [
                'name' => $font['id'],
                'type' => 'google',
            ];
        }

        foreach ($listFonts['upload']['data'] as $font) {
            $name = FontUtils::transliterateFontFamily($font['family']);
            $list[$name] = [
                'name' => $font['id'],
                'type' => 'upload',
            ];
        }

        return $list;
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
                $fontFamily['Default'] = [
                    'name' => $font['uuid'],
                    'type' => $font['uploadType'] ?? 'upload',
                ];
            } else {
                $fontFamily['kit'][$font['fontFamily']] = [
                   'name' => $font['uuid'],
                   'type' => $font['uploadType'] ?? 'upload',

                ];
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
