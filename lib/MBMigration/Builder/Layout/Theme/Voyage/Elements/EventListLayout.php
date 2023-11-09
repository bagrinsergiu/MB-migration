<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;

class EventListLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use MbSectionUtils;

    public function transformToItem(ElementDataInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySectionHead = new BrizyComponent(json_decode($this->brizyKit['head'], true));

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($brizySectionHead);
        $this->handleRichTextHead($elementContext, $this->browserPage);

        return $brizySection;
    }
}