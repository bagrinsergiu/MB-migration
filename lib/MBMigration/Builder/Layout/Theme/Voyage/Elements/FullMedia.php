<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class FullMedia extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DanationsAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));

        $this->handleSectionStyles($elementContext, $this->browserPage);


        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

        // configure the image wrapper
        $brizySection->getItemValueWithDepth(0, 0)
            ->set_marginType("ungrouped")
            ->set_margin(0)
            ->set_tempMargin(0)
            ->set_marginSuffix("px")
            ->set_tempMarginSuffix("px")
            ->set_marginTop(10)
            ->set_tempMarginTop(10)
            ->set_marginTopSuffix("px")
            ->set_tempMarginTopSuffix("px")
            ->set_marginRight(0)
            ->set_tempMarginRight(0)
            ->set_marginRightSuffix("px")
            ->set_tempMarginRightSuffix("px")
            ->set_marginBottom(30)
            ->set_tempMarginBottom(30)
            ->set_marginBottomSuffix("px")
            ->set_tempMarginBottomSuffix("px")
            ->set_marginLeft(0)
            ->set_tempMarginLeft(0)
            ->set_marginLeftSuffix("px")
            ->set_tempMarginLeftSuffix("px");

        $image = $brizySection->getItemValueWithDepth(0, 0, 0);

        $image->set_width(100)->set_widthSuffix('%')
            ->set_height('')
            ->set_heightSuffix('');

        return $brizySection;
    }

}