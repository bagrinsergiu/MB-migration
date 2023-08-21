<?php

namespace MBMigration\Builder\Layout;

use Exception;
use MBMigration\Builder\Layout\Elements\Head;
use MBMigration\Builder\Layout\Elements\Footer;
use MBMigration\Builder\Layout\Elements\TopMedia;
use MBMigration\Builder\Layout\Elements\FullText;
use MBMigration\Builder\Layout\Elements\FullMedia;
use MBMigration\Builder\Layout\Elements\LeftMedia;
use MBMigration\Builder\Layout\Elements\RightMedia;
use MBMigration\Builder\Layout\Elements\TabsLayout;
use MBMigration\Builder\Layout\Elements\GridLayout;
use MBMigration\Builder\Layout\Elements\ListLayout;
use MBMigration\Builder\Layout\Elements\GalleryLayout;
use MBMigration\Builder\Layout\Elements\LeftMediaCircle;
use MBMigration\Builder\Layout\Elements\AccordionLayout;
use MBMigration\Builder\Layout\Elements\RightMediaCircle;
use MBMigration\Builder\Layout\Elements\TwoHorizontalText;
use MBMigration\Builder\Layout\Elements\FourHorizontalText;
use MBMigration\Builder\Layout\Elements\ThreeHorizontalText;
use MBMigration\Builder\Layout\Elements\ThreeTopMediaCircle;
use MBMigration\Builder\Layout\Elements\DynamicElement\SermonLayoutPlaceholder;

class ElementsController
{
    /**
     * @throws Exception
     */
    public static function getElement($elementName, $jsonKitElements, array $elementData = [])
    {
        switch ($elementName) {
            case "footer":
                $element = new Footer($jsonKitElements);
                $element->getElement();
                break;
            case "head":
                $element = new Head($jsonKitElements);
                return $element->getElement($elementData);
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
            case "grid_Layout":
                $element = new GridLayout($jsonKitElements);
                return $element->getElement($elementData);
            case "list_Layout":
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
            case "list_media_layout":
                $element = new SermonLayoutPlaceholder($jsonKitElements);
                return $element->getElement($elementData);
            default:
                return false;
        }
    }
}