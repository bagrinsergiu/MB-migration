<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponentValue;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

trait MbSectionUtils
{
    protected function getItemByType($section, $itemType)
    {
        foreach ($section['items'] as $item) {
            if ($item['item_type'] == $itemType) {
                return $item;
            }
        }

        return null;
    }

    protected function getItemsByCategory($section, $category)
    {
        $items = [];
        foreach ($section['items'] as $item) {
            if ($item['category'] == $category) {
                $items[] = $item;
            }
        }

        return $items;
    }

    protected function sortItems($items)
    {
        $groupColum = array_column($items, 'group');
        $orderByColumn = array_column($items, 'order_by');

        if (count($groupColum) == 0 || count($orderByColumn) == 0) {
            return $items;
        }

        array_multisort(
            $groupColum,
            SORT_ASC,
            array_column($items, 'order_by'),
            SORT_ASC,
            $items
        );

        return $items;
    }
}