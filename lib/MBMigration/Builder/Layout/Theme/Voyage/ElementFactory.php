<?php

namespace MBMigration\Builder\Layout\Theme\Voyage;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Sermons\MediaLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Events\EventLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms\LeftForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms\RightForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Gallery\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Groups\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Head;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Prayer\PrayerForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Prayer\PrayerList;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Sermons\LivestreamLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\FullMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\FullText;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\GridLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\LeftMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\LeftMediaOverlap;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\ListLayout;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\RightMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\RightMediaOverlap;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\TabsLayout;

class ElementFactory  extends AbstractThemeElementFactory
{
    public function getElement($name, BrowserPageInterface $browserPage): ElementInterface
    {
        switch ($name) {
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $browserPage, $this->brizyApiClient);
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $browserPage, $this->brizyApiClient, $this->fontsController);
            case 'accordion-layout':
                return new AccordionLayout($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'tabs-layout':
                return new TabsLayout($this->blockKit['blocks']['tabs-layout'], $browserPage);
            case 'prayer-form':
                return new PrayerForm($this->blockKit['blocks']['prayer-form'], $browserPage);
            case 'prayer-list':
                return new PrayerList($this->blockKit['blocks']['prayer-list'], $browserPage);
            case 'livestream-layout':
                return new LivestreamLayout($this->blockKit['blocks']['livestream-layout'], $browserPage);
            case 'small-groups-list':
                return new SmallGroupsList($this->blockKit['blocks']['small-groups-list'], $browserPage);
            case 'full-width-form':
                return new FullWidthForm($this->blockKit['blocks']['form'], $browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $browserPage);

            case 'event-list-layout':
            case 'event-grid-layout':
            case 'event-gallery-layout':
            case 'event-tile-layout':
            case 'event-calendar-layout':
                return new EventLayout($this->blockKit['blocks']['event-calendar-layout'], $browserPage, $this->getQueryBuilder());

            case 'gallery-layout':
                return new GalleryLayout($this->blockKit['blocks']['gallery-layout'], $browserPage);
            case 'right-media-overlap':
                return new RightMediaOverlap($this->blockKit['blocks']['right-media-overlap'], $browserPage);
            case 'left-media-overlap':
                return new LeftMediaOverlap($this->blockKit['blocks']['left-media-overlap'], $browserPage);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $browserPage);
            case 'right-media':
                return new RightMedia($this->blockKit['blocks']['right-media'], $browserPage);

            case 'grid-media-layout':
            case 'list-media-layout':
                return new MediaLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            case 'list-layout':
                return new ListLayout($this->blockKit['blocks']['list-layout'], $browserPage);
            case 'grid-layout':
                return new GridLayout($this->blockKit['blocks']['grid-layout'], $browserPage);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
            case 'full-media':
                return new FullMedia($this->blockKit['blocks']['full-media'], $browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































