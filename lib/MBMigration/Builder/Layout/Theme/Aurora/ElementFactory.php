<?php

namespace MBMigration\Builder\Layout\Theme\Aurora;

use MBMigration\Builder\Layout\Common\MBElementFactoryInterface;
use MBMigration\Builder\Layout\Theme\Aurora\Elements\Footer;
use MBMigration\Builder\Layout\Theme\Aurora\Elements\Head;

class ElementFactory implements MBElementFactoryInterface
{
    private $blockKitPath = "";

    public function loadBrizyBlockKit($kitPath)
    {
        $this->blockKitPath = $kitPath;
    }

    public function getElement($name, $mbElementData)
    {
        switch ($name) {
            case "footer":
                $element = new Footer($jsonKitElements);
                return $element->getElement();
            case "head":
                $element = new Head($jsonKitElements);
            default:
                return false;
        }
    }

}