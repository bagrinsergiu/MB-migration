<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard;

class MenuBuilder extends \MBMigration\Builder\Layout\Common\MenuBuilder
{
    const MORE_MENU_ITEM_LABEL = 'More';
    const MAIN_MENU_ITEM_MAX_COUNT = 6;

    public function transformToBrizyMenu(array $menuItems): array
    {
        // #region agent log
        $logFile = '/home/sg/projects/MB-migration/.cursor/debug.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logData = [
            'location' => 'Boulevard/MenuBuilder.php:10',
            'message' => 'Boulevard transformToBrizyMenu started',
            'data' => [
                'input_count' => count($menuItems),
                'items_with_collection' => array_sum(array_map(function($item) {
                    return !empty($item['collection']) ? 1 : 0;
                }, $menuItems)),
                'items_without_collection' => array_sum(array_map(function($item) {
                    return empty($item['collection']) ? 1 : 0;
                }, $menuItems))
            ],
            'timestamp' => time() * 1000,
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'C'
        ];
        @file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND | LOCK_EX);
        // #endregion

        // filter hidden menu items
        $menuItems = $this->removeHiddenElements($menuItems);

        // #region agent log
        $logFile = '/home/sg/projects/MB-migration/.cursor/debug.log';
        $logData2 = [
            'location' => 'Boulevard/MenuBuilder.php:17',
            'message' => 'After removeHiddenElements',
            'data' => [
                'filtered_count' => count($menuItems),
                'items_with_collection' => array_sum(array_map(function($item) {
                    return !empty($item['collection']) ? 1 : 0;
                }, $menuItems))
            ],
            'timestamp' => time() * 1000,
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'C'
        ];
        @file_put_contents($logFile, json_encode($logData2) . "\n", FILE_APPEND | LOCK_EX);
        // #endregion

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

        $result = parent::transformToBrizyMenu(array_values($menuItems));
        
        // #region agent log
        $logFile = '/home/sg/projects/MB-migration/.cursor/debug.log';
        $logData3 = [
            'location' => 'Boulevard/MenuBuilder.php:60',
            'message' => 'After parent::transformToBrizyMenu',
            'data' => [
                'output_count' => count($result),
                'items_with_id' => array_sum(array_map(function($item) {
                    return !empty($item['id']) ? 1 : 0;
                }, $result)),
                'items_without_id' => array_sum(array_map(function($item) {
                    return empty($item['id']) ? 1 : 0;
                }, $result))
            ],
            'timestamp' => time() * 1000,
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'C'
        ];
        @file_put_contents($logFile, json_encode($logData3) . "\n", FILE_APPEND | LOCK_EX);
        // #endregion

        return $result;
    }

}
