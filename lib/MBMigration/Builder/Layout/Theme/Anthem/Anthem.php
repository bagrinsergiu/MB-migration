<?php

namespace MBMigration\Builder\Layout\Theme\Anthem;

use Exception;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Anthem extends LayoutUtils
{
    /**
     * @var mixed
     */
    protected $jsonDecode;

    protected $layoutName;

    /**
     * @var VariableCache
     */
    public $cache;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->layoutName = 'Anthem';

        $this->cache = VariableCache::getInstance();

        Utils::log('Connected!', 4, $this->layoutName . ' Builder');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        if($menuList['create'] === false) {
           $headElement = AnthemElementsController::getElement('head', $this->jsonDecode, $menuList);
            if ($headElement) {
                Utils::log('Success create MENU', 1, $this->layoutName . "] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, $this->layoutName . "] [__construct");
                throw new Exception('Failed create MENU');
            }
        }

        AnthemElementsController::getElement('footer', $this->jsonDecode);
    }

    /**
     * @throws Exception
     */
    public function build($preparedSectionOfThePage): bool
    {
        $QueryBuilder = $this->cache->getClass('QueryBuilder');

        $itemsID = $this->cache->get('currentPageOnWork');
        $slug = $this->cache->get('tookPage')['slug'];

        $url = PathSlugExtractor::getFullUrl($slug);

        $this->cache->set('CurrentPageURL', $url);

        $itemsData = [];
        $itemsData['items'][] = json_decode($this->cache->get('menuBlock'),true);

        Utils::log('Current Page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');
        $this->cache->update('createdFirstSection',false, 'flags');
        $this->cache->update('Success', '++', 'Status');

        foreach ($preparedSectionOfThePage as $section)
        {
            $blockData = $this->callMethod($section['typeSection'], $section, $slug);

            if($blockData === true) {
                $itemsData['items'][] = json_decode($this->cache->get('callMethodResult'));
            } else {
                if (!empty($blockData) && $blockData !== "null") {
                    $decodeBlock = json_decode($blockData, true);
                    $itemsData['items'][] = $decodeBlock;
                } else {
                    Utils::log('CallMethod return null. input data: ' . json_encode($section) . ' | Slug: '.$slug, 2, 'PageBuilder');
                }
            }
        }

        $itemsData['items'][] = json_decode($this->cache->get('footerBlock'),true);

        $pageData = json_encode($itemsData);

        Utils::log('Request to send content to the page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');


        $QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

        Utils::log('Content added to the page successfully: ' . $itemsID . ' | Slug: ' . $slug, 1, 'PageBuilder');
        return true;
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
            $result = AnthemElementsController::getElement($elementName, $this->jsonDecode, $params);
            if(!$result){
                Utils::log('Element ' . $elementName . ' does not exist. Page: ' . $marker, 2, $this->layoutName . "] [callMethod");
            }
        }
        return $result;
    }

}