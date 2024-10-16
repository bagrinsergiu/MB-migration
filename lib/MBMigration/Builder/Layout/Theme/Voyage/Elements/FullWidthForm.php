<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Element\Forms\FormElement;
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

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 50,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 50,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
