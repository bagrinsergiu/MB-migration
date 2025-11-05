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

    protected function getItemByTypeFromArray(array $items, $itemType)
    {
        foreach ($items as $item) {
            if ($item['item_type'] == $itemType) {
                return $item;
            }
        }

        return null;
    }

    protected function getByType($section, $itemType)
    {
        foreach ($section as $item) {
            if ($item['item_type'] == $itemType) {
                return $item;
            }
        }

        return null;
    }

    protected function groupingByGroupItems(&$section)
    {
        $items = [];
        foreach ($section['items'] as $item) {
                $items[$item['group']][] = $item;
        }

        ksort($items);

        $section['items'] = $items;
    }

    protected function getItemsByCategory($section, $category): array
    {
        $items = [];
        foreach ($section['items'] as $item) {
            if ($item['category'] == $category) {
                $items[] = $item;
            }
        }

        return $this->sortItems($items);
    }

    protected function getItemsByGroup($section, int $group): array
    {
        $items = [];
        foreach ($section['items'] as $item) {
            if ($item['group'] == $group) {
                $items[] = $item;
            }
        }

        return $this->sortItems($items);
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
    protected function groupItems($items)
    {
        $groups = [];
        foreach ($items as $item) {
            $groups[$item['group']][] = $item;
        }
        return $groups;
    }

    protected function transliterateFontFamily($fontName): string
    {
        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $fontName);

        $inputString = str_replace(',', '', $inputString);

        return strtolower($inputString);
    }

    protected function firstFontFamily($fontName): string
    {
        $inputString = explode(',',  $fontName);

        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $inputString[0]);

        return strtolower($inputString);
    }
}
