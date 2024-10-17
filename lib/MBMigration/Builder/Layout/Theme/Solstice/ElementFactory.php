<?php

namespace MBMigration\Builder\Layout\Theme\Solstice;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Events\EventLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Sermons\MediaLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Forms\FullWidthForm;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Forms\LeftForm;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Forms\RightForm;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Gallery\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Groups\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Head;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Prayer\PrayerForm;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Prayer\PrayerList;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Sermons\LivestreamLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\FourHorizontalText;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\FullText;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\FullMedia;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\GridLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\LeftMedia;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\ListLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\RightMedia;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\TabsLayout;
use MBMigration\Builder\Layout\Theme\Solstice\Elements\Text\ThreeBottomMediaCircle;

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

            case 'event-list-layout':
            case 'event-tile-layout':
            case 'event-calendar-layout':
            case 'event-gallery-layout':
                return new EventLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());


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

            case 'grid-media-layout':
            case 'list-media-layout':
                return new MediaLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            case 'full-media':
            case 'top-media':
                return new FullMedia($this->blockKit['blocks']['full-media'], $browserPage);

            default:
                throw new ElementNotFound("The Element [{$name}] was not found.");
        }
    }

}

































