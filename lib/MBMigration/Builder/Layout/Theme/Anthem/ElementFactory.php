<?php

namespace MBMigration\Builder\Layout\Theme\Anthem;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\EventLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\EventGalleryLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\EventListLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\EventTileLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Form;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\FullMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\GridLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\ThreeTopMediaCircle;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Head;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\LeftForm;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\ListLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\MediaLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\PrayerForm;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\RightForm;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\TabsLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\TwoRightMediaCircle;

class ElementFactory extends AbstractThemeElementFactory
{
    public function getElement($name, BrowserPageInterface $browserPage) :ElementInterface
    {
        switch ($name) {
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $browserPage,$this->brizyApiClient);
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $browserPage,$this->brizyApiClient, $this->fontsController);

            case 'left-media-circle':
                return new LeftMediaCircle($this->blockKit['blocks']['left-media-circle'], $browserPage);
            case 'two-right-media-circle':
                return new TwoRightMediaCircle($this->blockKit['blocks']['two-right-media-circle'], $browserPage);
            case 'three-top-media-circle':
                return new ThreeTopMediaCircle($this->blockKit['blocks']['three-top-media-circle'], $browserPage);

            case 'full-media':
                return new FullMedia($this->blockKit['blocks']['full-media'], $browserPage);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $browserPage);
            case 'right-media':
                return new RightMedia($this->blockKit['blocks']['right-media'], $browserPage);
            case 'gallery-layout':
                return new GalleryLayout($this->blockKit['blocks']['gallery-layout'], $browserPage);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
            case 'small-groups-list':
                return new SmallGroupsList($this->blockKit['blocks']['small-groups-list'], $browserPage);
            case 'tabs-layout':
                return new TabsLayout($this->blockKit['blocks']['tabs-layout'], $browserPage);
            case 'grid-layout':
                return new GridLayout($this->blockKit['blocks']['grid-layout'], $browserPage);
            case 'list-layout':
                return new ListLayout($this->blockKit['blocks']['list-layout'], $browserPage);
            case 'accordion-layout':
                return new AccordionLayout($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'event-list-layout':
            case 'event-tile-layout':
            case 'event-gallery-layout':
            case 'event-calendar-layout':
                return new EventLayout($this->blockKit['blocks']['event-calendar-layout'], $browserPage, $this->getQueryBuilder());

//            case 'prayer-list':
//                return new PrayerList($this->blockKit['blocks']['prayer-list'], $browserPage);
//            case 'livestream-layout':
//                return new LivestreamLayout($this->blockKit['blocks']['livestream-layout'], $browserPage);
//            case 'small-groups-grid':
//                return new SmallGroupsGrid($this->blockKit['blocks']['small-groups-grid'], $browserPage);
//            case 'right-form-with-text':
//                return new RightFormWithText($this->blockKit['blocks']['right-form-with-text'], $browserPage);
//            case 'left-form-with-text':
//                return new LeftFormWithText($this->blockKit['blocks']['left-form-with-text'], $browserPage);
//            case 'full-width-form':
//                return new FullWidthForm($this->blockKit['blocks']['full-width-form'], $browserPage);

            case 'full-width-form':
                return new Form($this->blockKit['blocks']['form'], $browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $browserPage);
            case 'prayer-form':
                return new PrayerForm($this->blockKit['blocks']['prayer-form'], $browserPage);

            case 'grid-media-layout':
            case 'list-media-layout':
                return new MediaLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            default:
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
        }
    }

}
