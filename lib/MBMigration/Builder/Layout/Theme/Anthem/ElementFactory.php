<?php

namespace MBMigration\Builder\Layout\Theme\Anthem;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\AbstractThemeElementFactory;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Events\EventLayoutElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Events\EventFeturedLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Forms\Form;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Forms\LeftForm;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Forms\RightForm;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Gallery\GalleryLayoutElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Groups\SmallGroupsListElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Head;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Prayer\PrayerFormElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Sermons\MediaLayoutElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\AccordionLayoutElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\FullMediaElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\FullText;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\GridLayoutElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\LeftMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\ListLayoutElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\RightMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\TabsLayoutElement;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\ThreeTopMediaCircle;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\TwoHorizontalText;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Text\TwoRightMediaCircle;

class ElementFactory extends AbstractThemeElementFactory
{
    public function getElement($name, BrowserPageInterface $browserPage) :ElementInterface
    {
        switch ($name) {
            case 'footer':
                return new Footer($this->blockKit['blocks']['footer'], $browserPage,$this->brizyApiClient);
            case 'head':
                return new Head($this->blockKit['blocks']['menu'], $browserPage,$this->brizyApiClient, $this->fontsController);

            case 'full-text':
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);

            case 'left-media-circle':
                return new LeftMediaCircle($this->blockKit['blocks']['left-media-circle'], $browserPage);
            case 'right-media-circle':
                return new RightMediaCircle($this->blockKit['blocks']['left-media-circle'], $browserPage);

            case 'two-right-media-circle':
                return new TwoRightMediaCircle($this->blockKit['blocks']['two-right-media-circle'], $browserPage);
            case 'two-horizontal-text':
                return new TwoHorizontalText($this->blockKit['blocks']['two-horizontal-text'], $browserPage);
            case 'three-top-media-circle':
                return new ThreeTopMediaCircle($this->blockKit['blocks']['three-top-media-circle'], $browserPage);

            case 'full-media':
                return new FullMediaElement($this->blockKit['blocks']['full-media'], $browserPage);
            case 'left-media':
                return new LeftMedia($this->blockKit['blocks']['left-media'], $browserPage);
            case 'right-media':
                return new RightMedia($this->blockKit['blocks']['right-media'], $browserPage);

            case 'gallery-layout':
                return new GalleryLayoutElement($this->blockKit['blocks']['gallery-layout'], $browserPage);

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
                return new EventFeturedLayout($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());
            case 'event-gallery-layout':
            case 'event-calendar-layout':
                return new EventLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            case 'prayer-list':
            case 'prayer-form':
                return new PrayerFormElement($this->blockKit['blocks']['prayer-form'], $browserPage);

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

            case 'grid-media-layout':
            case 'list-media-layout':
                return new MediaLayoutElement($this->blockKit['dynamic'], $browserPage, $this->getQueryBuilder());

            default:
                return new FullText($this->blockKit['blocks']['full-text'], $browserPage);
        }
    }

}
