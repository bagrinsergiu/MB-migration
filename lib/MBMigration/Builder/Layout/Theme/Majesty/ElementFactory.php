<?php

namespace MBMigration\Builder\Layout\Theme\Majesty;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\ThreeHorizontalText;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Head;

class ElementFactory  extends AbstractThemeElementFactory
{
    public function getElement($name, BrowserPage $browserPage): ElementInterface
    {
        switch ($name) {
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $browserPage);
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $browserPage);
//            case 'accordion-layout':
//                return new AccordionLayout($this->blockKit['blocks']['accordion-layout'], $browserPage);
//            case 'tabs-layout':
//                return new TabsLayout($this->blockKit['blocks']['tabs-layout'], $browserPage);
//            case 'prayer-list':
//                return new PrayerList($this->blockKit['blocks']['prayer-list'], $browserPage);
//            case 'prayer-form':
//                return new PrayerForm($this->blockKit['blocks']['prayer-form'], $browserPage);
//            case 'livestream-layout':
//                return new LivestreamLayout($this->blockKit['blocks']['livestream-layout'], $browserPage);
//            case 'small-groups-list':
//                return new SmallGroupsList($this->blockKit['blocks']['small-groups-list'], $browserPage);
//            case 'small-groups-grid':
//                return new SmallGroupsGrid($this->blockKit['blocks']['small-groups-grid'], $browserPage);
//            case 'right-form-with-text':
//                return new RightFormWithText($this->blockKit['blocks']['right-form-with-text'], $browserPage);
//            case 'left-form-with-text':
//                return new LeftFormWithText($this->blockKit['blocks']['left-form-with-text'], $browserPage);
//            case 'full-width-form':
//                return new FullWidthForm($this->blockKit['blocks']['full-width-form'], $browserPage);
//            case 'event-list-layout':
//                return new EventListLayout($this->blockKit['blocks']['event-list-layout'], $browserPage, $this->getQueryBuilder());
//            case 'event-grid-layout':
//                return new EventGridLayout($this->blockKit['blocks']['event-grid-layout'], $browserPage, $this->getQueryBuilder());
//            case 'event-gallery-layout':
//                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout'], $browserPage);
//            case 'event-tile-layout':
//                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout'], $browserPage);
//            case 'event-calendar-layout':
//                return new EventLayout($this->blockKit['blocks']['event-calendar-layout'], $browserPage);
            case 'gallery-layout':
                return new GalleryLayout($this->blockKit['blocks']['gallery-layout'], $browserPage);
            case 'three-horizontal-text':
                return new ThreeHorizontalText($this->blockKit['blocks']['three-horizontal-text'], $browserPage);
//            case 'right-media-overlap':
//                return new RightMediaOverlap($this->blockKit['blocks']['right-media-overlap'], $browserPage);
//            case 'left-media-overlap':
//                return new LeftMediaOverlap($this->blockKit['blocks']['left-media-overlap'], $browserPage);
//            case 'left-media':
//                return new LeftMedia($this->blockKit['blocks']['left-media'], $browserPage);
//            case 'right-media':
//                return new RightMedia($this->blockKit['blocks']['right-media'], $browserPage);
//            case 'grid-media-layout':
//                return new GridMediaLayout($this->blockKit['blocks']['grid-media-layout'], $browserPage, $this->getQueryBuilder());
//            case 'list-media-layout':
//                return new ListMediaLayout($this->blockKit['blocks']['list-media-layout'], $browserPage, $this->getQueryBuilder());
//            case 'list-layout':
//                return new ListLayout($this->blockKit['blocks']['list-layout'], $browserPage);
//            case 'grid-layout':
//                return new GridLayout($this->blockKit['blocks']['grid-layout'], $browserPage);
//            case 'full-text':
//                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
//            case 'full-media':
//                return new FullMedia($this->blockKit['blocks']['full-media'], $browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































