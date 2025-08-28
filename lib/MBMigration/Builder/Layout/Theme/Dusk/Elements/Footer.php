<?php

namespace MBMigration\Builder\Layout\Theme\Dusk\Elements;

use MBMigration\Builder\BrizyComponent\BrizyColumComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\FooterElement;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;

class Footer extends FooterElement
{
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();

        try {
            $brizySection->addRow([
                new BrizyColumComponent(),
                new BrizyColumComponent(),
                new BrizyColumComponent()
            ]);

            $aa= $brizySection->getItemWithDepth(0, 0, 0);

            $this->handleMbSectionItemsByOrder($data,
                $brizySection->getItemWithDepth(0, 0, 0),
                $mbSection['items'],
                0);

            $this->handleMbSectionItemsByOrder($data,
                $brizySection->getItemWithDepth(0, 0, 1),
                $mbSection['items'],
                1);

            $this->handleMbSectionItemsByOrder($data,
                $brizySection->getItemWithDepth(0, 0, 2),
                $mbSection['items'],
                2);

            $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
            $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);

            $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

            $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);


        } catch (\Exception $e) {
            return $brizySection;
        }

        return $brizySection;
    }

    public function handleMbSectionItemsByOrder(ElementContextInterface $data, BrizyComponent $brizySectionItemComponent, array $items, int $order_by): void
    {
        try {
            $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);

            foreach ($items as $item) {
                if ($item['order_by'] == $order_by) {
                    $this->handleItemMbSection($item, $elementContext);
                }
            }
        } catch (\Exception $e) {
            Logger::instance()->error($e->getMessage());
        }
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,

            "mobilePaddingType" => "grouped",
            "mobilePadding" => 20,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 20,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 20,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

}
