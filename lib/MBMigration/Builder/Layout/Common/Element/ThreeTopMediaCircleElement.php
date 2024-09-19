<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class ThreeTopMediaCircleElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DanationsAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $imageTargets = [
            $this->getImage1Component($brizySection),
            $this->getImage2Component($brizySection),
            $this->getImage3Component($brizySection),
        ];

        $k = 0;
        foreach ((array)$mbSection['items'] as $mbSectionItem) {
            switch ($mbSectionItem['category']) {
                case 'photo':
                    // add the photo items on the right side of the block
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbSectionItem,
                        $imageTargets[$k++]
                    );
                    $this->handleRichTextItem(
                        $elementContext,
                        $this->browserPage
                    );
                    break;
            }
        }
        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($this->getTextComponent($brizySection));
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

        return $brizySection;
    }
}
