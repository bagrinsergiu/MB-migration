<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class FormElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->getJsonFromBrizyKit(), true));
        $brizySection->getValue()->set_marginTop(0);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getFormContainerElement($brizySection));
        $this->handleForm($elementContext, $this->browserPage);

        return $brizySection;
    }

    abstract protected function getJsonFromBrizyKit();

    abstract protected function getFormContainerElement(BrizyComponent $brizyComponent): BrizyComponent;

    protected function handleForm(ElementContextInterface $elementContext, BrowserPageInterface $browserPage): BrizyComponent {
         // add the form here.
        $mbSection = $elementContext->getMbSection();
        $formId = $mbSection['settings']['sections']['form']['form_id'];

        $form = new BrizyComponent(json_decode($this->brizyKit['form-wrapper'], true));
        $form->getItemWithDepth(0)->getValue()->set_form($formId);

        $brizyComponent = $elementContext->getBrizySection();
        $brizyComponent->getValue()->set_items([$form]);

        return $brizyComponent;
    }
}