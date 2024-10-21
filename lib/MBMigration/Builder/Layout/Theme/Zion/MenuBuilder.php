<?php

namespace MBMigration\Builder\Layout\Theme\Zion;

class MenuBuilder extends \MBMigration\Builder\Layout\Common\MenuBuilder
{
    const MORE_MENU_ITEM_LABEL = 'More';
    const MAIN_MENU_ITEM_MAX_COUNT = 6;

    public function transformToBrizyMenu(array $menuItems): array
    {
        // filter hidden menu items
        $menuItems = $this->removeHiddenElements($menuItems);

//        $count = count($menuItems);
//        if ($count > self::MAIN_MENU_ITEM_MAX_COUNT) {
//            $moreItem = [
//                'id' => null,
//                'slug' => "",
//                'name' => self::MORE_MENU_ITEM_LABEL,
//                'parent_id' => null,
//                'collection' => null,
//                'position' => 7,
//                'landing' => true,
//                'hidden' => false,
//                'parentSettings' => "{}",
//                'protectedPage' => false,
//                'child' => [],
//                'iconName'=>'circle-down-40',
//                'iconType'=>'outline'
//            ];
//
//            for ($i = self::MAIN_MENU_ITEM_MAX_COUNT; $i < $count; $i++) {
//                // remove child fot the more items
//                //$menuItems[$i]['child'] = [];
//                $moreItem['child'][] = $menuItems[$i];
//                unset($menuItems[$i]);
//            }
//            // add more item
//            $menuItems[] = $moreItem;
//        }

        return parent::transformToBrizyMenu(array_values($menuItems));
    }

    private function removeHiddenElements(array $items): array {
        $filteredItems = [];

        foreach ($items as $item) {
            // Проверяем, если элемент не скрыт
            if (!$item['hidden']) {
                // Если у элемента есть дочерние элементы, запускаем рекурсию
                if (!empty($item['child'])) {
                    $item['child'] = $this->removeHiddenElements($item['child']);
                }
                // Добавляем элемент в итоговый массив
                $filteredItems[] = $item;
            }
        }

        return $filteredItems;
    }

}
