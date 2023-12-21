<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class FormWithTextElement extends FormElement
{
    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = parent::transformToItem($data);

        $elementContext = $data->instanceWithBrizyComponent($this->getTextContainerElement($brizySection));
        $this->handleText($elementContext, $this->browserPage);

        return $brizySection;
    }

    abstract protected function getTextContainerElement(BrizyComponent $brizyComponent): BrizyComponent;

    abstract protected function handleText(ElementContextInterface $elementContext, BrowserPageInterface $browserPage): BrizyComponent;
}