<?php

namespace MBMigration\Builder\Layout\Theme\Aurora;

use MBMigration\Core\Logger;
use Exception;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Builder\VariableCache;

class Aurora extends LayoutUtils implements ThemeInterface
{
    private $brizyKit;

    /**
     * @var mixed
     */
    protected $jsonDecode;

    protected $layoutName;

    /**
     * @var VariableCache
     */
    public $cache;
    private $menu;

    /**
     * @throws Exception
     */
    public function __construct($brizyKit, $menu)
    {
        $this->layoutName = 'Aurora';
        $this->brizyKit = $brizyKit;
        $this->menu = $menu;


        $this->cache = VariableCache::getInstance();

        Logger::instance()->info('Connected!');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        if ($menuList['create'] === false) {
            $headElement = AuroraElementsController::getElement('head', $this->jsonDecode, $menuList);
            if ($headElement) {
                Logger::instance()->info('Success create MENU');
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Logger::instance()->warning("Failed create MENU");
                throw new Exception('Failed create MENU');
            }
        }

        AuroraElementsController::getElement('footer', $this->jsonDecode);


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
        $itemsData['items'][] = json_decode($this->cache->get('menuBlock'), true);

        Logger::instance()->info('Current Page: '.$itemsID.' | Slug: '.$slug);
        $this->cache->update('createdFirstSection', false, 'flags');
        $this->cache->update('Success', '++', 'Status');

        foreach ($preparedSectionOfThePage as $section) {
            $blockData = $this->callMethod($section['typeSection'], $section, $slug);

            if ($blockData === true) {
                $itemsData['items'][] = json_decode($this->cache->get('callMethodResult'));
            } else {
                if (!empty($blockData) && $blockData !== "null") {
                    $decodeBlock = json_decode($blockData, true);
                    $itemsData['items'][] = $decodeBlock;
                } else {
                    Logger::instance()->warning('CallMethod return null. input data: '.json_encode($section).' | Slug: '.$slug);
                }
            }
        }

        $itemsData['items'][] = json_decode($this->cache->get('footerBlock'), true);

        $pageData = json_encode($itemsData);

        Logger::instance()->info('Request to send content to the page: '.$itemsID.' | Slug: '.$slug);


        $QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

        Logger::instance()->info('Content added to the page successfully: '.$itemsID.' | Slug: '.$slug);

        return true;
    }

    /**
     * @throws Exception
     */
    public function callMethod($methodName, $params = [], $marker = '')
    {
        $elementName = $this->replaceInName($methodName);

        if (method_exists($this, $elementName)) {
            Logger::instance()->info('Call Element '.$elementName);
            $result = call_user_func_array(array($this, $elementName), [$params]);
            $this->cache->set('callMethodResult', $result);
        } else {
            $result = AuroraElementsController::getElement($elementName, $this->jsonDecode, $params);
            if (!$result) {
                Logger::instance()->warning('Element '.$elementName.' does not exist. Page: '.$marker);
            }
        }

        return $result;
    }
}