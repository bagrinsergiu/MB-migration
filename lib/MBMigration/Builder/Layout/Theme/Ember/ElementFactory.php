<?php

namespace MBMigration\Builder\Layout\Theme\Ember;

use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Ember\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\EventCalendarLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\EventGalleryLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\EventTileLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\EventListLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Ember\Elements\FourHorizontalText;
use MBMigration\Builder\Layout\Theme\Ember\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Ember\Elements\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Ember\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\GridLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\GridMediaLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Head;
use MBMigration\Builder\Layout\Theme\Ember\Elements\LeftForm;
use MBMigration\Builder\Layout\Theme\Ember\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Ember\Elements\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Ember\Elements\ListLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\ListMediaLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\PrayerForm;
use MBMigration\Builder\Layout\Theme\Ember\Elements\PrayerList;
use MBMigration\Builder\Layout\Theme\Ember\Elements\RightForm;
use MBMigration\Builder\Layout\Theme\Ember\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Ember\Elements\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Ember\Elements\TabsLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\ThreeBottomMediaCircle;
use MBMigration\Builder\Layout\Theme\Ember\Elements\LivestreamLayout;

class ElementFactory extends AbstractThemeElementFactory
{
    public function getElement($name): ElementInterface
    {
        switch ($name) {
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $this->browserPage,$this->brizyApiClient, $this->fontsController);
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $this->browserPage,$this->brizyApiClient);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $this->browserPage);
            case 'right-media':
                return new RightMedia($this->blockKit['blocks']['right-media'], $this->browserPage);
            case 'left-media-circle':
                return new LeftMediaCircle($this->blockKit['blocks']['left-media-circle'], $this->browserPage);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $this->browserPage);
            case 'three-bottom-media-circle':
                return new ThreeBottomMediaCircle($this->blockKit['blocks']['three-bottom-media-circle'], $this->browserPage);
            case 'four-horizontal-text':
                return new FourHorizontalText($this->blockKit['blocks']['four-horizontal-text'], $this->browserPage);
           case 'gallery-layout':
                return new GalleryLayout($this->blockKit['blocks']['gallery-layout'], $this->browserPage);
            case 'livestream-layout':
                return new LivestreamLayout($this->blockKit['blocks']['livestream-layout'], $this->browserPage);
            case 'grid-media-layout':
                return new GridMediaLayout($this->blockKit['blocks']['abstract-media-layout'], $this->browserPage);
            case 'list-media-layout':
                return new ListMediaLayout($this->blockKit['blocks']['abstract-media-layout'], $this->browserPage);
            case 'event-list-layout':
                return new EventListLayout($this->blockKit['blocks']['event-list-layout'], $this->browserPage, $this->getQueryBuilder());
            case 'event-tile-layout':
                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout'], $this->browserPage, $this->getQueryBuilder());
            case 'event-calendar-layout':
                return new EventCalendarLayout($this->blockKit['blocks']['event-calendar-layout'], $this->browserPage);
            case 'event-gallery-layout':
                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout'], $this->browserPage);
            case 'full-width-form':
                return new FullWidthForm($this->blockKit['blocks']['form'], $this->browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $this->browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $this->browserPage);
            case 'grid-layout':
                return new GridLayout($this->blockKit['blocks']['grid-layout'], $this->browserPage);
            case 'list-layout':
                return new ListLayout($this->blockKit['blocks']['list-layout'], $this->browserPage);
            case 'prayer-form':
                return new PrayerForm($this->blockKit['blocks']['prayer-form'], $this->browserPage);
            case 'prayer-list':
                return new PrayerList($this->blockKit['blocks']['prayer-list'], $this->browserPage);
            case 'tabs-layout':
                return new TabsLayout($this->blockKit['blocks']['tabs-layout'], $this->browserPage);
            case 'accordion-layout':
                return new AccordionLayout($this->blockKit['blocks']['accordion-layout'], $this->browserPage);
            case 'small-groups-list':
                return new SmallGroupsList($this->blockKit['blocks']['small-groups-list'], $this->browserPage);


//            case 'two-right-media-circle':
//                return new TwoRightMediaCircle($this->blockKit['blocks']['two-right-media-circle'], $this->browserPage);


//            case 'full-width-form':
//                return new FullWidthForm($this->blockKit['blocks']['form'], $this->browserPage);
//            case 'left-form-with-text':
//                return new LeftForm($this->blockKit['blocks']['form'], $this->browserPage);
//            case 'right-form-with-text':
//                return new RightForm($this->blockKit['blocks']['form'], $this->browserPage);

//            case 'grid-media-layout':
//                return new GridMediaLayout($this->blockKit['blocks']['grid-media-layout'], $this->browserPage, $this->getQueryBuilder());
//            case 'list-media-layout':
//                return new ListMediaLayout($this->blockKit['blocks']['list-media-layout'], $this->browserPage, $this->getQueryBuilder());
//            case 'full-media':
//                return new FullMedia($this->blockKit['blocks']['full-media'], $this->browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































