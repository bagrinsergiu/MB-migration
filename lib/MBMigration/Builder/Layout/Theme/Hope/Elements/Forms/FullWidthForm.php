<?php

namespace MBMigration\Builder\Layout\Theme\Hope\Elements\Forms;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Forms\FormElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

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

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 200;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
