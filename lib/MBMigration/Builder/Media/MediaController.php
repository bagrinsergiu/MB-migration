<?php

namespace MBMigration\Builder\Media;

use MBMigration\Builder\Utils\ArrayManipulator;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;

class MediaController
{
    public function uploadPicturesFromSections(array $sectionsItems, BrizyAPI $brizyApi, $projectId, $uuid): array
    {
        Utils::log('Start upload image', 1, 'uploadPicturesFromSections');
        foreach ($sectionsItems as &$section) {
            if (ArrayManipulator::checkArrayPath($section, 'settings/sections/background/photo')) {
                if ($section['settings']['sections']['background']['photo'] != null) {
                    Utils::log('Found background image', 1, 'uploadPicturesFromSections');
                    $result = $brizyApi->createMedia(
                        $section['settings']['sections']['background']['photo'],
                        $projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        Utils::log('Upload image response: '.json_encode($result), 1, 'uploadPicturesFromSections');
                        $section['settings']['sections']['background']['photo'] = $result['name'];
                        $section['settings']['sections']['background']['filename'] = $result['filename'];
                        Utils::log(
                            'Success upload image fileName: '.$result['filename'].' srcName: '.$result['name'],
                            1,
                            'uploadPicturesFromSections'
                        );
                    }
                } else {
                    $this->checkItemForMediaFiles($section['items'], $section['typeSection'], $brizyApi, $projectId, $uuid);
                }
            }

            if (ArrayManipulator::checkArrayPath($section, 'settings/background/photo')) {
                if ($section['settings']['background']['photo'] != null) {
                    Utils::log('Found background image', 1, 'uploadPicturesFromSections');
                    $result = $brizyApi->createMedia(
                        $section['settings']['background']['photo'],
                        $projectId
                    );
                    if ($result) {
                        $result = json_decode($result['body'], true);
                        Utils::log('Upload image response: '.json_encode($result), 1, 'uploadPicturesFromSections');
                        $section['settings']['background']['photo'] = $result['name'];
                        $section['settings']['background']['filename'] = $result['filename'];
                        Utils::log(
                            'Success upload image fileName: '.$result['filename'].' srcName: '.$result['name'],
                            1,
                            'uploadPicturesFromSections'
                        );
                    }
                } else {
                    $this->checkItemForMediaFiles($section['items'], $section['typeSection'], $brizyApi, $projectId, $uuid);
                }
            }
            $this->checkItemForMediaFiles($section['items'], $section['typeSection'], $brizyApi, $projectId, $uuid);
        }

        return $sectionsItems;
    }

    private function checkItemForMediaFiles(&$section, $typeSection, $brizyApi, $projectId, $uuid): void
    {
        foreach ($section as &$item) {
            if ($item['category'] == 'photo' && $item['content'] != '') {
                $this->media($item, $typeSection, $brizyApi, $projectId, $uuid);
            }
            if ($item['category'] == 'list') {
                foreach ($item['item'] as &$piece) {
                    if ($piece['category'] == 'photo' && $piece['content'] != '') {
                        $this->media($piece, $typeSection, $brizyApi, $projectId, $uuid);
                    }
                }
            }
        }
    }

    private function media(&$item, $section, BrizyAPI $brizyApi, $projectId, $uuid): void
    {
        Utils::log('Found new image', 1, 'media');
        $downloadImageURL = $this->getPicturesUrl($item['content'], $section, $uuid);
        $result = $brizyApi->createMedia($downloadImageURL, $projectId);
        if ($result) {
            if (array_key_exists('status', $result)) {
                if ($result['status'] == 201) {
                    $result = json_decode($result['body'], true);
                    Utils::log('Upload image response: '.json_encode($result), 1, 'media');
                    $item['uploadStatus'] = true;
                    $item['imageFileName'] = $result['filename'];
                    $item['content'] = $result['name'];
                    Utils::log(
                        'Success upload image fileName: '.$result['filename'].' srcName: '.$result['name'],
                        1,
                        'media'
                    );
                } else {
                    Utils::log('Unexpected answer: '.json_encode($result), 3, 'media');
                }
            } else {
                Utils::log('Bad response: '.json_encode($result), 3, 'media');
            }
        } else {
            $item['uploadStatus'] = false;
            Utils::log('The structure of the image is damaged', 3, 'media');
        }
    }

    private function getPicturesUrl($nameImage, $type, $uuid): string
    {
        $folder = ['gallery-layout' => '/gallery/slides/'];

        if (array_key_exists($type, $folder)) {
            $folderLoad = $folder[$type];
        } else {
            $folderLoad = '/site-images/';
        }

//        $uuid = $this->cache->get('settings')['uuid'];
        $prefix = substr($uuid, 0, 2);
        $url = Config::$MBMediaStaging."/".$prefix.'/'.$uuid.$folderLoad.$nameImage;
        Utils::log('Created url pictures: '.$url.' Type folder '.$type, 1, 'getPicturesUrl');

        return $url;
    }

}