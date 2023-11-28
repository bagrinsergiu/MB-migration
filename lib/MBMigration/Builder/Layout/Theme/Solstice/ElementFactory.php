<?php

namespace MBMigration\Builder\Layout\Theme\Solstice;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\MBElementFactoryInterface;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\EventCalendarLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\EventGalleryLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\EventListLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\EventTileLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\FullMedia;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\GridLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\GridMediaLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Head;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\LeftFormWithText;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\LeftMediaOverlap;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\ListLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\ListMediaLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\ThreeTopMedia;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\LivestreamLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\PrayerForm;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\PrayerList;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\RightFormWithText;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\RightMediaOverlap;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\SmallGroupsGrid;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\TabsLayout;

class ElementFactory extends AbstractThemeElementFactory
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
//                return new EventListLayout($this->blockKit['blocks']['event-list-layout'], $this->browserPage);
//            case 'event-gallery-layout':
//                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout'], $this->browserPage);
//            case 'event-tile-layout':
//                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout'], $this->browserPage);
//            case 'event-calendar-layout':
//                return new EventCalendarLayout($this->blockKit['blocks']['event-calendar-layout'], $this->browserPage);
            case 'list-media-layout':
                return new ListMediaLayout($this->blockKit['blocks']['list-media-layout'], $this->browserPage);
            case 'gallery-layout':
                return new GalleryLayout($this->blockKit['blocks']['gallery-layout'], $this->browserPage);
            case 'right-media-overlap':
                return new RightMediaOverlap($this->blockKit['blocks']['right-media-overlap'], $this->browserPage);
            case 'left-media-overlap':
                return new LeftMediaOverlap($this->blockKit['blocks']['left-media-overlap'], $this->browserPage);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $this->browserPage);
            case 'right-media':
                return new RightMedia($this->blockKit['blocks']['right-media'], $this->browserPage);
            case 'grid-media-layout':
                return new GridMediaLayout($this->blockKit['blocks']['grid-media-layout'], $this->browserPage);
            case 'three-top-media':
                return new ThreeTopMedia($this->blockKit['blocks']['three-top-media'], $this->browserPage);
            case 'list-layout':
                return new ListLayout($this->blockKit['blocks']['list-layout'], $this->browserPage);
            case 'grid-layout':
                return new GridLayout($this->blockKit['blocks']['grid-layout'], $this->browserPage);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $this->browserPage);
            case 'full-media':
            case 'top-media':
                return new FullMedia($this->blockKit['blocks']['full-media'], $this->browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































