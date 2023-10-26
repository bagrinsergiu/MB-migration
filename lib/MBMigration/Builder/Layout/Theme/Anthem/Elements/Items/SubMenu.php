<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\Items;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Element;
use MBMigration\Builder\Utils\TextTools;
use MBMigration\Builder\VariableCache;
use MBMigration\Parser\JS;

class SubMenu extends Element
{
    /**
     * @var VariableCache|mixed
     */
    private $cache;
    /**
     * @var mixed
     */
    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    public function getElement(array $elementsData = [])
    {
        $textTransform = '';

        $menuStyle = $this->cache->get('menuStyles');

        $menuStyle = $this->getElementsWithPrefix($menuStyle, 'subMenu');

        $objBlock = new ItemBuilder($this->jsonDecode['blocks']['sub-menu']['main']);
        $item = $this->jsonDecode['blocks']['sub-menu']['item'];
        $objItem = new ItemBuilder();

        $objBlock->item()->setting('bgColorHex', $menuStyle['bgColorHex']);

        $settingsTextTransform = $this->cache->get('fonts', 'settings');
        foreach ($settingsTextTransform as $itemTextTransform){
            if ($itemTextTransform['name'] === 'sub_nav') {
                $textTransform = $itemTextTransform['text_transform'];
            }
        }


        foreach ($elementsData as $element){

            $objItem->newItem($item);

            $objItem->setting('text', TextTools::transformText($element['name'], $textTransform));
            $objItem->setting('linkExternal', $element['slug']);

            foreach ($menuStyle as $key => $value) {
                $objItem->setting($key, $value);
            }

            $objBlock->item()->item()->addItem($objItem->get());
        }

        return $this->replaceIdWithRandom($objBlock->get());
    }

    protected function SubMenuGeneralParameters($sectionData, &$options)
    {
        $SubMenuGeneralParameters = [
            'position'       => $sectionData['settings']['pagePosition'],
            'sectionID'      => $sectionData['sectionId'],
            'fontsFamily'    => $this->getFontsFamily(),
            'currentPageURL' => $this->cache->get('CurrentPageURL')
        ];

        $options = array_merge($options, $SubMenuGeneralParameters);
    }

    private function getElementsWithPrefix($array, $prefix) {
        $result = array();

        foreach ($array as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $result[$this->processArray($key)] = $value;
            }
        }

        return $result;
    }

    private function processArray($key): string
    {
        $newKey = str_replace('subMenu', '', $key);
        return lcfirst($newKey);
    }
}