<?php

namespace MBMigration\Builder\Layout\Common\Elements\Forms;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class FormWithTextElement extends FormElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);

        $elementContext = $data->instanceWithBrizyComponent($this->getTextContainerElement($brizySection));
        $this->handleText($elementContext, $this->browserPage);

        return $brizySection;
    }

    abstract protected function getTextContainerElement(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function handleText(ElementContextInterface $elementContext, BrowserPageInterface $browserPage): BrizyComponent;
}
