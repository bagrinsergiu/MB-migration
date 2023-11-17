<?php

namespace MBMigration\Builder\Layout\Common;

final class KitLoader
{
    private $layoutBasePath;

    public function __construct($layoutBasePath)
    {
        $this->layoutBasePath = $layoutBasePath;
    }

    public function loadKit($theme)
    {
        //$globalKit = json_decode(file_get_contents($this->getGlobalLayoutPath()), true);
        $themeKit = json_decode(file_get_contents($this->getThemeLayoutPath($theme)), true);

        return $themeKit;

        //return array_merge_recursive($globalKit, $themeKit);
    }

    /**
     * @return mixed
     */
    public function getGlobalLayoutPath()
    {
        return $this->layoutBasePath."/globalBlocksKit.json";
    }

    /**
     * @return mixed
     */
    public function getThemeLayoutPath($theme)
    {
        return $this->layoutBasePath."/Theme/{$theme}/blocksKit.json";
    }
}