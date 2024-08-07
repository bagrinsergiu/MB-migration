<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\Media\MediaController;
use MBMigration\Core\Logger;
use DOMDocument;
use Exception;
use MBMigration\Builder\Checking;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\LayoutUtils;

abstract class Element extends LayoutUtils
{

    use checking;

    /**
     * @throws Exception
     */
    protected function initData()
    {
        Logger::instance()->info('initData!');

        return $this->loadKit();
    }

    protected function backgroundParallax(ItemBuilder $objBlock, array $sectionData): void
    {
        if ($this->checkArrayPath($sectionData, 'settings/sections/background/photoOption')) {
            if ($sectionData['settings']['sections']['background']['photoOption'] === 'parallax-scroll' or
                $sectionData['settings']['sections']['background']['photoOption'] === 'parallax-fixed') {
                $objBlock->item()->setting('bgAttachment', 'fixed');
                $objBlock->item()->setting('bgColorOpacity', 0);
                $objBlock->setting('mobileFullHeight', "custom");
                $objBlock->setting('mobileSectionHeight', 213);
            } else if ($sectionData['settings']['sections']['background']['photoOption'] === 'fill' ) {
                $objBlock->item()->setting('bgAttachment', 'none');
                $objBlock->item()->setting('bgColorOpacity', 0);
                $objBlock->setting('mobileFullHeight', "custom");
                $objBlock->setting('mobileSectionHeight', 213);
            }
        }
    }


    public function detectLinkType($link): string
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


    protected function backgroundVideo(ItemBuilder $objBlock, array $sectionData): void
    {
        if ($this->checkArrayPath($sectionData, 'settings/sections/background/video')) {

            $videoUrl = $sectionData['settings']['sections']['background']['video'];
            $videoOpacity = $this->colorOpacity($sectionData['settings']['sections']['background']['opacity']);

            $objBlock->item()->setting('media', 'video');
            $objBlock->item()->setting('bgVideoType', 'url');
            $objBlock->item()->setting('bgVideo', $videoUrl);
            $objBlock->item()->setting('bgColorOpacity', $videoOpacity);
            $objBlock->item()->setting('bgVideoLoop', 'on');
            $objBlock->setting('sectionHeight', 500);
            $objBlock->setting('fullHeight', 'custom');
        }
    }

    protected function backgroundColor(ItemBuilder $objBlock, array $sectionData): void
    {
        if (isset($sectionData['style']['background-color'])) {
            $objBlock->item()->setting('bgColorHex', $sectionData['style']['background-color']);
            $objBlock->item()->setting('mobileBgColorHex', $sectionData['style']['background-color']);
        }
        if (isset($sectionData['settings']['sections']['background']['filename'])) {
            $objBlock->item()->setting(
                'bgColorOpacity',
                $this->convertToNumeric($sectionData['style']['opacity_div']['opacity'])
            );
            $objBlock->item()->setting(
                'mobileBgColorOpacity',
                $this->convertToNumeric($sectionData['style']['opacity_div']['opacity'])
            );
        } else {
            if (isset($sectionData['style']['opacity'])) {
                $objBlock->item()->setting('bgColorOpacity', $this->convertToNumeric($sectionData['style']['opacity']));
                $objBlock->item()->setting(
                    'mobileBgColorOpacity',
                    $this->convertToNumeric($sectionData['style']['opacity'])
                );
            }
        }
        $objBlock->item()->setting('bgColorType', 'solid');
        $objBlock->item()->setting('mobileBgColorType', 'solid');
        $objBlock->item()->setting('mobileBgColorPalette', '');
    }

    /**
     *
     */
    protected function setOptionsForTextColor(array $sectionData, array &$options): void
    {
        if ($this->checkArrayPath($sectionData, 'settings/color/text')) {
            $textColor = $sectionData['settings']['color']['text'];
            $options = array_merge($options, ['textColor' => $textColor]);
        }
    }

    /**
     *
     */
    protected function backgroundImages(ItemBuilder $objBlock, array $sectionData): void
    {
        if ($this->checkArrayPath($sectionData, 'settings/sections/background')) {
            Logger::instance()->info('Set background Images');

            if ($this->checkArrayPath($sectionData, 'settings/sections/background/filename') &&
                $this->checkArrayPath($sectionData, 'settings/sections/background/photo')) {
                $objBlock->item()->setting(
                    'bgImageFileName',
                    $sectionData['settings']['sections']['background']['filename']
                );
                $objBlock->item()->setting('bgImageSrc', $sectionData['settings']['sections']['background']['photo']);
                $objBlock->item()->setting(
                    'bgColorOpacity',
                    $this->convertToNumeric($sectionData['style']['opacity_div']['opacity'] ?? 1)
                );
            }
        }
    }

    /**
     *
     */
    protected function setOptionsForUsedFonts(array $item, array &$options): void
    {
        if (isset($item['settings']['used_fonts']['uuid'])) {
            $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
        }
        if (isset($item['item_type'])) {
            $options = array_merge($options, ['fontType' => $item['item_type']]);
        }
    }

    /**
     *
     */
    protected function defaultOptionsForElement($element, &$options): void
    {
//        $loadOptions = json_decode($element['options'], true);
//        $positionOption = [
//            'title' => $loadOptions['title']['textPosition'],
//            'body' => $loadOptions['body']['textPosition'],
//        ];
//        $options = array_merge($options, ['textPosition' => $positionOption]);
    }

    /**
     *
     */
    protected function defaultTextPosition($element, &$options): void
    {
        if (!empty($options['textPosition'])) {
            switch ($element['item_type']) {
                case "title":
                case "accordion_title":
                    $mainPosition = $options['textPosition']['title'];
                    break;
                case "body":
                case "accordion_body":
                    $mainPosition = $options['textPosition']['body'];
                    break;
                default:
                    $mainPosition = 'brz-text-lg-left';
            }
            $options = array_merge($options, ['mainPosition' => $mainPosition]);
        }
    }


    /**
     *
     */
    protected function textType($item, &$options, $type = 'detect')
    {
        if (!empty($item['fontType']) && $type == 'detect') {
            switch ($item['fontType']) {
                case "title":
                case "accordion_title":
                    $sectionType = 'brz-tp-lg-heading1';
                    break;
                case "body":
                case "accordion_body":
                    $sectionType = 'brz-tp-lg-paragraph';
                    break;
                default:
                    $sectionType = 'brz-tp-lg-paragraph';
            }
        } elseif ($type == 'title') {
            $sectionType = 'brz-tp-lg-heading1';
        } else {
            $sectionType = 'brz-tp-lg-paragraph';
        }
        $options = array_merge($options, ['sectionType' => $sectionType]);
    }

    protected function showHeader($sectionData)
    {
        $show_header = true;
        $sectionCategory = $sectionData['category'];
        $path = "settings/sections/".$sectionCategory."/show_header";
        if ($this->checkArrayPath($sectionData, $path)) {
            $show_header = $sectionData['settings']['sections'][$sectionCategory]['show_header'];
        }

        return $show_header;
    }

    protected function showBody($sectionData)
    {
        $show_header = true;
        $sectionCategory = $sectionData['category'];
        if ($this->checkArrayPath($sectionData, "settings/sections/".$sectionCategory."/show_body")) {
            $show_header = $sectionData['settings']['sections'][$sectionCategory]['show_body'];
        }

        return $show_header;
    }

    protected function createCollectionItems($mainCollectionType, $slug, $title)
    {
        Logger::instance()->info('Create Detail Page: '.$title);
        if ($this->pageCheck($slug)) {
            $QueryBuilder = $this->cache->getClass('QueryBuilder');
            $createdCollectionItem = $QueryBuilder->createCollectionItem($mainCollectionType, $slug, $title);

            return $createdCollectionItem['id'];
        } else {
            $ListPages = $this->cache->get('ListPages');
            foreach ($ListPages as $listSlug => $collectionItems) {
                if ($listSlug == $slug) {
                    return $collectionItems;
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function createEventDetailPage($itemsID, $slug, string $elementName, $palette): void
    {

        $itemsData = [];
        $jsonDecode = $this->initData();
        $QueryBuilder = $this->cache->getClass('QueryBuilder');

        if ($this->checkArrayPath($jsonDecode, "dynamic/$elementName")) {
            $decoded = $jsonDecode['dynamic'][$elementName];
            $detail = new ItemBuilder();

            $detail->newItem($decoded['detail']);
        } else {
            throw new Exception('Element not found');
        }

        $detail->item()->item(1)->item()->item()->item()->setting('showImage', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showTitle', 'on');
        $detail->item()->item(1)->item()->item()->item()->setting('showDescription', 'on');
        $detail->item()->item(1)->item()->item()->item()->setting('showSubscribeToEvent', 'on');
        $detail->item()->item(1)->item()->item()->item()->setting('showPreviousPage', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showCoordinatorPhone', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showMetaIcons', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showGroup', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showDate', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showCategory', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showMetaHeadings', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showLocation', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showRoom', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showCoordinator', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showCoordinatorEmail', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showCost', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showWebsite', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showRegistration', 'off');

        $detail->item()->item(1)->item()->item()->item()->setting('titleColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('titleColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('titleColorPalette', '');

        $detail->item()->item(1)->item()->item()->item()->setting('colorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('colorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('colorPalette', '');

        $detail->item()->item(1)->item()->item()->item()->setting('previewColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('previewColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('previewColorPalette', '');

        $detail->item()->item(1)->item()->item()->item()->setting('detailButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('detailButtonBgColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('detailButtonBgColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('hoverDetailButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('hoverDetailButtonBgColorOpacity', 0.75);
        $detail->item()->item(1)->item()->item()->item()->setting('hoverDetailButtonBgColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('metaLinksColorHex', $palette['link'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('metaLinksColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('metaLinksColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('hoverMetaLinksColorHex', $palette['link'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('hoverMetaLinksColorOpacity', 0.75);
        $detail->item()->item(1)->item()->item()->item()->setting('hoverMetaLinksColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('subscribeEventButtonColorHex', $palette['btn-text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('subscribeEventButtonColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('subscribeEventButtonColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('subscribeEventButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('subscribeEventButtonBgColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('subscribeEventButtonBgColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('hoverSubscribeEventButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('hoverSubscribeEventButtonBgColorOpacity', 0.75);
        $detail->item()->item(1)->item()->item()->item()->setting('hoverSubscribeEventButtonBgColorPalette', "");


        $detail->item()->item(1)->item(1)->item()->item()->setting('borderColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('borderColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item()->item()->setting('borderColorPalette', '');

        $detail->item()->item(1)->item(1)->item()->item()->item()->item()->setting('textBgColorHex', $palette['btn-text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->item()->item()->setting('textBgColorPalette', 1);
        $detail->item()->item(1)->item(1)->item()->item()->item()->item()->setting('textBgColorPalette', '');

        $detail->item()->item(1)->item(1)->item()->item()->item()->item()->setting('colorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->item()->item()->setting('colorOpacity', 1);
        $detail->item()->item(1)->item(1)->item()->item()->item()->item()->setting('colorPalette', '');


        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showImage', 'off');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showTitle', 'off');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showDescription', 'off');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showSubscribeToEvent', 'off');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showPreviousPage', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showCoordinatorPhone', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showMetaIcons', 'off');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showGroup', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showDate', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showCategory', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showMetaHeadings', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showLocation', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showRoom', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showCoordinator', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showCoordinatorEmail', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showCost', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showWebsite', 'on');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('showRegistration', 'on');

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('colorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('colorOpacity', 1);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('colorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('dateColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('dateColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('dateColorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('metaLinksColorHex', $palette['link'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('metaLinksColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('metaLinksColorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverMetaLinksColorHex', $palette['link'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverMetaLinksColorOpacity', 0.75);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverMetaLinksColorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('detailButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('detailButtonBgColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('detailButtonBgColorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('subscribeEventButtonColorHex', $palette['btn-text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('subscribeEventButtonColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('subscribeEventButtonColorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverDetailButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverDetailButtonBgColorOpacity', 0.75);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverDetailButtonBgColorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('subscribeEventButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('subscribeEventButtonBgColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('subscribeEventButtonBgColorPalette', "");

        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverSubscribeEventButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverSubscribeEventButtonBgColorOpacity', 0.75);
        $detail->item()->item(1)->item(1)->item(1)->item()->setting('hoverSubscribeEventButtonBgColorPalette', "");

        $itemsData['items'][] = $detail->get();

        $pageData = json_encode($itemsData);

        $QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);
    }

    protected function createSermonsDetailPage($itemsID, $slug, string $elementName, $palette): void
    {

        $itemsData = [];
        $jsonDecode = $this->initData();
        $QueryBuilder = $this->cache->getClass('QueryBuilder');

        if ($this->checkArrayPath($jsonDecode, "dynamic/$elementName")) {
            $decoded = $jsonDecode['dynamic'][$elementName];
            $detail = new ItemBuilder();

            $detail->newItem($decoded['detail']);
        } else {
            throw new Exception('Element not found');
        }

        $detail->item()->item(1)->item()->item()->item()->setting('showMediaLinksVideo', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showMediaLinksAudio', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showMediaLinksDownload', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showMediaLinksNotes', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showPreview', 'on');
        $detail->item()->item(1)->item()->item()->item()->setting('showVideo', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showTitle', 'on');
        $detail->item()->item(1)->item()->item()->item()->setting('showDate', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showCategory', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showPreacher', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showPassage', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showMetaHeadings', 'off');
        $detail->item()->item(1)->item()->item()->item()->setting('showPreviousPage', 'off');

        $detail->item()->item(1)->item()->item()->item()->setting('titleColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('titleTypographyFontStyle', 'heading3');
        $detail->item()->item(1)->item()->item()->item()->setting('titleColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('titleColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('previewColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('previewColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('previewColorPalette', "");

        $detail->item()->item(1)->item()->item()->item()->setting('colorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('colorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('colorPalette', '');

        $detail->item()->item(1)->item()->item()->item()->setting('previewColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item()->item()->item()->setting('previewColorOpacity', 1);
        $detail->item()->item(1)->item()->item()->item()->setting('previewColorPalette', '');

        $detail->item()->item(1)->item(1)->item()->item()->setting('showMediaLinksVideo', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showMediaLinksAudio', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showMediaLinksDownload', 'on');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showMediaLinksNotes', 'on');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showPreview', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showVideo', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showTitle', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showDate', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showCategory', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showPreacher', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showPassage', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showMetaHeadings', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showPreviousPage', 'off');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showGroup', 'on');
        $detail->item()->item(1)->item(1)->item()->item()->setting('showSeries', 'on');

        $detail->item()->item(1)->item(1)->item()->item()->setting('colorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('colorOpacity', 1);
        $detail->item()->item(1)->item(1)->item()->item()->setting('colorPalette', "");

        $detail->item()->item(1)->item(1)->item()->item()->setting('titleColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('titleTypographyFontStyle', 'heading3');
        $detail->item()->item(1)->item(1)->item()->item()->setting('titleColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item()->item()->setting('titleColorPalette', "");

        $detail->item()->item(1)->item(1)->item()->item()->setting('previewColorHex', $palette['text'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('previewColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item()->item()->setting('previewColorPalette', "");

        $detail->item()->item(1)->item(1)->item()->item()->setting('metaLinksColorHex', $palette['link'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('metaLinksColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item()->item()->setting('metaLinksColorPalette', "");

        $detail->item()->item(1)->item(1)->item()->item()->setting('hoverMetaLinksColorHex', $palette['link'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('hoverMetaLinksColorOpacity', 0.75);
        $detail->item()->item(1)->item(1)->item()->item()->setting('hoverMetaLinksColorPalette', "");

        $detail->item()->item(1)->item(1)->item()->item()->setting('detailButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('detailButtonBgColorOpacity', 1);
        $detail->item()->item(1)->item(1)->item()->item()->setting('detailButtonBgColorPalette', "");

        $detail->item()->item(1)->item(1)->item()->item()->setting('hoverDetailButtonBgColorHex', $palette['btn-bg'] ?? '#171727');
        $detail->item()->item(1)->item(1)->item()->item()->setting('hoverDetailButtonBgColorOpacity', 0.75);
        $detail->item()->item(1)->item(1)->item()->item()->setting('hoverDetailButtonBgColorPalette', "");


        $itemsData['items'][] = $detail->get();

        $pageData = json_encode($itemsData);

        $QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);
    }

    protected function generalParameters($objBlock, &$options, $sectionData, $primary = []): void
    {
        $padding = $sectionData['style'] ?? [];

        $options = [
            'position' => $sectionData['settings']['pagePosition'] ?? '',
            'currentPageURL' => $this->cache->get('CurrentPageURL'),
            'sectionID' => $sectionData['sectionId'],
            'fontsFamily' => FontsController::getFontsFamily(),
        ];

        foreach ($primary as $key => $value) {
            $padding[$key] = $value;
        }

        if (!empty($padding)) {
            $objBlock->item(0)->setting('bgColorPalette', '');
            $objBlock->item(0)->setting('colorPalette', '');

            if (!empty($padding['padding-bottom'])) {
                $objBlock->item(0)->setting('paddingBottom', $padding['padding-bottom']);
            }
            if (!empty($padding['padding-top'])) {
                $objBlock->item(0)->setting('paddingTop', $padding['padding-top']);
            }
            if (!empty($padding['padding-left'])) {
                $objBlock->item(0)->setting('paddingLeft', $padding['padding-left']);
            }
            if (!empty($padding['padding-right'])) {
                $objBlock->item(0)->setting('paddingRight', $padding['padding-right']);
            }
        }
    }


    protected function insertItemInArray(array $array, array $item, $index): array
    {
        if ($index >= 0 && $index <= count($array)) {
            $left = array_slice($array, 0, $index);
            $right = array_slice($array, $index);
            $result = array_merge($left, [$item], $right);
        } else {
            $result = array_merge($array, [$item]);
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    protected function itemWrapperRichText($content, array $settings = [], $associative = false)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--richText'];
        $block = new ItemBuilder($decoded);
        $block->item()->setText($content);

        if (!empty($settings)) {
            foreach ($settings as $key => $value) {
                $block->item()->setting($key, $value);
            }
        }
        $result = $block->get();
        if (!$associative) {
            return $result;
        }

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function embedCode($content)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--embedCode'];
        $block = new ItemBuilder($decoded);
        $block->item()->setCode($content);
        $result = $block->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function button($options, $position)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--button'];
        $block = new ItemBuilder($decoded);
        foreach ($options as $key => $value) {
            $block->item()->setting($key, $value);
        }
        $block->setting('horizontalAlign', $position);
        $result = $block->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperColumn(array $element = [], $multi = false)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--column'];
        $block = new ItemBuilder($decoded['main']);
        if (!empty($element)) {
            if ($multi) {
                foreach ($element as $item) {
                    $block->addItem($item);
                }
            } else {
                $block->addItem($element);
            }
        }
        $result = $block->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperSermon($options)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--sermonLayout'];
        $block = new ItemBuilder($decoded['main']);
        foreach ($options as $key => $value) {
            $block->item()->setting($key, $value);
        }

        $result = $block->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperLine(array $options = [])
    {
        $defOptions = [
            'borderColorPalette' => '',
            'borderColorHex' => '#000000',
            'borderColorOpacity' => 1,
            'borderWidth' => 1,
        ];

        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global'];
        $line = new ItemBuilder($decoded['wrapper--line']);

        $options = array_merge($defOptions, $options);

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $line->item()->setting($key, $value);
            }
        }
        $result = $line->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperForm(array $options = [], $formId = '', $type = 'main')
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global'];
        $objForm = new ItemBuilder($decoded['wrapper--form'][$type]);
        if (!empty($formId)) {
            $objForm->item()->setting('form', $formId);
        }
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $objForm->item()->setting($key, $value);
            }
        }
        $result = $objForm->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperImage(array $element, $wrapper = null)
    {
        if (empty($wrapper)) {
            $jsonDecode = $this->initData();
            $wrapper = $jsonDecode['global']['wrapper--image']['main'];
        }
        $block = new ItemBuilder($wrapper);
        foreach ($element as $key => $value) {
            $block->item()->setting($key, $value);
        }
        $result = $block->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperRow(array $element = [], $multi = false)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--row'];
        $block = new ItemBuilder($decoded['main']);
        if (!empty($element)) {
            if ($multi) {
                foreach ($element as $item) {
                    $block->addItem($item);
                }
            } else {
                $block->addItem($element);
            }
        }
        $result = $block->get();

        return json_decode(json_encode($result), true);
    }

    /**
     * @throws Exception
     */
    protected function wrapperIcon($items, $aline)
    {
        $jsonDecode = $this->initData();
        $decoded = $jsonDecode['global']['wrapper--icon'];
        $objColum = new ItemBuilder($decoded['main']);

        foreach ($items as $settings) {
            $objIcon = new ItemBuilder();
            $objIcon->newItem($decoded['item']);

            $objIcon->setting('name', $this->getIcoNameByUrl($settings['linkExternal'], $settings['iconCode']));
            $objIcon->setting('customSize', 26);

            foreach ($settings as $key => $value) {
                if ($key === 'iconCode') {
                    continue;
                }

                if ($key === 'bgColorHex') {
                    $objIcon->setting('padding', 10);
                    $objIcon->setting('bgColorOpacity', 1);
                    $objIcon->setting('borderRadiusType', 'custom');
                    $objIcon->setting('paddingSuffix', '%');
                    $objIcon->setting('borderRadius', 11);
                }
                $objIcon->setting($key, $value);
            }
            $objIcon = $objIcon->get();
            $objColum->addItem($objIcon);
        }
        $objColum->setting('horizontalAlign', $aline);

        $result = $objColum->get();

        return json_decode(json_encode($result), true);
    }

    public function findEmbeddedPasteDivs($html): array
    {
        $html = $this->styleIframes($html);

        $result = [];

        $dom = new DOMDocument();

        $dom->loadHTML($html);

        $divs = $dom->getElementsByTagName('div');
        foreach ($divs as $div) {
            if ($div->hasAttribute('class') && $div->getAttribute('class') === 'embedded-paste') {
                $dataSrc = $div->getAttribute('data-src');
                $escapedDataSrc = str_replace('"', '\\"', $dataSrc);
                $div->setAttribute('data-src', $escapedDataSrc);

                $result[] = $dom->saveHTML($div);
            }
        }

        return $result;
    }

//    public function hasAnyTagsInsidePTag($html)
//    {
//        $ignoreTags = ['<br>'];
//
//        $dom = new DOMDocument;
//
//        libxml_use_internal_errors(true);
//
//        $dom->loadHTML($html);
//
//        $pTags = $dom->getElementsByTagName('p');
//
//        foreach ($pTags as $pTag) {
//            if ($pTag->hasChildNodes()) {
//                foreach ($pTag->childNodes as $childNode) {
//                    if ($childNode->nodeType === XML_ELEMENT_NODE && !in_array($childNode->nodeName, $ignoreTags)) {
//                        return true;
//                    }
//                }
//            }
//        }
//
//        return false;
//    }

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

    public function link($objItem, $item): void
    {
        $sectionItem = [];
        $slash = '';
        if ($item['new_window'] === true) {
            $sectionItem['new_window'] = 'on';
        } else {
            $sectionItem['new_window'] = 'off';
        }

        switch ($this->detectLinkType($item['link'])) {
            case 'mail':
                $objItem->item()->item()->setting('linkType', 'external');
                $objItem->item()->item()->setting('linkExternal', 'mailto:'.$item['link']);
                $objItem->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                break;
            case 'phone':
                $objItem->item()->item()->setting('linkType', 'external');
                $objItem->item()->item()->setting('linkExternal', 'tel:'.$item['link']);
                $objItem->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                break;
            case 'string':
            case 'link':

                if(MediaController::is_doc($item['link'])){
                    $item['link'] = MediaController::getURLDoc($item['link']);
                }

                $slash = $this->processURL($item['link']);

                $objItem->item()->item()->setting('linkType', 'external');
                $objItem->item()->item()->setting('linkExternal', $slash.$item['link']);
                $objItem->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                break;
            default:
                $objItem->item()->item()->setting('linkType', 'external');
                $objItem->item()->item()->setting('linkExternal', $slash.$item['link']);
                $objItem->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                break;
        }
    }

    private function styleIframes($html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $iframes = $dom->getElementsByTagName('iframe');

        foreach ($iframes as $iframe) {
            $width = $iframe->getAttribute('width');
            $height = $iframe->getAttribute('height');

            if (!empty($width) && !empty($height)) {
                $iframe->setAttribute('style', "max-width: {$width}px; max-height: {$height}px; width: 100%;");
            }
        }

        return $dom->saveHTML();
    }

    private function convertToNumeric($input)
    {
        if (is_numeric($input)) {
            if (strpos($input, '.') !== false) {
                return (float)$input;
            } else {
                return (int)$input;
            }
        } else {
            return $input;
        }
    }

    private function convertColor($color): string
    {

        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            return $color;
        }

        if (preg_match('/rgba\((\d+), (\d+), (\d+), ([0-9]*\.?[0-9]+)\)/', $color, $matches)) {
            $r = $matches[1];
            $g = $matches[2];
            $b = $matches[3];

            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        if (preg_match_all('/\d+/', $color, $matches)) {
            if (count($matches[0]) !== 3) {
                return $color;
            }
            list($r, $g, $b) = $matches[0];

            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        return $color;
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

    protected function createSlug($string): string
    {
        $string = trim($string);

        $pattern = [
            '$(à|á|â|ã|ä|å|À|Á|Â|Ã|Ä|Å|æ|Æ)$',
            '$(è|é|é|ê|ë|È|É|Ê|Ë)$',
            '$(ì|í|î|ï|Ì|Í|Î|Ï)$',
            '$(ò|ó|ô|õ|ö|ø|Ò|Ó|Ô|Õ|Ö|Ø|œ|Œ)$',
            '$(ù|ú|û|ü|Ù|Ú|Û|Ü)$',
            '$(ñ|Ñ)$',
            '$(ý|ÿ|Ý|Ÿ)$',
            '$(ç|Ç)$',
            '$(ð|Ð)$',
            '$(ß)$'
        ];

        $replacement = [
            'a',
            'e',
            'i',
            'o',
            'u',
            'n',
            'y',
            'c',
            'd',
            's'
        ];

        $string =  preg_replace($pattern, $replacement, $string);

        $string = htmlentities($string, ENT_QUOTES, "UTF-8");

        $search = [
            '@(&(#?))[a-zA-Z0-9]{1,7}(;)@'
        ];

        $replace = array(
            ''
        );

        $string = preg_replace($search, $replace, $string);

        $search = [
            '@<script[^>]*?>.*?</script>@si',
            '@<[\/\!]*?[^<>]*?>@si',
            '@(\s-\s)@',
            '@[\s]{1,99}@',
            '@—@',
            '@[\\\/)({}^\@\[\]|!#$%*+=~`?.,_;:]@'
        ];

        $replace = [
            '',
            '',
            '-',
            '-',
            '-',
            ''
        ];

        $string = preg_replace($search, $replace, $string);

        return strtolower($string);
    }

}
