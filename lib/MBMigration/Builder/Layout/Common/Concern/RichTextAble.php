<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use DOMElement;
use DOMException;
use Exception;
use MBMigration\Builder\Media\MediaController;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;
use DOMDocument;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyEmbedCodeComponent;
use MBMigration\Builder\BrizyComponent\BrizyImageComponent;
use MBMigration\Builder\BrizyComponent\BrizyWrapperComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Layer\Brizy\BrizyAPI;

trait RichTextAble
{
    use TextsExtractorAware;
    use CssPropertyExtractorAware;
    use GeneratorID;

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextHead(ElementContextInterface $data, BrowserPageInterface $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        $mbSectionItem['head'] = $this->sortItems($mbSectionItem['head']);

        foreach ((array)$mbSectionItem['head'] as $mbSectionItem) {

            if ($mbSectionItem['item_type'] == 'title' && !$showHeader) {
                continue;
            }
            if ($mbSectionItem['item_type'] == 'body' && !$showBody) {
                continue;
            }

            $elementContext = $data->instanceWithMBSection($mbSectionItem);
            try {
                $this->handleRichTextItem(
                    $elementContext,
                    $browserPage
                );
            } catch (Exception $e) {
                Logger::instance()->info($e->getMessage());
            }
        }

        return $brizySection;
    }

    protected function handleRichTextHeadFromItems(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
        callable                $acllback = null
    ): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ((array)$mbSectionItem['items'] as $mbSectionItem) {

            if ($mbSectionItem['item_type'] == 'title' && !$showHeader) {
                continue;
            }
            if ($mbSectionItem['item_type'] == 'body' && !$showBody) {
                continue;
            }

            $elementContext = $data->instanceWithMBSection($mbSectionItem);

            try {
                if (!is_null($acllback)) {
                    $acllback($elementContext);
                } else {
                    $this->handleRichTextItem(
                        $elementContext,
                        $browserPage
                    );
                }
            } catch (Exception $e) {
                Logger::instance()->info($e->getMessage());
            }


        }

        return $brizySection;
    }

    /**
     * Process and add all items the same brizy section
     */
    protected function handleRichTextItems(ElementContextInterface $data, BrowserPageInterface $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $sectionCategory = $mbSectionItem['category'];
        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);
        $showButtons = $this->canShowButtons($mbSectionItem);

        // sort items
        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ((array)$mbSectionItem['items'] as $mbItem) {

            if ($mbItem['category'] == $sectionCategory && isset($mbItem['item_type'])) {
                if ($mbItem['item_type'] == 'title' && !$showHeader) {
                    continue;
                }
                if ($mbItem['item_type'] == 'body' && !$showBody) {
                    continue;
                }
                if ($mbItem['item_type'] == 'button' && !$showButtons) {
                    continue;
                }
            }

            $elementContext = $data->instanceWithMBSection($mbItem);
            try {
                $this->handleRichTextItem(
                    $elementContext,
                    $browserPage
                );
            } catch (Exception $e) {
                Logger::instance()->info($e->getMessage());
            }
        }

        return $brizySection;
    }

    protected function handleOnlyRichTextItems(ElementContextInterface $data, BrowserPageInterface $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $sectionCategory = $mbSectionItem['category'];
        $showHeader = $this->canShowHeader($mbSectionItem);
        $showBody = $this->canShowBody($mbSectionItem);

        // sort items
        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        foreach ((array)$mbSectionItem['items'] as $mbItem) {

            if ($mbItem['category'] == $sectionCategory && isset($mbItem['item_type'])) {
                if ($mbItem['item_type'] == 'title' && !$showHeader) {
                    continue;
                }
                if ($mbItem['item_type'] == 'body' && !$showBody) {
                    continue;
                }

            }

            $elementContext = $data->instanceWithMBSection($mbItem);
            try {
                $this->handleOnlyRichTextItem(
                    $elementContext,
                    $browserPage
                );
            } catch (Exception $e) {
                Logger::instance()->info($e->getMessage());
            }
        }

        return $brizySection;
    }

    /**
     * Process single rich text item and place it the brizy section
     */
    protected function  handleRichTextItem(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
                                $selector = null,
                                $settings = [],
                                $imageOptions = []
    ): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $default_fonts = $data->getDefaultFontFamily();
        $brizyComponent = $data->getBrizySection();
        $brizyAPI = $data->getBrizyAPI();
        $projectID = $data->getThemeContext()->getProjectId();
        $customSettings = $data->getCustomSettingsBrizyElement();

        if ($mbSectionItem['category'] === 'button') {
            $buttonStyle = $this->handleButtonStyle($mbSectionItem);
        }

        $clickableIconStyle = $this->handleClickableIconStyle($mbSectionItem);

        switch ($mbSectionItem['category']) {
            case 'button':
                // Buttons are handled elsewhere

            case 'text':
                $brizyComponent = $this->handleTextItem(
                    $mbSectionItem,
                    $brizyComponent,
                    $browserPage,
                    $brizyAPI,
                    $projectID,
                    $families,
                    $default_fonts,
                    $data->getThemeContext()->getUrlMap(),
                    $selector,
                    $settings,
                    $buttonStyle ?? [],
                    $clickableIconStyle,
                    $customSettings
                );
                break;
            case 'photo':
                $this->handlePhotoItem(
                    $mbSectionItem['sectionId'] ?? $mbSectionItem['id'],
                    $mbSectionItem,
                    $brizyComponent,
                    $browserPage,
                    $imageOptions
                );
                break;
        }

        return $brizyComponent;
    }

    protected function handleClickableIconStyle($mbSectionItem): array
    {
        try {
            $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];

            $iconStyleSelector = '[data-id="' . $sectionId . '"] a > span > span.socialIconSymbol';

            $stylesNormal = $this->getDomElementStyles(
                $iconStyleSelector,
                [
                    'background-color',
                    'border-bottom-style',
                    'border-bottom-color',
                    'border-bottom-width',
                ],
                $this->browserPage
            );

            $iconHoverSelector = '[data-id="' . $sectionId . '"] a';

            if ($this->browserPage->triggerEvent('hover', $iconHoverSelector)) {
                $stylesHover = $this->getDomElementStyles(
                    $iconStyleSelector,
                    [
                        'background-color',
                        'border-bottom-style',
                        'border-bottom-color',
                        'border-bottom-width',
                    ],
                    $this->browserPage
                );
            }

            return [
                'normal' => $this->convertStyles($stylesNormal),
                'hover' => $this->convertStyles($stylesHover ?? [])
            ];
        } catch (Exception $e) {

            return [];
        }
    }

    protected function handleButtonStyle($mbSectionItem): array
    {
        try {
            $this->browserPage->triggerEvent('hover');
            $this->browserPage->getPageScreen("before_button");

            $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];

            $buttonSelector = $this->selectorPrefix() . '[data-id="' . $sectionId . '"]';

            $stylesNormalD = $this->getDomElementStyles(
                $buttonSelector,
                [
                    'color',
                    'background-color',
                    'border-bottom-style',
                    'border-bottom-color',
                    'border-bottom-width',
                ],
                $this->browserPage
            );

            $stylesNormal['color'] = ColorConverter::rgba2hex($stylesNormalD['color']);
            $stylesNormal['color-opacity'] = ColorConverter::rgba2opacity($stylesNormalD['color']);
            $stylesNormal['background-color-opacity'] = ColorConverter::rgba2opacity($stylesNormalD['background-color']);
            $stylesNormal['background-color'] = ColorConverter::rgba2hex($stylesNormalD['background-color']);
            $stylesNormal['border-bottom-color'] = ColorConverter::rgba2hex($stylesNormalD['border-bottom-color']);
            $stylesNormal['border-bottom-color-opacity'] = ColorConverter::rgba2opacity($stylesNormalD['border-bottom-color']);

            if ($this->browserPage->triggerEvent('hover', $buttonSelector)) {
                $this->browserPage->getPageScreen("hover_button");

                $stylesHoverD = $this->getDomElementStyles(
                    $buttonSelector,
                    [
                        'color',
                        'background-color',
                        'border-bottom-style',
                        'border-bottom-color',
                        'border-bottom-width',

                    ],
                    $this->browserPage
                );

                $stylesHover['color'] = ColorConverter::rgba2hex($stylesHoverD['color']);
                $stylesHover['color-opacity'] = ColorConverter::rgba2opacity($stylesHoverD['color']);
                $stylesHover['background-color-opacity'] = ColorConverter::rgba2opacity($stylesHoverD['background-color']);
                $stylesHover['background-color'] = ColorConverter::rgba2hex($stylesHoverD['background-color']);
                $stylesHover['border-bottom-color'] = ColorConverter::rgba2hex($stylesHoverD['border-bottom-color']);
                $stylesHover['border-bottom-color-opacity'] = ColorConverter::rgba2opacity($stylesHoverD['border-bottom-color']);
            }

            return [
                'normal' => $stylesNormal,
                'hover' => $stylesHover ?? []
            ];
        } catch (Exception $e) {

            return [];
        }
    }

    protected function selectorPrefix(): string
    {
        return '';
    }

    private function convertStyles(array $styles): array
    {
        foreach ($styles as $key => $value) {
            // Only convert color-related properties (background-color, border-color, etc.)
            // Leave other properties unchanged (border-style, border-width, etc.)
            if (stripos($key, 'color') !== false) {
                $styles[$key] = ColorConverter::rgba2hex($value);
                $styles[$key . '-opacity'] = ColorConverter::rgba2opacity($value);
            }
            // For non-color properties (like 'border-bottom-style': 'solid', 'border-bottom-width': '2px')
            // the value remains unchanged
        }

        return $styles;
    }

    protected function handleOnlyRichTextItem(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
                                $selector = null,
                                $settings = []
    ): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $default_fonts = $data->getDefaultFontFamily();
        $brizyComponent = $data->getBrizySection();
        $projectID = $data->getThemeContext()->getProjectId();
        $customSettings = $data->getCustomSettingsBrizyElement();

        if ($mbSectionItem['category'] === 'button') {
            $buttonStyle = $this->handleButtonStyle($mbSectionItem);
        }

        $iconClikStyle = $this->handleClickableIconStyle($mbSectionItem);

        if ($mbSectionItem['category'] == 'text') {
            $brizyComponent = $this->handleTextItem(
                $mbSectionItem,
                $brizyComponent,
                $browserPage,
                $data->getBrizyAPI(),
                $projectID,
                $families,
                $default_fonts,
                $data->getThemeContext()->getUrlMap(),
                $selector,
                $settings,
                $buttonStyle ?? [],
                $iconClikStyle,
                $customSettings
            );
        }

        return $brizyComponent;
    }

    private function handleTextItem(
        $mbSectionItem,
        BrizyComponent $brizySection,
        BrowserPageInterface $browserPage,
        BrizyAPI $brizyAPI,
        int $projectID,
        $families = [],
        $defaultFont = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
        $urlMap = [],
        $selector = null,
        $settings = [],
        $buttonStyle = [],
        $iconClikStyle = [],
        $customSettings = []
    ): BrizyComponent
    {
        $sectionId = $mbSectionItem['sectionId'] ?? $mbSectionItem['id'];
        $richTextBrowserData = $this->extractTexts($selector ?? '[data-id="' . $sectionId . '"]', $browserPage, $families, $defaultFont, $urlMap);
        $styles = $this->getDomElementStyles(
            $selector ?? '[data-id="' . $sectionId . '"]',
            ['text-align', 'font-family'],
            $browserPage,
            $families,
            $defaultFont
        );
        $embeddedElements = $this->findEmbeddedElements($mbSectionItem['content']);
        $embeddIndex = 0;

        // Track which component types have been applied once (for 'implement' => 'one')
        $appliedOnce = [];

        // Track indices for each component type (for 'implement' => integer)
        $componentIndices = [];

        try {
            foreach ($richTextBrowserData as $i => $textItem) {
                switch ($textItem['type']) {
                    case 'EmbedCode':
                        //wrapper
                        if (!isset($embeddedElements[$embeddIndex])) break;

                        $embedCode = $embeddedElements[$embeddIndex++];
                        $brizyEmbedCodeComponent = new BrizyEmbedCodeComponent($embedCode['embed']);
                        $cssClass = 'custom-align-' . random_int(0, 10000);
                        $brizyEmbedCodeComponent->getValue()->set_customClassName($cssClass);
                        $brizyEmbedCodeComponent->getItemValueWithDepth(0)->set_overflow('on');
                        $brizyEmbedCodeComponent->getItemValueWithDepth(0)->set_mobileOverflow('off');
                        $brizyEmbedCodeComponent->getItemValueWithDepth(0)->set_customCSS(
                            ".{$cssClass} { text-align: {$styles['text-align']}; font-family: {$styles['font-family']}; }
.{$cssClass} .brz-embed-content > div:has(.embedded-paste iframe) {display: flex; justify-content:{$styles['text-align']}}"
                        );

                        // Apply custom settings if available
                        if (!empty($customSettings)) {
                            $hasAllKey = isset($customSettings['All']);

                            if ($hasAllKey || isset($customSettings['EmbedCode'])) {
                                if (!isset($componentIndices['EmbedCode'])) {
                                    $componentIndices['EmbedCode'] = 0;
                                }

                                $targetKey = $hasAllKey ? 'All' : 'EmbedCode';
                                $this->applyCustomSettings(
                                    $brizyEmbedCodeComponent,
                                    $customSettings,
                                    $targetKey,
                                    $appliedOnce,
                                    $componentIndices['EmbedCode']
                                );

                                $componentIndices['EmbedCode']++;
                            }
                        }

                        $brizySection->getValue()->add_items([$brizyEmbedCodeComponent]);
                        break;
                    case 'Cloneable':
                        foreach ($textItem['value']['items'] as &$clonableItem) {
//                            if (empty($clonableItem['value']['code'])) {
                            switch ($clonableItem['type']) {
                                case 'Icon':
                                    try {
                                        $customIconUploadResult = $brizyAPI->uploadCustomIcon(
                                            $projectID,
                                            $clonableItem['value']['filename'],
                                            $clonableItem['value']['code']
                                        );

                                        if (!empty($customIconUploadResult['filename']) && !empty($customIconUploadResult['uid'])) {
                                            $clonableItem['value']['name'] = $customIconUploadResult['uid'];
                                            $clonableItem['value']['filename'] = $customIconUploadResult['filename'];
                                        }

                                        if (!empty($iconClikStyle['hover']) && !empty( $iconClikStyle['normal'] )){
                                            $clonableItem['value']['borderStyle'] = $iconClikStyle['normal']['border-bottom-style'];

                                            $clonableItem['value']['hoverBgColorHex'] = $iconClikStyle['hover']['background-color'];
                                            $clonableItem['value']['hoverBgColorOpacity'] = $iconClikStyle['hover']['background-color-opacity'];
                                            $clonableItem['value']['hoverBgColorPalette'] = '';

                                            $clonableItem['value']['hoverBorderColorHex'] = $iconClikStyle['hover']['background-color-opacity'];
                                            $clonableItem['value']['hoverBorderColorOpacity'] = $iconClikStyle['hover']['background-color-opacity'];
                                            $clonableItem['value']['hoverBorderColorPalette'] = '';
                                        }

                                        unset($clonableItem['value']['code']);

                                    } catch (Exception $e) {

                                        $ddd = $e->getMessage();
                                    }
                                    break;
                                case 'Button':
                                    if (!empty($buttonStyle['hover']) && !empty($buttonStyle['normal'])) {

                                        $clonableItem['value']['colorHex'] = $buttonStyle['normal']['color'];
                                        $clonableItem['value']['colorOpacity'] = $buttonStyle['normal']['color-opacity'];
                                        $clonableItem['value']['colorPalette'] = '';

                                        $clonableItem['value']['bgColorHex'] = $buttonStyle['normal']['background-color'];
                                        $clonableItem['value']['bgColorOpacity'] = $buttonStyle['normal']['background-color-opacity'];
                                        $clonableItem['value']['bgColorPalette'] = '';

                                        $clonableItem['value']['borderStyle'] = $buttonStyle['normal']['border-bottom-style'];
                                        $clonableItem['value']['borderColorHex'] = $buttonStyle['normal']['border-bottom-color'];
                                        $clonableItem['value']['borderColorOpacity'] = $buttonStyle['normal']['border-bottom-color-opacity'];
                                        $clonableItem['value']['borderColorPalette'] = '';

                                        $clonableItem['value']['hoverColorHex'] = $buttonStyle['hover']['color'];
                                        $clonableItem['value']['hoverColorOpacity'] = $buttonStyle['hover']['color-opacity'];
                                        $clonableItem['value']['hoverColorPalette'] = '';

                                        $clonableItem['value']['hoverBgColorHex'] = $buttonStyle['hover']['background-color'];
                                        $clonableItem['value']['hoverBgColorOpacity'] = $buttonStyle['hover']['background-color-opacity'];
                                        $clonableItem['value']['hoverBgColorPalette'] = '';

                                        $clonableItem['value']['hoverBorderColorHex'] = $buttonStyle['hover']['border-bottom-color-opacity'];
                                        $clonableItem['value']['hoverBorderColorOpacity'] = $buttonStyle['hover']['border-bottom-color-opacity'];
                                        $clonableItem['value']['hoverBorderColorPalette'] = '';
                                    }
                                    break;
                            }
//                        }
                        }

                        $brzCloneableComponent = new BrizyComponent($textItem);

                        $brzCloneableComponent->addMargin(0, 0, 0, 0);

                        // Apply custom settings if available
                        if (!empty($customSettings)) {
                            $hasAllKey = isset($customSettings['All']);

                            if ($hasAllKey || isset($customSettings['Cloneable'])) {
                                if (!isset($componentIndices['Cloneable'])) {
                                    $componentIndices['Cloneable'] = 0;
                                }

                                $targetKey = $hasAllKey ? 'All' : 'Cloneable';
                                $this->applyCustomSettings(
                                    $brzCloneableComponent,
                                    $customSettings,
                                    $targetKey,
                                    $appliedOnce,
                                    $componentIndices['Cloneable']
                                );

                                $componentIndices['Cloneable']++;
                            }
                        }

                        $brizySection->getValue()->add_items([$brzCloneableComponent]);
                        break;
                    case 'Wrapper':
                        //wrapper--richText
                        $brzTextComponent = new BrizyComponent($textItem);

//                        $brzTextComponent->addMargin(0, 0, 0, 0);

                        if ($brzTextComponent->getItemWithDepth(0)->getType() === 'Image') {
                            $imageSrc = $brzTextComponent->getItemWithDepth(0)->getValue()->get_imageSrc();
                            if ($this->checkURL($imageSrc)) {
                                $uploadResult = $brizyAPI->createMedia($imageSrc);

                                if (!$uploadResult['error']) {
                                    $uploadImage = json_decode($uploadResult['body'], true);
                                    $size = getimagesize($imageSrc);
                                    $brzTextComponent->getItemWithDepth(0)->getValue()
                                        ->set_imageFileName($uploadImage['filename'])
                                        ->set_imageSrc($uploadImage['name'])
                                        ->set_imageWidth($size[0])
                                        ->set_imageHeight($size[1]);
                                }
                            }
                        }

                        // Apply custom settings if available
                        if (!empty($customSettings)) {
                            // Check if "All" key is used
                            $hasAllKey = isset($customSettings['All']);

                            // Apply settings to Wrapper itself
                            if ($hasAllKey || isset($customSettings['Wrapper'])) {
                                if (!isset($componentIndices['Wrapper'])) {
                                    $componentIndices['Wrapper'] = 0;
                                }

                                $targetKey = $hasAllKey ? 'All' : 'Wrapper';
                                $this->applyCustomSettings(
                                    $brzTextComponent,
                                    $customSettings,
                                    $targetKey,
                                    $appliedOnce,
                                    $componentIndices['Wrapper']
                                );

                                $componentIndices['Wrapper']++;
                            }

                            // Apply settings to RichText inside Wrapper ONLY if not using "All"
                            // When "All" is used, we don't apply to nested elements
                            if (!$hasAllKey && $brzTextComponent->getItemWithDepth(0)->getType() === 'RichText') {
                                if (!isset($componentIndices['RichText'])) {
                                    $componentIndices['RichText'] = 0;
                                }

                                $this->applyCustomSettings(
                                    $brzTextComponent->getItemWithDepth(0),
                                    $customSettings,
                                    'RichText',
                                    $appliedOnce,
                                    $componentIndices['RichText']
                                );

                                $componentIndices['RichText']++;
                            }
                        }

                        if (empty($settings['setEmptyText']) || $settings['setEmptyText'] === false) {
                            $brizySection->getValue()->add_items([$brzTextComponent]);
                        } elseif ($settings['setEmptyText'] === true) {
                            if ($this->hasAnyTagsInsidePTag($textItem['value']['items'][0]['value']['text'])) {
                                $brizySection->getValue()->add_items([$brzTextComponent]);
                            }
                        }
                        break;
                }
            }
        } catch (Exception $e) {
            Logger::instance()->info($e->getMessage());
        }

        return $brizySection;
    }

    private function handlePhotoItem(
        $mbSectionItemId,
        $mbSectionItem,
        BrizyComponent $brizyComponent,
        BrowserPageInterface $browserPage,
        $imageOptions = [],
        $position = null
    ): BrizyComponent
    {
        if ($brizyComponent->getType() !== 'Image') {
            return $this->handlePhotoAddNewItem(
                $mbSectionItemId,
                $mbSectionItem,
                $brizyComponent,
                $browserPage,
                $imageOptions,
                $position
            );
        } else {
            if (!empty($mbSectionItem['content'])) {
                $selectorImageSizes = '[data-id="' . $mbSectionItemId . '"] .photo-container img';
                $sizes = $this->handleSizeToInt(
                    $this->getDomElementSizes(
                        $selectorImageSizes,
                        $browserPage
                    )
                );
                $sizeUnit = 'px';

                $brizyComponent->getValue()
                    ->set_imageFileName($mbSectionItem['imageFileName'])
                    ->set_imageSrc($mbSectionItem['content']);

                if (!empty($sizes['width']) && !empty($sizes['height'])) {
                    if (strpos($sizes['width'], '%') !== false) {
                        $selectorImageSizes = '[data-id="' . $mbSectionItemId . '"] .photo-container';
                        $sizes = $this->getDomElementSizes($selectorImageSizes, $browserPage);
                    }

                    $brizyComponent->getValue()
                        ->set_width((int)$sizes['width'])
                        ->set_tabletWidth((int)$sizes['width'])
                        ->set_mobileWidth((int)$sizes['width'])
                        ->set_height((int)$sizes['height'])
                        ->set_tabletHeight((int)$sizes['height'])
                        ->set_mobileHeight((int)$sizes['height'])
                        ->set_imageWidth($mbSectionItem['settings']['image']['width'])
                        ->set_imageHeight($mbSectionItem['settings']['image']['height'])
                        ->set_widthSuffix($sizeUnit)
                        ->set_heightSuffix($sizeUnit)
                        ->set_tabletHeightSuffix($sizeUnit)
                        ->set_mobileSizeType('original')
                        ->set_mobileWidthSuffix($sizeUnit)
                        ->set_mobileHeightSuffix($sizeUnit);
                }

                foreach ($imageOptions as $key => $value) {
                    $method = 'set_' . $key;
                    $brizyComponent->getValue()->$method($value);
                }

                $this->handleLink(
                    $mbSectionItem,
                    $brizyComponent,
                    '[data-id="' . $mbSectionItemId . '"] div.photo-container a',
                    $browserPage);
            }

            return $brizyComponent;
        }
    }

    private function handlePhotoAddNewItem(
        $mbSectionItemId,
        $mbSectionItem,
        BrizyComponent $brizyComponent,
        BrowserPageInterface $browserPage,
        $options = [],
        $index = null
    ): BrizyComponent
    {
        if (!empty($mbSectionItem['content'])) {
            // Create new Image element according to aiRules
            $image = new BrizyImageComponent();
            $wrapperImage = new BrizyWrapperComponent('wrapper--image');

            $selectorImageSizes = '[data-id="' . $mbSectionItemId . '"] .photo-container img';
            $sizes = $this->handleSizeToInt($this->getDomElementSizes($selectorImageSizes, $browserPage));
            $sizeUnit = 'px';

            // Set required Image element properties according to aiRules
            $image->getValue()
                ->set_imageFileName($mbSectionItem['imageFileName'])
                ->set_imageSrc($mbSectionItem['content']);

            // Set image extension from filename or default to png
            if (!empty($mbSectionItem['imageFileName'])) {
                $extension = pathinfo($mbSectionItem['imageFileName'], PATHINFO_EXTENSION);
                if ($extension) {
                    $image->getValue()->set_imageExtension($extension);
                }
            }

            if (!empty($sizes['width']) && !empty($sizes['height'])) {
                if (strpos($sizes['width'], '%') !== false) {
                    $selectorImageSizes = '[data-id="' . $mbSectionItemId . '"] .photo-container';
                    $sizes = $this->getDomElementSizes($selectorImageSizes, $browserPage);
                }

                // Set display dimensions
                $image->getValue()
                    ->set_width((int)$sizes['width'])
                    ->set_tabletWidth((int)$sizes['width'])
                    ->set_mobileWidth((int)$sizes['width'])
                    ->set_height((int)$sizes['height'])
                    ->set_tabletHeight((int)$sizes['height'])
                    ->set_mobileHeight((int)$sizes['height'])
                    ->set_widthSuffix($sizeUnit)
                    ->set_heightSuffix($sizeUnit)
                    ->set_tabletHeightSuffix($sizeUnit)
                    ->set_mobileSizeType('original')
                    ->set_mobileWidthSuffix($sizeUnit)
                    ->set_mobileHeightSuffix($sizeUnit);

                // Set original image dimensions if available
                if (!empty($mbSectionItem['settings']['image']['width'])) {
                    $image->getValue()->set_imageWidth($mbSectionItem['settings']['image']['width']);
                }
                if (!empty($mbSectionItem['settings']['image']['height'])) {
                    $image->getValue()->set_imageHeight($mbSectionItem['settings']['image']['height']);
                }
            }

            foreach ($options as $key => $value) {
                $method = 'set_' . $key;
                $image->getValue()->$method($value);
            }

            // Handle link properties on the image element
            $this->handleLink(
                $mbSectionItem,
                $image,
                '[data-id="' . $mbSectionItemId . '"] div.photo-container a',
                $browserPage);

            // Wrap the image in wrapper--image according to aiRules pattern
            $wrapperImage->getValue()->add_items([$image]);

            // Add the wrapped image to the brizy component at the specified index
            $brizyComponent->getValue()->add_items([$wrapperImage], $index);
        }

        return $brizyComponent;
    }

    private function handleLink($mbSectionItem, $brizyComponent, $selector, $browserPage)
    {
        if (!empty($mbSectionItem['link'])) {
            if ($mbSectionItem['new_window']) {
                $mbSectionItem['new_window'] = 'on';
            } else {
                try {
                    $mbSectionItem['new_window'] = $this->openNewTab(
                        $this->getNodeAttribute(
                            $browserPage,
                            $selector,
                            'target'
                        )
                    );
                } catch (Exception $e) {
                    $mbSectionItem['new_window'] = 'on';
                }
            }

            if ($this->findTag($mbSectionItem['link'], 'iframe')) {
                $popupFromKit = $this->globalBrizyKit['popup']['popup--embedCode'];
                $popupSection = new BrizyComponent(json_decode($popupFromKit, true));

                $popupUid = $this->generateUniqueId(12);

                $attribute = [
                    'style' => "position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                ];

                $remove = [
                    'width', 'height'
                ];

                $iframe = $this->setAttributeInToElement($mbSectionItem['link'], 'iframe', $attribute, $remove);

                $attribute = [
                    'style' => "position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden;"
                ];

                $iframeUpdated = $this->putInsideTheBlock('div', $iframe, $attribute);

                $popupSection->getValue()
                    ->set_popupId($popupUid);
                $popupSection->getItemWithDepth(0, 0, 0, 0)->getValue()
                    ->set_code($iframeUpdated);

                $external = [
                    'linkType' => 'popup',
                    'linkPopup' => $popupUid,
                    'linkExternalBlank' => $mbSectionItem['new_window'],
                    'popups' => [$popupSection],
                ];

            } else {
                switch ($this->detectLinkType($mbSectionItem['link'])) {
                    case 'mail':
                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => 'mailto:' . $mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                        break;
                    case 'phone':
                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => 'tel:' . $mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                        break;
                    case 'string':
                    case 'link':
                        if (MediaController::isDoc($mbSectionItem['link'])) {
                            $mbSectionItem['link'] = MediaController::getURLDoc($mbSectionItem['link']);
                        } else if (MediaController::isVideo($mbSectionItem['link'])) {
                            $mbSectionItem['link'] = MediaController::getURLDoc($mbSectionItem['link']);
                        }

                        $slash = $this->processURL($mbSectionItem['link']);

                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => $slash . $mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                        break;
                    default:
                        $slash = $this->processURL($mbSectionItem['link']);

                        $external = [
                            'linkType' => 'external',
                            'linkExternal' => $slash . $mbSectionItem['link'],
                            'linkExternalBlank' => $mbSectionItem['new_window']
                        ];
                }
            }

            foreach ($external as $key => $value) {
                $method = 'set_' . $key;
                $brizyComponent->getValue()
                    ->$method($value);
            }
        }
    }

    private function detectLinkType($link): string
    {
        if (filter_var($link, FILTER_VALIDATE_EMAIL)) {
            return 'mail';
        }
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            return 'link';
        }
        if ($this->checkPhoneNumber($link)) {
            return 'phone';
        }

        return 'string';
    }

    private function processURL($url): string
    {
        $urlComponents = parse_url($url);

        $hasHost = isset($urlComponents['host']);
        $isValidScheme = isset($urlComponents['scheme']) && ($urlComponents['scheme'] === 'https' || $urlComponents['scheme'] === 'http');

        if ($hasHost || $isValidScheme) {
            return '';
        } else {
            return '/';
        }
    }

    public function checkPhoneNumber($str): bool
    {
        if (!preg_match("/^(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\$/", $str)) {

            return false;
        }

        $number = preg_replace('/[^0-9]/', '', $str);

        if (ctype_digit($number)) {

            return true;
        } else {

            return false;
        }
    }

    public function checkURL($str): bool
    {
        if (filter_var($str, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $parsed = parse_url($str);
        if (!isset($parsed['scheme']) || !in_array($parsed['scheme'], ['http', 'https'], true)) {
            return false;
        }

        return true;
    }

    private function findEmbeddedElements($html): array
    {
        $dom = new DOMDocument();
        $dom->loadHTML(
            "<!DOCTYPE html><html><head><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"></head><body>{$html}</body></html>",
            LIBXML_NOWARNING | LIBXML_BIGLINES | LIBXML_NOBLANKS | LIBXML_NONET | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_PARSEHUGE
        );

        $iframes = $dom->getElementsByTagName('iframe');

        foreach ($iframes as $iframe) {
            $width = $iframe->getAttribute('width');
            $height = $iframe->getAttribute('height');

            if (!empty($width) && !empty($height)) {
                $iframe->setAttribute('style', "max-width: {$width}px; min-width: {$width}px; max-height: {$height}px; width: 100%;");
            }
        }
        $result = [];
        $divs = $dom->getElementsByTagName('div');
        foreach ($divs as $div) {
            if ($div->hasAttribute('class') && $div->getAttribute('class') === 'embedded-paste') {
                $dataSrc = $div->getAttribute('data-src');
                preg_match('/text-align:\s*([^;]+)/', $div->getAttribute('style'), $matches);
                $escapedDataSrc = str_replace('"', '\\"', $dataSrc);
                $div->setAttribute('data-src', $escapedDataSrc);

                $result[] = [
                    'embed' => $dom->saveHTML($div),
                    'text-align' => $matches[1] ?? 'left'
                ];
            }
        }

        return $result;
    }

    private function findTag($html, $name): bool
    {
        $pattern = '/<\s*' . preg_quote($name, '/') . '\b[^>]*>(.*?)<\s*\/\s*' . preg_quote($name, '/') . '\s*>/i';

        if (preg_match($pattern, $html)) {
            return true;
        }

        return false;
    }

    private function setAttributeInToElement($html, $elementName, array $attributes, array $attributesToRemove = [])
    {
        $dom = new DOMDocument();
        $dom->loadHTML(
            $html,
            LIBXML_NOWARNING | LIBXML_BIGLINES | LIBXML_NOBLANKS | LIBXML_NONET | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_PARSEHUGE
        );

        $elements = $dom->getElementsByTagName($elementName);

        foreach ($elements as $element) {
            $this->setAttribute($element, $attributes);

            foreach ($attributesToRemove as $name) {
                if ($element->hasAttribute($name)) {
                    $element->removeAttribute($name);
                }
            }
        }

        return $dom->saveHTML();
    }

    private function putInsideTheBlock(string $blocName, string $inputHtml, array $attributes = [])
    {
        try {
            $dom = new DOMDocument();

            $div = $dom->createElement($blocName);

            $this->setAttribute($div, $attributes);

            $fragment = $dom->createDocumentFragment();
            $fragment->appendXML($inputHtml);
            $div->appendChild($fragment);

            $dom->appendChild($div);

            return $dom->saveHTML();
        } catch (DOMException $e) {

            return $inputHtml;
        }
    }

    public function hasAnyTagsInsidePTag($html): bool
    {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);

        $pTags = $dom->getElementsByTagName('p');

        $ignoreTags = ['br'];

        foreach ($pTags as $pTag) {
            if ($pTag->hasChildNodes()) {
                foreach ($pTag->childNodes as $childNode) {

                    if ($childNode->nodeType === XML_ELEMENT_NODE && !in_array(
                            strtolower($childNode->nodeName),
                            $ignoreTags
                        )) {
                        return true;
                    }

                    if ($childNode->nodeType === XML_TEXT_NODE && trim($childNode->nodeValue) !== '') {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function setAttribute(DOMElement $fragment, array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $fragment->setAttribute($name, $value);
        }
    }

    private function extractInteger($string): string
    {
        if (strpos($string, "px") !== false) {
            $cleanedString = str_replace("px", "", $string);
            return (int)$cleanedString;
        }

        return $string;
    }

    private function handleSizeToInt(array $size): array
    {
        $result = [];
        foreach ($size as $key => $size) {
            $result[$key] = $this->extractInteger($size);
        }
        return $result;
    }

    private function openNewTab(string $targetValue): string
    {
        switch ($targetValue) {
            case '_blank':
                return 'on';
            case '_self':
            default:
                return 'off';
        }

    }

    /**
     * Apply custom settings to a BrizyComponent based on its type
     *
     * @param BrizyComponent $component The component to apply settings to
     * @param array $customSettings The custom settings array from ElementContext
     * @param string $componentType The type of component (e.g., 'RichText', 'Wrapper', 'Image')
     * @param array &$appliedOnce Reference to track if we should apply (for 'implement' => 'one')
     * @param int $currentIndex The current index of this component type (for 'implement' => integer)
     * @return void
     */
    private function applyCustomSettings(
        BrizyComponent $component,
        array $customSettings,
        string $componentType,
        array &$appliedOnce = [],
        int $currentIndex = 0
    ): void
    {
        // Check if there are custom settings for this component type
        if (!isset($customSettings[$componentType])) {
            return;
        }

        $config = $customSettings[$componentType];

        // Check if we need to apply based on 'implement' rule
        $implement = $config['implement'] ?? 'all';

        // Determine if settings should be applied based on implement value
        $shouldApply = false;

        if ($implement === 'all') {
            // Apply to all instances
            $shouldApply = true;
        } elseif ($implement === 'one') {
            // Apply only to the first instance
            if (!isset($appliedOnce[$componentType])) {
                $shouldApply = true;
                $appliedOnce[$componentType] = true;
            }
        } elseif (is_int($implement)) {
            // Apply only to the specified index
            $shouldApply = ($currentIndex === $implement);
        }

        // Apply customSettings if we should and they are present
        if ($shouldApply && isset($config['customSettings']) && is_array($config['customSettings'])) {
            foreach ($config['customSettings'] as $key => $value) {
                $method = 'set_' . $key;

                // Check if the method exists on the component's value object
                try {
                    $component->getValue()->$method($value);
                } catch (Exception $e) {
                    Logger::instance()->warning("Failed to apply custom setting {$key} to {$componentType}: " . $e->getMessage());
                }

            }
        }

        // Process nested items if present
        if (isset($config['items']) && is_array($config['items'])) {
            foreach ($config['items'] as $childType => $childConfig) {
                // This will be applied recursively when processing child components
                // The child configuration is already part of customSettings
            }
        }
    }

}
