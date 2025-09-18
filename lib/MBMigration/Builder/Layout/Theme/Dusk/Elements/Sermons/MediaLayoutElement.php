<?php
namespace MBMigration\Builder\Layout\Theme\Dusk\Elements\Sermons;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Concern\SlugAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Template\DetailPages\SermonDetailsPageLayout;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;
use MBMigration\Core\Logger;
use MBMigration\Layer\Graph\QueryBuilder;

class MediaLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Sermons\MediaLayoutElement
{
    use RichTextAble;
    use SectionStylesAble;
    use CssPropertyExtractorAware;
    use BrizyQueryBuilderAware;
    use SlugAble;


    /**
     * @throws BadJsonProvided
     */
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['Section']['main'], true));

        $mbSection = $data->getMbSection();

        $mbContext = $data->getThemeContext();

        $slug = $mbContext->getSlug();

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());
        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $this->handleRichTextHead($elementContext, $this->browserPage);
        $this->handleRichTextItems($elementContext, $this->browserPage);

        $dataIdSelector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';

        $mbSection['mediaGridContainer'] = false;
        $mbSection['media-player'] = false;
        $mbSection['containTitle'] = $mbSection['containTitle'] ?? '';

        if ($this->hasNode($dataIdSelector . ' div.subsection.media-player-subsection', $this->browserPage)) {
            $bgSubStylesRaw = $this->getDomElementStyles(
                $dataIdSelector . ' div.subsection.media-player-subsection',
                ['background-color', 'opacity'],
                $this->browserPage);

            $bgColor = ColorConverter::convertColorRgbToHex($bgSubStylesRaw['background-color'] ?? null);
            $opacity = isset($bgSubStylesRaw['opacity']) ? NumberProcessor::convertToNumeric($bgSubStylesRaw['opacity']) : ColorConverter::rgba2opacity($bgSubStylesRaw['background-color'] ?? '');

            $brizySection->getItemValueWithDepth(0)
                ->set_bgColorOpacity($opacity)
                ->set_bgColorHex(is_array($bgColor) ? ($bgColor['color'] ?? '#000000') : ($bgColor ?: '#000000'))
                ->set_mobileBgColorType('solid')
                ->set_mobileBgColorHex(is_array($bgColor) ? ($bgColor['color'] ?? '#000000') : ($bgColor ?: '#000000'))
                ->set_mobileBgColorOpacity($opacity);
        }

        if ($this->hasNode($dataIdSelector . ' .media-grid-container', $this->browserPage)) {
            $mbSection['mediaGridContainer'] = true;
        } elseif ($this->hasNode($dataIdSelector . ' .media-list-container', $this->browserPage)) {
            $mbSection['mediaGridContainer'] = true;
        }

        if ($this->hasNode($dataIdSelector . ' .media-player', $this->browserPage)) {
            $mbSection['media-player'] = true;
        }

        if ($mbSection['media-player']) {
            $titleSelector = $dataIdSelector . ' .media-video-title';
            $mbSection['containTitle'] = $this->getNodeText($titleSelector, $this->browserPage);
        }

        $sectionSubPalette = $this->getNodeSubPalette($dataIdSelector, $this->browserPage);
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        if (!$mbSection['mediaGridContainer']) {
            $brizySectionFeaturedVideo = new BrizyComponent(json_decode($this->brizyKit['SermonFeatured']['elementVideo'], true));
            $brizySectionFeaturedDescription = new BrizyComponent(json_decode($this->brizyKit['SermonFeatured']['elementDescription'], true));

            $textColorStyles = $this->getDomElementStyles(
                $dataIdSelector . ' .media-player-container .media-header .text-content',
                ['color'],
                $this->browserPage);

            $backgroundColorStyles = $this->getDomElementStyles(
                $dataIdSelector . ' .media-player-container .media-player',
                ['background-color'],
                $this->browserPage);

            $backgroundColorStyles = ColorConverter::convertColorRgbToHex($backgroundColorStyles['background-color']);
            $textColorStyles = ColorConverter::convertColorRgbToHex($textColorStyles['color']);

            $sectionProperties = [
                'sermonSlug' => $this->createSlug($mbSection['containTitle']),

                'showImage' => 'on',
                'showVideo' => 'on',
                'showAudio' => 'on',

                'titleColorHex' => $sectionPalette['link'] ?? "#1e1eb7",
                'titleColorOpacity' => 1,
                'titleColorPalette' => "",

                'hoverTitleColorHex' => $sectionPalette['link'] ?? "#1e1eb7",
                'hoverTitleColorOpacity' => 0.7,
                'hoverTitleColorPalette' => "",

                'metaLinksColorHex' => $sectionPalette['link'] ?? "#3d79ff",
                'metaLinksColorOpacity' => 1,
                'metaLinksColorPalette' => "",

                'hoverMetaLinksColorHex' => $sectionPalette['link'] ?? "#3d79ff",
                'hoverMetaLinksColorOpacity' => 0.7,
                'hoverMetaLinksColorPalette' => "",
            ];

            $sectionPropertiesSecondLevel = [
                'sermonSlug' => $this->createSlug($mbSection['containTitle']),

                'showMetaHeadings' => 'off',

                'colorHex' => $textColorStyles ?? "#ebeff2",
                'colorOpacity' => 1,
                'colorPalette' => "",

                'titleColorHex' => $textColorStyles ?? "#ebeff2",
                'titleColorOpacity' => 1,
                'titleColorPalette' => "",

                'hoverTitleColorHex' => $textColorStyles ?? "#ebeff2",
                'hoverTitleColorOpacity' => 1,
                'hoverTitleColorPalette' => "",

                'previewColorHex' => $textColorStyles ?? "#ebeff2",
                'previewColorOpacity' => 1,
                'previewColorPalette' => "",

                'parentBgColorHex' => $backgroundColorStyles ?? '#505050',
                'parentBgColorOpacity' => 1,
                'parentBgColorPalette' => "",
            ];

            foreach ($sectionProperties as $key => $value) {
                $properties = 'set_' . $key;
                $brizySectionFeaturedVideo->getItemValueWithDepth(0)
                    ->$properties($value);
            }

            foreach ($sectionPropertiesSecondLevel as $key => $value) {
                $properties = 'set_' . $key;
                $brizySectionFeaturedDescription->getItemValueWithDepth(0)
                    ->$properties($value);
            }

            $brizySectionFeaturedDescription->getItemWithDepth(0)
                ->typography()
                ->dataTypography()
                ->titleTypography()
                ->previewTypography()
                ->subscribeEventButtonTypography();

            $brizySectionFeaturedDescription->addMobileMargin([0, -5, 0, -5]);

            $brizySection->getItemValueWithDepth(0)
                ->add('items', [$brizySectionFeaturedVideo])
                ->add('items', [$brizySectionFeaturedDescription]);
        } else {
            $brizySectionGrid = new BrizyComponent(json_decode($this->brizyKit['GridMediaLayout']['main'], true));

            $selector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';
            $sectionSubPalette = $this->getNodeSubPalette($selector, $this->browserPage);

            $DetailsPageLayout = new SermonDetailsPageLayout($this->brizyKit['GridMediaLayout']['detail'],
                $this->getTopPaddingOfTheFirstElement() + $this->getAdditionalTopPaddingOfDetailPage(),
                $this->getMobileTopPaddingOfTheFirstElement(),
                $this->pageTDO,
                $data,
                $sectionSubPalette ?? $mbSection['settings']['sections']['color']['subpalette'] ?? 'subpalette1'
            );

            $resultColorStyles['text'] = $this->getDomElementStyles(
                $dataIdSelector . ' .media-player-container .media-header .text-content',
                ['color'],
                $this->browserPage);

            $resultColorStyles['pagination-normal'] = $this->getDomElementStyles(
                $dataIdSelector . ' .pagination .previous a',
                ['color'],
                $this->browserPage);

            $resultColorStyles['opacity-pagination-normal'] = $this->getDomElementStyles(
                $dataIdSelector . ' .pagination .previous a',
                ['opacity'],
                $this->browserPage);

            $resultColorStyles['pagination-active'] = $this->getDomElementStyles(
                $dataIdSelector . ' .pagination .active a',
                ['color'],
                $this->browserPage);

            $resultColorStyles['pagination-active-bg'] = $this->getDomElementStyles(
                $dataIdSelector . ' .pagination .active a',
                ['background-color'],
                $this->browserPage,
                [], '',
                ':before');

            $resultColorStyles['opacity-pagination-active'] = $this->getDomElementStyles(
                $dataIdSelector . ' .pagination .active a',
                ['opacity'],
                $this->browserPage);
            #media-archive-dcf0cf6c-26ca-4d0a-af8f-e3dc0136f904 > div.media-grid-container > ul.pagination > li.active > a

            $resultColorStyles['bg-color'] = $this->getDomElementStyles(
                $dataIdSelector . ' .media-player-container .media-player',
                ['background-color'],
                $this->browserPage);

            $resultColorStyles['bg-opacity'] = $this->getDomElementStyles(
                $dataIdSelector . ' .media-player-container .media-player',
                ['opacity'],
                $this->browserPage);

            $resultColorStyles['color-text-description'] = $this->getDomElementStyles(
                $dataIdSelector . ' .media-player-container .media-description',
                ['color'],
                $this->browserPage);

            $resultColorStyles['color-text-header'] = $this->getDomElementStyles(
                $dataIdSelector . ' .media-player-container .media-header',
                ['color'],
                $this->browserPage);


            $colorStyles = [
                'text-color' => ColorConverter::convertColorRgbToHex($resultColorStyles['text']['color'] ?? $sectionPalette['text']),
                'bg-color' => ColorConverter::convertColorRgbToHex($resultColorStyles['bg-color']['background-color'] ?? $sectionPalette['bg']),
                'bg-opacity' => ColorConverter::normalizeOpacity($resultColorStyles['bg-opacity']['opacity'] ?? 1),
                'pagination-normal' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-normal']['color'] ?? $sectionPalette['text']),
                'pagination-active' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-active']['color'] ?? $sectionPalette['text']),
                'pagination-active-bg' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-active-bg']['background-color'] ?? null),
                'color-text-description' => ColorConverter::convertColorRgbToHex($resultColorStyles['color-text-description']['color'] ?? $sectionPalette['text']),
                'color-text-header' => ColorConverter::convertColorRgbToHex($resultColorStyles['color-text-header']['color'] ?? $sectionPalette['text']),
                'opacity-pagination-normal' => $resultColorStyles['opacity-pagination-normal']['opacity'] ?? 0.75,
                'opacity-pagination-active' => $resultColorStyles['opacity-pagination-active']['opacity'] ?? 1,
            ];

            $colorKeysToNormalize = [
                'bg-color',
                'pagination-normal',
                'pagination-active',
                'pagination-active-bg',
                'color-text-description',
                'color-text-header',
                'text-color',
            ];

            foreach ($colorKeysToNormalize as $key) {
                if (array_key_exists($key, $colorStyles)) {
                    $colorStyles[$key] = $this->normalizeColorEntry($colorStyles[$key], '#000000');
                }
            }

            // ensure item-bg is hex string
            $sectionPalette['item-bg'] = $colorStyles['bg-color']['color'] ?? (is_string($colorStyles['bg-color']) ? $colorStyles['bg-color'] : ($sectionPalette['bg'] ?? '#000000'));

            $detailsSection = $DetailsPageLayout->setStyleDetailPage($sectionPalette);

            $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
            $detailCollectionItem = $this->createDetailsCollectionItem(
                $collectionTypeUri,
                $detailsSection,
                self::DETAILS_SLUG_NAME,
                self::DETAILS_PAGE_NAME,
            );

            $placeholder = base64_encode('{{ brizy_dc_url_post entityType="' . $detailCollectionItem['type']['id'] . '" entityId="' . $detailCollectionItem['id'] . '" }}');

            $this->getDetailsLinksComponent($brizySectionGrid)
                ->getValue()
                ->set_defaultCategory($slug)
                ->set_parentCategory($slug)
                ->set_detailPageSource($collectionTypeUri)
                ->set_detailPage("{{placeholder content='$placeholder'}}");

            $paginationColorActive1 = $colorStyles['pagination-active']['color'] ?? (is_string($colorStyles['pagination-active']) ? $colorStyles['pagination-active'] : null);
            $paginationColorActive2 = $colorStyles['pagination-active-bg']['color'] ?? (is_string($colorStyles['pagination-active-bg']) ? $colorStyles['pagination-active-bg'] : null);
            $paginationColorActive = $paginationColorActive2 ?? $paginationColorActive1 ?? ($sectionPalette['link'] ?? '#3d79ff');

            $sectionProperties = [

                'showCategoryFilter' => 'off',

                'colorHex' => $sectionPalette['link'] ?? $colorStyles['color-text-header']['color'] ?? $colorStyles['color-text-header'],
                'colorOpacity' => $colorStyles['color-text-header']['opacity'] ?? 1,
                'colorPalette' => "",

                'titleColorHex' => $sectionPalette['link'] ?? "#1e1eb7",
                'titleColorOpacity' => 1,
                'titleColorPalette' => "",

                'hoverTitleColorHex' => $sectionPalette['link'] ?? "#1e1eb7",
                'hoverTitleColorOpacity' => 0.7,
                'hoverTitleColorPalette' => "",

                'metaLinksColorHex' => $sectionPalette['link'] ?? "#3d79ff",
                'metaLinksColorOpacity' => 1,
                'metaLinksColorPalette' => "",

                'hoverMetaLinksColorHex' => $sectionPalette['link'] ?? "#3d79ff",
                'hoverMetaLinksColorOpacity' => 0.7,
                'hoverMetaLinksColorPalette' => "",

                'filterBgColorHex' => $sectionPalette['input-border'] ?? $colorStyles['bg-color']['color'] ?? $colorStyles['bg-color'],
                'filterBgColorOpacity' => 1 ?? $colorStyles['bg-color']['opacity'] ?? $colorStyles['bg-opacity'],
                'filterBgColorPalette' => '',

                'itemBgColorHex' => $colorStyles['bg-color']['color'] ?? $colorStyles['bg-color'],
                'itemBgColorOpacity' =>$colorStyles['bg-color']['opacity'] ?? 0, // $colorStyles['bg-opacity'],
                'itemBgColorPalette' => '',

                'paginationColorHex' =>  $sectionPalette['link'] ?? $colorStyles['pagination-normal']['color'] ?? $colorStyles['pagination-normal'],
                'paginationColorOpacity' => 0.80 ?? floatval($colorStyles['pagination-normal']['opacity'] ?? $colorStyles['opacity-pagination-normal']),
                'paginationColorPalette' => '',

                'activePaginationColorHex' => $sectionPalette['link'] ?? $paginationColorActive,
                'activePaginationColorOpacity' => 1 ?? floatval($colorStyles['opacity-pagination-active'] ?? $colorStyles['pagination-active']['opacity']),
                'activePaginationColorPalette' => '',

                'hoverPaginationColorHex' => $sectionPalette['link'] ?? $colorStyles['pagination-normal']['color'] ?? $colorStyles['pagination-normal'],
                'hoverPaginationColorOpacity' => 1 ?? ColorConverter::getHoverOpacity($colorStyles['pagination-normal']['opacity'] ?? $colorStyles['opacity-pagination-normal']),
                'hoverPaginationColorPalette' => '',

                'resultsHeadingColorHex' => ($colorStyles['text-color']['color'] ?? (is_string($colorStyles['text-color']) ? $colorStyles['text-color'] : ($sectionPalette['text'] ?? '#000000'))),
                'resultsHeadingColorOpacity' => 1,
                'resultsHeadingColorPalette' => '',

                "itemPaddingType" => "ungrouped",
                "itemPadding" => 0,
                "itemPaddingSuffix" => "px",
                "itemPaddingTop" => 0,
                "itemPaddingTopSuffix" => "px",
                "itemPaddingRight" => 0,
                "itemPaddingRightSuffix" => "px",
                "itemPaddingBottom" => 0,
                "itemPaddingBottomSuffix" => "px",
                "itemPaddingLeft" => 0,
                "itemPaddingLeftSuffix" => "px",
            ];

            foreach ($sectionProperties as $key => $value) {
                $properties = 'set_' . $key;
                $brizySectionGrid->getItemValueWithDepth(0)
                    ->$properties($value);
            }

            $brizySection->getItemValueWithDepth(0)
                ->add('items', [$brizySectionGrid]);
        }

        return $brizySection;
    }

    private function setStyleDetailPage(BrizyComponent $detailsSection, array $sectionPalette)
    {
        $colorTitle = ColorConverter::hex2Rgb($sectionPalette['btn-text'] ?? $sectionPalette['text']);

        $richTextTitle = [
            'text' => '<h5 class="brz-text-lg-center brz-tp-lg-empty brz-ff-lato brz-ft-google brz-fs-lg-20 brz-fss-lg-px brz-fw-lg-400 brz-ls-lg-0 brz-lh-lg-1_6 brz-vfw-lg-400 brz-fwdth-lg-100 brz-fsft-lg-0" data-uniq-id="xdAq1" data-generated-css="brz-css-duw4v"><span style="color: ' . $colorTitle . ';">Sermon Details</span></h5>',
            'typographyFontStyle' => 'heading5'
        ];

        $wrapperItemTitle = [
            'bgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionStyle = [
            'paddingTop' => $this->getTopPaddingOfTheFirstElement() ?? 0,
            'bgColorHex' => $sectionPalette['bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionDescriptionStyle = [
            'bgColorHex' => $sectionPalette['item-bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 0,
        ];

        $sectionDescriptionItemsStyle = [
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 5,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 5,
            "mobilePaddingLeftSuffix" => "px",
        ];


        $sectionPlayerStyle = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => 0,
            "marginBottomSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",

            "mobileMarginType" => "ungrouped",
            "mobileMargin" => 0,
            "mobileMarginSuffix" => "px",
            "mobileMarginTop" => 10,
            "mobileMarginTopSuffix" => "px",
            "mobileMarginRight" => 0,
            "mobileMarginRightSuffix" => "px",
            "mobileMarginBottom" => 0,
            "mobileMarginBottomSuffix" => "px",
            "mobileMarginLeft" => 0,
            "mobileMarginLeftSuffix" => "px",
        ];

        $sectionProperties1 = [
            'showAudio' => 'off',
            'showImage' => 'off',
            'showVideo' => 'off',
            'showPreview' => 'on',
            'showTitle' => 'on',
            'showGroup' => 'on',
            'showDate' => 'off',
            'showCategory' => 'off',
            'showPreacher' => 'off',
            'showPassage' => 'off',
            'showMetaHeadings' => 'off',
            'showPreviousPage' => 'off',
            'showMediaLinksVideo' => 'off',
            'showMediaLinksAudio' => 'off',
            'showMediaLinksDownload' => 'off',
            'showMediaLinksNotes' => 'off',

            'colorHex' => $sectionPalette['text'],
            'colorOpacity' => 1,
            'colorPalette' => '',

            'titleColorHex' => $sectionPalette['text'],
            'titleTypographyFontStyle' => 'heading3',
            'titleColorOpacity' => 3,
            'titleColorPalette' => '',

            'previewColorHex' => $sectionPalette['text'],
            'previewColorOpacity' => 1,
            'previewColorPalette' => '',
        ];

        $sectionProperties2Margin = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => 0,
            "marginBottomSuffix" => "px",
            "marginLeft" => 30,
            "marginLeftSuffix" => "px",
        ];

        $sectionProperties2 = [
            'showAudio' => 'off',
            'showImage' => 'off',
            'showVideo' => 'off',
            'showPreview' => 'off',
            'showTitle' => 'off',
            'showDate' => 'on',
            'showGroup' => 'off',
            'showCategory' => 'on',
            'showPreacher' => 'on',
            'showPassage' => 'on',
            'showMetaHeadings' => 'on',
            'showPreviousPage' => 'on',
            'showMediaLinksVideo' => 'off',
            'showMediaLinksAudio' => 'off',
            'showMediaLinksDownload' => 'on',
            'showMediaLinksNotes' => 'on',

            'colorHex' => $sectionPalette['text'],
            'colorOpacity' => 1,
            'colorPalette' => '',

            'dateColorHex' => $sectionPalette['text'],
            'dateColorOpacity' => 1,
            'dateColorPalette' => '',

            'metaLinksColorHex' => $sectionPalette['link'],
            'metaLinksColorOpacity' => 1,
            'metaLinksColorPalette' => '',

            'hoverMetaLinksColorHex' => $sectionPalette['link'],
            'hoverMetaLinksColorOpacity' => 0.75,
            'hoverMetaLinksColorPalette' => '',

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'subscribeButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'subscribeButtonColorOpacity' => 1,
            'subscribeButtonColorPalette' => '',

            'subscribeButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'subscribeButtonBgColorOpacity' => 1,
            'subscribeButtonBgColorPalette' => '',

            'hoverSubscribeButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverSubscribeButtonBgColorOpacity' => 0.75,
            'hoverSubscribeButtonBgColorPalette' => '',

            "typographyBold" => false,
            "typographyItalic" => false,
            "typographyUnderline" => false,
            "typographyStrike" => false,
            "typographyUppercase" => false,
            "typographyLowercase" => false
        ];

        foreach ($sectionStyle as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0)
                ->$properties($value);
        }

        foreach ($sectionPlayerStyle as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 0)
                ->$properties($value);
        }

        foreach ($sectionDescriptionItemsStyle as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1, 0)
                ->$properties($value);
        }
        foreach ($sectionDescriptionItemsStyle as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1, 1)
                ->$properties($value);
        }

        foreach ($sectionProperties1 as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1, 0, 0, 0)
                ->$properties($value);
        }

        $detailsSection->getItemWithDepth(0, 1, 0, 0, 0)->titleTypography();

        foreach ($sectionDescriptionStyle as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1)
                ->$properties($value);
        }

        foreach ($sectionProperties2 as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 1, 0)
                ->$properties($value);
        }

        foreach ($sectionProperties2Margin as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 1)
                ->$properties($value);
        }

        foreach ($wrapperItemTitle as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 0, 0)
                ->$properties($value);
        }

        foreach ($richTextTitle as $key => $value) {
            $properties = 'set_' . $key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 0, 0, 0, 0)
                ->$properties($value);
        }
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    private function normalizeColorEntry($value, $fallback = '#000000'): array
    {
        $isValidHex = function ($c) {
            return is_string($c) && preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $c);
        };

        if (is_array($value)) {
            $color = $value['color'] ?? null;
            $opacity = isset($value['opacity']) ? (float)$value['opacity'] : 1.0;

            if (!$this->isLikelyColor($color)) {
                $color = $fallback;
            }

            return [
                'color' => $isValidHex($color) ? $color : $fallback,
                'opacity' => $opacity,
            ];
        }

        if ($this->isLikelyColor($value)) {
            return [
                'color' => $isValidHex($value) ? $value : $fallback,
                'opacity' => 1.0,
            ];
        }

        return ['color' => $fallback, 'opacity' => 1.0];
    }

    private function isLikelyColor($v): bool
    {
        if (!is_string($v)) return false;

        $v = trim($v);
        if ($v === '#') return true;
        if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $v)) return true;
        if (stripos($v, 'rgb(') === 0 || stripos($v, 'rgba(') === 0) return true;
        if (preg_match_all('/\d+/', $v, $m) && count($m[0]) === 3) return true;

        return false;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 25,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 25,
            "paddingTopSuffix" => "px",
            "paddingRight" => 20,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 50,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 20,
            "paddingLeftSuffix" => "px",
        ];
    }
}
