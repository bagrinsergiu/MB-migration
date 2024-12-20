<?php

namespace MBMigration\Builder\Fonts;

use Exception;
use MBMigration\Core\Logger;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\Utils\builderUtils;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
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
            if(isset($projectData['fonts']['upload']['data']))
            foreach ($projectData['fonts']['upload']['data'] as $projectFont) {
                if ($projectFont['family'] == $KitFonts['displayName']) {
                    foreach ($KitFonts['fontsFile'] as $type => $font) {
                        if (!in_array($type, $projectFont['files'])){
                            continue 2;
                        }
                    }
                    $uploaded = true;
                    $fontStyles[$index]['uuid'] = $projectFont['id'];
                    break;
                }
            }

            if($uploaded) {
                continue;
            }

            $fontToAttach = $this->BrizyApi->createFonts(
                $fontName,
                $this->projectId,
                $KitFonts['fontsFile'],
                $KitFonts['displayName']
            );

            $this->cache->add('responseDataAddedNewFont', [$fontName => $fontToAttach]);

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

    /**
     * @throws Exception
     */
    public function getFontsMap(): void
    {
        $this->layoutName = 'FontsController';
        if (Config::$urlJsonKits && Config::$devMode === false) {
            $createUrlForFileFontsMap = Config::$urlJsonKits.'/fonts/fonts.json';
            $this->fontsMap = $this->loadJsonFromUrl($createUrlForFileFontsMap);
        } else {
            $file = __DIR__.'/fonts.json';
            $fileContent = file_get_contents($file);
            $this->fontsMap = json_decode($fileContent, true);
        }
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
