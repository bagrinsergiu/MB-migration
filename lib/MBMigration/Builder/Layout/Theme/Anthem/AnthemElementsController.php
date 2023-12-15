<?php

namespace MBMigration\Builder\Layout\Theme\Anthem;

use Exception;
use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Events\EventCalendarLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Events\EventGalleryLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Events\EventGridLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Events\EventListLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Forms\Form;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Sermons\GridMediaLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements\Sermons\ListMediaLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\FourHorizontalText;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\FullMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\FullText;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\GridLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Head;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Items\SubMenu;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\LeftMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\LeftMediaCircle;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\ListLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\LivestreamLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\RightMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\RightMediaCircle;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\SmallGroupsList;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\TabsLayout;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\ThreeHorizontalText;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\ThreeTopMediaCircle;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\TopMedia;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\TwoHorizontalText;


class AnthemElementsController
{

    /**
     * @throws \DOMException
     */
    public static function getElement($elementName, $jsonKitElements, BrowserPage $browserPage, array $elementData = [])
    {
        $element = self::switchGlobalElements($elementName, $jsonKitElements, $elementData, $browserPage);
        if ($element) {
            return $element;
        }

        $element = self::switchElements($elementName, $jsonKitElements, $elementData);
        if ($element) {
            return $element;
        }

        $element = self::switchItems($elementName, $jsonKitElements, $elementData);
        if ($element) {
            return $element;
        }

        $element = self::switchDynamicElements($elementName, $jsonKitElements, $elementData);
        if ($element) {
            return $element;
        }

        return false;
    }

    /**
     * @throws \DOMException
     */
    private static function switchGlobalElements($elementName, $jsonKitElements, $elementData, BrowserPage $browserPage)
    {
        switch ($elementName) {
            case "footer":
                $element = new Footer($jsonKitElements);

                return $element->getElement();
            case "head":
                $element = new Head($jsonKitElements, $browserPage);

                return $element->getElement($elementData);
            default:
                return false;
        }
    }

    /**
     * @throws \DOMException
     * @throws Exception
     */
    private static function switchElements($elementName, $jsonKitElements, $elementData)
    {
        switch ($elementName) {
            case "top_media":
                $element = new TopMedia($jsonKitElements);

                return $element->getElement($elementData);
            case "full_text":
                $element = new FullText($jsonKitElements);

                return $element->getElement($elementData);
            case "full_media":
                $element = new FullMedia($jsonKitElements);

                return $element->getElement($elementData);
            case "left_media":
                $element = new LeftMedia($jsonKitElements);

                return $element->getElement($elementData);
            case "right_media":
                $element = new RightMedia($jsonKitElements);

                return $element->getElement($elementData);
            case "grid_layout":
                $element = new GridLayout($jsonKitElements);

                return $element->getElement($elementData);
            case "list_layout":
                $element = new ListLayout($jsonKitElements);

                return $element->getElement($elementData);
            case "tabs_layout":
                $element = new TabsLayout($jsonKitElements);

                return $element->getElement($elementData);
            case "gallery_layout":
                $element = new GalleryLayout($jsonKitElements);

                return $element->getElement($elementData);
            case "accordion_layout":
                $element = new AccordionLayout($jsonKitElements);

                return $element->getElement($elementData);
            case "grid_media_layout":
                $element = new GridMediaLayout($jsonKitElements);

                return $element->getElement($elementData);
            case "right_media_circle":
                $element = new RightMediaCircle($jsonKitElements);

                return $element->getElement($elementData);
            case "left_media_circle":
                $element = new LeftMediaCircle($jsonKitElements);

                return $element->getElement($elementData);
            case "two_horizontal_text":
                $element = new TwoHorizontalText($jsonKitElements);

                return $element->getElement($elementData);
            case "three_horizontal_text":
                $element = new ThreeHorizontalText($jsonKitElements);

                return $element->getElement($elementData);
            case "four_horizontal_text":
                $element = new FourHorizontalText($jsonKitElements);

                return $element->getElement($elementData);
            case "three_top_media_circle":
                $element = new ThreeTopMediaCircle($jsonKitElements);

                return $element->getElement($elementData);
            case "small_groups_list":
                $element = new SmallGroupsList($jsonKitElements);

                return $element->getElement($elementData);
            case "livestream_layout":
                $element = new LivestreamLayout($jsonKitElements);

                return $element->getElement($elementData);
            default:
                return false;
        }
    }

    /**
     * @throws \DOMException
     * @throws Exception
     */
    private static function switchDynamicElements($elementName, $jsonKitElements, $elementData)
    {
        switch ($elementName) {
            case "full_width_form":
                $element = new Form(['element_form_type' => 'full_width']);

                return $element->getElement($elementData);
            case "right_form_with_text":
                $element = new Form(['element_form_type' => 'right']);

                return $element->getElement($elementData);
            case "left_form_with_text":
                $element = new Form(['element_form_type' => 'left']);

                return $element->getElement($elementData);

            case "list_media_layout":
                $element = new ListMediaLayout();

                return $element->getElement($elementData);
            case "grid_media_layout":
                $element = new GridMediaLayout();

                return $element->getElement($elementData);

            case "event_calendar_layout":
                $element = new EventCalendarLayout();

                return $element->getElement($elementData);
            case "event_list_layout":
                $element = new EventListLayout();

                return $element->getElement($elementData);
            case "event_tile_layout":
                $element = new EventGridLayout();

                return $element->getElement($elementData);
            case "event_gallery_layout":
                $element = new EventGalleryLayout();

                return $element->getElement($elementData);
            default:
                return false;
        }
    }

    private static function switchItems($elementName, $jsonKitElements, array $elementData)
    {
        switch ($elementName) {
            case "SubMenu":
                $element = new SubMenu($jsonKitElements);
                return $element->getElement($elementData);
            case "item-image":
                $element = new ItemImage($jsonKitElements);
                return $element->getElement($elementData);
            case "item-empty":
                $element = new ItemEmpty($jsonKitElements);
                return $element->getElement($elementData);
            default:
                return false;
        }
    }
}