<?php

namespace MBMigration\Builder\Layout\Common\Elements;

use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\RootListFontFamilyExtractor;
use MBMigration\Builder\Layout\Common\RootPalettesExtractor;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;
use MBMigration\Layer\Brizy\BrizyAPI;

abstract class HeadElement extends AbstractElement
{
    const CACHE_KEY = 'head';
    use Cacheable;
    use SectionStylesAble;

    protected array $basicHeadParams = [
        'addMenuItems' => true,
        'addMenu' => true,
    ];

    protected BrizyAPI $brizyAPIClient;
    protected BrizyComponent $pageLayout;
    private FontsController $fontsController;

    public function __construct(
        $brizyKit,
        BrowserPageInterface $browserPage,
        BrizyAPI $brizyAPI,
        FontsController $fontsController
    )
    {
        Logger::instance()->info('HeadElement constructor called', [
            'brizy_kit_keys' => is_array($brizyKit) ? array_keys($brizyKit) : 'not_array',
            'browser_page_class' => get_class($browserPage),
            'brizy_api_class' => get_class($brizyAPI),
            'fonts_controller_class' => get_class($fontsController)
        ]);

        parent::__construct($brizyKit, $browserPage);

        $this->brizyAPIClient = $brizyAPI;
        $this->fontsController = $fontsController;

        Logger::instance()->info('HeadElement initialized successfully', [
            'basic_head_params' => $this->basicHeadParams,
            'has_brizy_api_client' => isset($this->brizyAPIClient),
            'has_fonts_controller' => isset($this->fontsController)
        ]);
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $startTime = microtime(true);
        Logger::instance()->info('HeadElement::transformToItem called', [
            'data_class' => get_class($data),
            'page_id' => $data->getThemeContext()->getPageDTO() ? $data->getThemeContext()->getPageDTO()->getId() : null,
            'start_time' => $startTime
        ]);

        $this->pageTDO = $data->getThemeContext()->getPageDTO();
        $this->themeContext = $data->getThemeContext();
        $this->pageLayout = $data->getPageLayout();

        Logger::instance()->info('HeadElement context setup completed', [
            'page_id' => $this->pageTDO ? $this->pageTDO->getId() : null,
            'theme_context_class' => get_class($this->themeContext)
        ]);

        return $this->getCache(self::CACHE_KEY, function () use ($data, $startTime): BrizyComponent {
            Logger::instance()->info('HeadElement cache miss - executing transformation', [
                'cache_key' => self::CACHE_KEY
            ]);

            $this->basicHeadParams = array_merge($this->basicHeadParams, $this->headParams);
            Logger::instance()->info('Head parameters merged', [
                'basic_head_params' => $this->basicHeadParams,
                'head_params' => $this->headParams
            ]);

            $this->beforeTransformToItem($data);
            Logger::instance()->info('Before transform hook completed');

            $this->fontHandle($data);
            Logger::instance()->info('Font handling completed');

            $component = $this->internalTransformToItem($data);
            Logger::instance()->info('Internal transformation completed', [
                'component_type' => $component->getType()
            ]);

            $this->generalSectionBehavior($data, $component);
            Logger::instance()->info('General section behavior completed');

            $this->afterTransformToItem($component);
            Logger::instance()->info('After transform hook completed');

            // save it as a global block
            if ($this->makeGlobalBlock()){
                $this->saveItAsAGlobalBlock($component);
            }

            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->info('HeadElement transformation completed successfully', [
                'execution_time_ms' => round($executionTime, 2),
                'component_type' => $component->getType()
            ]);

            return $component;
        });
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $headStyles = $this->extractBlockBrowserData(
            $data->getMbSection()['sectionId'],
            $data->getFontFamilies(),
            $data->getDefaultFontFamily(),
            $data
        );

        $section = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        // reset color palette
        $sectionItem = $this->getSectionItemComponent($section);

        $logoImageComponent = $this->getLogoComponent($section);
        $menuTargetComponent = $this->getTargetMenuComponent($section);

        // build menu items and set the menu uid
        $this->buildMenuItemsAndSetTheMenuUid($data, $menuTargetComponent, $headStyles);
        $this->setImageLogo($logoImageComponent, $data->getMbSection());

        $elementContext = $data->instanceWithBrizyComponent($sectionItem);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->getThemeMenuHeaderStyle($headStyles, $section);

        return $section;
    }

    // region Menu methods
    protected function createMenu($menuList): array
    {
        $menuItems = $this->creatingMenuTree($menuList);

        return $menuItems;
    }

    private function creatingMenuTree($menuList): array
    {
        $menuItemKit = json_decode($this->brizyKit['item'], true);
        $treeMenu = [];
        foreach ($menuList as $item) {
            $blockMenu = new BrizyComponent($menuItemKit);
            $blockMenu->getValue()->set_itemId($item['collection']);
            $blockMenu->getValue()->set_title($item['name']);
            $blockMenu->getValue()->set_url($item['slug'] == 'home' ? '/' : $item['slug']);
            $blockMenu->getValue()->set_id(bin2hex(random_bytes(16)));

            if (isset($item['iconName']) && isset($item['iconType'])) {
                $blockMenu->getValue()->set_iconName($item['iconName']);
                $blockMenu->getValue()->set_iconType($item['iconType']);
            }

            $menuSubItems = $this->creatingMenuTree($item['child']);
            $blockMenu->getValue()->set_items($menuSubItems);

            if ($item['landing'] == false && count($menuSubItems)) {
                $url = $blockMenu->getItemValueWithDepth(0)->get_url();
                $blockMenu->getValue()->set_url($url);
            }

            $treeMenu[] = $blockMenu;
        }

        return $treeMenu;
    }
    // endregion

    /**
     * @param BrizyComponent $component
     * @param $headItem
     * @return BrizyComponent
     */
    protected function setImageLogo(BrizyComponent $component, $headItem): BrizyComponent
    {
        $imageLogo = [];

        foreach ($headItem['items'] as $item) {
            if ($item['category'] = 'photo') {
                $imageLogo['imageSrc'] = $item['content'];
                $imageLogo['imageFileName'] = $item['content']; //$item['imageFileName'];
                $imageLogo['imageWidth'] = $item['settings']['image']['width'];
                $imageLogo['imageHeight'] = $item['settings']['image']['height'];
            }
        }

        if (!empty($imageLogo['imageWidth']) && !empty($imageLogo['imageHeight'])) {
            $component->getValue()
                ->set_imageHeight($imageLogo['imageHeight'])
                ->set_imageWidth($imageLogo['imageWidth']);
        }

        $component->getValue()
            ->set_imageSrc($imageLogo['imageSrc'])
            ->set_imageFileName($imageLogo['imageFileName'])
            ->set_sizeType('original');

        return $component;
    }

    /**
     * @param ElementContextInterface $data
     * @param BrizyComponent $component
     * @param $headStyles
     * @return BrizyComponent
     */
    protected function buildMenuItemsAndSetTheMenuUid(
        ElementContextInterface $data,
        BrizyComponent          $component,
                                $headStyles
    ): BrizyComponent
    {
        $menuComponentValue = $component->getValue();
        $projectName = $data->getThemeContext()->getProjectName();
        $menuComponentValue
            ->set('items', $this->createMenu($data->getBrizyMenuEntity()['list']))
            ->set_menuSelected($data->getBrizyMenuEntity()['uid'])
            ->set_mMenuTitle($projectName);

        // apply menu styles
        foreach ($headStyles['menu'] as $field => $value) {
            $method = "set_{$field}";
            $menuComponentValue->$method($value);
        }

        return $component;
    }

    /**
     * @throws GuzzleException
     */
    protected function extractBlockBrowserData(
        $sectionId,
        $families,
        $defaultFamilies,
        ElementContextInterface $elementContext
    ): array
    {
        $hoverMenuItemStyles = [];
        $hoverMenuSubItemStyles = [];
        $menuSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '[data-id=\'' . $sectionId . '\']',
                'styleProperties' => ['background-color', 'color', 'opacity', 'border-bottom-color'],
                'families' => $families,
                'defaultFamily' => $defaultFamilies,
            ]
        );

        $menuItemSelector = $this->getThemeMenuItemSelector();
        $itemMobileSelector = $this->getThemeMenuItemMobileSelector();

        $menuFont = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $menuItemSelector['selector'],
                'styleProperties' => ['font-family'],
                'families' => $families,
                'defaultFamily' => $defaultFamilies,
            ]
        );

        // check if the font exists and upload it if not.
        $fontFamily = $this->transliterateFontFamily($menuFont['data']['font-family']);

        if (!isset($families[$fontFamily])) {
            $fontName = $this->firstFontFamily($menuFont['data']['font-family']);

            $pd = $this->fontsController->getProjectData();

            $this->fontsController->upLoadFont($fontName, $fontFamily, '[Head] extractBlockBrowserData');

            $families = FontsController::getFontsFamily()['kit'];
            $elementContext->getThemeContext()->setFamilies($families);
        }

        // -------------------------------------
        $menuItemStyles = $this->browserPage->evaluateScript('brizy.getMenuItem', [
            'itemSelector' => $menuItemSelector,
            'itemActiveSelector' => $this->getThemeMenuItemActiveSelector(),
            'itemBgSelector' => $this->getMenuItemBgSelector(),
            'itemPaddingSelector' => $this->getThemeMenuItemPaddingSelector(),
            'itemMobileSelector' => $itemMobileSelector,
            'itemMobileBtnSelector' => $this->getThemeMobileBtnSelector(),
            'itemMobileNavSelector' => $this->getThemeMobileNavSelector(),
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
            'isBgHoverItemMenu' => $this->isBgHoverItemMenu(),
            'hover' => false,
        ]);

        $this->menuItemStylesValueConditions($menuItemStyles);

        if ($this->browserPage->triggerEvent('hover', $this->getNotSelectedMenuItemBgSelector()['selector'])) {

            $options = [
                'itemSelector' => $menuItemSelector,
                'itemBgSelector' => $this->getMenuHoverItemBgSelector(),
                'itemPaddingSelector' => $this->getThemeMenuItemPaddingSelector(),
                'families' => $families,
                'defaultFamily' => $defaultFamilies,
                'hover' => true,
                'isBgHoverItemMenu' => $this->isBgHoverItemMenu()
            ];

            $hoverMenuItemStyles = $this->browserPage->evaluateScript('brizy.getMenuItem', $options);
        }

        return [
            'menu' => array_merge(
                $menuItemStyles['data'] ?? [],
                $this->getNormalSubMenuStyle($families, $defaultFamilies),
                $hoverMenuItemStyles['data'] ?? [],
                $this->getHoverSubMenuStyle()
            ),
            'style' => $menuSectionStyles['data'] ?? [],
        ];
    }

    protected function getNormalSubMenuStyle($families, $defaultFamilies): array
    {
        $this->browserPage->triggerEvent('hover', 'body');

        $themeSubMenuNotSelectedItemSelector = $this->getThemeSubMenuNotSelectedItemSelector();
        $themeSubMenuItemBGSelector = $this->getThemeSubMenuItemBGSelector();
        $getSubMenuItemParams = [
            'itemSelector' => $themeSubMenuNotSelectedItemSelector,
            'itemBgSelector' => $themeSubMenuItemBGSelector,
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
            'hover' => false,
        ];

        $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $getSubMenuItemParams);

        $menuSubItemDropdownStyles = $this->browserPage->evaluateScript('brizy.getSubMenuDropdown', [
            'nodeSelector' => $this->getThemeSubMenuItemDropDownSelector(),
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
        ]);

        $menuSubItemStyles['data'] = array_merge($menuSubItemStyles['data'], $menuSubItemDropdownStyles['data']);

        if (isset($menuSubItemStyles['error'])) {
            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            //$this->browserPage->getPageScreen('subNormal_1');

            $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $getSubMenuItemParams);

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);
        }

        return $menuSubItemStyles['data'] ?? [];
    }

    protected function getHoverSubMenuStyle(): array
    {
        $selector = $this->getThemeParentMenuItemSelector()['selector'];
        if ($this->browserPage->triggerEvent('click', $selector)) {

           // $this->browserPage->getPageScreen('1');

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            //$this->browserPage->getPageScreen('1_1');

            $themeSubMenuSelectedItemSelector = $this->getThemeSubMenuSelectedItemSelector();
            $activeMenuSubItemStyles = $this->scrapeStyle($themeSubMenuSelectedItemSelector['selector'],['color']);

            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            //$this->browserPage->getPageScreen('remove_node_1');

            $selector1 = $this->getThemeSubMenuNotSelectedItemSelector()['selector'];
            if ($this->browserPage->triggerEvent('hover', $selector1)) {

                //$this->browserPage->getPageScreen('subMenu_Selected');
                $entrySubMenu = [
                    'itemSelector' => $this->getThemeSubMenuNotSelectedItemSelector(),
                    'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                    'families' => '',
                    'defaultFamily' => [],
                    'hover' => true,
                ];

                $hoverMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $entrySubMenu);
            }

            //$this->browserPage->getPageScreen(2);

            $hoverMenuSubItemStyles['data']['activeSubMenuColorHex'] = ColorConverter::rgba2hex($activeMenuSubItemStyles['color']);
            $hoverMenuSubItemStyles['data']['hoverSubMenuColorHex'] = ColorConverter::rgba2hex($activeMenuSubItemStyles['color']);
            $hoverMenuSubItemStyles['data']['activeSubMenuColorOpacity'] = 1;
            $hoverMenuSubItemStyles['data']['hoverSubMenuColorOpacity'] = 1;

        } else {
            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            $entrySubMenu = [
                'itemSelector' => $this->getThemeSubMenuItemSelector(),
                'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                'families' => '',
                'defaultFamily' => [],
                'hover' => true,
            ];

            $hoverMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', $entrySubMenu);

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);
        }

        // close all thing that where open untiln now... just in case
        $this->browserPage->triggerEvent('click', 'body');

        return $hoverMenuSubItemStyles['data'] ?? [];
    }

    protected function generalSectionBehavior(ElementContextInterface $data, BrizyComponent $section): void
    {
        $section->getItemWithDepth(0)->addCustomCSS('blockquote{margin:0;}'); //fix for table in richtext
        $section->getItemWithDepth(0)->addCustomCSS("@font-face {\n    font-family: 'Mono Social Icons Font';\n    src: url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.eot\");\n    src: url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.eot?#iefix\") format(\"embedded-opentype\"), url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.woff\") format(\"woff\"), url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.ttf\") format(\"truetype\"), url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.svg#MonoSocialIconsFont\") format(\"svg\");\n    src: url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.ttf\") format(\"truetype\");\n    font-weight: normal;\n    font-style: normal\n}\n\n.socialIconSymbol {\n    font-family: 'Mono Social Icons Font';\n    font-size: 2em;\n    font-style: normal !important;\n}\n\n.text-content span.socialIconSymbol, .text-content a.socialIconSymbol {\n    line-height: .5em;\n    font-weight: 300\n}"); //fix for icons in embed code
        $section->getItemWithDepth(0)->addCustomCSS('.brz-a.brz-btn, .brz-a.brz-btn > span {white-space: normal  !important; }');
        $section->getItemWithDepth(0)->addCustomCSS(".brz-rich-text__custom a:hover{\n text-decoration: underline !important; \n}");
        $section->getItemWithDepth(0)->addCustomCSS("@media (max-width: 767px){\n   .embedded-paste iframe {\n    width: 100% !important;\nmax-width: unset !important;   min-width:unset !important; }\n}");
    }

    private function fontHandle(ElementContextInterface $data): void
    {
        Logger::instance()->info('Starting font handling for page', [
            'theme' => $data->getThemeContext()->getThemeName(),
            'url' => $this->browserPage->getCurrentUrl()
        ]);

        $fontController = $data->getThemeContext()->getFontsController();

        Logger::instance()->info('Creating font family extractor');
        $RootListFontFamilyExtractor = new RootListFontFamilyExtractor($this->browserPage);

        Logger::instance()->info('Uploading custom fonts');
        $fontController->upLoadCustomFonts($RootListFontFamilyExtractor);

        $families = FontsController::getFontsFamily()['kit'];

        Logger::instance()->info('Font handling completed', [
            'familiesCount' => count($families),
            'theme' => $data->getThemeContext()->getThemeName()
        ]);

        $data->getThemeContext()->setFamilies($families);
    }

    protected function scrapeStyle($selector, array $styleProperties)
    {
        try {
            $menuSectionStyles = $this->browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => $selector,
                    'styleProperties' => $styleProperties,
                    'families' => [],
                    'defaultFamily' => '',
                ]
            );
            return $menuSectionStyles['data'] ?? [];
        } catch (\Exception $e) {
            Logger::instance()->warning('Scrape Style: ' . $e);
        }
        return [];
    }

    protected function getPageLayout(): BrizyComponent
    {
        return $this->pageLayout;
    }

    protected function makeGlobalBlock(): bool
    {
        return true;
    }

    protected function menuItemStylesValueConditions(array &$menuItemStyles): void
    {
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    abstract protected function getLogoComponent(BrizyComponent $brizySection): BrizyComponent;

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    abstract protected function getTargetMenuComponent(BrizyComponent $brizySection): BrizyComponent;

    abstract protected function getThemeMenuItemSelector(): array;

    function saveItAsAGlobalBlock(BrizyComponent $component): void
    {
        $position = '{"align":"top","top":0,"bottom":0}';
        $rules = '[{"type":1,"appliedFor":null,"entityType":"","entityValues":[]}]';

        try {
            Logger::instance()->info('Deleting existing global blocks');
            $this->brizyAPIClient->deleteAllGlobalBlocks();

            Logger::instance()->info('Creating new global block', [
                'component_json_length' => strlen(json_encode($component)),
                'position' => $position,
                'rules' => $rules
            ]);
            $this->brizyAPIClient->createGlobalBlock(json_encode($component), $position, $rules);

            Logger::instance()->info('Global block created successfully');
        } catch (\Exception $e) {
            Logger::instance()->error('Error managing global blocks', [
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);
        } catch (GuzzleException $e) {
            Logger::instance()->error('Error managing global blocks', $e->getMessage());
        }
    }

    abstract protected function getPropertiesIconMenuItem(): array;

    abstract protected function getThemeMenuItemMobileSelector(): array;

    abstract protected function getThemeParentMenuItemSelector(): array;

    abstract protected function getThemeSubMenuNotSelectedItemSelector(): array;

    abstract protected function getThemeSubMenuSelectedItemSelector(): array;

    abstract protected function getThemeSubMenuItemClassSelected(): array;

    abstract protected function getThemeSubMenuItemSelector(): array;

    abstract protected function getThemeSubMenuItemDropDownSelector(): array;

    abstract protected function getThemeMobileBtnSelector(): array;

    abstract protected function getThemeMobileNavSelector(): array;

    abstract public function getThemeSubMenuItemBGSelector(): array;

    abstract public function getThemeMenuItemPaddingSelector(): array;

    abstract public function getThemeMenuItemActiveSelector(): array;

    abstract public function getNotSelectedMenuItemBgSelector(): array;

    abstract public function getMenuItemBgSelector(): array;

    abstract public function getMenuHoverItemBgSelector(): array;
}
