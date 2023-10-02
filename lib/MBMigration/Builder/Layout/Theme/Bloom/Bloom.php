<?php

namespace MBMigration\Builder\Layout\Theme\Bloom;

use DOMDocument;
use Exception;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\ElementsController;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Bloom extends Layout
{
    /**
     * @var mixed
     */
    private $jsonDecode;
    /**
     * @var VariableCache
     */
    private $cache;
    /**
     * @var string
     */
    private $layoutName;

    /**
     * @throws \DOMException
     * @throws Exception
     */
    public function __construct(VariableCache $cache)
    {
        $this->layoutName = 'Bloom';

        $this->cache = $cache;

        Utils::log('Connected!', 4, $this->layoutName . ' Builder');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        if($menuList['create'] === false) {
            $headElement = ElementsController::getElement('head', $this->jsonDecode, $menuList);
            if ($headElement) {
                Utils::log('Success create MENU', 1, $this->layoutName . "] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, $this->layoutName . "] [__construct");
                throw new Exception('Failed create MENU');
            }
        }

        ElementsController::getElement('footer', $this->jsonDecode);
    }

    /**
     * @throws Exception
     */
    public function callMethod($methodName, $params = [], $marker = '')
    {
        $elementName = $this->replaceInName($methodName);

        if (method_exists($this, $elementName)) {
            Utils::log('Call Element ' . $elementName , 1, $this->layoutName . "] [callMethod");
            $result = call_user_func_array(array($this, $elementName), [$params]);
            $this->cache->set('callMethodResult', $result);
        } else {
            $result = ElementsController::getElement($elementName, $this->jsonDecode, $params);
            if(!$result){
                Utils::log('Element ' . $elementName . ' does not exist. Page: ' . $marker, 2, $this->layoutName . "] [callMethod");
            }
        }
        return $result;
    }

}