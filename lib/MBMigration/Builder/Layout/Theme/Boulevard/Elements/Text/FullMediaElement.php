<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullMediaElementElement;

class FullMediaElement extends FullMediaElementElement
{

    use LineAble;
    use ShadowAble;

    protected function internalTransformToItem(ElementContextInterface $data ): BrizyComponent
    {
        $brizySection = parent::internalTransformToItem($data);
        $mbSection = $data->getMbSection();

        $brizySection->getValue()
            ->set_mobileMarginType('ungrouped')
            ->set_mobileMarginTop(-10)
            ->set_mobileMarginBottom(-10);

        $showHeader = $this->canShowHeader($mbSection);
        if($showHeader) {
            $titleMb = $this->getByType($mbSection['items'], 'title');
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSection,
                $brizySection->getItemWithDepth(0)
            );

            $this->handleLine(
                $elementContext,
                $this->browserPage,
                $titleMb['id'],
                null,
                [
                    "borderWidth"=> 1,
                    "width"=> 100,
                    "widthSuffix"=> "%"
                ],
                1,
                ''
            );
        }

        return $brizySection;
    }
    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
       return $brizySection->getItemWithDepth(0);
    }

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }
}
