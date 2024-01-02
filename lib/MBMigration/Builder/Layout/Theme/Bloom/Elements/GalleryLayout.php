<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class GalleryLayout extends \MBMigration\Builder\Layout\Theme\Voyage\Elements\GalleryLayout
{
    protected function setSlideImage(BrizyComponent $brizySectionItem, $mbItem): BrizyComponent
    {
        $brizyComponentValue = $brizySectionItem
            ->getValue()
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_bgImageSrc($mbItem['content'])
            ->set_bgImageFileName($mbItem['imageFileName'])
            ->set_bgImageExtension($mbItem['settings']['slide']['extension']);

        if (isset($mbItem['settings']['slide']['slide_width'])) {
            $brizyComponentValue->set_width($mbItem['settings']['slide']['slide_width']);
            $brizyComponentValue->set_widthSuffix('px');
        }
        if (isset($mbItem['settings']['slide']['slide_height'])) {
            $brizyComponentValue->set_height($mbItem['settings']['slide']['slide_height']);
            $brizyComponentValue->set_heightSuffix('px');
        }

        return $brizySectionItem;
    }
}
