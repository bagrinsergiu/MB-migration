<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Section;
class SectionElement extends Section
{

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = $data->getBrizySection();
        $MbSection = $data->getMbSection();

        $elementContext = $data->instanceWithBrizyComponentAndMBSection($MbSection[0], $brizySection->getItemWithDepth(1));

        $sectionStyles = $this->getSectionListStyle($elementContext, $this->browserPage);
        $options = ['heightType' => $this->getHeightTypeHandleSectionStyles()];

        $this->handleSectionBackground(
            $elementContext->getBrizySection(),
            $elementContext->getMbSection(),
            $sectionStyles,
            $options
        );

        return $brizySection;
    }

}
