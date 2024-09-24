<?php

namespace MBMigration\Builder\Layout\Common\Element\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class EventTileLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use BrizyQueryBuilderAware;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $eventsComponent = new BrizyComponent(json_decode($this->brizyKit['events'], true));

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getTextContainerComponent($brizySection));
        $this->handleRichTextHeadFromItems($elementContext, $this->browserPage);

        $this->getSectionItemComponent($brizySection)->getValue()->add_items([$eventsComponent]);

        return $brizySection;
    }

    abstract protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent;
}
