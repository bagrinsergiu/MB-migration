<?php

namespace MBMigration\Builder\Layout\Common\Config;

use MBMigration\Builder\Layout\Common\ElementContextInterface;

/**
 * Configuration for mobile image parameters
 * 
 * Supports three levels of customization (in priority order):
 * 1. For specific element (via $imageOptions in handlePhotoItem)
 * 2. For specific theme (via getThemeSpecificOptions)
 * 3. Global default values for all themes (via getGlobalDefaults)
 */
class MobilePhotoOptionsConfig
{
    /**
     * Get global default values for all themes
     * 
     * @return array Array with default values
     */
    public static function getGlobalDefaults(): array
    {
        return [
            'mobileSizeType' => 'original',
            'mobileSize' => 100
        ];
    }

    /**
     * Get theme-specific options for mobile images
     * 
     * Automatically searches for theme configuration class by naming convention:
     * - For theme "Aurora_v2" searches for: MBMigration\Builder\Layout\Theme\Aurora_v2\Config\MobilePhotoOptionsConfig
     * - If class found and has getOptions() method, uses it
     * - If not - returns empty array (global defaults will be used)
     * 
     * Themes can create their own configuration class in Theme/{ThemeName}/Config/MobilePhotoOptionsConfig.php
     * 
     * @param ElementContextInterface $data Element context
     * @return array Array with theme-specific values (can be empty)
     */
    public static function getThemeSpecificOptions(ElementContextInterface $data): array
    {
        $themeName = $data->getThemeContext()->getThemeName();
        
        // Try to find theme configuration class
        $themeConfigClass = self::getThemeConfigClassName($themeName);
        
        if (!$themeConfigClass) {
            return [];
        }
        
        // First check file existence (for PSR-0 autoloading)
        $configFilePath = self::getThemeConfigFilePath($themeName);
        if ($configFilePath && file_exists($configFilePath)) {
            // If file exists but class is not loaded yet, load it explicitly
            if (!class_exists($themeConfigClass, false)) {
                require_once $configFilePath;
            }
            
            // Use class_exists with autoloading (second parameter true)
            // This ensures class will be loaded via autoloader if it exists
            if (class_exists($themeConfigClass, true)) {
                // Check that class has getOptions method
                if (method_exists($themeConfigClass, 'getOptions')) {
                    try {
                        return $themeConfigClass::getOptions($data);
                    } catch (\Throwable $e) {
                        // If error occurred during method call, return empty array
                        // (global defaults will be used)
                        return [];
                    }
                }
            }
        } else {
            // If file not found, try via class_exists (in case autoloader knows about the class)
            if (class_exists($themeConfigClass, true)) {
                if (method_exists($themeConfigClass, 'getOptions')) {
                    try {
                        return $themeConfigClass::getOptions($data);
                    } catch (\Throwable $e) {
                        return [];
                    }
                }
            }
        }
        
        // If configuration class not found, return empty array
        // (global defaults will be used)
        return [];
    }
    
    /**
     * Get path to theme configuration file
     * 
     * @param string $themeName Theme name
     * @return string|null File path or null if couldn't determine
     */
    private static function getThemeConfigFilePath(string $themeName): ?string
    {
        // Determine base path to library
        // Use reflection to determine path to current class
        $reflection = new \ReflectionClass(self::class);
        $currentFile = $reflection->getFileName();
        
        // Current file path: .../lib/MBMigration/Builder/Layout/Common/Config/MobilePhotoOptionsConfig.php
        // Need to get: .../lib/MBMigration/Builder/Layout/Theme/{ThemeName}/Config/MobilePhotoOptionsConfig.php
        // Go up 2 levels: Config -> Common -> Layout
        $basePath = dirname($currentFile, 2); // Get .../lib/MBMigration/Builder/Layout
        $themePath = $basePath . '/Theme/' . str_replace(' ', '', $themeName) . '/Config/MobilePhotoOptionsConfig.php';
        
        return $themePath;
    }

    /**
     * Get full class name for theme configuration
     * 
     * @param string $themeName Theme name (e.g., "Aurora_v2", "Serene")
     * @return string Full class name (e.g., "MBMigration\Builder\Layout\Theme\Aurora_v2\Config\MobilePhotoOptionsConfig")
     */
    private static function getThemeConfigClassName(string $themeName): string
    {
        // Form namespace for theme configuration class
        // Example: MBMigration\Builder\Layout\Theme\Aurora_v2\Config\MobilePhotoOptionsConfig
        return sprintf(
            'MBMigration\\Builder\\Layout\\Theme\\%s\\Config\\MobilePhotoOptionsConfig',
            str_replace(' ', '', $themeName)
        );
    }

    /**
     * Get all default options (global + theme-specific)
     * 
     * @param ElementContextInterface|null $data Element context (optional, for theme detection)
     * @return array Array with default values
     */
    public static function getDefaultOptions(?ElementContextInterface $data = null): array
    {
        // Global default values for all themes
        $defaults = self::getGlobalDefaults();
        
        // If context provided, get theme-specific values
        if ($data) {
            $themeSpecificOptions = self::getThemeSpecificOptions($data);
            // Merge: theme-specific values have priority over global ones
            $defaults = array_merge($defaults, $themeSpecificOptions);
        }
        
        return $defaults;
    }
}
