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
            Logger::instance()->error('getProjectData failed: ' . $e->getMessage());
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
            Logger::instance()->error('getFontsFromProjectData failed: ' . $e->getMessage());
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

        Logger::instance()->info('Fonts detected on page', [
            'detectedCount' => count($fontsList),
            'detected' => $fontsList,
        ]);
        Logger::instance()->info('Fonts already in project (by family)', [
            'presentCount' => count($allUpLoadFonts),
            'present' => $allUpLoadFonts,
        ]);

        $fontListToAdd = array_unique(array_diff($fontsList, $allUpLoadFonts));
        Logger::instance()->info('Fonts to upload (diff)', [
            'toUploadCount' => count($fontListToAdd),
            'toUpload' => $fontListToAdd,
        ]);

        if (empty($fontListToAdd)) {
            Logger::instance()->info('No fonts require upload for this page');
        }

        foreach ($fontListToAdd as $font) {
                $fontId = $RootListFontFamilyExtractor->getFontIdByName($font);

                if (!$this->upLoadMBFonts($font)) {
                    $this->upLoadGoogleFonts($font, $fontId);
                }
                // Dynamic delays are now handled within individual upload methods
        }
    }

    public function upLoadMBFonts($fontName): bool
    {
        foreach ($this->fontsMap as $key => $font){
            if($key === $fontName){
                try{
                   Logger::instance()->info("Uploading MB-packaged font", ['font' => $fontName]);
                   $this->upLoadFont($fontName, null, 'upLoadMBFonts');
                   Logger::instance()->info("MB font upload completed", ['font' => $fontName]);
                   return true;
                } catch (Exception|GuzzleException $e){
                    Logger::instance()->error("MB font upload failed", ['font' => $fontName, 'error' => $e->getMessage()]);
                    return false;
                }
            }
        }
        return false;
    }

    public function upLoadGoogleFonts($fontName, $fontFamilyId): void
    {
        try{
            $KitFonts = $this->getGoogleFontBnName($fontName);
            if ($KitFonts) {
                $fontNameLower = FontUtils::convertFontFamily($KitFonts['family']);
                if(!$this->cache->get($fontNameLower, 'responseDataAddedNewFont')) {
                    Logger::instance()->info("Create FontName $fontNameLower, type font: google");
                    $this->cache->add('responseDataAddedNewFont', [$fontNameLower => $KitFonts]);

                    $fontId = $this->BrizyApi->addFontAndUpdateProject($KitFonts, 'google');

                    if ($fontId) {
                        // Persist only after success: uuid should be the id returned by API; fontFamily is the normalized family name
                        self::addFontInMigration($fontNameLower, $fontId, $fontNameLower, 'google');
                        Logger::instance()->info('Google font added to project', ['family' => $fontNameLower, 'fontId' => $fontId]);
                    } else {
                        Logger::instance()->warning('Google font upload returned empty fontId', ['family' => $fontNameLower]);
                    }
                } else {
                    Logger::instance()->info('Skip Google font upload (cached)', ['family' => $fontNameLower]);
                }
            } else {
                Logger::instance()->warning('Google font not found in map', ['requested' => $fontName]);
            }
        } catch (Exception|GuzzleException $e){
            Logger::instance()->error('Google font upload failed', ['font' => $fontName, 'error' => $e->getMessage()]);
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
        $projectGoogleFonts = $this->getGoogleFontsFromProject();

        $all = array_unique(array_merge($projectDefaultFonts, $projectUploadedFonts, $projectGoogleFonts));
        Logger::instance()->info('Aggregated project fonts (normalized families)', [
            'defaultCount' => count($projectDefaultFonts),
            'uploadCount' => count($projectUploadedFonts),
            'googleCount' => count($projectGoogleFonts),
            'totalCount' => count($all),
        ]);
        return $all;
    }

    /**
     * @throws Exception
     */
    public function getDefaultFontsFromProject(): array
    {
        $result = [];

        $projectData = $this->getProjectData();

        if (isset($projectData['fonts']['config']['data']) && is_array($projectData['fonts']['config']['data'])) {
            foreach ($projectData['fonts']['config']['data'] as $projectFont) {
                if (!isset($projectFont['family'])) { continue; }
                $fontFamily = FontUtils::convertFontFamily($projectFont['family']);
                if (!in_array($fontFamily, $result)) {
                    $result[] = $fontFamily;
                }
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

        if (isset($projectData['fonts']['upload']['data']) && is_array($projectData['fonts']['upload']['data'])) {
            foreach ($projectData['fonts']['upload']['data'] as $projectFont) {
                if (!isset($projectFont['family'])) { continue; }
                $fontFamily = FontUtils::convertFontFamily($projectFont['family']);
                if (!in_array($fontFamily, $result)) {
                    $result[] = $fontFamily;
                }
            }
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function getGoogleFontsFromProject(): array
    {
        $result = [];

        $projectData = $this->getProjectData();

        if (isset($projectData['fonts']['google']['data']) && is_array($projectData['fonts']['google']['data'])) {
            foreach ($projectData['fonts']['google']['data'] as $projectFont) {
                if (!isset($projectFont['family'])) { continue; }
                $fontFamily = FontUtils::convertFontFamily($projectFont['family']);
                if (!in_array($fontFamily, $result)) {
                    $result[] = $fontFamily;
                }
            }
        }

        return $result;
    }

    /**
     * Tracks font operation metrics
     * @param string $fontName Font name
     * @param float $startTime Operation start time
     * @param bool $success Operation success status
     */
    private function trackFontMetrics(string $fontName, float $startTime, bool $success): void
    {
        $duration = microtime(true) - $startTime;
        Logger::instance()->info('Font operation metrics', [
            'font' => $fontName,
            'duration' => round($duration, 3),
            'success' => $success
        ]);
    }

    /**
     * Uploads font with retry logic
     * @param string $fontName Font name to upload
     * @param int $maxRetries Maximum retry attempts
     * @return string Font ID or default font
     */
    private function uploadWithRetry(string $fontName, int $maxRetries = 3): string
    {
        $startTime = microtime(true);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $result = $this->upLoadFont($fontName, null, 'uploadWithRetry');
                $this->trackFontMetrics($fontName, $startTime, true);
                return $result;
            } catch (Exception|GuzzleException $e) {
                Logger::instance()->warning("Font upload attempt $attempt failed", [
                    'font' => $fontName,
                    'error' => $e->getMessage(),
                    'attempt' => $attempt,
                    'maxRetries' => $maxRetries
                ]);

                if ($attempt === $maxRetries) {
                    $this->trackFontMetrics($fontName, $startTime, false);
                    Logger::instance()->error('All font upload attempts failed, falling back to default', [
                        'font' => $fontName,
                        'totalAttempts' => $maxRetries
                    ]);
                    return 'lato';
                }

                // Exponential backoff
                $backoffDelay = $attempt * 2;
                Logger::instance()->info("Retrying font upload after backoff", [
                    'font' => $fontName,
                    'backoffDelay' => $backoffDelay
                ]);
                sleep($backoffDelay);
            }
        }

        return 'lato';
    }

    /**
     * Validates and manages font cache entries
     */
    private function validateFontCache(): void
    {
        $cacheKeys = ['responseDataAddedNewFont'];
        $cacheMaxAge = 3600; // 1 hour in seconds

        foreach ($cacheKeys as $cacheKey) {
            $cacheData = $this->cache->get('', $cacheKey);
            if ($cacheData && is_array($cacheData)) {
                $staleCacheCount = 0;
                foreach ($cacheData as $key => $data) {
                    if (isset($data['timestamp']) && (time() - $data['timestamp']) > $cacheMaxAge) {
                        $this->cache->remove($key, $cacheKey);
                        $staleCacheCount++;
                    }
                }

                if ($staleCacheCount > 0) {
                    Logger::instance()->info('Cleared stale font cache entries', [
                        'clearedCount' => $staleCacheCount,
                        'cacheKey' => $cacheKey
                    ]);
                }
            }
        }
    }

    /**
     * Validates font data before upload
     * @param array $fontData Font data to validate
     * @return bool True if valid, false otherwise
     */
    private function validateFontData(array $fontData): bool
    {
        $validFormats = ['woff', 'woff2', 'ttf', 'eot'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!isset($fontData['files']) || empty($fontData['files'])) {
            Logger::instance()->warning('Font data missing files', ['font' => $fontData['family'] ?? 'unknown']);
            return false;
        }

        // Handle nested structure: files -> weight -> format -> url/info
        foreach ($fontData['files'] as $weight => $weightData) {
            if (!is_array($weightData)) {
                Logger::instance()->warning('Invalid weight data structure', [
                    'weight' => $weight,
                    'font' => $fontData['family'] ?? 'unknown'
                ]);
                return false;
            }

            foreach ($weightData as $format => $fileInfo) {
                if (!in_array($format, $validFormats)) {
                    Logger::instance()->warning('Invalid font format detected', [
                        'format' => $format,
                        'weight' => $weight,
                        'font' => $fontData['family'] ?? 'unknown',
                        'validFormats' => $validFormats
                    ]);
                    return false;
                }

                // Check file size if provided
                if (is_array($fileInfo) && isset($fileInfo['size']) && $fileInfo['size'] > $maxSize) {
                    Logger::instance()->warning('Font file exceeds size limit', [
                        'size' => $fileInfo['size'],
                        'maxSize' => $maxSize,
                        'format' => $format,
                        'weight' => $weight,
                        'font' => $fontData['family'] ?? 'unknown'
                    ]);
                    return false;
                }

                // Validate URL if fileInfo is a string (URL)
                if (is_string($fileInfo) && !filter_var($fileInfo, FILTER_VALIDATE_URL)) {
                    Logger::instance()->warning('Invalid font file URL', [
                        'url' => $fileInfo,
                        'format' => $format,
                        'weight' => $weight,
                        'font' => $fontData['family'] ?? 'unknown'
                    ]);
                    return false;
                }
            }
        }

        Logger::instance()->info('Font data validation passed', ['font' => $fontData['family'] ?? 'unknown']);
        return true;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function upLoadFont($fontName, $fontFamily = null, $nameFunctionExec = ''): string
    {
        $fontName = $fontFamily ?? $fontName;

        // Validate cache before checking for existing fonts
        $this->validateFontCache();

        if(!$presentFont = $this->cache->get($fontName, 'responseDataAddedNewFont')) {
            $KitFonts = $this->getPathFont($fontName);
            if ($KitFonts) {
                Logger::instance()->info("Create FontName $fontName, type font: upload", [$nameFunctionExec]);

                $startTime = microtime(true);
                $responseDataAddedNewFont = $this->BrizyApi->createFonts(
                    $fontName,
                    $this->projectId,
                    $KitFonts['fontsFile'],
                    $KitFonts['displayName']
                );

                // Validate font data before proceeding
                if (!$this->validateFontData($responseDataAddedNewFont)) {
                    Logger::instance()->error('Font validation failed, falling back to default', ['font' => $fontName]);
                    return 'lato';
                }

                // Dynamic delay based on API response time (minimum 1s, maximum 5s)
                $responseTime = microtime(true) - $startTime;
                $dynamicDelay = max(1, min(5, ceil($responseTime * 2)));
                Logger::instance()->info('Using dynamic delay after font creation', [
                    'responseTime' => round($responseTime, 3),
                    'delay' => $dynamicDelay
                ]);
                sleep($dynamicDelay);

                $this->cache->add('responseDataAddedNewFont', [$fontName => $responseDataAddedNewFont]);

                $fontFamilyId = $this->BrizyApi->addFontAndUpdateProject($responseDataAddedNewFont);

                $fontFamilyConverted = FontUtils::transliterateFontFamily($fontName ?? $KitFonts['displayName']);

                self::addFontInMigration($fontName, $fontFamilyId, $fontFamilyConverted);
                Logger::instance()->info('Upload font added to project', ['family' => $fontFamilyConverted, 'fontId' => $fontFamilyId]);

                return $fontFamilyId;
            }

            Logger::instance()->warning('Font not found in MB packaged map, fallback to default', ['requested' => $fontName]);
            return 'lato';
        } else {
            Logger::instance()->info('Skip font creation (cached upload present)', ['family' => $fontName, 'uid' => $presentFont['uid'] ?? null]);
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
        Logger::instance()->info('MB default fonts to attach (diff vs project upload)', [
            'toAttachCount' => count($fontListToAdd),
            'toAttach' => $fontListToAdd,
        ]);

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
                } else {
                    Logger::instance()->info('Using cached created font payload for attach', ['font' => $fontName]);
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
                Logger::instance()->info('MB default font attached to project data', ['font' => $fontName, 'uid' => $fontToAttach['uid']]);
            } else {
                Logger::instance()->warning('MB default font not found in packaged map', ['requested' => $fontName]);
            }
        }

        $projectFullData['data'] = json_encode($projectData);
        $this->BrizyApi->updateProject($projectFullData);
        Logger::instance()->info('Project updated after attaching default fonts', ['attachedCount' => count($fontListToAdd)]);

        return $fontStyles;
    }

    static public function addFontInMigration($fontName, $FontFamilyId, $FontFamily, $uploadType = null)
    {
        $cache = VariableCache::getInstance();
        $settings = $cache->get('settings') ?? [];

        if (!isset($settings['fonts']) || !is_array($settings['fonts'])) {
            $settings['fonts'] = [];
        }

        $newEntry = [
            'fontName' => $fontName,
            'fontFamily' => $FontFamily,
            'uploadType' => $uploadType ?? 'upload',
            'uuid' => $FontFamilyId,
        ];

        // Update existing font if same family or uuid present, otherwise append
        $updated = false;
        foreach ($settings['fonts'] as $idx => $font) {
            if ((isset($font['uuid']) && $font['uuid'] === $FontFamilyId) ||
                (isset($font['fontFamily']) && $font['fontFamily'] === $FontFamily)) {
                $settings['fonts'][$idx] = array_merge($font, $newEntry);
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            $settings['fonts'][] = $newEntry;
        }

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

        if (isset($listFonts['config']['data']) && is_array($listFonts['config']['data'])) {
            foreach ($listFonts['config']['data'] as $font) {
                if (!isset($font['family'])) { continue; }
                $name = FontUtils::transliterateFontFamily($font['family']);
                $list[$name] = [
                    'name' => $name,
                    'type' => 'google',
                ];
            }
        }

        if (isset($listFonts['google']['data']) && is_array($listFonts['google']['data'])) {
            foreach ($listFonts['google']['data'] as $font) {
                if (!isset($font['family'])) { continue; }
                $name = FontUtils::transliterateFontFamily($font['family']);
                $list[$name] = [
                    'name' => $font['id'] ?? $name,
                    'type' => 'google',
                ];
            }
        }

        if (isset($listFonts['upload']['data']) && is_array($listFonts['upload']['data'])) {
            foreach ($listFonts['upload']['data'] as $font) {
                if (!isset($font['family'])) { continue; }
                $name = FontUtils::transliterateFontFamily($font['family']);
                $list[$name] = [
                    'name' => $font['id'] ?? ($font['uid'] ?? $name),
                    'type' => 'upload',
                ];
            }
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
        $fontFamily = [
            'Default' => [
                'name' => null,
                'type' => null,
            ],
            'kit' => []
        ];
        $cache = VariableCache::getInstance();
        $fonts = $cache->get('fonts', 'settings');
        if (!is_array($fonts)) {
            return $fontFamily;
        }
        foreach ($fonts as $font) {
            if (isset($font['name']) && $font['name'] === 'primary') {
                $fontFamily['Default'] = [
                    'name' => $font['uuid'] ?? null,
                    'type' => $font['uploadType'] ?? 'upload',
                ];
            } else if (isset($font['fontFamily'])) {
                $key = $font['fontFamily'];
                $fontFamily['kit'][$key] = [
                   'name' => $font['uuid'] ?? null,
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
