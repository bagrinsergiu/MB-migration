<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Utils\ColorConverter;

class Head extends AbstractElement
{
    const CACHE_KEY = 'head';
    use Cacheable;

    public function transformToItem(ElementDataInterface $data): BrizyComponent
    {
        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {

            $headStyles = $this->extractBlockBrowserData($data->getMbSection()['sectionId']);

            $section = new BrizyComponent(json_decode($this->brizyKit['main'], true));

            // reset color palette
            $section->getItemValueWithDepth(0)->set_bgColorPalette('');

            // build menu items and set the menu uid
            $this->buildMenuItemsAndSetTheMenuUid($data, $section, $headStyles);
            $this->setImageLogo($section, $data->getMbSection());
            $this->setColorBackground($section, $headStyles['style']);

            return $section;
        });
    }

    // region Menu methods
    private function creatingMenu($jsonItemKit, $menuList): array
    {
        $menuItemKit = json_decode($jsonItemKit, true);
        $brizyComponent = new BrizyComponent($menuItemKit);
        $menuItems = $this->creatingMenuTree($menuList['list'], $brizyComponent);

        return $menuItems;
    }

    private function creatingMenuTree($menuList, BrizyComponent $cloneBlockMenu): array
    {
        $treeMenu = [];
        foreach ($menuList as $item) {
            $blockMenu = clone $cloneBlockMenu;
            $blockMenu->getValue()->set_itemId($item['collection']);
            $blockMenu->getValue()->set_title($item['name']);
            $blockMenu->getValue()->set_url($item['slug'] == 'home' ? '/' : $item['slug']);
            $blockMenu->getValue()->set_id(bin2hex(random_bytes(16)));

            $menuSubItems = $this->creatingMenuTree($item['child'], $cloneBlockMenu);
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
            $component->getItemValueWithDepth(0, 0, 0, 0, 0)->set_imageHeight($imageLogo['imageHeight']);
            $component->getItemValueWithDepth(0, 0, 0, 0, 0)->set_imageWidth($imageLogo['imageWidth']);
        }

        $component->getItemValueWithDepth(0, 0, 0, 0, 0)->set_imageSrc($imageLogo['imageSrc']);
        $component->getItemValueWithDepth(0, 0, 0, 0, 0)->set_imageFileName($imageLogo['imageFileName']);

        return $component;
    }

    /**
     * @param BrizyComponent $component
     * @param $styles
     * @return BrizyComponent
     */
    private function setColorBackground(BrizyComponent $component, $styles): BrizyComponent
    {
        $rgbaToHex = ColorConverter::rgba2hex($styles['background-color']);
        $opacity = ColorConverter::rgba2opacity($styles['background-color']);

        $component->getItemValueWithDepth(0)->set_bgColorHex($rgbaToHex)->set_bgColorOpacity($opacity);

        return $component;
    }


    /**
     * @param ElementDataInterface $data
     * @param BrizyComponent $component
     * @return BrizyComponent
     */
    private function buildMenuItemsAndSetTheMenuUid(
        ElementDataInterface $data,
        BrizyComponent $component,
        $headStyles
    ): BrizyComponent {
        $menuItems = $this->creatingMenu($this->brizyKit['item'], $data->getMenu());
        $menuComponentValue = $component->getItemValueWithDepth(0, 0, 1, 0, 0);
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

    protected function extractBlockBrowserData($sectionId): array
    {
        return array_merge(
            $this->browserPage->runScript(
                'getMenuStyle.js',
                [
                    'selector' => '[data-id="'.$sectionId.'"]',
                    'families' => '[]',

                ]
            ),
            $this->browserPage->runScript(
                'StylesExtractor.js',
                [
                    'selector' => '[data-id="'.$sectionId.'"]',
                    'styleProperties' => json_encode(['background-color', 'opacity', 'border-bottom-color']),
                ]
            )
        );
    }
}