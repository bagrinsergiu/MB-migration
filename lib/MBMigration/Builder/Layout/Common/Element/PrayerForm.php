<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class PrayerForm extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizyPrayerForm = new BrizyComponent(json_decode($this->brizyKit['form'], true));
        $brizyComponent = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($brizyComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage);
        $this->handleRichTextHead($elementContext, $this->browserPage);
        $this->handleRichTextHeadFromItems($elementContext, $this->browserPage);

        $brizyComponent->getValue()->add_items([$brizyPrayerForm]);

        return $brizySection;
    }
}