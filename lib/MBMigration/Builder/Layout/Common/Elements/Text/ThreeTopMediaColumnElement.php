<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class ThreeTopMediaColumnElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;

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

        $brizyComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($brizyComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $brizyComponent);

        $elementContext = $data->instanceWithBrizyComponent($this->getText1Component($brizySection));
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit);

        $elementContext = $data->instanceWithBrizyComponent($this->getText2Component($brizySection));
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit);

        $elementContext = $data->instanceWithBrizyComponent($this->getText3Component($brizySection));
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit);

        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}

