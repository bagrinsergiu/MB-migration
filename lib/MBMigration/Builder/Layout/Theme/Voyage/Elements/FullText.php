<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;

class FullText extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;

    public function transformToItem(ElementDataInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $brizySection->getItemValueWithDepth(0)->set_items([]);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));

        $this->handleSectionStyles($elementContext, $this->browserPage);
        $this->handleRichTextItems($elementContext, $this->browserPage);

        return $brizySection;
    }

}