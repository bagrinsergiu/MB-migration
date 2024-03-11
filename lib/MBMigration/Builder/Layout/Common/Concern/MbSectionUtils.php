<?php

namespace MBMigration\Builder\Layout\Common\Concern;

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

    protected function transliterateFontFamily($fontName): string
    {
        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $fontName);

        $inputString = str_replace(',', '', $inputString);

        return strtolower($inputString);
    }

    protected function fisrtFontFamily($fontName): string
    {
        $inputString = explode(',',  $fontName);

        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $inputString[0]);

        return strtolower($inputString);
    }
}