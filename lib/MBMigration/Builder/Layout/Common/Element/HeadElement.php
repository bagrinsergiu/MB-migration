<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class HeadElement extends AbstractElement
{
    const CACHE_KEY = 'head';
    use Cacheable;
    use SectionStylesAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {

            $headStyles = $this->extractBlockBrowserData(
                $data->getMbSection()['sectionId'],
                $data->getFontFamilies(),
                $data->getDefaultFontFamily()
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
            $this->handleSectionStyles($elementContext, $this->browserPage);

            return $section;
        });
    }

    // region Menu methods
    protected function createMenu($menuList): array
    {
        $menuItems = $this->creatingMenuTree($menuList['list']);

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

            if ($item['landing'] == false) {
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
            ->set_imageFileName($imageLogo['imageFileName']);

        return $component;
    }

    /**
     * @param ElementContextInterface $data
     * @param BrizyComponent $component
     * @return BrizyComponent
     */
    private function buildMenuItemsAndSetTheMenuUid(
        ElementContextInterface $data,
        BrizyComponent $component,
        $headStyles
    ): BrizyComponent {
        $menuItems = $this->createMenu($data->getMenu());
        $menuComponentValue = $component->getValue();
        $menuComponentValue
            ->set('items', $menuItems)
            ->set_menuSelected($data->getMenu()['uid']);

        // apply menu styles
        foreach ($headStyles['menu'] as $field => $value) {
            $method = "set_{$field}";
            $menuComponentValue->$method($value);
        }

        return $component;
    }

    protected function extractBlockBrowserData($sectionId, $families, $defaultFamilies): array
    {
        $menuSectionStyles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => '[data-id="'.$sectionId.'"]',
                'styleProperties' => ['background-color', 'color', 'opacity', 'border-bottom-color'],
                'families' => $families,
                'defaultFamily' => $defaultFamilies,
            ]
        );

        $this->browserPage->screenshot("/project/var/log/test3.png");
        $menuStyles = $this->browserPage->evaluateScript(
            'brizy.getMenu',
            [
                'selector' => '[data-id="'.$sectionId.'"]',
                'families' => $families,
                'defaultFamily' => $defaultFamilies,
            ]
        );
        $this->browserPage->screenshot("/project/var/log/test4.png");

        return [
            'menu' => isset($menuStyles['data']) ? $menuStyles['data'] : [],
            'style' => isset($menuSectionStyles['data']) ? $menuSectionStyles['data'] : [],
        ];
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

}