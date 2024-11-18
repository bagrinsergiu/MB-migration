<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\Forms;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Forms\FormWithTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class LeftForm extends FormWithTextElement
{

    protected function getFormContainerElement(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    /**
     * @throws BadJsonProvided
     */
    protected function handleForm(
        ElementContextInterface $elementContext,
        BrowserPageInterface $browserPage
    ): BrizyComponent {
        $mbSection = $elementContext->getMbSection();
        $formId = $mbSection['settings']['sections']['form']['form_id'] ?? '';

        $form = new BrizyComponent(json_decode($this->brizyKit['form-wrapper'], true));
        $form->getItemWithDepth(0)->getValue()->set_form($formId);

        $brizyComponent = $elementContext->getBrizySection();
        $brizyComponent->getValue()->set_items([$form]);

        return $brizyComponent;
    }

    protected function getTextContainerElement(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 1);
    }

    protected function handleText(
        ElementContextInterface $elementContext,
        BrowserPageInterface $browserPage
    ): BrizyComponent {
        return $this->handleRichTextItems($elementContext, $this->browserPage);
    }

    protected function getJsonFromBrizyKit()
    {
        return $this->brizyKit['left-form'];
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
