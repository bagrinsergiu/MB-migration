<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\Element\FormElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

class FullWidthForm extends FormElement
{
    protected function getJsonFromBrizyKit()
    {
        return $this->brizyKit['full-width'];
    }

    protected function getFormContainerElement(BrizyComponent $brizyComponent): BrizyComponent {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function handleForm(ElementContextInterface $elementContext, BrowserPageInterface $browserPage): BrizyComponent {

        // add the form here.
        return $elementContext->getBrizySection();
    }
}