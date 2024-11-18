<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms;

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
        $mbSection = $elementContext->getMbSection();
        $formId = $mbSection['settings']['sections']['form']['form_id'] ?? '';

        $form = new BrizyComponent(json_decode($this->brizyKit['form-wrapper'], true));
        $form->getItemWithDepth(0)->getValue()->set_form($formId);

        $brizyComponent = $elementContext->getBrizySection();
        $brizyComponent->getValue()->set_items([$form]);

        return $brizyComponent;
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 80;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
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
            "paddingTop" => 80,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 80,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }
}
