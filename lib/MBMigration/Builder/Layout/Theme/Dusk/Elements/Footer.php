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
        if (!is_array($mbSection) || empty($mbSection['items']) || !is_array($mbSection['items'])) {
            // Nothing to map; return base section as-is
            return $brizySection;
        }

        try {
            $brizySection->addRow([
                new BrizyColumComponent(),
                new BrizyColumComponent(),
                new BrizyColumComponent()
            ]);

            try{
                $this->handleMbSectionItemsByOrder($data,
                    $brizySection->getItemWithDepth(0, 0),
                    $mbSection['items'],
                    0);
            } catch (\Exception $e) {
                Logger::instance()->error($e->getMessage());
            }

            try {
                $this->handleMbSectionItemsByOrder($data,
                    $brizySection->getItemWithDepth(0, 1),
                    $mbSection['items'],
                    1);
            } catch (\Exception $e) {
                Logger::instance()->error($e->getMessage());
            }

            try {
                $this->handleMbSectionItemsByOrder($data,
                    $brizySection->getItemWithDepth(0, 2),
                    $mbSection['items'],
                    2);
                $brizySection->getItemWithDepth(0, 2)->addVerticalContentAlign('center')->applyHorizontalAlignToCloneableItemsInColumn();
            } catch(\Exception $e) {
                Logger::instance()->error($e->getMessage());
            }

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
            foreach ($items as $item) {
                if (!is_array($item)) { continue; }
                $ob = $item['order_by'] ?? null;
                if ($ob === $order_by) {
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection($item, $brizySectionItemComponent);
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
