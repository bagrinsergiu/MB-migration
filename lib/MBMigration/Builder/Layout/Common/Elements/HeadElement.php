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
    private FontsController $fontsController;

    public function __construct(
        $brizyKit,
        BrowserPageInterface $browserPage,
        BrizyAPI $brizyAPI,
        FontsController $fontsController
    ) {
        parent::__construct($brizyKit, $browserPage);

        $this->brizyAPIClient = $brizyAPI;
        $this->fontsController = $fontsController;
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $this->pageTDO = $data->getThemeContext()->getPageDTO();
        $this->themeContext = $data->getThemeContext();

        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {
            $this->basicHeadParams = array_merge($this->basicHeadParams, $this->headParams);
            $this->beforeTransformToItem($data);

            $this->fontHandle($data);

            $component = $this->internalTransformToItem($data);
            $this->generalSectionBehavior($data, $component);
            $this->afterTransformToItem($component);

            // save it as a global block
            $position = '{"align":"top","top":0,"bottom":0}';
            $rules = '[{"type":1,"appliedFor":null,"entityType":"","entityValues":[]}]';
            $this->brizyAPIClient->deleteAllGlobalBlocks();
            $this->brizyAPIClient->createGlobalBlock(json_encode($component), $position, $rules);

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
    private function setImageLogo(BrizyComponent $component, $headItem): BrizyComponent
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
    private function buildMenuItemsAndSetTheMenuUid(
        ElementContextInterface $data,
        BrizyComponent $component,
        $headStyles
    ): BrizyComponent {
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
    ): array {
        $hoverMenuItemStyles = [];
        $hoverMenuSubItemStyles = [];
        $menuSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '[data-id=\''.$sectionId.'\']',
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

            $this->fontsController->upLoadFont($fontName, $fontFamily);

            $families = FontsController::getFontsFamily()['kit'];
            $elementContext->getThemeContext()->setFamilies($families);
        }

        // -------------------------------------
        $menuItemStyles = $this->browserPage->evaluateScript('brizy.getMenuItem', [
            'itemSelector' => $menuItemSelector,
            'itemMobileSelector' => $itemMobileSelector,
            'itemBgSelector' => $this->getThemeMenuItemBgSelector(),
            'itemPaddingSelector' => $this->getThemeMenuItemPaddingSelector(),
            'itemMobileBtnSelector' => $this->getThemeMobileBtnSelector(),
            'itemMobileNavSelector' => $this->getThemeMobileNavSelector(),
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
            'hover' => false,
        ]);

        if ($this->browserPage->triggerEvent('hover', $menuItemSelector['selector'])) {
            $hoverMenuItemStyles = $this->browserPage->evaluateScript('brizy.getMenuItem', [
                'itemSelector' => $menuItemSelector,
                'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                'itemPaddingSelector' => $this->getThemeMenuItemPaddingSelector(),
                'families' => $families,
                'defaultFamily' => $defaultFamilies,
                'hover' => true,
            ]);
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
        $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', [
            'itemSelector' => $this->getThemeSubMenuNotSelectedItemSelector(),
            'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
            'families' => $families,
            'defaultFamily' => $defaultFamilies,
            'hover' => false,
        ]);

        if(isset($menuSubItemStyles['error'])) {
            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            $this->browserPage->getPageScreen('subNormal_1');

            $menuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', [
                'itemSelector' => $this->getThemeSubMenuNotSelectedItemSelector(),
                'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                'families' => $families,
                'defaultFamily' => $defaultFamilies,
                'hover' => false,
            ]);

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);
        }

        return $menuSubItemStyles['data'] ?? [];
    }

    protected function getHoverSubMenuStyle(): array
    {
        if ($this->browserPage->triggerEvent('hover', $this->getThemeParentMenuItemSelector()['selector'])) {

            $this->browserPage->getPageScreen(1);

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            $activeMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', [
                'itemSelector' => $this->getThemeSubMenuSelectedItemSelector(),
                'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                'families' => '',
                'defaultFamily' => [],
                'hover' => true,
            ]);

            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            $this->browserPage->getPageScreen('remove_node_1');

            if($this->browserPage->triggerEvent('hover', $this->getThemeSubMenuNotSelectedItemSelector()['selector'])){
                $hoverMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', [
                    'itemSelector' => $this->getThemeSubMenuNotSelectedItemSelector(),
                    'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                    'families' => '',
                    'defaultFamily' => [],
                    'hover' => true,
                ]);
            }

            $this->browserPage->getPageScreen(2);

            $hoverMenuSubItemStyles['data']['activeSubMenuColorHex'] = $activeMenuSubItemStyles['data']['activeSubMenuColorHex'];
            $hoverMenuSubItemStyles['data']['activeSubMenuColorOpacity'] = $activeMenuSubItemStyles['data']['activeSubMenuColorOpacity'];

        } else {

            $this->browserPage->evaluateScript('brizy.dom.removeNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);

            $hoverMenuSubItemStyles = $this->browserPage->evaluateScript('brizy.getSubMenuItem', [
                'itemSelector' => $this->getThemeSubMenuItemSelector(),
                'itemBgSelector' => $this->getThemeSubMenuItemBGSelector(),
                'families' => '',
                'defaultFamily' => [],
                'hover' => true,
            ]);

            $this->browserPage->evaluateScript('brizy.dom.addNodeClass', [
                'selector' => $this->getThemeSubMenuItemClassSelected()['selector'],
                'className' => $this->getThemeSubMenuItemClassSelected()['className'],
            ]);
        }

        return $hoverMenuSubItemStyles['data'] ?? [];
    }

    private function generalSectionBehavior(ElementContextInterface $data, BrizyComponent $section): void
    {
        $section->getItemWithDepth(0)->addCustomCSS('blockquote{margin:0;}'); //fix for table in richtext
        $section->getItemWithDepth(0)->addCustomCSS("@font-face {\n    font-family: 'Mono Social Icons Font';\n    src: url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.eot\");\n    src: url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.eot?#iefix\") format(\"embedded-opentype\"), url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.woff\") format(\"woff\"), url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.ttf\") format(\"truetype\"), url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.svg#MonoSocialIconsFont\") format(\"svg\");\n    src: url(\"https://assets.cloversites.com/fonts/icon-fonts/social/2/CloverMonoSocialIcons.ttf\") format(\"truetype\");\n    font-weight: normal;\n    font-style: normal\n}\n\n.socialIconSymbol {\n    font-family: 'Mono Social Icons Font';\n    font-size: 2em;\n    font-style: normal !important;\n}\n\n.text-content span.socialIconSymbol, .text-content a.socialIconSymbol {\n    line-height: .5em;\n    font-weight: 300\n}"); //fix for icons in embed code
    }

    private function fontHandle(ElementContextInterface $data): void
    {
        $fontController = $data->getThemeContext()->getFontsController();

        $RootListFontFamilyExtractor = new RootListFontFamilyExtractor($this->browserPage);

        $fontController->upLoadCustomFonts($RootListFontFamilyExtractor);

        $families = FontsController::getFontsFamily()['kit'];

        $data->getThemeContext()->setFamilies($families);
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

    abstract protected function getPropertiesIconMenuItem(): array;

    abstract protected function getThemeMenuItemMobileSelector(): array;

    abstract protected function getThemeParentMenuItemSelector(): array;

    abstract protected function getThemeSubMenuNotSelectedItemSelector(): array;

    abstract protected function getThemeSubMenuSelectedItemSelector(): array;

    abstract protected function getThemeSubMenuItemClassSelected(): array;

    abstract protected function getThemeSubMenuItemSelector(): array;

    abstract protected function getThemeMobileBtnSelector(): array;

    abstract protected function getThemeMobileNavSelector(): array;

    abstract public function getThemeSubMenuItemBGSelector(): array;

    abstract public function getThemeMenuItemBgSelector(): array;

    abstract public function getThemeMenuItemPaddingSelector(): array;
}
