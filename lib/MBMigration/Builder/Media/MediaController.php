<?php

namespace MBMigration\Builder\Media;

use Exception;
use MBMigration\Builder\Utils\ArrayManipulator;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;

class MediaController
{
    public static function getURLDoc($fileName): string
    {
        $cache = VariableCache::getInstance();
        $uuid = $cache->get('settings')['uuid'];
        $prefix = substr($uuid, 0, 2);

        return Config::$MBMediaStaging."/".$prefix.'/'.$uuid.'/documents/'.$fileName;
    }

    public static function is_doc($file): bool
    {
        if(!filter_var($file, FILTER_VALIDATE_URL)){
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if ($extension === 'pdf' || $extension === 'doc' || $extension === 'docx') {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public static function media(&$item, $section, $projectId, BrizyAPI $brizyApi): void
    {
        $parts = explode(".", $item['content']);
        $downloadImageURL = self::getPicturesUrl($parts[0].'@2x.'.$parts[1], $section);
        Logger::instance()->debug('Found new image', [$downloadImageURL]);
        $result = $brizyApi->createMedia($downloadImageURL, $projectId);
        if ($result) {
            if (array_key_exists('status', $result)) {
                if ($result['status'] == 201) {
                    $result = json_decode($result['body'], true);
                    $item['uploadStatus'] = true;
                    $item['imageFileName'] = $result['filename'];
                    $item['content'] = $result['name'];
                    $item['settings'] = array_merge(json_decode($result['metadata'], true), $item['settings']);
                    Logger::instance()->debug('Success upload image fileName', $result);
                } else {
                    Logger::instance()->critical('Unexpected answer: '.json_encode($result));
                }
            } else {
                Logger::instance()->critical('Bad response: '.json_encode($result));
            }
        } else {
            $item['uploadStatus'] = false;
            Logger::instance()->critical('The structure of the image is damaged');
        }
    }

    private static function getPicturesUrl($nameImage, $type): string
    {
        $cache = VariableCache::getInstance();

        $folder = [
            'gallery-layout' => '/gallery/slides/',
            'favicons' => '/favicons/',
        ];

        if (array_key_exists($type, $folder)) {
            $folderLoad = $folder[$type];
        } else {
            $folderLoad = '/site-images/';
        }

        $uuid = $cache->get('settings')['uuid'];
        $prefix = substr($uuid, 0, 2);

        return Config::$MBMediaStaging."/".$prefix.'/'.$uuid.$folderLoad.$nameImage;
    }

    /**
     * @throws Exception
     */
    public static function setFavicon($favicon, $projectId, BrizyAPI $brizyApi, QueryBuilder $QueryBuilder)
    {
        if ($favicon != null) {
            $parts = explode(".", $favicon);
            $downloadImageURL = self::getPicturesUrl($parts[0].'.'.$parts[1], 'favicons');
            $resultImageUpload = $brizyApi->createMedia($downloadImageURL, $projectId);
            if ($resultImageUpload) {
                if (array_key_exists('status', $resultImageUpload)) {
                    if ($resultImageUpload['status'] == 201) {
                        $result = json_decode($resultImageUpload['body'], true);
                        $QueryBuilder->updateFaviconMetafield($result['name']);
                    }
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function uploadPicturesFromSections(array $sectionsItems, $projectId, BrizyAPI $brizyApi): array
    {
        Logger::instance()->debug('Start uploading section images');
        foreach ($sectionsItems as &$section) {
            if (ArrayManipulator::checkArrayPath($section, 'settings/sections/background/photo')) {
                if ($section['settings']['sections']['background']['photo'] != null) {
                    $result = $brizyApi->createMedia(
                        $section['settings']['sections']['background']['photo'],
                        $projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        $section['settings']['sections']['background']['photo'] = $result['name'];
                        $section['settings']['sections']['background']['filename'] = $result['filename'];
                        Logger::instance()->debug('Success upload image', [$result]);
                    }
                } else {
                    self::checkItemForMediaFiles($section['items'], $section['typeSection'], $projectId, $brizyApi);

                }
            }

            if (ArrayManipulator::checkArrayPath($section, 'settings/background/photo')) {
                if ($section['settings']['background']['photo'] != null) {
                    $result = $brizyApi->createMedia(
                        $section['settings']['background']['photo'],
                        $projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        $section['settings']['background']['photo'] = $result['name'];
                        $section['settings']['background']['filename'] = $result['filename'];
                        Logger::instance()->info('Success upload image fileName', $result);
                    }
                } else {
                    self::checkItemForMediaFiles($section['items'], $section['typeSection'], $projectId, $brizyApi);
                }
            }
            if (!empty($section['items'])) {
                self::checkItemForMediaFiles($section['items'], $section['typeSection'], $projectId, $brizyApi);
            }

            if (!empty($section['slide'])) {
                self::checkItemForMediaFiles($section['slide'], $section['typeSection'], $projectId, $brizyApi);
            }
        }

        return $sectionsItems;
    }

    /**
     * @throws Exception
     */
    public static function checkItemForMediaFiles(&$section, $typeSection, $projectId, BrizyAPI $brizyApi): void
    {
        foreach ($section as &$item) {
            if ($item['category'] == 'photo' && $item['content'] != '') {
                MediaController::media($item, $typeSection, $projectId, $brizyApi);
            }
            if ($item['category'] == 'list') {
                foreach ($item['items'] as &$piece) {
                    if ($piece['category'] == 'photo' && $piece['content'] != '') {
                        MediaController::media($piece, $typeSection, $projectId, $brizyApi);
                    }
                }
            }
        }
    }
}
