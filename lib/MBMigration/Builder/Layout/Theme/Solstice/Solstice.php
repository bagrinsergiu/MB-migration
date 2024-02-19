<?php

namespace MBMigration\Builder\Layout\Theme\Solstice;

use Exception;
use MBMigration\Browser\Browser;
use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\Utils\FamilyTreeMenu;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Solstice extends LayoutUtils
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
     * @var mixed
     */
    private $browserPage;
    /**
     * @var array
     */
    private $fontFamily;

    /**
     * @var Browser
     */
    private $browser;

    /**
     * @throws Exception
     */
    public function __construct($browserPage, $browser)
    {
        $this->layoutName = 'Solstice';

        $this->cache = VariableCache::getInstance();

        $this->browserPage = $browserPage;
        $this->browser = $browser;

        $this->fontFamily = FontsController::getFontsFamily();

//        ThemePreProcess::treeMenu();

        Utils::log('Connected!', 4, $this->layoutName.' Builder');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        if ($menuList['create'] === false) {
            $headElement = SolsticeElementsController::getElement(
                'head',
                $this->jsonDecode,
                $this->browser,
                ['menu' => $menuList, 'activePage' => '']
            );
            if ($headElement) {
                Utils::log('Success create MENU', 1, $this->layoutName."] [__construct");
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Utils::log("Failed create MENU", 2, $this->layoutName."] [__construct");
                throw new Exception('Failed create MENU');
            }
        }
        $MainSectionData = $this->cache->get('mainSection');
        $this->ExtractDataFromPage($MainSectionData, $this->browserPage, 'sectionId');
        $this->cache->set('mainSection', $MainSectionData);

        SolsticeElementsController::getElement('footer', $this->jsonDecode, $this->browserPage);
    }

    /**
     * @throws Exception
     */
    public function build($preparedSectionOfThePage): bool
    {
        $QueryBuilder = $this->cache->getClass('QueryBuilder');

        $itemsID = $this->cache->get('currentPageOnWork');
        $slug = $this->cache->get('tookPage')['slug'];
        $parentPages = $this->cache->get('menuList');

        $resultFind = FamilyTreeMenu::findParentByChildSlug($parentPages['list'], $slug);
        if (!empty($resultFind)) {
            $activeParentPage = $resultFind['slug'];
        } else {
            $activeParentPage = $slug;
        }
        $url = PathSlugExtractor::getFullUrl($slug);

        $this->cache->set('CurrentPageURL', $url);

        $this->ExtractDataFromPage($preparedSectionOfThePage, $this->browserPage);

//        $menuList = $this->cache->get('menuList');
//
//        $headElement = SolsticeElementsController::getElement(
//            'head',
//            $this->jsonDecode,
//            ['menu' => $menuList, 'activePage' => $activeParentPage]
//        );
//
//        $itemsData['items'][] = $headElement;

        $itemsData['items'][] = json_decode($this->cache->get('menuBlock'), true);

//        $resultFind = FamilyTreeMenu::findChildrenByChildId($parentPages['list'], $itemsID);
//        if (!empty($resultFind)) {
//            $itemsData['items'][] = SolsticeElementsController::getElement(
//                'SubMenu',
//                $this->jsonDecode,
//                ['menu' => $resultFind, 'activePage' => $slug]
//            );
//        }
        Utils::log('Current Page: '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');
        $this->cache->update('createdFirstSection', false, 'flags');
        $this->cache->update('Success', '++', 'Status');
//        $this->browser->close();

        foreach ($preparedSectionOfThePage as $section) {
            if ($section['typeSection'] !== 'grid-layout'){
                continue;
            }
            $blockData = $this->callMethod($section['typeSection'], $section, $slug);

            if ($blockData === true) {
                $itemsData['items'][] = json_decode($this->cache->get('callMethodResult'));
            } else {
                if (!empty($blockData) && $blockData !== "null") {
                    $decodeBlock = json_decode($blockData, true);
                    $itemsData['items'][] = $decodeBlock;
                } else {
                    Utils::log(
                        'CallMethod return null. input data: '.json_encode($section).' | Slug: '.$slug,
                        2,
                        'PageBuilder'
                    );
                }
            }
        }

        $itemsData['items'][] = json_decode($this->cache->get('footerBlock'), true);

        $pageData = json_encode($itemsData);

        Utils::log('Request to send content to the page: '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');


        $QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

        Utils::log('Content added to the page successfully: '.$itemsID.' | Slug: '.$slug, 1, 'PageBuilder');

        return true;
    }

    /**
     * @throws Exception
     */
    public function callMethod($methodName, $params = [], $marker = '')
    {
        $elementName = $this->replaceInName($methodName);

        if (method_exists($this, $elementName)) {
            Utils::log('Call Element '.$elementName, 1, $this->layoutName."] [callMethod");
            $result = call_user_func_array(array($this, $elementName), [$params]);
            $this->cache->set('callMethodResult', $result);
        } else {
            $result = SolsticeElementsController::getElement(
                $elementName,
                $this->jsonDecode,
                $this->browserPage,
                $params
            );
            if (!$result) {
                Utils::log(
                    'Element '.$elementName.' does not exist. Page: '.$marker,
                    2,
                    $this->layoutName."] [callMethod"
                );
            }
        }

        return $result;
    }

    /**
     * Extract data from a page and update the SectionPage array with the extracted data.
     *
     * @param array $SectionPage The array representing the sections on the page.
     * @param BrowserPage $browserPage The browser page object used to extract data from the page.
     * @param string $nameSectionId The name of the section ID parameter. Default is 'id'.
     * @throws Exception If failed to extract data.
     */
    private function ExtractDataFromPage(&$SectionPage, BrowserPage $browserPage, $nameSectionId = 'id')
    {
        $selectorIcon = "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
        $browserPage->ExtractHover($selectorIcon);

        $selectorButton = ".sites-button:not(.nav-menu-button)";
        $browserPage->ExtractHover($selectorButton);

        foreach ($SectionPage as &$section) {
            Utils::log('Extract Data'.$section['sectionId'], 1, 'ExtractDataFromPage');

            $section['style'] = $this->ExtractStyleSection($browserPage, $section['sectionId']);

            $section['style']['opacity_div'] = $this->ExtractStyleSectionOpacity(
                $browserPage,
                $section['sectionId']
            ) ?? [];
            $section['style']['body'] = $this->ExtractStylePage($browserPage);

            if (!empty($section['items'])) {
                foreach ($section['items'] as &$item) {
                    if ($item['category'] === 'text') {

                        if (isset($item['item_type']) && $item['item_type'] == 'title') {
                            $section['style']['border'] = $this->ExtractBorderColorFromItem(
                                $browserPage,
                                $item['id']
                            ) ?? [];
                        }

                        $item['brzElement'] = $this->ExtractTextContent($browserPage, $item[$nameSectionId]);

                    } else {

                        switch ($item['category']) {
                            case "list":
                            case "tab":
                                $this->ExtractItemContent($item['item'], $browserPage);

                                foreach ($item['item'] as $listItem) {
                                    if ($item['item_type'] == 'title') {
                                        $section['style']['border'] = $this->ExtractBorderColorFromItem(
                                            $browserPage,
                                            $listItem['sectionId']
                                        ) ?? [];
                                    }
                                }
                        }
                    }
                }
            }
            if (!empty($section['head'])) {
                foreach ($section['head'] as &$item) {
                    if ($item['category'] === 'text') {
                        $item['brzElement'] = $this->ExtractTextContent($browserPage, $item['id']);

                        if ($item['item_type'] == 'title') {
                            $section['style']['border'] = $this->ExtractBorderColorFromItem(
                                $browserPage,
                                $item['id']
                            ) ?? [];
                        }
                    }
                }
            }
        }
    }

    private function ExtractStyleSection($browserPage, int $sectionId): array
    {
        $style = [];
        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => '[data-id="'.$sectionId.'"]',
                'STYLE_PROPERTIES' => [
                    'background-color',
                    'opacity',
                    'padding-top',
                    'padding-bottom',
                    'margin-top',
                    'margin-bottom',
                    'padding-left',
                    'padding-right',
                ],
                'FAMILIES' => $this->fontFamily['kit'],
                'DEFAULT_FAMILY' => $this->fontFamily['Default'],
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }
        $this->convertStyle($sectionStyles, $style);

        return $style;
    }

    private function removePx($inputString) {
        $pos = strpos($inputString, "px");

        if ($pos !== false) {
            $inputString = substr_replace($inputString, "", $pos, 2);
        }

        return $inputString;
    }

    private function ExtractBorderColorFromItem($browserPage, int $sectionId): array
    {
        $style = [];
        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => '[data-id="'.$sectionId.'"]',
                'STYLE_PROPERTIES' => [
                    'border-bottom-color',
                ],
                'FAMILIES' => $this->fontFamily['kit'],
                'DEFAULT_FAMILY' => $this->fontFamily['Default'],
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }
        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = $this->convertColor(str_replace("px", "", $value));
                if (is_array($convertedData)) {
                    $style[$key] = $convertedData['color'];
                    $style['opacity'] = $convertedData['opacity'];
                    $opacityIsSet = true;
                } else {
                    if ($opacityIsSet && $key == 'opacity') {
                        continue;
                    } else {
                        $style[$key] = $convertedData;
                    }
                }
            }
        }  else { return [];}

        return $style;
    }

    private function ExtractStyleBorderSection($browserPage, int $sectionId): array
    {
        $style = [];
        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => '[data-id="'.$sectionId.'"]',
                'STYLE_PROPERTIES' => [
                    'border-bottom-color',
                ],
                'FAMILIES' => $this->fontFamily['kit'],
                'DEFAULT_FAMILY' => $this->fontFamily['Default'],
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }
        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = $this->convertColor(str_replace("px", "", $value));
                if (is_array($convertedData)) {
                    $style[$key] = $convertedData['color'];
                    $style['opacity'] = $convertedData['opacity'];
                    $opacityIsSet = true;
                } else {
                    if ($opacityIsSet && $key == 'opacity') {
                        continue;
                    } else {
                        $style[$key] = $convertedData;
                    }
                }
            }
        }  else { return [];}

        return $style;
    }

    private function ExtractStyleSectionOpacity($browserPage, int $sectionId)
    {

        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => '[data-id="'.$sectionId.'"] .bg-opacity',
                'STYLE_PROPERTIES' => [
                    'opacity',
                ],
                'FAMILIES' => $this->fontFamily['kit'],
                'DEFAULT_FAMILY' => $this->fontFamily['Default'],
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }

        return $sectionStyles['data'] ?? [];
    }

    private function ExtractStylePage($browserPage): array
    {
        $style = [];
        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => 'body',
                'STYLE_PROPERTIES' => [
                    'background-color',
                ],
                'FAMILIES' => $this->fontFamily['kit'],
                'DEFAULT_FAMILY' => $this->fontFamily['Default'],
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }

        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = $this->convertColor(str_replace("px", "", $value));
                if (is_array($convertedData)) {
                    $style[$key] = $convertedData['color'];
                    $style['opacity'] = $convertedData['opacity'];
                    $opacityIsSet = true;
                } else {
                    if ($opacityIsSet && $key == 'opacity') {
                        continue;
                    } else {
                        $style[$key] = $convertedData;
                    }
                }
            }
        }  else { return [];}

        return $style;
    }

    private function ExtractTextContent($browserPage, int $mbSectionItemId)
    {
        $richTextBrowserData = $browserPage->evaluateScript('Text.js', [
            'SELECTOR' => '[data-id="'.$mbSectionItemId.'"]',
            'FAMILIES' => $this->fontFamily['kit'],
            'DEFAULT_FAMILY' => $this->fontFamily['Default'],
        ]);

        if (array_key_exists('error', $richTextBrowserData)) {
            return [];
        }

        if(!isset($richTextBrowserData['data'])){
            return [];
        }

        return $richTextBrowserData['data'];
    }

    private function ExtractItemContent(&$items, $browserPage)
    {
        foreach ($items as &$item) {
            if ($item['category'] === 'text') {
                $item['brzElement'] = $this->ExtractTextContent($browserPage, $item['id']);
            }
        }
    }

    private function convertStyle($sectionStyles, &$style, $section = 'data')
    {
        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles[$section] as $key => $value) {
                $data = $this->removePx($value);
                $convertedData = $this->convertColor(str_replace("px", "", $value));
                if (is_array($convertedData)) {
                    $style[$key] = $convertedData['color'];
                    $style['opacity'] = $convertedData['opacity'];
                    $opacityIsSet = true;
                } else {
                    if ($opacityIsSet && $key == 'opacity') {
                        continue;
                    } else {
                        $style[$key] = $convertedData;
                    }
                }
            }
        } else { return [];}
    }

    private function convertColor($color)
    {

        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            return $color;
        }

        if (preg_match('/rgba\((\d+), (\d+), (\d+), ([0-9]*\.?[0-9]+)\)/', $color, $matches)) {
            $r = $matches[1];
            $g = $matches[2];
            $b = $matches[3];
            $a = $matches[4];

            $color = sprintf("#%02X%02X%02X", $r, $g, $b);

            if ($a == 0 && $color === "#000000") {
                return '#ffffff';
            } else {
                return [
                    'color' => sprintf("#%02X%02X%02X", $r, $g, $b),
                    'opacity' => $a,
                ];
            }
        }

        if (preg_match_all("/rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)/", $color, $matches)) {

            list($r, $g, $b) = array($matches[1][0], $matches[2][0], $matches[3][0]);

            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        return $color;
    }
}