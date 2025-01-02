<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Forms;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Forms\FormElement;

class FullWidthForm extends FormElement
{
    protected function getJsonFromBrizyKit()
    {
        return $this->brizyKit['full-width'];
    }

    protected function getFormContainerElement(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 95;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
    }

}
