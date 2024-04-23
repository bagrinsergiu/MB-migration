<?php

namespace MBMigration\Builder\Layout\Theme\Anthem;

use MBMigration\Core\Logger;
use Exception;
use MBMigration\Browser\BrowserPHP;
use MBMigration\Browser\BrowserPagePHP;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\FamilyTreeMenu;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Builder\VariableCache;
use MBMigration\Layer\Brizy\BrizyAPI;

class Anthem extends LayoutUtils
{
    /**
     * @var mixed
     */
    protected $jsonDecode;

    protected string $layoutName;

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
    private array $fontFamily;

    /**
     * @var BrowserPHP
     */
    private BrowserPHP $browser;
    /**
     * @var mixed|null
     */
    private $pageMapping;

    /**
     * @throws Exception
     */
    public function __construct($browserPage, $browser, BrizyAPI $brizyAPI)
    {
        $this->layoutName = 'Anthem';

        $this->cache = VariableCache::getInstance();

        $this->browserPage = $browserPage;
        $this->browser = $browser;

        $this->fontFamily = FontsController::getFontsFamily();

//        ThemePreProcess::treeMenu();

        Logger::instance()->info('Connected!');

        $this->jsonDecode = $this->loadKit($this->layoutName);

        $menuList = $this->cache->get('menuList');

        $this->pageMapping = $this->cache->get('pageMapping');

        if (empty($menuList['create'])) {
            $headElement = AnthemElementsController::getElement(
                'head',
                $this->jsonDecode,
                $this->browser,
                ['menu' => $menuList, 'activePage' => ''],
                $brizyAPI
            );
            if ($headElement) {
                Logger::instance()->info('Success create MENU');
                $menuList['create'] = true;
                $this->cache->set('menuList', $menuList);
            } else {
                Logger::instance()->warning("Failed create MENU");
                throw new Exception('Failed create MENU');
            }
        }

        if ($this->cache->get('footerBlockCreated') === false) {
            $MainSectionData = $this->cache->get('mainSection');
            $this->ExtractDataFromPage($MainSectionData, $this->browserPage, 'sectionId');
            $this->cache->set('mainSection', $MainSectionData);

            AnthemElementsController::getElement('footer', $this->jsonDecode, $this->browserPage, [], $brizyAPI);
        }
    }

    /**
     * @throws Exception
     */
    public function build($preparedSectionOfThePage): bool
    {
        $QueryBuilder = $this->cache->getClass('QueryBuilder');

        $itemsID = $this->cache->get('currentPageOnWork');
        $slug = $this->cache->get('tookPage')['slug'];
        $pageId = $this->cache->get('tookPage')['id'];
        $parentPages = $this->cache->get('menuList');

        $resultFind = FamilyTreeMenu::findParentByChildSlug($parentPages['list'], $slug);
        if (!empty($resultFind)) {
            $activeParentPage = $resultFind['slug'];
        } else {
            $activeParentPage = $slug;
        }
        $url = PathSlugExtractor::getFullUrlById($pageId);
        $this->browserPage = $this->browser->openPage($url, 'Anthem');
        $this->cache->set('CurrentPageURL', $url);

        $this->ExtractDataFromPage($preparedSectionOfThePage, $this->browserPage);

//        $menuList = $this->cache->get('menuList');
//
//        $headElement = AnthemElementsController::getElement(
//            'head',
//            $this->jsonDecode,
//            ['menu' => $menuList, 'activePage' => $activeParentPage]
//        );
//
//        $itemsData['items'][] = $headElement;
//        $itemsData['items'][] = json_decode($this->cache->get('menuBlock'), true);
//        $resultFind = FamilyTreeMenu::findChildrenByChildId($parentPages['list'], $itemsID);
//        if (!empty($resultFind)) {
//            $itemsData['items'][] = AnthemElementsController::getElement(
//                'SubMenu',
//                $this->jsonDecode,
//                ['menu' => $resultFind, 'activePage' => $slug]
//            );
//        }
        Logger::instance()->info('Current Page: '.$itemsID.' | Slug: '.$slug);
        $this->cache->update('createdFirstSection', false, 'flags');
        $this->cache->update('Success', '++', 'Status');
//        $this->browser->close();

        // list of elements that are processed once for a page
        $elementsList = ['event'];
        $processedItems = [];

        foreach ($preparedSectionOfThePage as $section) {

            $elementName = explode("-", $section['typeSection']);

            if (in_array($elementName[0], $elementsList)) {
                if (!in_array($elementName[0], $processedItems)) {
                    $processedItems[] = $elementName[0];
                } else {
                    continue;
                }
            }

            $blockData = $this->callMethod($section['typeSection'], $section, $slug);

            if ($blockData === true) {
                $itemsData['items'][] = json_decode($this->cache->get('callMethodResult'));
            } else {
                if (!empty($blockData) && $blockData !== "null") {
                    $decodeBlock = json_decode($blockData, true);
                    $itemsData['items'][] = $decodeBlock;
                } else {
                    Logger::instance()->warning('CallMethod return null. input data: ' . json_encode($section) . ' | Slug: ' . $slug);
                }
            }
        }

//        $itemsData['items'][] = json_decode($this->cache->get('footerBlock'), true);

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
            $result = AnthemElementsController::getElement(
                $elementName,
                $this->jsonDecode,
                $this->browserPage,
                $params
            );
            if (!$result) {
                Logger::instance()->warning('Element '.$elementName.' does not exist. Page: '.$marker);
            }
        }

        return $result;
    }

    /**
     * Extract data from a page and update the SectionPage array with the extracted data.
     *
     * @param array $SectionPage The array representing the sections on the page.
     * @param BrowserPagePHP $browserPage The browser page object used to extract data from the page.
     * @param string $nameSectionId The name of the section ID parameter. Default is 'id'.
     * @throws Exception If failed to extract data.
     */
    private function ExtractDataFromPage(array &$SectionPage, BrowserPageInterface $browserPage, string $nameSectionId = 'id')
    {
        $selectorIcon = "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
        $browserPage->ExtractHover($selectorIcon);

        $selectorButton = ".sites-button:not(.nav-menu-button)";
        $browserPage->ExtractHover($selectorButton);

        foreach ($SectionPage as &$section) {
            Logger::instance()->info('Extract Data'.$section['sectionId']);

            $section['style'] = $this->ExtractStyleSection($browserPage, $section['sectionId']);

            $section['style']['opacity_div'] = $this->ExtractStyleSectionOpacity(
                $browserPage,
                $section['sectionId']
            ) ?? [];
            $section['style']['body'] = $this->ExtractStylePage($browserPage);

            if ($section['typeSection'] === 'two-horizontal-text') {
                $section['style']['vertical-border'] = $this->ExtractBorderColorForTHT(
                    $browserPage,
                    $section['sectionId']
                ) ?? [];
            }

            if ($section['typeSection'] === 'accordion-layout') {
                $section['style']['accordion'] = $this->ExtractAccordion(
                    $browserPage,
                    $section['sectionId']
                ) ?? [];
            }

            if ($section['category'] === 'donation') {

                $style = [];
                $buttonStyle = $this->ExtractStyleDonateButton(
                    $browserPage,
                    $section['sectionId']
                ) ?? [];
                $style = array_merge($style, $buttonStyle);

                $buttonStyle  = $this->ExtractStyleDonateButtonStyle(
                    $browserPage,
                    $section['sectionId']
                ) ?? [];
                $style = array_merge($style, $buttonStyle);
                $section['style']['donation']['button'] = $style;
            }

            if (!empty($section['items'])) {
                foreach ($section['items'] as &$item) {
                    switch ($item['category']) {
                        case "text":
                            if (isset($item['item_type']) && $item['item_type'] == 'title') {
                                $section['style']['border'] = $this->ExtractBorderColorFromItem(
                                    $browserPage,
                                    $item['sectionId'] ?? $item['id']
                                ) ?? [];
                            }

                            $item['brzElement'] = $this->ExtractTextContent($browserPage, $item[$nameSectionId]);
                            break;
                        case "photo":
                            $target = $this->ExtractTargetLinkFromPhoto(
                                $browserPage,
                                $item['sectionId'] ?? $item['id']
                            ) ?? '_blank';

                            if ($target === '_blank') {
                                $item['new_window'] = true;
                            } else {
                                $item['new_window'] = false;
                            }
                            break;
                        case "list":
                        case "tab":
                        case "accordion":
                            $this->ExtractItemContent($item['item'], $browserPage);

                            foreach ($item['item'] as &$listItem) {
                                if ($listItem['category']=== "photo") {
                                    $target = $this->ExtractTargetLinkFromPhoto(
                                        $browserPage,
                                        $listItem['sectionId'] ?? $listItem['id']
                                    ) ?? '_blank';

                                    if ($target === '_blank') {
                                        $listItem['new_window'] = true;
                                    } else {
                                        $listItem['new_window'] = false;
                                    }
                                }

                                if ($item['item_type'] == 'title') {
                                    $section['style']['border'] = $this->ExtractBorderColorFromItem(
                                        $browserPage,
                                        $listItem['sectionId']
                                    ) ?? [];
                                }
                            }
                            break;
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
            'brizy.getStyles',
            [
                'selector' => '[data-id="'.$sectionId.'"]',
                'styleProperties' => [
                    'background-color',
                    'opacity',
                    'padding-top',
                    'padding-bottom',
                    'margin-top',
                    'margin-bottom',
                    'padding-left',
                    'padding-right',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }
        $this->convertStyle($sectionStyles, $style);

        return $style;
    }

    private function removePx($inputString)
    {
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
            'brizy.getStyles',
            [
                'selector' => '[data-id="'.$sectionId.'"]',
                'styleProperties' => [
                    'border-bottom-color',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }
        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
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
        } else {
            return [];
        }

        return $style;
    }

    private function ExtractBorderColorForTHT($browserPage, int $sectionId): array
    {
        $style = [];
        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '[data-id="'.$sectionId.'"] .group-0',
                'styleProperties' => [
                    'border-color',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }
        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
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
        } else {
            return [];
        }

        return $style;
    }
    private function ExtractAccordion($browserPage, int $sectionId): array
    {
        $style = [];
        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getAccordion',
            [
                'selector' => '[data-id="'.$sectionId.'"]',
                'styleProperties' => [],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }

        return $sectionStyles['data'];
    }


    private function ExtractStyleSectionOpacity($browserPage, int $sectionId)
    {

        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '[data-id="'.$sectionId.'"] .bg-opacity',
                'styleProperties' => [
                    'opacity',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }

        return $sectionStyles['data'] ?? [];
    }
    private function ExtractStyleDonateButton($browserPage, int $sectionId): array
    {
        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '[data-id="'.$sectionId.'"] .sites-button-text',
                'styleProperties' => [
                    'color',
                    'text-transform',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }

        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
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
        } else {
            return [];
        }

        return $style;
    }

    private function ExtractTargetLinkFromPhoto($browserPage, int $sectionId): string
    {
        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getAttributes',
            [
                'selector' => '[data-id="'.$sectionId.'"] .photo-container a',
                'attributeNames' => [
                    'target',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return '_blank';
        }

        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
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
        } else {
            return '_blank';
        }

        return $style['target'] ?? '_blank';
    }

    private function ExtractStyleDonateButtonStyle($browserPage, int $sectionId): array
    {
        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '[data-id="'.$sectionId.'"] .sites-button',
                'styleProperties' => [
                    'background-color',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }

        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
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
        } else {
            return [];
        }

        return $style;
    }

    private function ExtractStylePage($browserPage): array
    {
        $style = [];
        $sectionStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => 'body',
                'styleProperties' => [
                    'background-color',
                ],
                'families' => $this->fontFamily['kit'],
                'defaultFamily' => $this->fontFamily['Default'],
                'urlMap' => $this->pageMapping,
            ]
        );

        if (array_key_exists('error', $sectionStyles)) {
            return [];
        }

        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles['data'] as $key => $value) {
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
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
        } else {
            return [];
        }

        return $style;
    }

    private function ExtractTextContent($browserPage, int $mbSectionItemId)
    {
        $richTextBrowserData = $browserPage->evaluateScript('brizy.getText', [
            'selector' => '[data-id="'.$mbSectionItemId.'"]',
            'families' => $this->fontFamily['kit'],
            'defaultFamily' => $this->fontFamily['Default'],
            'urlMap' => $this->pageMapping,
        ]);

        if (array_key_exists('error', $richTextBrowserData)) {
            return [];
        }

        if (!isset($richTextBrowserData['data'])) {
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
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
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
        } else {
            return [];
        }
    }

}