<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Config;

use MBMigration\Builder\Layout\Common\ElementContextInterface;

/**
 * Mobile image parameters configuration for Boulevard theme
 *
 * This class is automatically loaded by base MobilePhotoOptionsConfig class
 * by naming convention: Theme\{ThemeName}\Config\MobilePhotoOptionsConfig
 *
 * Boulevard theme uses mobileSize: 100 for mobile images
 */
class MobilePhotoOptionsConfig
{
    /**
     * Get theme-specific options for mobile images
     *
     * @param ElementContextInterface $data Element context
     * @return array Array with theme-specific values
     */
    public static function getOptions(ElementContextInterface $data): array
    {
        return [
            'mobileSizeType' => 'original',
            'mobileSize' => 100
        ];
    }
}
