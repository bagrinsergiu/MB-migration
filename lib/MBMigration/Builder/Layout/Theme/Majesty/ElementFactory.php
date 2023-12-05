<?php

namespace MBMigration\Builder\Layout\Theme\Majesty;

use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\EventCalendarLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\EventGridLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\EventListLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\FullMedia;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\ThreeHorizontalText;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\GridLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\GridMediaLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Head;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\LeftMediaOverlap;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\ListLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\ListMediaLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\PrayerForm;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\RightMediaOverlap;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\TabsLayout;

class ElementFactory  extends AbstractThemeElementFactory
{
    public function getElement($name): ElementInterface
    {
        switch ($name) {
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $this->browserPage);
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $this->browserPage);
//            case 'accordion-layout':
//                return new AccordionLayout($this->blockKit['blocks']['accordion-layout'], $this->browserPage);
//            case 'tabs-layout':
//                return new TabsLayout($this->blockKit['blocks']['tabs-layout'], $this->browserPage);
//            case 'prayer-list':
//                return new PrayerList($this->blockKit['blocks']['prayer-list'], $this->browserPage);
//            case 'prayer-form':
//                return new PrayerForm($this->blockKit['blocks']['prayer-form'], $this->browserPage);
//            case 'livestream-layout':
//                return new LivestreamLayout($this->blockKit['blocks']['livestream-layout'], $this->browserPage);
//            case 'small-groups-list':
//                return new SmallGroupsList($this->blockKit['blocks']['small-groups-list'], $this->browserPage);
//            case 'small-groups-grid':
//                return new SmallGroupsGrid($this->blockKit['blocks']['small-groups-grid'], $this->browserPage);
//            case 'right-form-with-text':
//                return new RightFormWithText($this->blockKit['blocks']['right-form-with-text'], $this->browserPage);
//            case 'left-form-with-text':
//                return new LeftFormWithText($this->blockKit['blocks']['left-form-with-text'], $this->browserPage);
//            case 'full-width-form':
//                return new FullWidthForm($this->blockKit['blocks']['full-width-form'], $this->browserPage);
//            case 'event-list-layout':
//                return new EventListLayout($this->blockKit['blocks']['event-list-layout'], $this->browserPage, $this->getQueryBuilder());
//            case 'event-grid-layout':
//                return new EventGridLayout($this->blockKit['blocks']['event-grid-layout'], $this->browserPage, $this->getQueryBuilder());
//            case 'event-gallery-layout':
//                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout'], $this->browserPage);
//            case 'event-tile-layout':
//                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout'], $this->browserPage);
//            case 'event-calendar-layout':
//                return new EventCalendarLayout($this->blockKit['blocks']['event-calendar-layout'], $this->browserPage);
            case 'gallery-layout':
                return new GalleryLayout($this->blockKit['blocks']['gallery-layout'], $this->browserPage);
            case 'three-horizontal-text':
                return new ThreeHorizontalText($this->blockKit['blocks']['three-horizontal-text'], $this->browserPage);
//            case 'right-media-overlap':
//                return new RightMediaOverlap($this->blockKit['blocks']['right-media-overlap'], $this->browserPage);
//            case 'left-media-overlap':
//                return new LeftMediaOverlap($this->blockKit['blocks']['left-media-overlap'], $this->browserPage);
//            case 'left-media':
//                return new LeftMedia($this->blockKit['blocks']['left-media'], $this->browserPage);
//            case 'right-media':
//                return new RightMedia($this->blockKit['blocks']['right-media'], $this->browserPage);
//            case 'grid-media-layout':
//                return new GridMediaLayout($this->blockKit['blocks']['grid-media-layout'], $this->browserPage, $this->getQueryBuilder());
//            case 'list-media-layout':
//                return new ListMediaLayout($this->blockKit['blocks']['list-media-layout'], $this->browserPage, $this->getQueryBuilder());
//            case 'list-layout':
//                return new ListLayout($this->blockKit['blocks']['list-layout'], $this->browserPage);
//            case 'grid-layout':
//                return new GridLayout($this->blockKit['blocks']['grid-layout'], $this->browserPage);
//            case 'full-text':
//                return new FullText($this->blockKit['blocks']['full-text'], $this->browserPage);
//            case 'full-media':
//                return new FullMedia($this->blockKit['blocks']['full-media'], $this->browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































