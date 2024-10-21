<?php

namespace MBMigration\Builder\Layout\Theme\Bloom;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\AccordionLayoutElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Events\EventLayoutElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Forms\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Forms\LeftForm;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Forms\PrayerFormElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Forms\RightForm;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\FullMediaElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\GalleryLayoutElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\GridLayoutElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Head;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\RightMediaCircle;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\ListLayoutElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\Semons\MediaLayoutElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\SmallGroupsListElement;
use MBMigration\Builder\Layout\Theme\Bloom\Elements\TabsLayoutElement;
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
            case 'right-media-circle':
                return new RightMediaCircle($this->blockKit['blocks']['right-media'], $browserPage);
            case 'two-right-media-circle':
                return new TwoRightMediaCircle($this->blockKit['blocks']['two-right-media-circle'], $browserPage);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $browserPage);
            case 'right-media':
                return new RightMedia($this->blockKit['blocks']['right-media'], $browserPage);
            case 'gallery-layout':
                return new GalleryLayoutElement($this->blockKit['blocks']['gallery-layout'], $browserPage);
            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
            case 'small-groups-list':
                return new SmallGroupsListElement($this->blockKit['blocks']['small-groups-list'], $browserPage);
            case 'tabs-layout':
                return new TabsLayoutElement($this->blockKit['blocks']['tabs-layout'], $browserPage);
            case 'grid-layout':
                return new GridLayoutElement($this->blockKit['blocks']['grid-layout'], $browserPage);
            case 'list-layout':
                return new ListLayoutElement($this->blockKit['blocks']['list-layout'], $browserPage);
            case 'prayer-form':
                return new PrayerFormElement($this->blockKit['blocks']['prayer-form'], $browserPage);
            case 'full-width-form':
                return new FullWidthForm($this->blockKit['blocks']['form'], $browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $browserPage);
            case 'accordion-layout':
                return new AccordionLayoutElement($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'event-list-layout':
//                return new EventListLayout($this->blockKit['blocks']['event-list-layout'], $browserPage, $this->getQueryBuilder());
            case 'event-tile-layout':
//                return new EventTileLayout($this->blockKit['blocks']['event-tile-layout'], $browserPage);
            case 'event-gallery-layout':
//                return new EventGalleryLayout($this->blockKit['blocks']['event-gallery-layout'], $browserPage);
            case 'event-calendar-layout':
                return new EventLayoutElement($this->blockKit['blocks']['event-calendar-layout'], $browserPage, $this->getQueryBuilder());

//            case 'prayer-list':
//                return new PrayerListElement($this->blockKit['blocks']['prayer-list'], $browserPage);
//            case 'livestream-layout':
//                return new LivestreamLayoutElement($this->blockKit['blocks']['livestream-layout'], $browserPage);
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
                return new MediaLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            case 'full-media':
                return new FullMediaElement($this->blockKit['blocks']['full-media'], $browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}
