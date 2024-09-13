<?php

namespace MBMigration\Builder\Layout\Theme\Bloom;

use MBMigration\Browser\BrowserPage;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\EventLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\EventGalleryLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\EventListLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\EventTileLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\FullMedia;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\GridLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\GridMediaLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Head;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\LeftForm;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\ListLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\MediaLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\PrayerForm;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\RightForm;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\TabsLayout;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\TwoRightMediaCircle;

class ElementFactory  extends AbstractThemeElementFactory
{
    public function getElement($name, BrowserPageInterface $browserPage): ElementInterface
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
            case 'prayer-form':
                return new PrayerForm($this->blockKit['blocks']['prayer-form'], $browserPage);
            case 'full-width-form':
                return new FullWidthForm($this->blockKit['blocks']['form'], $browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $browserPage);
            case 'accordion-layout':
                return new AccordionLayout($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'event-list-layout':
//                return new EventListLayout($this->blockKit['blocks']['event-list-layout'], $browserPage, $this->getQueryBuilder());
            case 'event-tile-layout':
//                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout'], $browserPage);
            case 'event-gallery-layout':
//                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout'], $browserPage);
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

            case 'grid-media-layout':
            case 'list-media-layout':
                return new MediaLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            case 'full-media':
                return new FullMedia($this->blockKit['blocks']['full-media'], $browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































