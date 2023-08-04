<?php

namespace MBMigration\Builder\Layout\Anthem;

use MBMigration\Builder\Layout\Anthem\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Anthem\Elements\ThreeTopMediaCircle;
use MBMigration\Core\Utils;

class ElementsController
{

    /**
     * @throws \Exception
     */
    public static function getElement($elementName, array $elementData)
    {
        switch ($elementName) {
            case "gallery_layout":
                $galleryLayout = new GalleryLayout();
                return $galleryLayout->getElement($elementData);
            case "three_top_media_circle":
                $threeTopMediaCircle = new ThreeTopMediaCircle();
                return $threeTopMediaCircle->getElement($elementData);
            default:
                return false;
        }
    }
}