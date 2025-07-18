<?php

namespace MBMigration\Builder\Layout\Theme\Majesty;


use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Events\EventLayoutElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Forms\Form;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Forms\LeftForm;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Forms\RightForm;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Gallery\GalleryLayoutElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Groups\SmallGroupsListElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Head;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Prayer\PrayerFormElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Sermons\MediaLayoutElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\AccordionLayoutElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\FullMediaElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\FullText;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\GridLayoutElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\LeftMedia;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\ListLayoutElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\RightMedia;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\TabsLayoutElement;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\ThreeTopMediaCircle;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\ThreeHorizontalText;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\ThreeTopMediaColumn;
use MBMigration\Builder\Layout\Theme\Majesty\Elements\Text\TwoRightMediaCircle;

class ElementFactory extends AbstractThemeElementFactory
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
            case 'three-top-media-circle':
                return new ThreeTopMediaCircle($this->blockKit['blocks']['three-top-media-circle'], $browserPage);
            case 'sub-gallery-layout':
                return new ThreeTopMediaColumn($this->blockKit['blocks']['three-top-media-column'], $browserPage);
            case 'full-media':
                return new FullMediaElement($this->blockKit['blocks']['full-media'], $browserPage);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $browserPage);
            case 'left-gallery':
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
            case 'accordion-layout':
                return new AccordionLayoutElement($this->blockKit['blocks']['accordion-layout'], $browserPage);
            case 'event-list-layout':
            case 'event-tile-layout':
            case 'event-gallery-layout':
            case 'event-calendar-layout':
                return new EventLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

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

            case 'full-width-form':
                return new Form($this->blockKit['blocks']['form'], $browserPage);
            case 'left-form-with-text':
                return new LeftForm($this->blockKit['blocks']['form'], $browserPage);
            case 'right-form-with-text':
                return new RightForm($this->blockKit['blocks']['form'], $browserPage);
            case 'prayer-form':
                return new PrayerFormElement($this->blockKit['blocks']['prayer-form'], $browserPage);
            case 'three-horizontal-text':
                return new ThreeHorizontalText($this->blockKit['blocks']['three-horizontal-text'], $browserPage);

            case 'grid-media-layout':
            case 'list-media-layout':
                return new MediaLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            default:
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
        }
    }

}

































