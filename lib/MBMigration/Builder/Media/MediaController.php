<?php

namespace MBMigration\Builder\Media;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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

    public static function getPicturesUrl($nameImage, $type): string
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

    public static function validateBgImag(
        $background
    ) {
        if (empty($background)) {
            return false;
        }

        if (filter_var($background, FILTER_VALIDATE_URL)) {
            $imageUrl = $background;
        } else {
            if (strpos($background, 'background-image') !== false) {
                preg_match('/background-image:\s*url\(["\']?(.*?)["\']?\)/', $background, $matches);
            } else {
                preg_match('/url\(["\']?(.*?)["\']?\)/', $background, $matches);
            }

            $imageUrl = $matches[1] ?? null;

            if ($imageUrl) {
                $imageUrl = trim($imageUrl, "'\"");
            }
        }

        if (empty($imageUrl)) {
            return false;
        }

        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return false;
        }

        $client = new Client([
            'verify' => false,
            'timeout' => 15,
            'connect_timeout' => 5
        ]);

        try {
            $response = $client->get($imageUrl);
            if ($response->getStatusCode() !== 200) {
                return false;
            }

            $contentType = $response->getHeaderLine('Content-Type');
            $validImageTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'];
            if (!in_array(strtolower($contentType), $validImageTypes)) {
                return false;
            }

            if (strtolower($contentType) === 'image/svg+xml') {
                return $imageUrl;
            }

            $imageData = $response->getBody()->getContents();
            $tempFile = tempnam(sys_get_temp_dir(), 'img');
            file_put_contents($tempFile, $imageData);
            $imageInfo = @getimagesize($tempFile);
            unlink($tempFile);

            if ($imageInfo === false) {
                return false;
            }

            $minWidth = 20;
            $minHeight = 20;
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            if ($width < $minWidth || $height < $minHeight) {
                return false;
            }

            return $imageUrl;
        } catch (RequestException $e) {
            return false;
        } catch (\Exception $e) {
            Logger::instance()->error("Unexpected error validating image: $imageUrl - " . $e->getMessage());
            return false;
        }
    }

}

