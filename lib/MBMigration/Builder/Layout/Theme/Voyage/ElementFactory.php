<?php

namespace MBMigration\Builder\Layout\Theme\Voyage;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Sermons\MediaLayoutElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Events\EventLayoutElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms\LeftForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms\RightForm;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Gallery\GalleryLayoutElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Groups\SmallGroupsListElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Head;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Prayer\PrayerFormElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Prayer\PrayerListElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Sermons\LivestreamLayoutElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\AccordionLayoutElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\FullMediaElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\FullText;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\GridLayoutElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\LeftMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\LeftMediaOverlap;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\ListLayoutElement;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\RightMedia;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\RightMediaOverlap;
use MBMigration\Builder\Layout\Theme\Voyage\Elements\Text\TabsLayoutElement;

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
                return new AccordionLayoutElement($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'tabs-layout':
                return new TabsLayoutElement($this->blockKit['blocks']['tabs-layout'], $browserPage);
            case 'prayer-form':
                return new PrayerFormElement($this->blockKit['blocks']['prayer-form'], $browserPage);
            case 'prayer-list':
                return new PrayerListElement($this->blockKit['blocks']['prayer-list'], $browserPage);
            case 'livestream-layout':
                return new LivestreamLayoutElement($this->blockKit['blocks']['livestream-layout'], $browserPage);
            case 'small-groups-list':
                return new SmallGroupsListElement($this->blockKit['blocks']['small-groups-list'], $browserPage);
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
                return new EventLayoutElement($this->blockKit['blocks']['event-calendar-layout'], $browserPage, $this->getQueryBuilder());

            case 'gallery-layout':
                return new GalleryLayoutElement($this->blockKit['blocks']['gallery-layout'], $browserPage);
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
                return new MediaLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            case 'list-layout':
                return new ListLayoutElement($this->blockKit['blocks']['list-layout'], $browserPage);
            case 'grid-layout':
                return new GridLayoutElement($this->blockKit['blocks']['grid-layout'], $browserPage);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
            case 'full-media':
                return new FullMediaElement($this->blockKit['blocks']['full-media'], $browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































