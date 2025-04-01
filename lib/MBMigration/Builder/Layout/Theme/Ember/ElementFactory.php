<?php

namespace MBMigration\Builder\Layout\Theme\Ember;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Events\EventFeturedLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Events\EventGalleryLayout;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Events\EventLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Forms\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Forms\LeftForm;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Forms\RightForm;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Gallery\GalleryLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Groups\SmallGroupsListElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Head;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Prayer\PrayerFormElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Sermons\LivestreamLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Sermons\MediaLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\AccordionLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\FourHorizontalText;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\FullText;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\GridLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\LeftMedia;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\RightMediaCircle;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\ListLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\RightMedia;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\TabsLayoutElement;
use MBMigration\Builder\Layout\Theme\Ember\Elements\Text\ThreeBottomMediaCircle;

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
            case 'right-media-circle':
                return new RightMediaCircle($this->blockKit['blocks']['left-media-circle'], $browserPage);

            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
            case 'three-bottom-media-circle':
                return new ThreeBottomMediaCircle($this->blockKit['blocks']['three-bottom-media-circle'], $browserPage);
            case 'four-horizontal-text':
                return new FourHorizontalText($this->blockKit['blocks']['four-horizontal-text'], $browserPage);
            case 'gallery-layout':
                return new GalleryLayoutElement($this->blockKit['blocks']['gallery-layout'], $browserPage);
            case 'livestream-layout':
                return new LivestreamLayoutElement($this->blockKit['blocks']['livestream-layout'], $browserPage);
            case 'full-width-form':
                return new FullWidthForm($this->blockKit['blocks']['form'], $browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $browserPage);
            case 'grid-layout':
                return new GridLayoutElement($this->blockKit['blocks']['grid-layout'], $browserPage);
            case 'list-layout':
                return new ListLayoutElement($this->blockKit['blocks']['list-layout'], $browserPage);

            case 'prayer-list':
            case 'prayer-form':
                return new PrayerFormElement($this->blockKit['blocks']['prayer-form'], $browserPage);

            case 'tabs-layout':
                return new TabsLayoutElement($this->blockKit['blocks']['tabs-layout'], $browserPage);
            case 'accordion-layout':
                return new AccordionLayoutElement($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'small-groups-list':
                return new SmallGroupsListElement($this->blockKit['blocks']['small-groups-list'], $browserPage);

            case 'event-tile-layout':
                return new EventFeturedLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());
            case 'event-list-layout':
            case 'event-grid-layout':
            case 'event-gallery-layout':
                return new EventGalleryLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());
            case 'event-calendar-layout':
                return new EventLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            case 'grid-media-layout':
            case 'list-media-layout':
                return new MediaLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());


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
//                return new FullMediaElement($this->blockKit['blocks']['full-media'], $browserPage);
            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































