<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class PrayerForm extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizyPrayerForm = new BrizyComponent(json_decode($this->brizyKit['form'], true));

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);
        $this->handleRichTextHeadFromItems($elementContext, $this->browserPage);
        $brizySection->getItemWithDepth(0)->getValue()->add_items([$brizyPrayerForm->getItemWithDepth(0,0)]);

        return $brizySection;
    }

    abstract public function getFormParentComponent(BrizyComponent $brizySection): BrizyComponent;
}