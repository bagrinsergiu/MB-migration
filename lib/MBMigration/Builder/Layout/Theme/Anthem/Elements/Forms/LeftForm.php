<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Forms;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Forms\FormWithTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class LeftForm extends FormWithTextElement
{

    protected function getFormContainerElement(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0, 0, 0);
    }

    protected function handleForm(
        ElementContextInterface $elementContext,
        BrowserPageInterface $browserPage
    ): BrizyComponent {

        $mbSection = $elementContext->getMbSection();
        $formId = $mbSection['settings']['sections']['form']['form_id'];

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
}
