<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;

class ThreeHorizontalTextElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {

        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $this->groupingByGroupItems($mbSection);

        $brizyComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($brizyComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        $this->setTopPaddingOfTheFirstElement($data, $brizyComponent);

        foreach ($mbSection['items'] as $groupItems) {
            $brizyItemColumn = new BrizyComponent(json_decode($this->globalBrizyKit['column'], true));

            foreach ($groupItems as $item) {
                $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                    $item,
                    $brizyItemColumn
                );
                $this->handleRichTextItem($elementContext, $this->browserPage);
            }

            $brizySection->getItemWithDepth(0, 0)->getValue()->add_items([$brizyItemColumn]);
        }

        return $brizySection;
    }
}
