<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Gallery;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem)
    {
        return $brizySectionItem;
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

//    protected function getMobileTopMarginOfTheFirstElement(): int
//    {
//        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();
//
//        return (int) $dtoPageStyle['headerHeight'];
//    }

}
