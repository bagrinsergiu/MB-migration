<?php

namespace MBMigration\Builder\Layout\Theme\Voyage;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\MBElementFactoryInterface;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\EventCalendarLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\EventGalleryLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\EventListLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\EventTileLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\FullMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\GridLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\GridMediaLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Head;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\LeftFormWithText;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\LeftMediaOverlap;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\ListLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\ListMediaLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\LivestreamLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\PrayerForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\PrayerList;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\RightFormWithText;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\RightMediaOverlap;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\SmallGroupsGrid;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\TabsLayout;

class ElementFactory implements MBElementFactoryInterface
{
    /**
     * @var array
     */
    private $blockKit;
    /**
     * @var BrowserPageInterface
     */
    private $browserPage;

    static public function instance($blockKit, BrowserPageInterface $browserPage): MBElementFactoryInterface
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        return $instance = new self($blockKit, $browserPage);
    }

    protected function __construct($blockKit, BrowserPageInterface $browserPage)
    {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;
    }

    public function getElement($name): ElementInterface
    {
        switch ($name) {
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $this->browserPage);
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $this->browserPage);
//            case 'accordion-layout':
//                return new AccordionLayout($this->blockKit['blocks']['accordion-layout']);
//            case 'tabs-layout':
//                return new TabsLayout($this->blockKit['blocks']['tabs-layout']);
//            case 'prayer-list':
//                return new PrayerList($this->blockKit['blocks']['prayer-list']);
//            case 'prayer-form':
//                return new PrayerForm($this->blockKit['blocks']['prayer-form']);
//            case 'livestream-layout':
//                return new LivestreamLayout($this->blockKit['blocks']['livestream-layout']);
//            case 'small-groups-list':
//                return new SmallGroupsList($this->blockKit['blocks']['small-groups-list']);
//            case 'small-groups-grid':
//                return new SmallGroupsGrid($this->blockKit['blocks']['small-groups-grid']);
//            case 'right-form-with-text':
//                return new RightFormWithText($this->blockKit['blocks']['right-form-with-text']);
//            case 'left-form-with-text':
//                return new LeftFormWithText($this->blockKit['blocks']['left-form-with-text']);
//            case 'full-width-form':
//                return new FullWidthForm($this->blockKit['blocks']['full-width-form']);
//            case 'event-list-layout':
//                return new EventListLayout($this->blockKit['blocks']['event-list-layout']);
//            case 'event-gallery-layout':
//                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout']);
//            case 'event-tile-layout':
//                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout']);
//            case 'event-calendar-layout':
//                return new EventCalendarLayout($this->blockKit['blocks']['event-calendar-layout']);
////            case 'list-media-layout':
////                return new ListMediaLayout($this->blockKit['blocks']['list-media-layout']);
//            case 'grid-media-layout':
//                return new GridMediaLayout($this->blockKit['blocks']['grid-media-layout']);
//            case 'gallery-layout':
//                return new GalleryLayout($this->blockKit['blocks']['gallery-layout']);
//            case 'right-media-overlap':
//                return new RightMediaOverlap($this->blockKit['blocks']['right-media-overlap']);
//            case 'left-media':
//                return new LeftMedia($this->blockKit['blocks']['left-media']);
//            case 'list-layout':
//                return new ListLayout($this->blockKit['blocks']['list-layout']);
//            case 'grid-layout':
//                return new GridLayout($this->blockKit['blocks']['grid-layout']);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $this->browserPage);
////            case 'right-media':
////                return new RightMedia($this->blockKit['blocks']['right-media']);
//            case 'left-media-overlap':
//                return new LeftMediaOverlap($this->blockKit['blocks']['left-media-overlap']);
//            case 'full-media':
//                return new FullMedia($this->blockKit['blocks']['full-media']);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































