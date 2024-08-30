<?php

namespace MBMigration\Builder\Layout\Theme\Ember;

use MBMigration\Browser\BrowserPage;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Ember\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\EventLayout;
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
    public function getElement($name, BrowserPageInterface $browserPage): ElementInterface
    {
        switch ($name) {
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $browserPage,$this->brizyApiClient, $this->fontsController);
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $browserPage,$this->brizyApiClient);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $browserPage);
            case 'right-media':
                return new RightMedia($this->blockKit['blocks']['right-media'], $browserPage);
            case 'left-media-circle':
                return new LeftMediaCircle($this->blockKit['blocks']['left-media-circle'], $browserPage);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
            case 'three-bottom-media-circle':
                return new ThreeBottomMediaCircle($this->blockKit['blocks']['three-bottom-media-circle'], $browserPage);
            case 'four-horizontal-text':
                return new FourHorizontalText($this->blockKit['blocks']['four-horizontal-text'], $browserPage);
           case 'gallery-layout':
                return new GalleryLayout($this->blockKit['blocks']['gallery-layout'], $browserPage);
            case 'livestream-layout':
                return new LivestreamLayout($this->blockKit['blocks']['livestream-layout'], $browserPage);
            case 'grid-media-layout':
                return new GridMediaLayout($this->blockKit['blocks']['abstract-media-layout'], $browserPage);
            case 'list-media-layout':
                return new ListMediaLayout($this->blockKit['blocks']['abstract-media-layout'], $browserPage);
            case 'event-list-layout':
                return new EventListLayout($this->blockKit['blocks']['event-list-layout'], $browserPage, $this->getQueryBuilder());
            case 'event-tile-layout':
                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout'], $browserPage, $this->getQueryBuilder());
            case 'event-calendar-layout':
//                return new EventCalendarLayout($this->blockKit['blocks']['event-calendar-layout'], $browserPage);
            case 'event-gallery-layout':
                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout'], $browserPage);
            case 'full-width-form':
                return new FullWidthForm($this->blockKit['blocks']['form'], $browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $browserPage);
            case 'grid-layout':
                return new GridLayout($this->blockKit['blocks']['grid-layout'], $browserPage);
            case 'list-layout':
                return new ListLayout($this->blockKit['blocks']['list-layout'], $browserPage);
            case 'prayer-form':
                return new PrayerForm($this->blockKit['blocks']['prayer-form'], $browserPage);
            case 'prayer-list':
                return new PrayerList($this->blockKit['blocks']['prayer-list'], $browserPage);
            case 'tabs-layout':
                return new TabsLayout($this->blockKit['blocks']['tabs-layout'], $browserPage);
            case 'accordion-layout':
                return new AccordionLayout($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'small-groups-list':
                return new SmallGroupsList($this->blockKit['blocks']['small-groups-list'], $browserPage);


//            case 'two-right-media-circle':
//                return new TwoRightMediaCircle($this->blockKit['blocks']['two-right-media-circle'], $browserPage);


//            case 'full-width-form':
//                return new FullWidthForm($this->blockKit['blocks']['form'], $browserPage);
//            case 'left-form-with-text':
//                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
//            case 'right-form-with-text':
//                return new RightForm($this->blockKit['blocks']['form'], $browserPage);

//            case 'grid-media-layout':
//                return new GridMediaLayout($this->blockKit['blocks']['grid-media-layout'], $browserPage, $this->getQueryBuilder());
//            case 'list-media-layout':
//                return new ListMediaLayout($this->blockKit['blocks']['list-media-layout'], $browserPage, $this->getQueryBuilder());
//            case 'full-media':
//                return new FullMedia($this->blockKit['blocks']['full-media'], $browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































