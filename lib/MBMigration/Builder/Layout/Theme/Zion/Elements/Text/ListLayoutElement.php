<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    use LineAble;

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 1 : 0);
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0,0);
    }

    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $brizySection;
    }

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $showHeader = $this->canShowHeader($mbSectionItem);

        if($showHeader) {
            $titleMb = $this->getByType($mbSectionItem['head'], 'title');
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem,
                $brizySection
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 1, null);
        }

        return $brizySection;
    }
}
