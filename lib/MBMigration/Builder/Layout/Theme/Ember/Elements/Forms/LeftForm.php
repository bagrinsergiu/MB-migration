<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Forms;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Forms\FormWithTextElement;

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

        // add the form here.
        return $elementContext->getBrizySection();
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

    protected function getMobileTopMarginOfTheFirstElement(): int
    {
        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();

        return (int) $dtoPageStyle['headerHeight'];
    }


    protected function getJsonFromBrizyKit()
    {
        return $this->brizyKit['left-form'];
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
