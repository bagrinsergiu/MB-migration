<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class EventGalleryLayout extends AbstractElement
{
    use SectionStylesAble;
    use RichTextAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySectionHead = new BrizyComponent(json_decode($this->brizyKit['head'], true));
        $brizyComponent = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($brizySectionHead->getItemWithDepth(0));
        $this->handleRichTextHead($elementContext, $this->browserPage);
        $this->handleRichTextItems($elementContext, $this->browserPage);

        $brizyComponent->getValue()->set_items(
            array_merge([$brizySectionHead], $brizyComponent->getValue()->get_items())
        );

        $elementContext = $data->instanceWithBrizyComponent($brizyComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;
    }
}