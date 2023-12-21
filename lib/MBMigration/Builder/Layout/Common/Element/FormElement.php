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

abstract class FormElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
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

    abstract protected function handleForm(ElementContextInterface $elementContext, BrowserPageInterface $browserPage): BrizyComponent;
}